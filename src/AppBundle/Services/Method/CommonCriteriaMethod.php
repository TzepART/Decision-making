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
        /** @var CommonCriteriaModel $matrixModel */
        $matrixModel = $this->initalMatrixModel($request);
        $select_variants = [];

        $arBadVariants = $this->getBadVariantsByCriteria($matrixModel);

        foreach ($matrixModel->getVectorRowName() as $index => $item) {
            if(!array_search($index,$arBadVariants)){
                $select_variants[] = $item;
            }
        }


        if(!empty($select_variants)){
            $decisionSolutionModel->setSolution(implode(',',$select_variants));
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

        $matrixModel = new CommonCriteriaModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);

        return $matrixModel;
    }

    /**
     * @param CommonCriteriaModel $matrixModel
     * @return array
     * @internal param array $arCriteria
     */
    private function getBadVariantsByCriteria(CommonCriteriaModel $matrixModel)
    {
        $result = [];
        //Цикл по критериям
        foreach ($matrixModel->getVectorColumnName() as $index => $arCriterionName) {
            // Выбираем столбец с id критерия
            $arCriteria = $matrixModel->getColumnById($index);

            //  Обратно сортируем его с сохранением ключей
            arsort($arCriteria);
            //Получаем наихудший по критерию вариант
            $variantKeys = array_keys($arCriteria);
            $badVariant =  array_pop($variantKeys);

            //Добавляем в массив, где ключ - id критерия
            $result[$index] = $badVariant;
        }

        //На выходе набор наихудших вариантов по критериям
        return $result;
    }

}