<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.04.17
 * Time: 11:06
 */

namespace AppBundle\Services;


use AppBundle\Services\Strategy\BayasLaplasStrategy;
use Symfony\Component\DependencyInjection\Container;

class StrategyManager
{
    /**
     * @var Container
     */
    private $container;

    /**
     * StrategyManager constructor.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Task1
     * @param string $strategyName
     * @param array $matrix
     * @param float $coefficient
     * @return array
     */
    function getSolution($strategyName, $matrix, $coefficient)
    {
        $result = [];
        $strategy = $this->container->get('app.strategy')->getStrategy($strategyName);

        /** @var \Controllers\AbstractStrategy $strategy */
        if ($strategy != null) {
            $result = $strategy->getOptimalSolution($matrix, $coefficient);
//            $solution = '';
//            $solution .= $strategyName . '<br>';
//            $solution .= 'solution ' . $result['solution'] . '<br>';
//            $solution .= 'value ' . $result['value'] . '<br>';
//            echo \App\AppKernel::getInstance()->getTwig()->render('solution.html.twig', ['solution' => $solution]);
        }

        return $result;
    }

    /**
     * @param $arCountElements
     * @param $good_price
     * @param $bad_price
     * @param $cost
     * @param $matrix
     * @param $arProbabilities
     * @return array
     */
    function getBLSolution($arCountElements, $good_price, $bad_price, $cost, $matrix, $arProbabilities)
    {
        $result = [];
        $strategy =     $this->container->get('app.strategy')->getStrategy(BayasLaplasStrategy::STRATEGY_NAME);
        /** @var BayasLaplasStrategy $strategy */
        if ($strategy != null) {
            $count = count($arCountElements);
            $solution = '';

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

            $solution .= '<br>';
            foreach ($matrix as $index => $row) {
                $solution .= '| ';
                foreach ($row as $i => $item) {
                    $solution .= $item . ' | ';
                }
                $solution .= '<br>';
            }

            $result = $strategy->setArrayProbabilities($arProbabilities)->getOptimalSolution($matrix);

//            $solution .= '<br>';
//            foreach ($result['new_matrix'] as $index => $row) {
//                $solution .= '| ';
//                foreach ($row as $i => $item) {
//                    $solution .= $item . ' | ';
//                }
//                $solution .= '<br>';
//            }
//
//            $solution .= 'bayes-laplas <br>';
//            $solution .= 'solution ' . $result['solution'] . '<br>';
//            $solution .= 'value ' . $result['value'] . '<br>';
//
//            echo \App\AppKernel::getInstance()->getTwig()->render('solution.html.twig', ['solution' => $solution]);
        }
        return $result;

    }
}