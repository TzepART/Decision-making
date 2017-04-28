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


class LoadVariantData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();

        /** @var Task $task */
        $task = $this->getReference('example_task');

        foreach ($faker->words($nb = 5, $asText = false) as $index => $word) {
            $variant = new Variant();
            $variant->setName($word);
            $variant->setTask($task);
            $manager->persist($variant);
            $this->addReference('example_variant_'.$index, $variant);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 3;
    }
}