<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.04.17
 * Time: 11:06
 */

namespace AppBundle\Services;


use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;
use AppBundle\Services\Strategy\AbstractStrategy;
use Symfony\Component\DependencyInjection\Container;

class StrategyManager
{
    /**
     * @var Container
     */
    private $container;

    /**
     * StrategyManager constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $strategyName
     * @param DecisionTaskModel $decisionTaskModel
     * @return array
     * @internal param array $matrix
     * @internal param float $coefficient
     */
    function getSolution($strategyName, DecisionTaskModel $decisionTaskModel)
    {
        $result = [];
        $strategy = $this->container->get('app.strategy')->getStrategy($strategyName);

        /** @var AbstractStrategy $strategy */
        if ($strategy != null) {
            $result = $strategy->getOptimalSolution($decisionTaskModel, new DecisionSolutionModel());
        }

        return $result;
    }
}