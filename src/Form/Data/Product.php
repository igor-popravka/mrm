<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 04.04.2018
 * Time: 22:39
 */

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Product as ProductEntity;

class Product extends AbstractData {
    /**
     * @Assert\NotBlank(message="Please type code")
     */
    protected $code;

    /**
     * @Assert\NotBlank(message="Please type name")
     */
    protected $name;

    /**
     * @Assert\NotBlank(message="Please type cost")
     */
    protected $cost;

    /**
     * @Assert\NotBlank(message="Please type currency")
     */
    protected $currency;

    /**
     * @Assert\NotBlank(message="Please type assets")
     */
    protected $assets;

    /**
     * @return mixed
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCost() {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost): void {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getAssets() {
        return json_encode($this->assets);
    }

    /**
     * @param mixed $assets
     */
    public function setAssets($assets): void {
        if (is_string($assets)) {
            $assets = json_decode($assets, true);
        }

        $this->assets = $assets;
    }

    public function initEntity(ProductEntity $product){
        $this->setCode($product->getCode());
        $this->setName($product->getName());
        $this->setCost($product->getCost());
        $this->setCurrency($product->getCurrency());
        $this->setAssets($product->getAssets());
        return $this;
    }
}