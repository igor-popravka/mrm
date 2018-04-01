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
        $properties = (new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PROTECTED);

        foreach ($properties  as $property) {
            /** @var \ReflectionProperty $property */
            $entity->set($property->name, $this->{$property->name});
        }
    }

    public function initEntity(AbstractEntity $entity){
        $properties = (new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PROTECTED);

        foreach ($properties  as $property) {
            /** @var \ReflectionProperty $property */
            if($entity->has($property->name)){
                $this->{$property->name} = $entity->get($property->name);
            }
        }
    }
}