<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 * @UniqueEntity(fields="code", message="Code should be unique")
 */
class Product extends AbstractEntity {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=250)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=250)
     * @var float
     */
    private $cost;

    /**
     * @ORM\Column(type="string", length=5)
     * @var string
     */
    private $currency;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    private $assets;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $modified;

    public function __construct() {
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getCost(): float {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost): void {
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function getCurrency(): string {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void {
        $this->currency = $currency;
    }

    /**
     * @return array
     */
    public function getAssets(): array {
        return $this->assets;
    }

    /**
     * @param array $assets
     */
    public function setAssets(array $assets): void {
        $this->assets = $assets;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getModified(): \DateTime {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     */
    public function setModified(\DateTime $modified): void {
        $this->modified = $modified;
    }

    public function assets2string() {
        $pre_format = array_map(function ($a) {
            return "{$a['name']}:{$a['value']}";
        }, $this->getAssets());
        return implode(' | ', $pre_format);
    }
}
