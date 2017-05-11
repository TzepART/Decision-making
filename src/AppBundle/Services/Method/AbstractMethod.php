<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 17:55
 */

namespace AppBundle\Services\Method;


use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\ExtendMatrixModel;

abstract class AbstractMethod
{

    public abstract function getOptimalSolution(ExtendMatrixModel $matrixModel, DecisionSolutionModel $decisionSolutionModel);

}