<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Plan;
use AppBundle\Entity\Shift;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadCompleteDataSet extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@admin.ch');
        $admin->setPlainPassword('12345678');
        $admin->setEnabled(1);
        $this->setReference('admin-user', $admin);

        $adminPlan = new Plan();
        $adminPlan->setDescription('hmm bli blb blu');
        $adminPlan->setDate(new \DateTime('1970-01-01 00:01:00'));
        $adminPlan->setTitle('admin plan');
        $adminPlan->setUser($admin);

        $adminShift = new Shift();
        $adminShift->setDescription('meiu asdjffs');
        $adminShift->setTitle('admin shift');
        $adminShift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $adminShift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $adminShift->setNumberPeople(2);
        $adminPlan->addShift($adminShift);

        // set 2
        $rudolf = new User();
        $rudolf->setUsername('rudolf');
        $rudolf->setEmail('rudolf@rudolf.ch');
        $rudolf->setPlainPassword('12345678');
        $rudolf->setEnabled(1);
        $this->setReference('rudolf-user', $rudolf);

        $rudolfPlan = new Plan();
        $rudolfPlan->setDescription('hmm bli blb blu');
        $rudolfPlan->setDate(new \DateTime('1970-01-01 00:01:00'));
        $rudolfPlan->setTitle('rudolf plan');
        $rudolfPlan->setUser($rudolf);

        $rudolfShift = new Shift();
        $rudolfShift->setDescription('meiu asdjffs');
        $rudolfShift->setTitle('rudolf shift');
        $rudolfShift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $rudolfShift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $rudolfShift->setNumberPeople(2);
        $rudolfPlan->addShift($rudolfShift);


        $manager->persist($admin);
        $manager->persist($adminShift);
        $manager->persist($adminPlan);
        $manager->persist($rudolf);
        $manager->persist($rudolfShift);
        $manager->persist($rudolfPlan);

        $manager->flush();
    }
}
