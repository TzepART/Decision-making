<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 11.02.17
 * Time: 18:24
 */

namespace Controllers;


abstract class FactoryStrategies
{

    public function __construct() {

    }

    public static function getStrategy($strategyName) {
        switch ($strategyName) {
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

    public abstract function getOptimalSolution($matrix, $coefficient = 0);

}