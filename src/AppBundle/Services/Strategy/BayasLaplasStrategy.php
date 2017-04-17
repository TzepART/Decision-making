<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:00
 */

namespace AppBundle\Services\Strategy;


class BayasLaplasStrategy extends AbstractStrategy
{
    const STRATEGY_NAME = 'bayes-laplas';
    private $arProbabilities = [];

    /**
     * @param array $matrix
     * @param int $coefficient
     * @return array
     */
    function getOptimalSolution($matrix, $coefficient = 0)
    {
        $result = [];

        if(empty($this->arProbabilities)){
            return $result;
        }

        $valueArray = [];
        $solutionArray = [];
        $newMatrix = [];

        foreach ($matrix as $i => $row) {
            $newRow = [];
            foreach ($row as $j => $item) {
                $newRow[] = $this->arProbabilities[$j]*$item;
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

    /**
     * @param $arProbabilities
     * @return $this
     */
    public function setArrayProbabilities($arProbabilities)
    {
        $this->arProbabilities = $arProbabilities;
        return $this;
    }

}