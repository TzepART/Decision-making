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
        $decisionTaskModel = $this->initialTaskModel($request);

        if($request->get('strategy') && !empty($decisionTaskModel->getMatrix())){

            /**
             * @var DecisionSolutionModel $result
             * */
            $result = $this->get('app.strategy_manager')->getSolution($request->get('strategy'), $decisionTaskModel);

            return $this->render('@App/Stratagy/solution.html.twig',
                [
                    'result' => $result,
                    'initial_matrix' => $decisionTaskModel->getMatrix()
                ]
            );
        }

        return $this->render('@App/Stratagy/simpleStrategy.html.twig');
    }

    /**
     * @Route("/tasks/bl_strategy/", name="tasks.bl_strategy")
     */
    public function blStrategyAction(Request $request)
    {
        $matrix = $this->createBLMatrix($request);
        $decisionTaskModel = $this->initialTaskModel($request);

        if(!empty($matrix) && !empty($decisionTaskModel->getArProbabilities())){

            $decisionTaskModel->setMatrix($matrix);

            /**
             * @var DecisionSolutionModel $result
             * */
            $result = $this->get('app.strategy_manager')->getSolution(BayasLaplasStrategy::STRATEGY_NAME, $decisionTaskModel);

            return $this->render('@App/Stratagy/solution.html.twig',
                [
                    'result' => $result,
                    'initial_matrix' => $decisionTaskModel->getMatrix()
                ]
            );

        }


        return $this->render('@App/Stratagy/blStrategy.html.twig');
    }

    /**
     * @param Request $request
     * @return DecisionTaskModel
     */
    protected function initialTaskModel(Request $request){

        $decisionTaskModel = new DecisionTaskModel();

        if($request->get('p')) {
            $decisionTaskModel->setArProbabilities($request->get('p'));
        }

        if($request->get('matrix')) {
            $decisionTaskModel->setMatrix($request->get('matrix'));
        }

        if($request->get('coefficient')) {
            $decisionTaskModel->setCoefficient($request->get('coefficient'));
        }

        return $decisionTaskModel;
    }


    /**
     * @param Request $request
     * @return array
     * @internal param $arCountElements
     * @internal param $good_price
     * @internal param $bad_price
     * @internal param $cost
     */
    private function createBLMatrix(Request $request)
    {
        $arCountElements = !empty($request->get('x')) ? $request->get('x') : null;
        $cost = !empty($request->get('cost')) ? $request->get('cost') : null;
        $good_price = !empty($request->get('good_price')) ? $request->get('good_price') : null;
        $bad_price = !empty($request->get('bad_price')) ? $request->get('bad_price') : null;

        $matrix = [];

        if(!empty($arCountElements) && !empty($cost)&& !empty($good_price)&& !empty($bad_price)){
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
        }


        return $matrix;
    }
}
