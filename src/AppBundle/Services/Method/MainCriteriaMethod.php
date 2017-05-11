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
        $matrixModel = $this->initalMatrixModel($request);

        dump($matrixModel);
        die();

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

}