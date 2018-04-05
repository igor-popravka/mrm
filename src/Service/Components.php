<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 04.04.2018
 * Time: 00:06
 */

namespace App\Service;


use App\Entity\Product;
use App\Form\Data\Product as ProductData;

class Components extends AbstractService {

    public function getProductsList() {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $list = $repository->findAll();
        return $list;
    }

    public function getProduct($id) {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->findOneBy(['id' => $id]);
        return $product;
    }

    public function insertProduct(ProductData $productData) {
        $product = new Product();
        $productData->handleEntity($product);
        return $this->updateProduct($product);
    }

    public function updateProduct(Product $product) {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        } catch (\Throwable $t) {
            return false;
        }
        return true;
    }
}