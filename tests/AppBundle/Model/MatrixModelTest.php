<?php

namespace Tests\AppBundle\Services;

use AppBundle\Model\MatrixModel;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class MatrixModelTest extends WebTestCase
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
         * @var MatrixModel $matrixModel
         * */
        $matrixModel = new MatrixModel($this->matrix);
        $column = $matrixModel->getColumnById(2);

        self::assertEquals($column,[10, 22, 34, 33]);
    }

    public function testGetRow()
    {
        /**
         * @var MatrixModel $matrixModel
         * */
        $matrixModel = new MatrixModel($this->matrix);
        $row = $matrixModel->getRowById(2);

        self::assertEquals($row,[44, 88, 34, 56, 76]);
    }

}
