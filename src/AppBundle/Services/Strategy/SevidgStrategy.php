<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:04
 */

namespace AppBundle\Services\Strategy;


use AppBundle\Model\DecisionTaskModel;

class SevidgStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'savidg';

    /**
     * @param DecisionTaskModel $decisionTaskModel
     * @return array
     * @internal param array $matrix
     * @internal param int $coefficient
     */
    function getOptimalSolution(DecisionTaskModel $decisionTaskModel)
    {
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

        $result['solution'] = $solution;
        $result['value'] = $solutionValue;

        return $result;
    }

}