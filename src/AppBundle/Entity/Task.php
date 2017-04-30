<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 04.04.17
 * Time: 19:17
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 * @ORM\Table(name="task")
 */

class Task
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
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


    /**
     * @var Variant[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Variant", mappedBy="task", cascade={"persist"})
     */
    private $variants;


    /**
     * @var Criteria[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Criteria", mappedBy="task", cascade={"persist"})
     */
    private $criteria;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->criteria = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->id;
    }

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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return ArrayCollection|Variant[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param ArrayCollection $variants
     * @return $this
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCriteria()
    {
        return $this->criteria;

    }

    /**
     * @param ArrayCollection $criteria
     * @return $this
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
        return $this;

    }



}