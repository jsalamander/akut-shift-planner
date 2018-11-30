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
        $adminPerson->setEmail('tst@tst.ch');
        $adminShift->addPerson($adminPerson);
        $adminPerson->setShift($adminShift);

        $adminPersonTwo = new Person();
        $adminPersonTwo->setAlias('alias');
        $adminPersonTwo->setName('no mail');
        $adminShift->addPerson($adminPerson);
        $adminPersonTwo->setShift($adminShift);

        $toRemind = new Person();
        $toRemind->setAlias('alias');
        $toRemind->setName('real name');
        $adminShift->addPerson($toRemind);
        $toRemind->setShift($adminShift);

        $adminPlanNow = new Plan();
        $adminPlanNow->setDescription('today');
        $now = new \DateTime();
        $date = new \DateTime($now->format("Y-m-d")." 00:00:00");
        $adminPlanNow->setDate($date);
        $adminPlanNow->setTitle('admin plan now');
        $adminPlanNow->setUser($admin);
        $this->setReference('admin-plan-now', $adminPlanNow);

        $adminPlanUpcoming = new Plan();
        $adminPlanUpcoming->setDescription('remind me upcoming');
        $now = new \DateTime();
        $date = new \DateTime($now->format("Y-m-d")." 00:00:00");
        $date->add(new \DateInterval('P30D'));

        $adminPlanUpcoming->setDate($date);
        $adminPlanUpcoming->setTitle('admin plan upcoming');
        $adminPlanUpcoming->setUser($admin);
        $this->setReference('admin-plan-upcoming', $adminPlanUpcoming);

        $adminShiftUpcoming = new Shift();
        $adminShiftUpcoming->setDescription('meiu asdjffs');
        $adminShiftUpcoming->setTitle('admin shift past');
        $adminShiftUpcoming->setStart(new \DateTime('1970-01-01 00:01:00'));
        $adminShiftUpcoming->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $adminShiftUpcoming->setNumberPeople(2);
        $adminPlanUpcoming->addShift($adminShiftUpcoming);
        $this->setReference('admin-shift-Upcoming', $adminShiftUpcoming);

        $manager->persist($adminPlanUpcoming);
        $manager->flush();

        $mailReceiver = new Person();
        $mailReceiver->setName('receives mail');
        $mailReceiver->setEmail('test@test.de');
        $mailReceiver->setAlias("wuuup");
        $mailReceiver->setShift($adminShiftUpcoming);

        $mailReceiverNoMail = new Person();
        $mailReceiverNoMail->setName("no mailer");
        $mailReceiverNoMail->setAlias("miau");
        $mailReceiverNoMail->setShift($adminShiftUpcoming);

        $manager->persist($mailReceiver);
        $manager->persist($mailReceiverNoMail);
        $manager->flush();

        $adminShiftUpcoming->addPerson($mailReceiverNoMail);
        $adminShiftUpcoming->addPerson($mailReceiver);


        $adminShiftPast = new Shift();
        $adminShiftPast->setDescription('meiu asdjffs');
        $adminShiftPast->setTitle('admin shift past');
        $adminShiftPast->setStart(new \DateTime('1970-01-01 00:01:00'));
        $adminShiftPast->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $adminShiftPast->setNumberPeople(2);
        $adminPlanNow->addShift($adminShiftPast);
        $this->setReference('admin-shift-past', $adminShiftPast);

        $adminPlanPast = new Plan();
        $adminPlanPast->setDescription('sfsadfs');
        $adminPlanPast->setDate(new \DateTime('1999-01-01 00:01:00'));
        $adminPlanPast->setTitle('admin plan past');
        $adminPlanPast->setUser($admin);
        $this->setReference('admin-plan-past', $adminPlanPast);

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
        $manager->persist($adminPersonTwo);
        $manager->persist($toRemind);
        $manager->persist($adminShift);
        $manager->persist($adminShiftPast);
        $manager->persist($adminPlan);
        $manager->persist($adminPlanSecond);
        $manager->persist($adminPlanNow);
        $manager->persist($adminPlanUpcoming);
        $manager->persist($adminCollection);
        $manager->persist($adminCollectionPast);
        $manager->persist($adminPlanPast);
        $manager->persist($adminPlanUpcoming);

        $manager->flush();
    }
}
