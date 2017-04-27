<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.04.17
 * Time: 12:15
 */

namespace AppBundle\Model;


class DecisionSolutionModel
{
    protected  $solution;

    protected  $value;

    protected  $new_matrix;

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





}