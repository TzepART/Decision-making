<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:24
 */

namespace AppBundle\Services\Strategy;


abstract class FactoryStrategies
{

    private $strategyName;

    public function __construct(string $strategyName) {

        $this->strategyName = $strategyName;
    }

    public function getStrategy() {
        switch ($this->strategyName) {
            case SevidgStrategy::STRATEGY_NAME:
                $strategy = new SevidgStrategy();
                break;

            case MinimaxStrategy::STRATEGY_NAME:
                $strategy = new MinimaxStrategy();
                break;

            case HurwitzStrategy::STRATEGY_NAME:
                $strategy = new HurwitzStrategy();
                break;
            case BayasLaplasStrategy::STRATEGY_NAME:
                $strategy = new BayasLaplasStrategy();
                break;

            default:
                $strategy = null;
        }

        return $strategy;
    }


}