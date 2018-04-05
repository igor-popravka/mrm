<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\Permissions;
use App\Entity\Product as ProductEntity;
use App\Form\Data\Manager as ManagerData;
use App\Form\Data\Product as ProductData;
use App\Form\ManagerType;
use App\Form\ProductType;
use App\Service\MRMToken;
use App\Service\PasswordEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\ISO4217;

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
     * @Route("/manager/{action}/{id}", name="route_section_manager")
     *
     * @param Request $request
     * @param string $action
     * @param int $id
     *
     * @return RedirectResponse|Response
     */
    public function manager(Request $request, string $action, int $id = null) {
        if (!$this->getAuth()->isAuthenticated()) {
            $this->getAuth()->logout();
            return $this->redirectToLogin();
        }

        switch ($action) {
            case 'list';
                return $this->getManagerList();
            case 'new':
                return $this->createNewManager($request);
            case 'edit':
                return $this->editManager($request, $id);
        }

        $this->addFlash('danger', "The action {$action} is invalid.");
        return $this->redirectToRoute('route_section_manager', ['action' => 'list']);
    }

    private function getManagerList() {
        if (!$this->getAuth()->canDo(Permissions::CAN_READ_MANAGER)) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $repository = $this->getDoctrine()->getRepository(Manager::class);
        $managers = $repository->findAll();

        return $this->render(
            'sections/manager-list.html.twig',
            ['managers' => $managers]
        );
    }

    private function createNewManager(Request $request) {
        if (!$this->getAuth()->canDo(Permissions::CAN_EDIT_MANAGER)) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        $form = $this->createForm(ManagerType::class, null, ['can_edit' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Form\Data\Manager $managerData */
            $managerData = $form->getData();

            if (($token = $this->getAuth()->createNewManager($managerData)) instanceof MRMToken) {
                $this->getMailer()->send(
                    (new \Swift_Message('MRM Service | Invitation'))
                        ->setFrom($this->getParameter('mrm_email'))
                        ->setTo($token->getData()['login'])
                        ->setBody($this->render('email/invite.html.twig', [
                            'NAME' => $token->getData()['full_name'],
                            'LOGIN' => $token->getData()['login'],
                            'PASSWORD_RESET_LINK' => $this->generateUrl('route_password_change', [
                                'hash' => $token->getHash()
                            ], UrlGeneratorInterface::ABSOLUTE_URL),
                            'EXPIRE_LINK' => 4
                        ]), 'text/html')
                    );

                $this->addFlash('success', 'The new manager was created successfully and we sent massage with registration info.');
                return $this->redirectToRoute('route_section_manager', ['action' => 'list']);
            }

            $form->addError(new FormError('Error during creating new account'));
        }

        return $this->render('sections/manager.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->renderFormErrors($form),
            'PAGE_NAME' => 'New Manager'
        ]);
    }

    private function editManager(Request $request, $id) {
        if (!$this->getAuth()->canDo(Permissions::CAN_READ_MANAGER) && !$this->getAuth()->canDo(Permissions::CAN_EDIT_MANAGER)) {
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
                return $this->redirectToRoute('route_section_manager', ['action' => 'list']);
            }

            $form->addError(new FormError('Error during updating manager data'));
        }

        return $this->render('sections/manager.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->renderFormErrors($form),
            'PAGE_NAME' => 'Edit Manager'
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
            case 'edit':
                return $this->editProduct($request, $id);
        }

        $this->addFlash('danger', "The action {$action} is invalid.");
        return $this->redirectToRoute('route_section_product', ['action' => 'list']);
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
            $data = $form->getData();

            if ($this->getComponents()->insertProduct($data)) {
                $this->addFlash('success', 'The product was crated successfully.');
                return $this->redirectToRoute('route_section_product', ['action' => 'list']);
            }

            $form->addError(new FormError('Error during crating product'));
        }

        return $this->render('sections/product.html.twig', [
            'errors' => $this->renderFormErrors($form),
            'form_product' => $form->createView(),
            'crypto_codes' => ISO4217::getCryptoList(),
            'PAGE_NAME' => 'New Product'
        ]);
    }

    private function editProduct(Request $request, $id) {
        if (!$this->getAuth()->canDo(Permissions::CAN_READ_CONFIGURATION) || !$this->getAuth()->canDo(Permissions::CAN_EDIT_CONFIGURATION)) {
            $this->addFlash('danger', "You haven't permissions to do this action.");
            return $this->redirectToDashboard();
        }

        if (($product = $this->getComponents()->getProduct($id)) instanceof ProductEntity) {
            /** @var ProductEntity $product */
            $form = $this->createForm(
                ProductType::class,
                (new ProductData())->initEntity($product),
                ['can_edit' => $this->getAuth()->canDo(Permissions::CAN_EDIT_CONFIGURATION)]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductData $productData */
                $productData = $form->getData();

                $productData->handleEntity($product);

                if ($this->getComponents()->updateProduct($product)) {
                    $this->addFlash('success', 'The product was updated successfully.');
                    return $this->redirectToRoute('route_section_product', ['action' => 'list']);
                }

                $form->addError(new FormError('Error during updating product'));
            }

            return $this->render('sections/product.html.twig', [
                'errors' => $this->renderFormErrors($form),
                'form_product' => $form->createView(),
                'crypto_codes' => ISO4217::getCryptoList(),
                'assets' => $product->getAssets(),
                'PAGE_NAME' => 'Edit Product'
            ]);
        }

        $this->addFlash('danger', "The product #{$id} wasn't found.");
        return $this->redirectToRoute('route_section_product', ['action' => 'list']);
    }
}