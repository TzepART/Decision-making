<?php

namespace AppBundle\Controller;

use AppBundle\Model\MethodModel\DecisionSolutionModel;
use AppBundle\Model\MethodModel\BiasedIdealModel;
use AppBundle\Model\MethodModel\CommonCriteriaModel;
use AppBundle\Model\MethodModel\MainCriteriaModel;
use AppBundle\Model\MethodModel\ParetoModel;
use AppBundle\Services\Method\BiasedIdealMethod;
use AppBundle\Services\Method\CommonCriteriaMethod;
use AppBundle\Services\Method\MainCriteriaMethod;
use AppBundle\Services\Method\ParetoMethod;
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
        $matrixModel = MainCriteriaModel::getDefaultModel();
        return ['matrixModel' => $matrixModel, 'method' => MainCriteriaMethod::METHOD_NAME];
    }

    /**
     * @Route("/pareto/", name="method.pareto")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     */
    public function paretoAction()
    {
        $matrixModel = ParetoModel::getDefaultModel();
        return ['matrixModel' => $matrixModel, 'method' => ParetoMethod::METHOD_NAME];
    }

    /**
     * @Route("/common-criteria/", name="method.common-criteria")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     */
    public function commonCriteriaAction()
    {
        $matrixModel = CommonCriteriaModel::getDefaultModel();
        return ['matrixModel' => $matrixModel, 'method' => CommonCriteriaMethod::METHOD_NAME];
    }

    /**
     * @Route("/biased-ideal/", name="method.biased-ideal")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template()
     */
    public function biasedIdealAction()
    {
        $matrixModel = BiasedIdealModel::getDefaultModel();
        return ['matrixModel' => $matrixModel, 'method' => BiasedIdealMethod::METHOD_NAME];
    }

    /**
     * @Route("/solution/", name="method.get-solution")
     * @param Request $request
     * @Template()
     * @return array
     */
    public function getSolutionAction(Request $request)
    {
        $method = $request->get('method');
        $solution = $this->get('app.method')->getMethod($method)->getOptimalSolution($request, new DecisionSolutionModel());

        return ['solution' => $solution];
    }
}
