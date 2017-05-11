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
     * @var string
     */
    protected $mainCriteria;


    /**
     * @return MainCriteriaModel
     */
    public static function getDefaultModel()
    {
        $arCriteriaName = ['К1','К2','К3','К4','К5','К6','К7','К8'];
        $arVariantName = ['Смена', 'Час Пик', 'Невское время', 'Вечерний Пб', 'СПб ведомости', 'Деловой Пб', 'Реклама - Шанс'];
        $arLimitations = [0.010,0.1000,0.038,44000,400,2500000,0.3,10];

        $matrix = [
            [0.008,0.100,0.500,44000,500,2800000,0.3,30],
            [0.010,0.0625,0.125,70000,700,3000000,0.8,45],
            [0.010,0.1111,0.200,47000,500,2550000,0.2,19],
            [0.010,0.1250,0.050,49000,600,2600000,0.6,20],
            [0.008,0.2000,0.143,45000,400,2500000,0.3,13],
            [0.003,0.2500,0.167,80000,600,3300000,0.1,92],
            [0.001,0.7500,0.038,85000,600,2500000,0.9,11],
        ];

        $matrixModel = new MainCriteriaModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);
        $matrixModel->setLimitations($arLimitations);
        $matrixModel->setMainCriteria('K1');

        return $matrixModel;
    }

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

    /**
     * @return string
     */
    public function getMainCriteria(): string
    {
        return $this->mainCriteria;
    }

    /**
     * @param string $mainCriteria
     */
    public function setMainCriteria(string $mainCriteria)
    {
        $this->mainCriteria = $mainCriteria;
    }



}