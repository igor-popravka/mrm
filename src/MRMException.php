<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 30.03.2018
 * Time: 12:46
 */

namespace App;


class MRMException extends \Exception {
    /**
     * @param string $massage
     * @param int $code
     *
     * @throws MRMException
     */
    public static function throwNew (string $massage = '', int $code = 0) {
        throw new MRMException($massage, $code);
    }
}