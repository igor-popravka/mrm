<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 05.02.2018
 * Time: 16:55
 */

namespace App\Service;


class Token {
    private $tkid;
    private $data;
    private $valid_till;

    public function compile($data, \DateTime $valid_till, string $tkid) {
        $this->data = $data;
        $this->valid_till = $valid_till->getTimestamp();
        $this->tkid = $tkid;

        if ($this->valid($tkid)) {
            $serialized = serialize([
                $this->data,
                $this->valid_till,
                $this->tkid
            ]);

            return base64_encode($serialized);
        }

        return null;
    }

    public function uncompile($compiled) {
        $serialized = base64_decode($compiled);
        list($this->data, $this->valid_till, $this->tkid) = unserialize($serialized);
        return $this;
    }

    public function valid(string $tkid) {
        if (empty($this->data) || empty($this->valid_till) || empty($this->tkid)) {
            return false;
        } else if ($this->tkid !== $tkid) {
            return false;
        } else if ($this->valid_till < (new \DateTime)->getTimestamp()) {
            return false;
        }

        return true;
    }

    public function getData() {
        return $this->data;
    }
}