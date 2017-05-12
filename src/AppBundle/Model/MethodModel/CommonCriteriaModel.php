<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.05.17
 * Time: 19:18
 */

namespace AppBundle\Model\MethodModel;


use AppBundle\Model\ExtendMatrixModel;

/**
 * Class CommonCriteriaModel
 * @package AppBundle\Model\MethodModel
 */
class CommonCriteriaModel extends ExtendMatrixModel
{
    /**
     * @var array
     */
    private $significance;

    /**
     * @return CommonCriteriaModel()
     */
    public static function getDefaultModel()
    {
        $arVariantName = ['U1','U2','U3','U4','U5','U6','U7','U8','U9'];
        $arCriteriaName = ['Общая площадь','Площадь кухни','Близость метро','Качество дома','Цена','Этаж'];
        $arSignificance = [0.2,0.2,0.2,0.2,0.1,0.1];

        $matrix = [
            [10,2,3,2,2,4],
            [4,7,3,4,3,1],
            [6,8,3,5,4,2],
            [9,2,3,2,1,3],
            [5,1,0.1,5,2,2],
            [3,8,0.1,2,4,1],
            [3,5,4,2,7,7],
            [3,6,3,4,4,1],
            [2,5,3,1,5,7],
        ];

        $matrixModel = new CommonCriteriaModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);
        $matrixModel->setSignificance($arSignificance);

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
     * @return array
     */
    public function getSignificance(): array
    {
        return $this->significance;
    }

    /**
     * @param array $significance
     */
    public function setSignificance(array $significance)
    {
        $this->significance = $significance;
    }



}