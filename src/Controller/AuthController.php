<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\Data\ChangePassword;
use App\Form\Data\Login;
use App\Form\Data\ResetPassword;
use App\Form\LoginType;
use App\Form\ResetPasswordType;
use App\MRMException;
use App\Service\Auth;
use App\Service\Country;
use App\Service\MRMToken;
use App\Service\PasswordEncoder;
use App\Service\Token;
use App\Service\CRM\IFTZohoClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author: igor.popravka
 * Date: 21.02.2018
 * Time: 12:26
 */
class AuthController extends MRMController {
    /**
     * @Route("/login", name="route_login")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function login(Request $request) {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Login $loginData */
            $loginData = $form->getData();
            try {
                $this->getAuth()->login($loginData);
                return $this->redirectToRoute('route_section_dashboard');
            } catch (MRMException $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->renderFormErrors($form)
        ]);
    }

    /**
     * @Route("/logout", name="route_logout")
     *
     * @return RedirectResponse|Response
     */
    public function logout() {
        $this->getAuth()->logout();
        return $this->redirectToLogin();
    }

    /**
     * @Route("/password/reset", name="route_password_reset")
     *
     * @param Request $request
     * @param \Swift_Mailer $mailer
     *
     * @return RedirectResponse|Response
     */
    public function resetPassword(Request $request, \Swift_Mailer $mailer) {
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ResetPassword $resetData */
            $resetData = $form->getData();

            if (($token = $this->getAuth()->resetPassword($resetData)) instanceof MRMToken) {
                $message = (new \Swift_Message('Back Office Support'))
                    ->setFrom($this->getParameter('mrm_email'))
                    ->setTo($token->getData()['login'])
                    ->setBody($this->render('email/invite.html.twig', [
                        'NAME' => $token->getData()['full_name'],
                        'PASSWORD_RESET_LINK' => $this->generateUrl('route_password_change', [
                            'hash' => $token->getHash()
                        ], UrlGeneratorInterface::ABSOLUTE_URL),
                        'EXPIRE_LINK' => 4
                    ]), 'text/html');

                $mailer->send($message);

                $this->addFlash('success', "Check your email for a link to reset your password. If it doesn't appear within a few minutes, check your spam folder.");

                return $this->render('auth/password-reset.html.twig', ['form' => null]);
            }

            $this->addFlash('danger', "The user with the same email \"{$resetData->getLogin()}\" doesn't exit!");
        }

        return $this->render('auth/password-reset.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/password/change/{hash}", name="route_password_change")
     *
     * @param Request $request
     * @param $hash
     *
     * @return RedirectResponse|Response
     */
    public function changePassword(Request $request, $hash) {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ChangePassword $changeData */
            $changeData = $form->getData();

            if ($this->getAuth()->changePassword($changeData, $hash)) {
                $this->addFlash('success', "Your password was changed successfully!");
                return $this->redirectToLogin();
            }
        }

        return $this->render('auth/password-change.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->renderFormErrors($form)
        ]);
    }
}