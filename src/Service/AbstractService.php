<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 23.03.2018
 * Time: 22:55
 */

namespace App\Service;


use Doctrine\Common\Persistence\ManagerRegistry;

abstract class AbstractService {
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
    }

    protected function getDoctrine(): ManagerRegistry {
        return $this->doctrine;
    }
}