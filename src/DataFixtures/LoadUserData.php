<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LoadUserData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@admin.ch');
        $admin->setPlainPassword('12345678');
        $admin->setEnabled(1);
        $this->setReference('admin-user', $admin);
        $manager->persist($admin);
        $manager->flush();
    }
}
