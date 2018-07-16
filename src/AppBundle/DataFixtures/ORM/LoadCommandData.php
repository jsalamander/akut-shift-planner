<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Person;
use AppBundle\Entity\PlanCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Plan;
use AppBundle\Entity\Shift;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadCommandData extends AbstractFixture implements FixtureInterface
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
        $adminPlan->setDate(new \DateTime('2099-01-01 00:01:00'));
        $adminPlan->setTitle('admin plan');
        $adminPlan->setUser($admin);
        $this->setReference('admin-plan-stored', $adminPlan);

        $adminPlanSecond = new Plan();
        $adminPlanSecond->setDescription('yolo');
        $adminPlanSecond->setDate(new \DateTime('2098-01-01 00:01:00'));
        $adminPlanSecond->setTitle('admin second plan');
        $adminPlanSecond->setUser($admin);
        $this->setReference('admin-plan-stored-second', $adminPlan);

        $adminShift = new Shift();
        $adminShift->setDescription('meiu asdjffs');
        $adminShift->setTitle('admin shift');
        $adminShift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $adminShift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $adminShift->setNumberPeople(3);
        $adminPlan->addShift($adminShift);
        $this->setReference('admin-shift', $adminShift);

        $adminPerson = new Person();
        $adminPerson->setAlias('alias');
        $adminPerson->setName('real name');
        $adminShift->addPerson($adminPerson);
        $adminPerson->setShift($adminShift);

        $adminPlanPast = new Plan();
        $adminPlanPast->setDescription('sfsadfs');
        $adminPlanPast->setDate(new \DateTime('1999-01-01 00:01:00'));
        $adminPlanPast->setTitle('admin plan past');
        $adminPlanPast->setUser($admin);
        $this->setReference('admin-plan-past', $adminPlanPast);

        $adminShiftPast = new Shift();
        $adminShiftPast->setDescription('meiu asdjffs');
        $adminShiftPast->setTitle('admin shift past');
        $adminShiftPast->setStart(new \DateTime('1970-01-01 00:01:00'));
        $adminShiftPast->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $adminShiftPast->setNumberPeople(2);
        $adminPlanPast->addShift($adminShiftPast);
        $this->setReference('admin-shift-past', $adminShiftPast);

        $adminCollectionPast = new PlanCollection();
        $adminCollectionPast->setTitle('CollectionToBeDeleted');
        $adminCollectionPast->setUser($admin);
        $adminCollectionPast->addPlan($adminPlanPast);
        $this->setReference('admin-past-collection', $adminCollectionPast);

        $adminCollection = new PlanCollection();
        $adminCollection->setTitle('CollectionToStay');
        $adminCollection->setUser($admin);
        $adminCollection->addPlan($adminPlan);
        $adminCollection->addPlan($adminPlanSecond);
        $this->setReference('admin-collection', $adminCollection);

        //save all the things!
        $manager->persist($admin);
        $manager->persist($adminPerson);
        $manager->persist($adminShift);
        $manager->persist($adminShiftPast);
        $manager->persist($adminPlan);
        $manager->persist($adminPlanPast);
        $manager->persist($adminPlanSecond);
        $manager->persist($adminCollection);
        $manager->persist($adminCollectionPast);

        $manager->flush();
    }
}
