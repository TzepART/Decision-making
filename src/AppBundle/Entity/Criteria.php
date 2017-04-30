<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 04.04.17
 * Time: 19:17
 */

namespace AppBundle\Entity;

use AppBundle\Model\MatrixModel;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CriteriaRepository")
 * @ORM\Table(name="criteria")
 */

class Criteria
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var \AppBundle\Entity\Task
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Task")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     * })
     */
    private $task;

    /**
     * @ORM\Column(name="matrix", type="array", nullable=true)
     * */
    private $matrix;


    /**
     * @ORM\Column(name="significance", type="float", nullable=true)
     * */
    private $significance;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSignificance()
    {
        return $this->significance;
    }

    /**
     * @param mixed $significance
     * @return $this
     */
    public function setSignificance($significance)
    {
        $this->significance = $significance;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function setTask(Task $task)
    {
        $this->task = $task;
        return $this;
    }

    /**
     * @return MatrixModel
     */
    public function getMatrix(): MatrixModel
    {
        $matrix = new MatrixModel($this->matrix);
        return $matrix;
    }

    /**
     * @param MatrixModel $matrix
     * @return $this
     */
    public function setMatrix(MatrixModel $matrix)
    {
        $this->matrix = $matrix->toArray();
        return $this;
    }

}