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
        if(empty($this->vectorColumnName) && count($vectorColumnName) == $this->getCountColumns()) {
            foreach ($this->getColumnKeys() as $index => $columnKey) {
                $this->vectorColumnName[$columnKey] = $vectorColumnName[$index];
            }
        }
    }

    /**
     * @return array
     */
    public function getVectorColumnName(): array
    {
        return $this->vectorColumnName;
    }


    /**
     * @param array $vectorRowName
     */
    public function setVectorRowName(array $vectorRowName)
    {
        if (empty($this->vectorRowName) && count($vectorRowName) == $this->getCountRows()) {
            foreach ($this->getRowKeys() as $index => $columnKey) {
                $this->vectorRowName[$columnKey] = $vectorRowName[$index];
            }
        }
    }

    /**
     * @return array
     */
    public function getVectorRowName(): array
    {
        return $this->vectorRowName;
    }

}