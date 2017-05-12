<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.05.17
 * Time: 19:18
 */

namespace AppBundle\Model\MethodModel;


use AppBundle\Model\ExtendMatrixModel;

class ParetoModel extends ExtendMatrixModel
{

    /**
     * @return ParetoModel()
     */
    public static function getDefaultModel()
    {
        $arCriteriaName = ['K1','K2','K3','K4','K5','K6','K7','K8'];
        $arVariantName = ['Смена', 'Час Пик', 'Невское время', 'Вечерний Пб', 'СПб ведомости', 'Деловой Пб', 'Реклама - Шанс'];

        $matrix = [
            [0.008,0.100,0.500,44000,500,2800000,0.3,30],
            [0.010,0.0625,0.125,70000,700,3000000,0.8,45],
            [0.010,0.1111,0.200,47000,500,2550000,0.2,19],
            [0.010,0.1250,0.050,49000,600,2600000,0.6,20],
            [0.008,0.2000,0.143,45000,400,2500000,0.3,13],
            [0.003,0.2500,0.167,80000,600,3300000,0.1,92],
            [0.001,0.7500,0.038,85000,600,2500000,0.9,11],
        ];

        $matrixModel = new ParetoModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);

        return $matrixModel;
    }

    /**
     * @return array
     */
    public function getVectorRowName(): array
    {
        return $this->vectorRowName;
    }


}