<?php

namespace App\Controller;

use App\Service\Auth;
use App\Service\Components;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

/**
 * @author: igor.popravka
 * Date: 21.02.2018
 * Time: 12:26
 */
class MRMController extends Controller {
    private $auth;
    private $components;

    /**
     * MRMController constructor.
     * @param $auth
     * @param $components
     */
    public function __construct(Auth $auth, Components $components) {
        $this->auth = $auth;
        $this->components = $components;
    }

    /**
     * @return Auth
     */
    public function getAuth(): Auth {
        return $this->auth;
    }

    /**
     * @return Components
     */
    public function getComponents(): Components {
        return $this->components;
    }

    /**
     * @Route("/", name="route_home")
     *
     * @return RedirectResponse|Response
     */
    public function home() {
        if (!$this->getAuth()->isAuthenticated()) {
            return $this->redirectToLogin();
        }

        return $this->redirectToDashboard();
    }

    protected function redirectToDashboard() {
        return $this->redirectToRoute('route_section_dashboard');
    }

    protected function redirectToLogin() {
        return $this->redirectToRoute('route_login');
    }

    protected function renderFormErrors(FormInterface $form){
        $errors = '';
        foreach ($form->getErrors() as $error){
            /** @var FormError $error */
            if(!empty($error->getMessage())){
                $errors .= "<li>{$error->getMessage()}</li>";
            }
        }
        return  !empty($errors) ? "<ul>{$errors}</ul>" : null;
    }
}