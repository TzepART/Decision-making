<?php

namespace AppBundle\Controller;

use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\ExtendMatrixModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/method")
 * Class MethodDecisionMakingController
 * @package AppBundle\Controller
 * @Template()
 */
class MethodDecisionMakingController extends Controller
{
    /**
     * @Route("/main-criteria/", name="method.main-criteria")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     * @return array
     */
    public function mainCriteriaAction()
    {
        $arCriteriaName = ['К1','К2','К3','К4','К5','К6','К7','К8'];
        $arVariantName = ['Смена', 'Час Пик', 'Невское время', 'Вечерний Пб', 'СПб ведомости', 'Деловой Пб', 'Реклама - Шанс'];
        $matrix = [
            [0.008,0.100,0.500,44000,500,2800000,0.3,30],
            [0.010,0.0625,0.125,70000,700,3000000,0.8,45],
            [0.010,0.1111,0.200,47000,500,2550000,0.2,19],
            [0.010,0.1250,0.050,49000,600,2600000,0.6,20],
            [0.008,0.2000,0.143,45000,400,2500000,0.3,13],
            [0.003,0.2500,0.167,80000,600,3300000,0.1,92],
            [0.001,0.7500,0.038,85000,600,2500000,0.9,11],
        ];

        $matrixModel = $this->initalMatrixModel($matrix, $arCriteriaName, $arVariantName);

        return ['matrixModel' => $matrixModel, 'method' => 'main-criteria'];
    }

    /**
     * @Route("/pareto/", name="method.pareto")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     */
    public function paretoAction()
    {
        return null;
    }

    /**
     * @Route("/common-criteria/", name="method.common-criteria")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     */
    public function commonCriteriaAction()
    {
        return null;
    }

    /**
     * @Route("/biased-ideal/", name="method.biased-ideal")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     */
    public function biasedIdealAction()
    {
        return null;
    }

    /**
     * @Route("/solution/", name="method.get-solution")
     * @param Request $request
     * @Template()
     * @return array
     */
    public function getSolutionAction(Request $request)
    {
        $solution = new DecisionSolutionModel();
        $matrixModel = $this->initalMatrixModel($request->get('matrix'),$request->get('columnName'),$request->get('rowName'));
        $method = $request->get('method');

        return ['solution' => $solution];
    }

    /**
     * @param array $matrix
     * @param array $arCriteriaName
     * @param array $arVariantName
     * @return ExtendMatrixModel
     */
    private function initalMatrixModel($matrix, $arCriteriaName, $arVariantName): ExtendMatrixModel
    {
        $matrixModel = new ExtendMatrixModel($matrix);
        $matrixModel->setVectorColumnName($arCriteriaName);
        $matrixModel->setVectorRowName($arVariantName);

        return $matrixModel;
    }
}
