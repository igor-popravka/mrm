<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 30.03.2018
 * Time: 10:45
 */

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class Login extends AbstractData {
    /**
     * @Assert\NotBlank(message="Please type your username")
     * @Assert\Email(message="Username should be of your email in correct format")
     */
    private $login;

    /**
     * @Assert\NotBlank(message="Please type your password")
     * @Assert\Length(min=8, max=20, minMessage="Min length should be 8 chars")
     */
    private $password;

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

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void {
        $this->password = $password;
    }
}