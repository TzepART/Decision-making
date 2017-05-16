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

    /*
     * Варианты имеющие максимальные результаты по критериям
     * */
    protected $winVariants = [];

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

        $solution = '</br> Варианты имеющие максимальные результаты по критериям:</br>';

        foreach ($this->winVariants as $indexC => $winVariants) {
            $solution .= $matrixModel->getVectorColumnName()[$indexC].' - '.implode(', ',$winVariants).'</br>';
        }

        $solution .= '</br>Наиболее подходящий - '.$matrixModel->getVectorRowName()[$select_variant];

        if($select_variant ==! null){
            $decisionSolutionModel->setSolution($solution);
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

            //Получим отсортированный список значений по критерию
            arsort($arColumn);
            //первое - соответсвует максимальному значению по критерию
            $maxValue = current($arColumn);
            foreach ($arColumn as $indexV => $item) {
                if($item == $maxValue){
                    $this->winVariants[$indexC][]=$matrixModel->getVectorRowName()[$indexV];
                }else{
                    break;
                }
            }

        }

        arsort($result);

        //На выходе набор наихудших вариантов по критериям
        return array_keys($result);
    }

}