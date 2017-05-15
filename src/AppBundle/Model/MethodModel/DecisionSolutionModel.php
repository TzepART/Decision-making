<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 27.04.17
 * Time: 12:15
 */

namespace AppBundle\Model\MethodModel;


use AppBundle\Model\ExtendMatrixModel;

/**
 * Class DecisionSolutionModel
 * @package AppBundle\Model\MethodModel
 */
class DecisionSolutionModel extends \AppBundle\Model\DecisionSolutionModel
{

    /**
     * @var ExtendMatrixModel
     */
    protected $matrixModel;

    /**
     * @return ExtendMatrixModel
     */
    public function getMatrixModel(): ExtendMatrixModel
    {
        return $this->matrixModel;
    }

    /**
     * @param ExtendMatrixModel $matrixModel
     */
    public function setMatrixModel(ExtendMatrixModel $matrixModel)
    {
        $this->matrixModel = $matrixModel;
    }



}