<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.05.17
 * Time: 19:18
 */

namespace AppBundle\Model\MethodModel;


use AppBundle\Model\ExtendMatrixModel;

class MainCriteriaModel extends ExtendMatrixModel
{
    /**
     * @var array
     */
    protected $limitations = [];


    /**
     * @return array
     */
    public function getVectorRowName(): array
    {
        return $this->vectorRowName;
    }

    /**
     * @param array $limitations
     */
    public function setLimitations(array $limitations)
    {
        if (empty($this->limitations) && count($limitations) == $this->getCountColumns()) {
            foreach ($this->getColumnKeys() as $index => $columnKey) {
                $this->limitations[$columnKey] = $limitations[$index];
            }
        }
    }

    /**
     * @return array
     */
    public function getLimitations(): array
    {
        return $this->limitations;
    }

}