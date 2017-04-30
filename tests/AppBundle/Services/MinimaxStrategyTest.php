<?php

namespace Tests\AppBundle\Services;

use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;
use AppBundle\Model\MatrixModel;
use AppBundle\Services\Strategy\MinimaxStrategy;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class MinimaxStrategyTest extends WebTestCase
{
    protected $arrayMatrix = [
        [34, 44, 10, 0, 11],

        [2, 32, 22, 56, 53],

        [44, 88, 34, 56, 76],

        [34, 45, 33, 6, 31],
    ];

    protected $matrix;

    public function setUp()
    {
        $this->matrix = new MatrixModel($this->arrayMatrix);
    }


    public function testCheckCorrectLogic()
    {
        $decisionTaskModel = new DecisionTaskModel();
        $decisionTaskModel->setMatrix($this->matrix);

        /**
         * @var DecisionSolutionModel $result
         * */
        $result = $this->getContainer()->get('app.strategy_manager')->getSolution(MinimaxStrategy::STRATEGY_NAME, $decisionTaskModel);

        self::assertTrue($result->getSolution() == 3);
        self::assertTrue($result->getValue() == 34);

    }


}
