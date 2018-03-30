<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\User;
use App\Repository\ManagerRepository;
use App\Service\Auth;
use App\Service\Country;
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
 * Time: 13:45
 */
class SectionController extends MRMController {
    /**
     * @Route("/dashboard", name="route_section_dashboard")
     *
     * @return RedirectResponse|Response
     */
    public function dashboard() {
        if (!$this->getAuth()->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        return $this->render('sections/dashboard.html.twig');

    }

    /**
     * @Route("/managers", name="route_section_managers")
     *
     * @return RedirectResponse|Response
     */
    public function managers() {
        if (!$this->getAuth()->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        if (!$this->getAuth()->isAdmin()) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $repository = $this->getDoctrine()->getRepository(Manager::class);
        $managers = $repository->findAll();

        return $this->render(
            'sections/managers.html.twig',
            ['managers' => $managers]
        );
    }

    /**
     * @Route("/managers/add", name="route_section_managers_add")
     *
     *
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param PasswordEncoder $encoder
     *
     * @return RedirectResponse|Response
     */
    public function addManager(Request $request, \Swift_Mailer $mailer, PasswordEncoder $encoder) {
        if (!$this->getAuth()->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        if (!$this->getAuth()->isAdmin()) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $manager = new Manager();
        $manager->setPassword($encoder->generateRaw());

        $form = $this->createFormBuilder($manager)
            ->add('login', Type\EmailType::class)
            ->add('role', Type\ChoiceType::class, [
                'choices' => [
                    'Full Access Manager' => "ROLE_FULL_ACCESS_MANAGER"
                ]
            ])
            ->add('status', Type\ChoiceType::class, [
                'choices' => [
                    'Active' => 1,
                    'Disabled' => 0
                ]
            ])
            ->add('submit', Type\SubmitType::class, ['label' => 'Add Manager'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $manager->getPassword();
            $manager->setPassword($encoder->hashPassword($password));

            $em = $this->getDoctrine()->getManager();
            $em->persist($manager);
            $em->flush();

            $message = (new \Swift_Message('Welcome to Back Office'))
                ->setFrom($this->getParameter('ift_from_email'))
                ->setTo($manager->getUsername())
                ->setBody($this->render('email/invite.html.twig', [
                    'NAME' => $manager->getUsername(),
                    'LOGIN' => $manager->getUsername(),
                    'PASSWORD' => $password
                ]), 'text/html');
            $mailer->send($message);

            $this->addFlash('success', 'The user was added successfully and we sent massage with registration info.');
            return $this->redirectToRoute('route_section_managers');

        }

        return $this->render(
            'sections/managers-add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/users", name="route_section_users")
     *
     * @return RedirectResponse|Response
     */
    public function users() {
        if (!$this->getAuth()->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        if (!$this->getAuth()->isAdmin()) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        return $this->render(
            'sections/users.html.twig',
            ['users' => $users]
        );
    }

    /**
     * @Route("/user/{id}", name="route_section_user")
     *
     * @param $id
     *
     * @return RedirectResponse|Response
     */
    public function user($id) {
        if (!$this->getAuth()->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        if (!$this->getAuth()->isAdmin() || !$this->getAuth()->canDo(Auth::CAN_EDIT_USER, Auth::CAN_READ_USER)) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $user_repository = $this->getDoctrine()->getRepository(User::class);
        $user = $user_repository->find($id);

        return $this->render(
            'sections/user.html.twig',
            ['user' => $user]
        );
    }
}