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
     * @internal param array $matrix
     * @internal param int $coefficient
     */
    function getOptimalSolution(DecisionTaskModel $decisionTaskModel, DecisionSolutionModel $decisionSolutionModel){

        $minArray = [];
        $solutionArray = [];
        $tempArray = [];
        $result = [];

        $tempArray = $this->transponirating($decisionTaskModel->getMatrix());

        foreach ($tempArray as $index => $row) {
            $minValue = min($row);
            $minArray[] = $minValue;
            $solutionArray[] = array_search($minValue,$row);
        }

        $solutionValue = max($minArray);

        //TODO предусмотреть случай с несколькими решениями
        $decisionSolutionModel->setSolution($solutionArray[array_search($solutionValue,$minArray)]+1)
            ->setValue($solutionValue);

        return $decisionSolutionModel;

    }

}