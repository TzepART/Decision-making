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

class MinimaxStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'minimax';

    /**
     * @param DecisionTaskModel $decisionTaskModel
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     */
    function getOptimalSolution(DecisionTaskModel $decisionTaskModel, DecisionSolutionModel $decisionSolutionModel){

        $minArray = [];
        $solutionArray = [];

        foreach ($decisionTaskModel->getMatrix()->toArray() as $index => $row) {
            $minValue = min($row);
            $minArray[] = $minValue;
            $solutionArray[] = array_search($minValue,$row);
        }

        $solutionValue = max($minArray);
        $solution = $solutionArray[array_search($solutionValue,$minArray)]+1;


        //TODO предусмотреть случай с несколькими решениями
        $decisionSolutionModel->setSolution($solution)
                              ->setValue($solutionValue);

        return $decisionSolutionModel;

    }

}