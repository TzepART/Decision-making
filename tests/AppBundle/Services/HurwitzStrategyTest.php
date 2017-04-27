<?php

namespace Tests\AppBundle\Services;

use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;
use AppBundle\Services\Strategy\HurwitzStrategy;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class HurwitzStrategyTest extends WebTestCase
{
    protected $matrix = [
        [34, 44, 10, 0, 11],

        [2, 32, 22, 56, 53],

        [44, 88, 34, 56, 76],

        [34, 45, 33, 6, 31],
    ];

    protected $coefficient = 0.75;

    public function testCheckCorrectLogic()
    {
        $decisionTaskModel = new DecisionTaskModel();
        $decisionTaskModel->setMatrix($this->matrix);
        $decisionTaskModel->setCoefficient($this->coefficient);

        /**
         * @var DecisionSolutionModel $result
         * */
        $result = $this->getContainer()->get('app.strategy_manager')->getSolution(HurwitzStrategy::STRATEGY_NAME, $decisionTaskModel);


        self::assertTrue($result->getSolution() == 3);
        self::assertTrue($result->getValue() == 47.5);

    }


}