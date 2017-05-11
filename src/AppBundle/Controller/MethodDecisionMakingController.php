<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


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
     */
    public function mainCriteriaAction()
    {
        return null;
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
}
