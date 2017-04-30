<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 05.04.17
 * Time: 21:08
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Criteria;
use AppBundle\Model\MatrixModel;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Task;
use Faker\Factory as Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadCriteriaData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();

        /** @var Task $task */
        $task = $this->getReference('example_task');
        $emptyMatrix = new MatrixModel($this->container->get('app.matrix_manager')->getEmptyMatrixByVariants($task->getVariants()));

        foreach ($faker->words($nb = 5, $asText = false) as $index => $word) {
            $criteria = new Criteria();
            $criteria->setName($word)
                     ->setTask($task)
                     ->setMatrix($emptyMatrix)
                     ->setSignificance($faker->randomFloat(2,0,1));
            $manager->persist($criteria);
            $this->addReference('example_criteria_'.$index, $criteria);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 4;
    }
}