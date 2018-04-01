<?php

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class Manager extends AbstractData {
    /**
     * @Assert\NotBlank(message="Please type first name")
     */
    protected $first_name;

    /**
     * @Assert\NotBlank(message="Please type last name")
     */
    protected $last_name;

    /**
     * @Assert\NotBlank(message="Please type your username")
     * @Assert\Email(message="Username should be of your email in correct format")
     */
    protected $login;

    /**
     * @Assert\NotBlank(message="Please type status")
     */
    protected $role;

    /**
     * @Assert\NotBlank(message="Please type status")
     */
    protected $status;

    /**
     * @Assert\Valid
     */
    protected $read_order;

    /**
     * @Assert\Valid
     */
    protected $edit_order;

    /**
     * @Assert\Valid
     */
    protected $read_manager;

    /**
     * @Assert\Valid
     */
    protected $edit_manager;

    /**
     * @Assert\Valid
     */
    protected $read_configuration;

    /**
     * @Assert\Valid
     */
    protected $edit_configuration;

    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name): void {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name): void {
        $this->last_name = $last_name;
    }

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
    public function getRole() {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getReadOrder() {
        return $this->read_order;
    }

    /**
     * @param mixed $read_order
     */
    public function setReadOrder($read_order): void {
        $this->read_order = $read_order;
    }

    /**
     * @return mixed
     */
    public function getEditOrder() {
        return $this->edit_order;
    }

    /**
     * @param mixed $edit_order
     */
    public function setEditOrder($edit_order): void {
        $this->edit_order = $edit_order;
    }

    /**
     * @return mixed
     */
    public function getReadManager() {
        return $this->read_manager;
    }

    /**
     * @param mixed $read_manager
     */
    public function setReadManager($read_manager): void {
        $this->read_manager = $read_manager;
    }

    /**
     * @return mixed
     */
    public function getEditManager() {
        return $this->edit_manager;
    }

    /**
     * @param mixed $edit_manager
     */
    public function setEditManager($edit_manager): void {
        $this->edit_manager = $edit_manager;
    }

    /**
     * @return mixed
     */
    public function getReadConfiguration() {
        return $this->read_configuration;
    }

    /**
     * @param mixed $read_configuration
     */
    public function setReadConfiguration($read_configuration): void {
        $this->read_configuration = $read_configuration;
    }

    /**
     * @return mixed
     */
    public function getEditConfiguration() {
        return $this->edit_configuration;
    }

    /**
     * @param mixed $edit_configuration
     */
    public function setEditConfiguration($edit_configuration): void {
        $this->edit_configuration = $edit_configuration;
    }
}
