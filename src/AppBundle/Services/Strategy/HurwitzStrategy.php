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
        $coefficient = $decisionTaskModel->getCoefficient();

        foreach ($decisionTaskModel->getMatrix()->toArray() as $i => $row) {
            $max = max($row);
            $min = min($row);
            $value = $coefficient*$min+(1-$coefficient)*$max;
            $valueArray[] = $value;
            $solutionArray[] = $i;
        }

        $solutionValue = max($valueArray);
        $solution = $solutionArray[array_search($solutionValue,$valueArray)]+1;

        //TODO предусмотреть случай с несколькими решениями
        $decisionSolutionModel->setSolution($solution)
                              ->setValue($solutionValue);

        return $decisionSolutionModel;
    }

}