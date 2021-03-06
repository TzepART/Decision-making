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

class BayasLaplasStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'bayes-laplas';

    /**
     * @param DecisionTaskModel $decisionTaskModel
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     * @internal param array $matrix
     * @internal param int $coefficient
     */
    function getOptimalSolution(DecisionTaskModel $decisionTaskModel, DecisionSolutionModel $decisionSolutionModel)
    {

        $arProbabilities = $decisionTaskModel->getArProbabilities();

        $valueArray = [];
        $solutionArray = [];
        $newMatrix = [];

        foreach ($decisionTaskModel->getMatrix()->toArray() as $i => $row) {
            $newRow = [];
            foreach ($row as $j => $item) {
                $newRow[] = $arProbabilities[$j]*$item;
            }
            $newMatrix[]=$newRow;
            $valueArray[] = array_sum($newRow);
            $solutionArray[] = $i;
        }

        $solutionValue = max($valueArray);
        $solution = $solutionArray[array_search($solutionValue,$valueArray)]+1;

        //TODO предусмотреть случай с несколькими решениями
        $decisionSolutionModel->setSolution($solution)
                              ->setValue($solutionValue)
                              ->setNewMatrix($newMatrix);

        return $decisionSolutionModel;
    }

}