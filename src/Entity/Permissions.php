<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PermissionsRepository")
 */
class Permissions extends AbstractEntity implements \Serializable {
    const CAN_READ_ORDER = 'read_order';
    const CAN_EDIT_ORDER = 'edit_order';
    const CAN_READ_MANAGER = 'read_manager';
    const CAN_EDIT_MANAGER = 'edit_manager';
    const CAN_READ_CONFIGURATION = 'read_configuration';
    const CAN_EDIT_CONFIGURATION = 'edit_configuration';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $read_order;

    /**
     * @ORM\Column(type="boolean")
     */
    private $edit_order;

    /**
     * @ORM\Column(type="boolean")
     */
    private $read_manager;

    /**
     * @ORM\Column(type="boolean")
     */
    private $edit_manager;

    /**
     * @ORM\Column(type="boolean")
     */
    private $read_configuration;

    /**
     * @ORM\Column(type="boolean")
     */
    private $edit_configuration;

    /**
     * Permissions constructor.
     * @param $read_order
     * @param $edit_order
     * @param $read_manager
     * @param $edit_manager
     * @param $read_configuration
     * @param $edit_configuration
     */
    public function __construct($read_order = false, $edit_order = false, $read_manager = false, $edit_manager = false, $read_configuration = false, $edit_configuration = false) {
        $this->read_order = $read_order;
        $this->edit_order = $edit_order;
        $this->read_manager = $read_manager;
        $this->edit_manager = $edit_manager;
        $this->read_configuration = $read_configuration;
        $this->edit_configuration = $edit_configuration;
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
    public function setId($id): void {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function isReadOrder() {
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
    public function isEditOrder(): bool {
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
    public function isReadManager(): bool {
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
    public function isEditManager(): bool {
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
    public function isReadConfiguration(): bool {
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
    public function isEditConfiguration(): bool {
        return $this->edit_configuration;
    }

    /**
     * @param mixed $edit_configuration
     */
    public function setEditConfiguration($edit_configuration): void {
        $this->edit_configuration = $edit_configuration;
    }

    public function serialize() {
        return serialize([
            $this->id,
            $this->read_order,
            $this->edit_order,
            $this->read_manager,
            $this->edit_manager,
            $this->read_configuration,
            $this->edit_configuration
        ]);
    }

    public function unserialize($serialized) {
        list(
            $this->id,
            $this->read_order,
            $this->edit_order,
            $this->read_manager,
            $this->edit_manager,
            $this->read_configuration,
            $this->edit_configuration
            ) = unserialize($serialized);
    }
}
