<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:00
 */

namespace AppBundle\Services\Strategy;


use AppBundle\Model\DecisionTaskModel;

class HurwitzStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'hurwitz';

    /**
     * @param DecisionTaskModel $decisionTaskModel
     * @return array
     * @internal param array $matrix
     * @internal param int $coefficient
     */
    function getOptimalSolution(DecisionTaskModel $decisionTaskModel){

        $valueArray = [];
        $solutionArray = [];
        $result = [];

        foreach ($decisionTaskModel->getMatrix() as $i => $col) {
            $max = max($col);
            $min = min($col);
            $value = $decisionTaskModel->getCoefficient()*$min+(1-$decisionTaskModel->getCoefficient())*$max;
            $valueArray[] = $value;
            $solutionArray[] = $i;
        }

        $solutionValue = max($valueArray);

        //TODO предусмотреть случай с несколькими решениями
        $result['solution'] = $solutionArray[array_search($solutionValue,$valueArray)]+1;
        $result['value'] = $solutionValue;


        return $result;
    }

}