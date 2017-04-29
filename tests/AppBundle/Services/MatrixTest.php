<?php

namespace Tests\AppBundle\Services;

use AppBundle\Services\MatrixManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class MatrixTest extends WebTestCase
{
    protected $matrix = [
        [34, 44, 10, 0, 11],

        [2, 32, 22, 56, 53],

        [44, 88, 34, 56, 76],

        [34, 45, 33, 6, 31],
    ];

    public function testGetColumn()
    {
        /**
         * @var MatrixManager $matrixManager
         * */
        $matrixManager = $this->getContainer()->get('app.matrix_manager');
        $column = $matrixManager->getColumnById($this->matrix,2);

        self::assertEquals($column,[10, 22, 34, 33]);
    }

    public function testGetRow()
    {
        /**
         * @var MatrixManager $matrixManager
         * */
        $matrixManager = $this->getContainer()->get('app.matrix_manager');
        $row = $matrixManager->getRowById($this->matrix,2);

        self::assertEquals($row,[44, 88, 34, 56, 76]);
    }

}
