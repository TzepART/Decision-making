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
        $select_variant = null;

        /*
         * Для каждого варианта
         *   - Массив с информацией по критериям
         *      - Место по критерию
         *      - Больше/меньше граничного условия
         * */
        $sortVariantsByCriteria = $this->getSortVariantsByCriteria($matrixModel);

        /*
         * Выберем главный критерий, и удалим его из общего массива
         * */
        $mainCriteria = $sortVariantsByCriteria[$matrixModel->getMainCriteriaKey()];
        unset($sortVariantsByCriteria[$matrixModel->getMainCriteriaKey()]);

        /*
         * Пробежимся по массиву с вариантами для главного критерия
         * */
        foreach ($mainCriteria['sort_variants'] as $index => $sort_variant) {
            //если проходят граничные условия для главного критерия
            if($sort_variant['check_border']){
                $check_border = true;
                $variant_key = $sort_variant['variant_key'];
                //проверим на граничные условия по остальным критериям и если он прошел по остальным, значит это победитель!
                foreach ($sortVariantsByCriteria as $sortVariantsByCriterion) {
                    foreach ($sortVariantsByCriterion["sort_variants"] as $item) {
                        if($item["variant_key"] == $variant_key && !$item["check_border"]){
                            $check_border = false;
                            break;
                        }
                    }
                }
                if($check_border){
                    $select_variant = $variant_key;
                    break;
                }
            }
        }
        if($select_variant ==! null){
            $decisionSolutionModel->setSolution($matrixModel->getVectorRowName()[$select_variant]);
        }else{
            $decisionSolutionModel->setError('Нет варианта, удовлетворяющего, условию метода');
        }

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