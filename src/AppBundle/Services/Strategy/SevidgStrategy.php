<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:04
 */

namespace AppBundle\Services\Strategy;


use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;

class SevidgStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'savidg';

    /**
     * @param DecisionTaskModel $decisionTaskModel
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     * @internal param array $matrix
     * @internal param int $coefficient
     */
    function getOptimalSolution(DecisionTaskModel $decisionTaskModel, DecisionSolutionModel $decisionSolutionModel){

        $refactorArray = [];
        $maxArray = [];
        $result = [];

        $tempArray = $this->transponirating($decisionTaskModel->getMatrix());

        foreach ($tempArray as $i => $col) {
            $max = max($col);
            foreach ($col as $j => $item) {
                $refactorArray[$j][$i] = $max-$item;
            }
        }

        $solution = 0;
        $solutionValue = max($refactorArray[0]);
        foreach ($refactorArray as $index => $row) {
            $max = max($row);
            if($max < $solutionValue){
                $solution = $index+1;
                $solutionValue = $max;
            }
        }

        //TODO предусмотреть случай с несколькими решениями
        $decisionSolutionModel->setSolution($solution)
            ->setValue($solutionValue);

        return $decisionSolutionModel;

    }

}