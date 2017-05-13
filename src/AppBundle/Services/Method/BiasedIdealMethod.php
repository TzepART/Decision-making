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
        $solution = '';

        /** @var BiasedIdealModel $matrixModel */
        $matrixModel = $this->initalMatrixModel($request);
        $variants = $this->getSortVariants($matrixModel);

        if(!empty($variants)){
            $solution = '</br>';
            foreach ($variants as $p => $arVariants) {
                $keys = array_keys($arVariants);
                $solution.= 'При P = '.$p.' - '.implode(' > ',$keys).'</br>';
            }
        }



        if($solution ==! ''){
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
    private function getSortVariants(BiasedIdealModel $matrixModel)
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
            //TODO добавить флаг на то, в каком направлении смотреть критерий
            if($indexC == 1){
                arsort($arColumn);
            }else{
                asort($arColumn);
            }

            //IdealPositive добалем [indexC] = max $arColumn
            //IdealNegative [indexC] = min $arColumn
            $idealPositive[$indexC] = array_shift($arColumn);
            $idealNegative[$indexC] = array_pop($arColumn);
        }

        dump($idealPositive);
        dump($idealNegative);

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
                $newRow = [];
                foreach ($row as $index => $val) {
                    $newRow[] = $val**$p;
                }
                $arTemp[$matrixModel->getVectorRowName()[$varId]] = array_sum($newRow)**(1/$p);
            }
            arsort($arTemp);
            $result[$p] = $arTemp;
        }

        return $result;
    }

}