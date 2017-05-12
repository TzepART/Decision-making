<?php

namespace AppBundle\Controller;

use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\MethodModel\MainCriteriaModel;
use AppBundle\Services\Method\MainCriteriaMethod;
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
        $method = $request->get('method');
        $solution = $this->get('app.method')->getMethod($method)->getOptimalSolution($request, new DecisionSolutionModel());

        return ['solution' => $solution];
    }
}
