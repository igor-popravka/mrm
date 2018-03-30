<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 30.03.2018
 * Time: 08:55
 */

namespace App\Form\Data;


use App\Entity\AbstractEntity;

abstract class AbstractData {
    public function handleEntity(AbstractEntity $entity) {
        foreach ($this as $field => $value) {
            $entity->setField($field, $value);
        }
    }
}