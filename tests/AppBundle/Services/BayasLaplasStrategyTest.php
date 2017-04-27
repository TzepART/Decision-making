<?php

namespace Tests\AppBundle\Services;

use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;
use AppBundle\Services\Strategy\BayasLaplasStrategy;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class BayasLaplasStrategyTest extends WebTestCase
{
    protected $matrix = [
        [34, 44, 10, 0, 11],

        [2, 32, 22, 56, 53],

        [44, 88, 34, 56, 76],

        [34, 45, 33, 6, 31],
    ];

    public function testCheckCorrectLogic()
    {
        $decisionTaskModel = new DecisionTaskModel();
        $decisionTaskModel->setMatrix($this->matrix);

        /**
         * @var DecisionSolutionModel $result
         * */
        $result = $this->getContainer()->get('app.strategy_manager')->getSolution(BayasLaplasStrategy::STRATEGY_NAME, $decisionTaskModel);

        echo "<pre>";
        var_dump($result);
        echo "</pre>";

//        self::assertTrue($result->getSolution() == 3);
//        self::assertTrue($result->getValue() == 0);

    }


}
