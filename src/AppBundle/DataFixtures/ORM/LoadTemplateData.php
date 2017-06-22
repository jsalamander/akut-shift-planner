<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Plan;
use AppBundle\Entity\Shift;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadTemplateData extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $template = new Plan();
        $template->setIsTemplate(1);
        $template->setIsPublic(1);
        $template->setDescription('hmm bli blb blu');
        $template->setDate(new \DateTime('1970-01-01 00:01:00'));
        $template->setTitle('yeah');

        $shift = new Shift();
        $shift->setDescription('meiu asdjffs');
        $shift->setTitle('shift');
        $shift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $shift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $shift->setNumberPeople(2);
        $template->addShift($shift);
        $this->setReference('public-plan-template', $template);

        $manager->persist($template);
        $manager->flush();
    }
}
