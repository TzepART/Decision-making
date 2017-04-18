<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:default:index.html.twig');
    }

    /**
     * @Route("/tasks/simple_strategy/", name="tasks.simple_strategy")
     */
    public function simpleStrategyAction(Request $request)
    {
        $solution = '';
        $matrix = !empty($request->get('matrix')) ? $request->get('matrix') : null;
        $strategyName = !empty($request->get('strategy')) ? $request->get('strategy') : null;
        $coefficient = !empty($request->get('coefficient')) ? $request->get('coefficient') : 0;

        if(!empty($matrix) && !empty($strategyName)){
            $result = $this->get('app.strategy_manager')->getSolution($strategyName, $matrix, $coefficient);

            $solution = '';
            $solution .= $strategyName . '<br>';
            $solution .= 'solution ' . $result['solution'] . '<br>';
            $solution .= 'value ' . $result['value'] . '<br>';
            return $this->render('@App/Task/solution.html.twig', ['solution' => $solution]);
        }

        return $this->render('@App/Task/simpleStrategy.html.twig');
    }
}
