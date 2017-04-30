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


    /**
     * @param MatrixModel $matrix
     * @return $this
     */
    public function setMatrix(MatrixModel $matrix)
    {
        $this->matrix = $matrix;
        return $this;
    }

    /**
     * @return MatrixModel
     */
    public function getMatrix()
    {
        return $this->matrix;
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