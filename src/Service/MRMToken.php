<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 21.03.2018
 * Time: 22:47
 */

namespace App\Service;

use App\Entity\Token;
use App\Repository\TokenRepository;

class MRMToken extends AbstractService {
    private $token;

    public function load(string $hash) {
        /** @var TokenRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Token::class);
        $this->token = $repository->findByHash($hash);
        return $this;
    }

    /**
     * @return Token
     */
    private function getToken() {
        return $this->token;
    }

    public function getData() {
        return $this->getToken()->getData();
    }

    public function getHash() {
        return $this->getToken()->getHash();
    }

    public function isValid() {
        if ($this->isSet()) {
            $expiredTime = $this->getToken()->getExpiredTime();
            if (($expiredTime instanceof \DateTime) && $expiredTime->getTimestamp() < (new \DateTime)->getTimestamp()) {
                $this->reset();
                return false;
            }
            return true;
        }
        return false;
    }

    public function generate(array $data = [], \DateTime $expiredTime = null) {
        $token = new Token();
        $token->setHash(bin2hex(random_bytes(32)));
        $token->setData($data);
        $token->setExpiredTime($expiredTime);

        $em = $this->getDoctrine()->getManager();
        $em->persist($token);
        $em->flush();

        $this->token = $token;
        return $this;
    }

    public function reset() {
        if ($this->isSet()) {
            /** @var TokenRepository $repository */
            $repository = $this->getDoctrine()->getRepository(Token::class);
            $token = $repository->findByHash($this->getToken()->getHash());

            $em = $this->getDoctrine()->getManager();
            $em->remove($token);
            $em->flush();
        }
        return $this;
    }

    public function isSet() {
        return isset($this->token) && $this->token instanceof Token;
    }
}