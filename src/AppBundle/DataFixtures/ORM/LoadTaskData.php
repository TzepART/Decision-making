<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 05.04.17
 * Time: 21:08
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Task;


class LoadTaskData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $task = new Task();
        $task->setName('example_task')
             ->setUser($this->getReference('example_user'));

        $manager->persist($task);
        $manager->flush();

        $this->addReference('example_task', $task);
    }

    public function getOrder()
    {
        return 2;
    }
}