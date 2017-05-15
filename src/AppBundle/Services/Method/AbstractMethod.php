<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 17:55
 */

namespace AppBundle\Services\Method;


use AppBundle\Model\MethodModel\DecisionSolutionModel;
use AppBundle\Model\ExtendMatrixModel;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractMethod
{
    public abstract function getOptimalSolution(Request $request, DecisionSolutionModel $decisionSolutionModel);

    protected abstract function initalMatrixModel(Request $request): ExtendMatrixModel;

}