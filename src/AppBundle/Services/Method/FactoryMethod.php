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
            case ParetoMethod::METHOD_NAME:
                $method = new ParetoMethod();
                break;
            case CommonCriteriaMethod::METHOD_NAME:
                $method = new CommonCriteriaMethod();
                break;
            case BiasedIdealMethod::METHOD_NAME:
                $method = new BiasedIdealMethod();
                break;
            case OptimizationMethod::METHOD_NAME:
                $method = new OptimizationMethod();
                break;

            default:
                $method = null;
        }

        return $method;
    }


}