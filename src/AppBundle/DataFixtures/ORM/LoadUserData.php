<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Plan;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadPlanData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $plan = new Plan();
        $plan->setTitle('tst');
        $plan->setDate(new \DateTime('now'));
        $plan->setDescription('asdf');

        $manager->persist($plan);
        $manager->flush();
    }
}