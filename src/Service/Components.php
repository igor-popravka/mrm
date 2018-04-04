<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 04.04.2018
 * Time: 00:06
 */

namespace App\Service;


use App\Entity\Product;

class Components extends AbstractService {

    public function getProductsList(){
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $list = $repository->findAll();
        return $list;
    }

    public function getProduct($id){
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);
        return $product;
    }

    public function insertProduct(Product $product){
        /*$repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);*/
        return $product;
    }

    public function updateProduct(Product $product){
        /*$repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);*/
        return $product;
    }
}