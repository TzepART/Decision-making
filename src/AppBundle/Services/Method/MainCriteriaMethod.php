<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.05.17
 * Time: 17:20
 */

namespace AppBundle\Services\Method;


use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\ExtendMatrixModel;

/**
 * Class MainCriteriaMethod
 * @package AppBundle\Services\Method
 */

class MainCriteriaMethod extends AbstractMethod
{
    const METHOD_NAME = 'main-criteria';

    /**
     * @param ExtendMatrixModel $matrixModel
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     */
    public function getOptimalSolution(ExtendMatrixModel $matrixModel, DecisionSolutionModel $decisionSolutionModel)
    {

        return $decisionSolutionModel;
    }

}