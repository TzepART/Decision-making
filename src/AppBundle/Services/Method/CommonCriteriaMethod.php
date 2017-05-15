<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.05.17
 * Time: 17:20
 */

namespace AppBundle\Services\Method;

use AppBundle\Model\MethodModel\DecisionSolutionModel;
use AppBundle\Model\ExtendMatrixModel;
use AppBundle\Model\MethodModel\CommonCriteriaModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommonCriteriaMethod
 * @package AppBundle\Services\Method
 *
 */

class CommonCriteriaMethod extends AbstractMethod
{
    const METHOD_NAME = 'common-criteria';

    /**
     * @param Request $request
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     */
    public function getOptimalSolution(Request $request, DecisionSolutionModel $decisionSolutionModel)
    {
        $select_variant = null;

        /** @var CommonCriteriaModel $matrixModel */
        $matrixModel = $this->initalMatrixModel($request);
        $variants = $this->getVariantsSortByWeight($matrixModel);


        $select_variant = array_shift($variants);

        $decisionSolutionModel->setMatrixModel($matrixModel);


        if($select_variant ==! null){
            $decisionSolutionModel->setSolution($matrixModel->getVectorRowName()[$select_variant]);
        }else{
            $decisionSolutionModel->setError('Нет вариантов, удовлетворяющего, условию метода');
        }

        return $decisionSolutionModel;
    }

    /**
     * @param Request $request
     * @return ExtendMatrixModel
     * @internal param array $matrix
     * @internal param array $arCriteriaName
     * @internal param array $arVariantName
     */
    protected function initalMatrixModel(Request $request): ExtendMatrixModel
    {
        $matrix = $request->get('matrix');
        $arCriteriaName = $request->get('columnName');
        $arVariantName = $request->get('rowName');
        $arSignificance = $request->get('significances');

        $matrixModel = new CommonCriteriaModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);
        $matrixModel->setSignificance($arSignificance);

        return $matrixModel;
    }

    /**
     * @param CommonCriteriaModel $matrixModel
     * @return array
     * @internal param array $arCriteria
     */
    private function getVariantsSortByWeight(CommonCriteriaModel $matrixModel)
    {
        $result = [];
        //Цикл по критериям
        foreach ($matrixModel->getVectorColumnName() as $indexC => $criterionName) {
            $significance = $matrixModel->getSignificance()[$indexC];
            $arColumn = $matrixModel->getColumnById($indexC);

            foreach ($arColumn as $indexV => $value) {
                if(isset($result[$indexV])){
                    $result[$indexV] += $significance*$value;
                }else{
                    $result[$indexV] = $significance*$value;
                }
            }
        }

        arsort($result);

        //На выходе набор наихудших вариантов по критериям
        return array_keys($result);
    }

}