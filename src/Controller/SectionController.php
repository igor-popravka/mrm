<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\Permissions;
use App\Form\Data\Manager as ManagerData;
use App\Form\ManagerType;
use App\Form\ProductType;
use App\Repository\ManagerRepository;
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
            $this->getAuth()->logout();
            return $this->redirectToLogin();
        } else if (!$this->getAuth()->canDo(Permissions::CAN_READ_MANAGER)) {
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
     * @Route("/managers/new", name="route_section_manager_new")
     *
     *
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param PasswordEncoder $encoder
     *
     * @return RedirectResponse|Response
     */
    public function newManager(Request $request, \Swift_Mailer $mailer, PasswordEncoder $encoder) {
        if (!$this->getAuth()->isAuthenticated()) {
            $this->getAuth()->logout();
            return $this->redirectToLogin();
        } else if (!$this->getAuth()->canDo(Permissions::CAN_EDIT_MANAGER)) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $form = $this->createForm(ManagerType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Form\Data\Manager $managerData */
            $managerData = $form->getData();

            if (($token = $this->getAuth()->createNewManager($managerData)) instanceof MRMToken) {
                $message = (new \Swift_Message('MRM Service | Invitation'))
                    ->setFrom($this->getParameter('mrm_email'))
                    ->setTo($token->getData()['login'])
                    ->setBody($this->render('email/invite.html.twig', [
                        'NAME' => $token->getData()['full_name'],
                        'LOGIN' => $token->getData()['login'],
                        'PASSWORD_RESET_LINK' => $this->generateUrl('route_password_change', [
                            'hash' => $token->getHash()
                        ], UrlGeneratorInterface::ABSOLUTE_URL),
                        'EXPIRE_LINK' => 4
                    ]), 'text/html');
                $mailer->send($message);

                $this->addFlash('success', 'The new manager was created successfully and we sent massage with registration info.');
                return $this->redirectToRoute('route_section_managers');
            }

            $form->addError(new FormError('Error during creating new account'));
        }

        return $this->render('sections/manager.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->renderFormErrors($form),
            'PAGE_NAME' => 'New'
        ]);
    }

    /**
     * @Route("/manager/{id}", name="route_section_manager_edit")
     *
     *
     * @param Request $request
     * @param int $id
     * @param PasswordEncoder $encoder
     *
     * @return RedirectResponse|Response
     */
    public function editManager(Request $request, int $id, PasswordEncoder $encoder) {
        if (!$this->getAuth()->isAuthenticated()) {
            $this->getAuth()->logout();
            return $this->redirectToLogin();
        } else if (!$this->getAuth()->canDo(Permissions::CAN_READ_MANAGER) && !$this->getAuth()->canDo(Permissions::CAN_EDIT_MANAGER)) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $manager = $this->getAuth()->getManagerByID($id);
        $managerData = new ManagerData();
        $managerData->setFirstName($manager->getFirstName());
        $managerData->setLastName($manager->getLastName());
        $managerData->setLogin($manager->getLogin());
        $managerData->setRole($manager->getRole());
        $managerData->setStatus($manager->getStatus());

        /** @var Permissions $permissions */
        $permissions = $manager->getPermissions();
        $managerData->setReadOrder($permissions->isReadOrder());
        $managerData->setEditOrder($permissions->isEditOrder());
        $managerData->setReadManager($permissions->isReadManager());
        $managerData->setEditManager($permissions->isEditManager());
        $managerData->setReadConfiguration($permissions->isReadConfiguration());
        $managerData->setEditConfiguration($permissions->isEditConfiguration());

        $form = $this->createForm(ManagerType::class, $managerData, ['can_edit' => $this->getAuth()->canDo(Permissions::CAN_EDIT_MANAGER)]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Form\Data\Manager $managerData */
            $managerData = $form->getData();

            if ($this->getAuth()->updateManager($managerData)) {
                $this->addFlash('success', 'The manager data was updated successfully.');
                return $this->redirectToRoute('route_section_managers');
            }

            $form->addError(new FormError('Error during updating manager data'));
        }

        return $this->render('sections/manager.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->renderFormErrors($form),
            'PAGE_NAME' => 'Edit'
        ]);
    }

    /**
     * @Route("/product/{action}/{id}", name="route_section_product")
     *
     * @var Request $request
     * @var string $action
     * @var int $id
     *
     * @return RedirectResponse|Response
     */
    public function product(Request $request, string $action, int $id = null) {
        if (!$this->getAuth()->isAuthenticated()) {
            $this->getAuth()->logout();
            return $this->redirectToLogin();
        }

        switch ($action) {
            case 'list':
                return $this->getProductList($request);
            case 'new':
                return $this->createNewProduct($request);

        }

        return new Response('');
    }

    private function getProductList(Request $request) {
        if (!$this->getAuth()->canDo(Permissions::CAN_READ_CONFIGURATION)) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        return $this->render('sections/product-list.html.twig', [
            'products' => $this->getComponents()->getProductsList()
        ]);
    }

    private function createNewProduct(Request $request) {
        if (!$this->getAuth()->canDo(Permissions::CAN_EDIT_CONFIGURATION)) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $form = $this->createForm(ProductType::class, null, ['can_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('sections/product.html.twig', [
            'products' => $this->getComponents()->getProductsList(),
            'errors' => $this->renderFormErrors($form),
            'form' => $form->createView(),
            'assets' => [
                ['name' => 'EFR', 'value' => 15],
                ['name' => 'USD', 'value' => 13],
                ['name' => 'XRT', 'value' => 10],
                ['name' => 'SM', 'value' => 19]
            ]
        ]);
    }
}