<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:00
 */

namespace AppBundle\Services;


class HurwitzStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'hurwitz';

    /**
     * @param array $matrix
     * @param int $coefficient
     * @return array
     */
    function getOptimalSolution($matrix, $coefficient = 0)
    {
        $valueArray = [];
        $solutionArray = [];
        $result = [];

        foreach ($matrix as $i => $col) {
            $max = max($col);
            $min = min($col);
            $value = $coefficient*$min+(1-$coefficient)*$max;
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