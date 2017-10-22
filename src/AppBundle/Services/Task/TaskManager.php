<?php

namespace AppBundle\Services\Task;


use AppBundle\Entity\Criteria;
use AppBundle\Entity\Task;
use AppBundle\Model\MatrixModel;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class TaskManager
{

    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var Container $container
     * */
    protected $container;

    /**
     * @param Container $container
     * @param EntityManager $em
     */
    public function __construct(Container $container, EntityManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Преобразует Task в массив матриц БО
     * @param Task $task
     * @return MatrixModel[]
     */
    public function getMatrixesByTask(Task $task)
    {
        $matrixesArray = [];

        /** @var Criteria $criterion */
        foreach($task->getCriteria() as $index => $criterion) {
            $matrixesArray[] = $criterion->getMatrix();
        }

        return $matrixesArray;
    }


}
