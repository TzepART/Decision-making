<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.04.17
 * Time: 12:10
 */

namespace AppBundle\Model;


class DecisionTaskModel
{
    protected $matrix;
    protected $coefficient;
    protected $arProbabilities;
    protected $blMatrix;

    /**
     * @return array
     */
    public function getBlMatrix()
    {
        return $this->blMatrix;
    }

    /**
     * @param array $arCountElements
     * @param float $good_price
     * @param float $bad_price
     * @param float $cost
     * @return $this
     */
    public function setBlMatrix($arCountElements, $good_price, $bad_price, $cost)
    {
        $this->blMatrix = [];
        $count = count($arCountElements);

        for ($i = 0; $i < $count; $i++) {
            $payedCount = $arCountElements[$i];
            foreach ($arCountElements as $j => $realizedCount) {
                $unrealizedCount = 0;
                if ($payedCount > $realizedCount) {
                    $unrealizedCount = $realizedCount - $payedCount;
                } else {
                    $realizedCount = $payedCount;
                }
                $this->blMatrix[$i][$j] = $good_price * $realizedCount + $bad_price * $unrealizedCount - $cost * $payedCount;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMatrix()
    {
        return $this->matrix;
    }

    /**
     * @param array $matrix
     * @return $this
     */
    public function setMatrix($matrix)
    {
        $this->matrix = $matrix;
        return $this;
    }

    /**
     * @return float
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * @param float $coefficient
     * @return $this
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;
        return $this;
    }

    /**
     * @return array
     */
    public function getArProbabilities()
    {
        return $this->arProbabilities;
    }

    /**
     * @param array $arProbabilities
     * @return $this
     */
    public function setArProbabilities($arProbabilities)
    {
        $this->arProbabilities = $arProbabilities;
        return $this;
    }


}