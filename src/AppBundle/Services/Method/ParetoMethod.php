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
use AppBundle\Model\MethodModel\ParetoModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ParetoMethod
 * @package AppBundle\Services\Method
 *
 * Процедура выделения множества Парето.
 * Процедура состоит из N циклов сравнения каждого очередного элемента множества (N- число элементов в множестве ),
 * последовательно со всеми  элементами текущего множества Парето (списка на данный момент).
 * Возможны три исхода:
 *  1. Очередной элемент множества не хуже хотя бы одного из элементов текущего множества Парето. Тогда он сразу же
 * и окончательно выбывает из участия в процедуре.
 *  2. Очередной элемент множества не хуже, но и лучше,  ни одного из
 * элементов текущего множества Парето. Тогда он добавляется к списку текущего множества Парето.
 *  3. Очередной элемент множества лучше одного или нескольких элементов текущего множества Парето.
 * Тогда этот элемент множества добавляется к списку текущего множества Парето, а элементы текущего
 * множества, худшие, чем добавленный, исключаются из текущего множества и выбывают из процедуры.
 *
 * После N циклов список текущего множества Парето становится окончательным.
 */

class ParetoMethod extends AbstractMethod
{
    const METHOD_NAME = 'pareto';

    /**
     * @param Request $request
     * @param DecisionSolutionModel $decisionSolutionModel
     * @return DecisionSolutionModel
     */
    public function getOptimalSolution(Request $request, DecisionSolutionModel $decisionSolutionModel)
    {
        /** @var ParetoModel $matrixModel */
        $matrixModel = $this->initalMatrixModel($request);
        $select_variant = null;

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
     */
    protected function initalMatrixModel(Request $request): ExtendMatrixModel
    {
        $matrix = $request->get('matrix');
        $arCriteriaName = $request->get('columnName');
        $arVariantName = $request->get('rowName');

        $matrixModel = new ParetoModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);

        return $matrixModel;
    }

}