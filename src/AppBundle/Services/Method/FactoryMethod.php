<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:24
 */

namespace AppBundle\Services\Method;


class FactoryMethod
{

    public function getMethod($methodName) {
        switch ($methodName) {
            case MainCriteriaMethod::METHOD_NAME:
                $method = new MainCriteriaMethod();
                break;

            default:
                $method = null;
        }

        return $method;
    }


}