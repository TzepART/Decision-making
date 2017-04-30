<?php

namespace Tests\AppBundle\Services;

use AppBundle\Model\DecisionSolutionModel;
use AppBundle\Model\DecisionTaskModel;
use AppBundle\Model\MatrixModel;
use AppBundle\Services\Strategy\BayasLaplasStrategy;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class BayasLaplasStrategyTest extends WebTestCase
{
    protected $arrayMatrix = [
        [34, 44, 10, 0, 11],

        [2, 32, 22, 56, 53],

        [44, 88, 34, 56, 76],

        [34, 45, 33, 6, 31],
    ];

    protected $matrix;

    protected $probability = [0.2, 0.1, 0.25, 0.15, 0.3];


    public function setUp()
    {
        $this->matrix = new MatrixModel($this->arrayMatrix);
    }


    public function testCheckCorrectLogic()
    {
        $decisionTaskModel = new DecisionTaskModel();
        $decisionTaskModel->setMatrix($this->matrix);
        $decisionTaskModel->setArProbabilities($this->probability);

        /**
         * @var DecisionSolutionModel $result
         * */
        $result = $this->getContainer()->get('app.strategy_manager')->getSolution(BayasLaplasStrategy::STRATEGY_NAME, $decisionTaskModel);

        self::assertTrue($result->getSolution() == 3);
        self::assertTrue($result->getValue() == 57.3);

    }


}
