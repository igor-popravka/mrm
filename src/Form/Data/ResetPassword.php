<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 30.03.2018
 * Time: 19:10
 */

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class ResetPassword extends AbstractData {
    /**
     * @Assert\NotBlank(message="Please type your username")
     * @Assert\Email(message="Username should be of your email in correct format")
     */
    private $login;

    /**
     * @return mixed
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void {
        $this->login = $login;
    }
}