<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.05.17
 * Time: 17:20
 */

namespace AppBundle\Services\Method;

use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\ExtendMatrixModel;
use AppBundle\Model\MethodModel\BiasedIdealModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BiasedIdealMethod
 * @package AppBundle\Services\Method
 *
 */

class BiasedIdealMethod extends AbstractMethod
{
    const METHOD_NAME = 'biased-deal';

    /**
     * @param Request $request
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     */
    public function getOptimalSolution(Request $request, DecisionSolutionModel $decisionSolutionModel)
    {
        $select_variant = null;

        /** @var BiasedIdealModel $matrixModel */
        $matrixModel = $this->initalMatrixModel($request);
        $variants = $this->getVariantsSortByWeight($matrixModel);


        $select_variant = array_shift($variants);


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

        $matrixModel = new BiasedIdealModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);
        $matrixModel->setSignificance($arSignificance);

        return $matrixModel;
    }

    /**
     * @param BiasedIdealModel $matrixModel
     * @return array
     * @internal param array $arCriteria
     */
    private function getVariantsSortByWeight(BiasedIdealModel $matrixModel)
    {
        $result = [];
        /*
          Создаем IdealPositive
          Создаем IdealNegative
          Бежим циклом по критриям
            Выбираем столбцы по id критерия
            Сортируем их
            IdealPositive добалем [indexC] = max
            IdealNegative [indexC] = min

            Делаем обновленную матрицу, где значения ячеек
            D ij = (K+j- Kij) / (K+j- K-j)

            Домножаются на веса и вычислется для каждого варианта  рас-
            стояния от объектов до идеального, используя выражение:
            Lpi= sum((Wj*(1-D ij))) p
            при p от 1 .. 5
         */
        //Создаем IdealPositive, IdealNegative
        $idealPositive = [];
        $idealNegative = [];

        //Бежим циклом по критриям
        foreach ($matrixModel->getVectorColumnName() as $indexC => $criterionName) {
            $significance = $matrixModel->getSignificance()[$indexC];
            //Выбираем столбцы по id критерия
            $arColumn = $matrixModel->getColumnById($indexC);

            //Сортируем
            arsort($arColumn);

            //IdealPositive добалем [indexC] = max $arColumn
            //IdealNegative [indexC] = min $arColumn
            $idealPositive[$indexC] = array_shift($arColumn);
            $idealNegative[$indexC] = array_pop($arColumn);
        }

        $updateMatrix = [];
        //Делаем обновленную матрицу, где значения ячеек
        //D ij = (K+j- Kij) / (K+j- K-j) ->
        // -> Wj*(1-D ij)
        $W = $matrixModel->getSignificance();
        foreach ($matrixModel->toArray() as $varId => $row) {
            foreach ($row as $critId => $value) {
                $D = ($idealPositive[$critId] - $value)/($idealPositive[$critId] - $idealNegative[$critId]);
                $updateMatrix[$varId][$critId] = $W[$critId]*(1-$D);
            }
        }

        // Вычислим расстояния от объектов до идеального,
        // используя выражение:
        // Lpi= sum((Wj*(1-D ij))) p
        // при p от 1..5
        for($p = 1; $p <= 5; $p++){
            $arTemp = [];
            foreach ($updateMatrix as $varId => $row) {
                $arTemp[$varId] = array_sum($row)**$p;
            }
            arsort($arTemp);
            $result[$p] = $arTemp;
        }

        dump($result);
        die();

        return $result;
    }

}