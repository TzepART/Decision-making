<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 27.04.17
 * Time: 12:15
 */

namespace AppBundle\Model;


class DecisionSolutionModel
{
    /**
     * @var string
     */
    protected  $solution;

    /**
     * @var string|int
     */
    protected  $value;

    /**
     * @var array
     */
    protected  $new_matrix;

    /**
     * @var string
     */
    protected $error;

    /**
     * @return mixed
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * @param mixed $solution
     * @return $this
     */
    public function setSolution($solution)
    {
        $this->solution = $solution;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getNewMatrix()
    {
        return $this->new_matrix;
    }

    /**
     * @param mixed $new_matrix
     * @return $this
     */
    public function setNewMatrix($new_matrix)
    {
        $this->new_matrix = $new_matrix;
        return $this;

    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error)
    {
        $this->error = $error;
    }



}