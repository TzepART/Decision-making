<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:00
 */

namespace AppBundle\Services\Strategy;


use AppBundle\Model\DecisionTaskModel;

class BayasLaplasStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'bayes-laplas';

    /**
     * @param DecisionTaskModel $decisionTaskModel
     * @return array
     * @internal param array $matrix
     * @internal param int $coefficient
     */
    function getOptimalSolution(DecisionTaskModel $decisionTaskModel)
    {
        $result = [];

        $arProbabilities = $decisionTaskModel->getArProbabilities();

        $valueArray = [];
        $solutionArray = [];
        $newMatrix = [];

        foreach ($decisionTaskModel->getBlMatrix() as $i => $row) {
            $newRow = [];
            foreach ($row as $j => $item) {
                $newRow[] = $arProbabilities[$j]*$item;
            }
            $newMatrix[]=$newRow;
            $valueArray[] = array_sum($newRow);
            $solutionArray[] = $i;
        }

        $solutionValue = max($valueArray);

        //TODO предусмотреть случай с несколькими решениями
        $result['solution'] = $solutionArray[array_search($solutionValue,$valueArray)]+1;
        $result['value'] = $solutionValue;
        $result['new_matrix'] = $newMatrix;


        return $result;
    }

}