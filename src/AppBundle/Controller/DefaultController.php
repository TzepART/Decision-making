<?php

namespace AppBundle\Controller;

use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;
use AppBundle\Services\Strategy\BayasLaplasStrategy;
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
            $decisionTaskModel = new DecisionTaskModel();
            $decisionTaskModel->setMatrix($matrix)
                ->setCoefficient($coefficient);

            /**
             * @var DecisionSolutionModel $result
             * */
            $result = $this->get('app.strategy_manager')->getSolution($strategyName, $decisionTaskModel);

            $solution .= $strategyName . '<br>';
            $solution .= 'solution ' . $result->getSolution() . '<br>';
            $solution .= 'value ' . $result->getValue() . '<br>';
            return $this->render('@App/Task/solution.html.twig', ['solution' => $solution]);
        }

        return $this->render('@App/Task/simpleStrategy.html.twig');
    }

    /**
     * @Route("/tasks/bl_strategy/", name="tasks.bl_strategy")
     */
    public function blStrategyAction(Request $request)
    {
        $arCountElements = !empty($request->get('x')) ? $request->get('x') : null;
        $arProbabilities = !empty($request->get('p')) ? $request->get('p') : null;
        $cost = !empty($request->get('cost')) ? $request->get('cost') : null;
        $good_price = !empty($request->get('good_price')) ? $request->get('good_price') : null;
        $bad_price = !empty($request->get('bad_price')) ? $request->get('bad_price') : null;

        if(!empty($arCountElements) && !empty($arProbabilities)&& !empty($cost)&& !empty($good_price)&& !empty($bad_price)){

            $solution = '';
            $matrix = $this->createBLMatrix($arCountElements, $good_price, $bad_price, $cost);

            $decisionTaskModel = new DecisionTaskModel();
            $decisionTaskModel->setArProbabilities($arProbabilities)
                ->setMatrix($matrix);

            /**
             * @var DecisionSolutionModel $result
             * */
            $result = $this->get('app.strategy_manager')->getSolution(BayasLaplasStrategy::STRATEGY_NAME, $decisionTaskModel);

            $solution .= '<br>';
            foreach ($result->getNewMatrix() as $index => $row) {
                $solution .= '| ';
                foreach ($row as $i => $item) {
                    $solution .= $item . ' | ';
                }
                $solution .= '<br>';
            }

            $solution .= 'bayes-laplas <br>';
            $solution .= 'solution ' . $result->getSolution() . '<br>';
            $solution .= 'value ' . $result->getValue() . '<br>';

            return $this->render('@App/Task/solution.html.twig', ['solution' => $solution]);

        }


        return $this->render('@App/Task/blStrategy.html.twig');
    }


    /**
     * @param $arCountElements
     * @param $good_price
     * @param $bad_price
     * @param $cost
     * @return array
     */
    private function createBLMatrix($arCountElements, $good_price, $bad_price, $cost)
    {
        $matrix = [];
        $count = count($arCountElements);

        for ($i = 0; $i < $count; $i++) {
            $payedCount = $arCountElements[$i];
            foreach ($arCountElements as $j => $realizedCount) {
                $unrealizedCount = 0;
                if ($payedCount > $realizedCount) {
                    $unrealizedCount = $realizedCount - $payedCount;
                } else {
                    $realizedCount = $payedCount;
                }
                $matrix[$i][$j] = $good_price * $realizedCount + $bad_price * $unrealizedCount - $cost * $payedCount;
            }
        }

        return $matrix;
    }
}
