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
    protected $matrix;

    /**
     * @var array
     */
    protected $row_keys = [];

    /**
     * @var array
     */
    protected $column_keys = [];


    /**
     * MatrixModel constructor.
     * @param array $matrix
     */
    public function __construct(array $matrix)
    {
        $this->matrix = $matrix;

        /*
         * set keys
         * */
        foreach ($this->matrix as $i => $row) {
            if(empty($this->row_keys)){
                foreach ($row as $j => $item) {
                    $this->column_keys[] = $j;
                }
            }
            $this->row_keys[] = $i;
        }
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

    /**
     * @return array
     */
    public function getRowKeys(): array
    {
        return $this->row_keys;
    }

    /**
     * @return array
     */
    public function getColumnKeys(): array
    {
        return $this->column_keys;
    }

    /**
     * @return int
     */
    public function getCountRows(): int
    {
        return count($this->row_keys);
    }

    /**
     * @return int
     */
    public function getCountColumns(): int
    {
        return count($this->column_keys);
    }
}