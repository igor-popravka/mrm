<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManagerRepository")
 * @ORM\Table(name="managers")
 * @UniqueEntity(fields="login", message="Login already taken")
 */
class Manager extends AbstractEntity implements \Serializable {
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="string", name="Password", length=455)
     */
    private $password;

    /**
     * @ORM\Column(type="smallint", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     */
    private $permission_id;

    /**
     * @ORM\OneToOne(targetEntity="Permissions")
     * @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     */
    private $permissions;

    public function __construct() {
        $this->role = self::ROLE_MANAGER;
        $this->status = self::STATUS_ACTIVE;
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
     * @return string
     */
    public function getFullName() {
        return "{$this->getFirstName()} {$this->getLastName()}";
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
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
    public function setLogin($login) {
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
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPermissionId() {
        return $this->permission_id;
    }

    /**
     * @param mixed $permission_id
     */
    public function setPermissionId($permission_id) {
        $this->permission_id = $permission_id;
    }

    /**
     * @return Permissions
     */
    public function getPermissions(): Permissions {
        return $this->permissions;
    }

    /**
     * @param Permissions $permissions
     */
    public function setPermissions(Permissions $permissions) {
        $this->permissions = $permissions;
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
    public function setStatus($status) {
        $this->status = $status;
    }

    public function serialize() {
        return serialize([
            $this->id,
            $this->first_name,
            $this->last_name,
            $this->login,
            $this->status,
            $this->role,
            $this->permission_id,
            $this->permissions
        ]);
    }

    public function unserialize($serialized) {
        list(
            $this->id,
            $this->first_name,
            $this->last_name,
            $this->login,
            $this->status,
            $this->role,
            $this->permission_id,
            $this->permissions
            ) = unserialize($serialized);
    }
}
