<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenRepository")
 * @ORM\Table(name="tokens")
 */
class Token extends AbstractEntity {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="array")
     */
    private $data;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_time;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    private $expired_time;

    public function __construct() {
        $this->created_time = new \DateTime();
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
    public function getHash() {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash): void {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void {
        $this->data = $data;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedTime() {
        return $this->created_time;
    }

    /**
     * @param mixed $created_time
     */
    public function setCreatedTime($created_time): void {
        $this->created_time = $created_time;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiredTime() {
        return $this->expired_time;
    }

    /**
     * @param mixed $expired_time
     */
    public function setExpiredTime($expired_time): void {
        $this->expired_time = $expired_time;
    }
}
