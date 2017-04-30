<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 30.04.17
 * Time: 2:24
 */

namespace AppBundle\Model;

use vermotr\Math\Matrix;


/**
 * Class MatrixModel
 * @package AppBundle\Model
 */
class MatrixModel
{
    /**
     * @var array
     */
    private $matrix;


    /**
     * MatrixModel constructor.
     * @param array $matrix
     */
    public function __construct(array $matrix)
    {
        $this->matrix = $matrix;
    }

    public function toArray()
    {
        return $this->matrix;
    }

    /**
     * Gets a new transposed matrix
     * @return array
     */
    public function getTransposeMatrix()
    {
        $transposeMatrix = [];
        foreach ($this->matrix as $i => $row) {
            foreach ($row as $j => $item) {
                $transposeMatrix[$j][$i] = $item;
            }
        }
        return $transposeMatrix;
    }


    /**
     * @param int $id
     * @return array
     */
    public function getColumnById($id): array
    {
        $column = array_column($this->matrix, $id);
        return $column;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getRowById($id): array
    {
        $row = $this->matrix[$id];
        return $row;
    }
}