<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 17:55
 */

namespace AppBundle\Services;


abstract class AbstractStrategy extends FactoryStrategies
{

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