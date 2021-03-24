<?php

namespace App\DataFixtures;

use App\Entity\Person;
use App\Entity\PlanCollection;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Plan;
use App\Entity\Shift;
use Doctrine\Bundle\FixturesBundle\Fixture;


class LoadCompleteDataSet extends Fixture
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
        $this->setReference('admin-plan', $adminPlan);

        $adminPlanSecond = new Plan();
        $adminPlanSecond->setDescription('yolo');
        $adminPlanSecond->setDate(new \DateTime('2098-01-01 00:01:00'));
        $adminPlanSecond->setTitle('admin second plan');
        $adminPlanSecond->setUser($admin);
        $this->setReference('admin-plan-second', $adminPlan);

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

        $adminCollection = new PlanCollection();
        $adminCollection->setTitle('admincollection');
        $adminCollection->setUser($admin);
        $adminCollection->addPlan($adminPlan);
        $adminCollection->addPlan($adminPlanSecond);
        $this->setReference('admin-collection', $adminCollection);

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

        $adminTemplate = new Plan();
        $adminTemplate->setDescription('hmm bli blb blu');
        $adminTemplate->setDate(new \DateTime('1970-01-01 00:01:00'));
        $adminTemplate->setTitle('admin plan template');
        $adminTemplate->setUser($admin);
        $adminTemplate->setIsTemplate(true);

        $adminTemplateShift = new Shift();
        $adminTemplateShift->setDescription('meiu asdjffs');
        $adminTemplateShift->setTitle('admin shift template');
        $adminTemplateShift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $adminTemplateShift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $adminTemplateShift->setNumberPeople(2);
        $adminTemplate->addShift($adminTemplateShift);

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

        $rudolfTemplatePlan = new Plan();
        $rudolfTemplatePlan->setDescription('hmm bli blb blu');
        $rudolfTemplatePlan->setIsTemplate(true);
        $rudolfTemplatePlan->setDate(new \DateTime('1970-01-01 00:01:00'));
        $rudolfTemplatePlan->setTitle('rudolf plan template');
        $rudolfTemplatePlan->setUser($rudolf);

        $rudolfTemplateShift = new Shift();
        $rudolfTemplateShift->setDescription('meiu asdjffs');
        $rudolfTemplateShift->setTitle('rudolf shift template');
        $rudolfTemplateShift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $rudolfTemplateShift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $rudolfTemplateShift->setNumberPeople(2);
        $rudolfTemplatePlan->addShift($rudolfTemplateShift);

        //save all the things!
        $manager->persist($admin);
        $manager->persist($adminPerson);
        $manager->persist($adminShift);
        $manager->persist($adminShiftPast);
        $manager->persist($adminPlan);
        $manager->persist($adminPlanPast);
        $manager->persist($adminTemplateShift);
        $manager->persist($adminTemplate);
        $manager->persist($adminPlanSecond);
        $manager->persist($adminCollection);
        $manager->persist($rudolf);
        $manager->persist($rudolfShift);
        $manager->persist($rudolfPlan);
        $manager->persist($rudolfTemplateShift);
        $manager->persist($rudolfTemplatePlan);

        $manager->flush();
    }
}
