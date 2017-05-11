<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 30.04.17
 * Time: 2:24
 */

namespace AppBundle\Model;

/**
 * Class ExtendMatrixModel
 * @package AppBundle\Model
 */
class ExtendMatrixModel extends MatrixModel
{
    /**
     * @var array
     */
    protected $vectorColumnName = [];

    /**
     * @var array
     */
    protected $vectorRowName = [];


    /**
     * @param array $vectorColumnName
     */
    public function setVectorColumnName(array $vectorColumnName)
    {
        $this->vectorColumnName = $vectorColumnName;
    }

    /**
     * @return array
     */
    public function getVectorColumnName(): array
    {
        return $this->vectorColumnName;
    }


    public function addVectorColumnName()
    {
        if (!empty($this->vectorColumnName) && count($this->vectorColumnName) == $this->count_col) {
            array_unshift($this->matrix,$this->vectorColumnName);
        }
    }


    public function removeVectorColumnName()
    {
        if (!empty($this->vectorColumnName)) {
            $firstRow = array_shift($this->matrix);

            /*
             * if first row != return first row back
             * */
            if($firstRow != $this->vectorColumnName){
                array_unshift($this->matrix,$firstRow);
            }
        }
    }



    /**
     * @param array $vectorRowName
     */
    public function setVectorRowName(array $vectorRowName)
    {
        $this->vectorRowName = $vectorRowName;
    }

    /**
     * @return array
     */
    public function getVectorRowName(): array
    {
        return $this->vectorRowName;
    }


    public function addVectorRowName()
    {
        if (!empty($this->vectorRowName) && count($this->vectorRowName) == $this->count_row) {
            array_unshift($this->matrix,$this->vectorRowName);
        }
    }


    /**
     * @return array
     */
    public function removeVectorRowName(): array
    {
        return $this->vectorRowName;
    }


}