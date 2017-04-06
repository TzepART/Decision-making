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
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('expert@gmail.com');
        $user->setUsername('expert');
        $user->setPlainPassword('123');
        $user->setEnabled(true);

        $manager->persist($user);
        $manager->flush();

        $this->addReference('example_user', $user);
    }

    public function getOrder()
    {
        return 1;
    }
}