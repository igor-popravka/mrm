<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 30.03.2018
 * Time: 09:10
 */

namespace App\Entity;


use App\Form\Data\AbstractData;

abstract class AbstractEntity {
    public function set(string $field, $value) {
        $method = $this->getGetSetMethod($field, 'set');
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        }
    }

    public function get(string $field) {
        $method = $this->getGetSetMethod($field, 'get');
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        return null;
    }

    public function is(string $field) {
        $method = $this->getGetSetMethod($field, 'is');

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        return false;
    }

    public function has(string $field, $value = null) {
        $method = $this->getGetSetMethod($field, 'has');

        if (method_exists($this, $method)) {
            return $this->{$method}($value);
        }
        return false;
    }

    private function getGetSetMethod(string $field, string $type) {
        $name_parts = explode('_', $field);
        $name = $type;

        foreach ($name_parts as $part) {
            $name .= ucfirst($part);
        }

        return $name;
    }
}