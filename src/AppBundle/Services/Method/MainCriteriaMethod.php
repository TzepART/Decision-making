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
use AppBundle\Model\MethodModel\MainCriteriaModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MainCriteriaMethod
 * @package AppBundle\Services\Method
 */

class MainCriteriaMethod extends AbstractMethod
{
    const METHOD_NAME = 'main-criteria';

    /**
     * @param Request $request
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     */
    public function getOptimalSolution(Request $request, DecisionSolutionModel $decisionSolutionModel)
    {
        /** @var MainCriteriaModel $matrixModel */
        $matrixModel = $this->initalMatrixModel($request);

        /*
         * Для каждого варианта
         *   - Массив с информацией по критериям
         *      - Место по критерию
         *      - Больше/меньше граничного условия
         * */
        $sortVariantsByCriteria = $this->getSortVariantsByCriteria($matrixModel);


        return $decisionSolutionModel;
    }

    /**
     * @param Request $request
     * @return ExtendMatrixModel
     * @internal param array $matrix
     * @internal param array $arCriteriaName
     * @internal param array $arVariantName
     * @internal param $limitations
     */
    protected function initalMatrixModel(Request $request): ExtendMatrixModel
    {
        $matrix = $request->get('matrix');
        $arCriteriaName = $request->get('columnName');
        $arVariantName = $request->get('rowName');
        $limitations = $request->get('limitations');
        $mainCriteria = $request->get('mainCriteria');

        $matrixModel = new MainCriteriaModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);
        $matrixModel->setLimitations($limitations);
        $matrixModel->setMainCriteria($mainCriteria);

        return $matrixModel;
    }


    /**
     * @param MainCriteriaModel $matrixModel
     * @return array
     * @internal param array $arCriteria
     */
    private function getSortVariantsByCriteria(MainCriteriaModel $matrixModel)
    {
        $result = [];
        $mainCriteriaKey = $matrixModel->getMainCriteriaKey();
        //Цикл по критериям
        foreach ($matrixModel->getVectorColumnName() as $index => $arCriterionName) {
            // Выбираем столбец с id критерия
            $arCriteria = $matrixModel->getColumnById($index);
            //Граничное условие
            $border = $matrixModel->getLimitations()[$index];

            //  Обратно сортируем его с сохранением ключей
            arsort($arCriteria);
            //Получаем массив ключей
            $arKeys =  array_keys($arCriteria);

            //Добавляем этот массив в массив, где ключ - id критерия + добавляем проверку на граничнеое условие
            foreach ($arKeys as $i => $key) {
                $result[$index]['sort_variants'][$i]['variant_key'] = $key;
                $result[$index]['sort_variants'][$i]['value'] = $arCriteria[$key];
                $result[$index]['sort_variants'][$i]['check_border'] = ($arCriteria[$key] >= $border);
            }
            $result[$index]['name'] = $arCriterionName;
            $result[$index]['main'] = ($index == $mainCriteriaKey);
        }

        //На выходе набор массивов с остсортированными вариантами
        return $result;
    }

}