<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:00
 */

namespace AppBundle\Services\Strategy;


use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;

class HurwitzStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'hurwitz';

    /**
     * @param DecisionTaskModel $decisionTaskModel
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     * @internal param array $matrix
     * @internal param int $coefficient
     */
    function getOptimalSolution(DecisionTaskModel $decisionTaskModel, DecisionSolutionModel $decisionSolutionModel){

        $valueArray = [];
        $solutionArray = [];

        foreach ($decisionTaskModel->getMatrix()->toArray() as $i => $col) {
            $max = max($col);
            $min = min($col);
            $value = $decisionTaskModel->getCoefficient()*$min+(1-$decisionTaskModel->getCoefficient())*$max;
            $valueArray[] = $value;
            $solutionArray[] = $i;
        }

        $solutionValue = max($valueArray);

        //TODO предусмотреть случай с несколькими решениями
        $decisionSolutionModel->setSolution($solutionArray[array_search($solutionValue,$valueArray)]+1)
                              ->setValue($solutionValue);

        return $decisionSolutionModel;
    }

}