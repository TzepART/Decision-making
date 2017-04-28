<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.04.17
 * Time: 19:17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CriteriaRepository")
 * @ORM\Table(name="criteria")
 */

class Criteria
{
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

}