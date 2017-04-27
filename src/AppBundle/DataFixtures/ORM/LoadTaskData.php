<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 05.04.17
 * Time: 21:08
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Criteria;
use AppBundle\Entity\Variant;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Task;
use Faker\Factory as Faker;


class LoadTaskData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();
        $task = new Task();
        $task->setName('example_task')
             ->setUser($this->getReference('example_user'));

        foreach ($faker->words($nb = 5, $asText = false) as $index => $word) {
            $variant = new Criteria();
            $variant->setName($word);
            $task->getCriteria()->add($variant);
        }

        foreach ($faker->words($nb = 5, $asText = false) as $index => $word) {
            $variant = new Variant();
            $variant->setName($word);
            $task->getVariants()->add($variant);
        }

        $manager->persist($task);
        $manager->flush();

        $this->addReference('example_task', $task);
    }

    public function getOrder()
    {
        return 2;
    }
}