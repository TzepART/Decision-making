<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 17:55
 */

namespace AppBundle\Services\Strategy;


use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;

abstract class AbstractStrategy
{

    public abstract function getOptimalSolution(DecisionTaskModel $decisionTaskModel, DecisionSolutionModel $decisionSolutionModel);

    /**
     * @param $array
     * @return array
     */
    protected function transponirating($array)
    {
        $tempArray = [];
        foreach ($array as $i => $row) {
            foreach ($row as $j => $item) {
                $tempArray[$j][$i] = $item;
            }
        }
        return $tempArray;
    }

}