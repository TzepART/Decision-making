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
 * Class BiasedIdealModel
 * @package AppBundle\Model\MethodModel
 */
class BiasedIdealModel extends ExtendMatrixModel
{
    /**
     * @var array
     */
    private $significance;


    /**
     * @var array
     */
    private $maxType;

    /**
     * @return BiasedIdealModel()
     */
    public static function getDefaultModel()
    {
        $arVariantName = ['МКО1','МКО2','МКО3','МКО4','МКО5','МКО6'];
        $arCriteriaName = ['K1','K2','K3'];
        $arSignificance = [6,6,2];
        $maxType = [false,true,false];

        $matrix = [
            [5.0,1.0,50.0],
            [10.0,1.5,40.0],
            [2.0,1.5,90.0],
            [1.0,1.0,100.0],
            [6.0,3.0,100.0],
            [16.0,3.5,50.0],
        ];

        $matrixModel = new BiasedIdealModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);
        $matrixModel->setSignificance($arSignificance);
        $matrixModel->setMaxType($maxType);

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

    /**
     * @return array
     */
    public function getMaxType(): array
    {
        return $this->maxType;
    }

    /**
     * @param array $maxType
     */
    public function setMaxType(array $maxType)
    {
        $this->maxType = $maxType;
    }

}