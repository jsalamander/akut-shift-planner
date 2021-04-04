<?php

namespace App\DataFixtures;

use App\Entity\Plan;
use App\Entity\Shift;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LoadTemplateData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin3');
        $admin->setEmail('admin3@admin.ch');
        $admin->setPlainPassword('12345678');
        $admin->setEnabled(1);
        $this->setReference('admin-three-user', $admin);

        $template = new Plan();
        $template->setIsTemplate(1);
        $template->setIsPublic(1);
        $template->setDescription('hmm bli blb blu');
        $template->setDate(new \DateTime('1970-01-01 00:01:00'));
        $template->setTitle('yeah');
        $template->setUser($admin);
        $this->setReference('admin-template', $template);

        $publicTemplate = new Plan();
        $publicTemplate->setIsTemplate(1);
        $publicTemplate->setIsPublic(1);
        $publicTemplate->setDescription('am public');
        $publicTemplate->setDate(new \DateTime('1970-01-01 00:01:00'));
        $publicTemplate->setTitle('public template');

        $publicShift = new Shift();
        $publicShift->setDescription('public shift');
        $publicShift->setTitle('shift');
        $publicShift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $publicShift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $publicShift->setNumberPeople(2);
        $publicTemplate->addShift($publicShift);

        $shift = new Shift();
        $shift->setDescription('meiu asdjffs');
        $shift->setTitle('shift');
        $shift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $shift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $shift->setNumberPeople(2);
        $template->addShift($shift);
        $this->setReference('public-plan-template', $template);

        $privateTemplate = new Plan();
        $privateTemplate->setIsTemplate(1);
        $privateTemplate->setIsPublic(0);
        $privateTemplate->setDescription('hmm bli blb blu');
        $privateTemplate->setDate(new \DateTime('1970-01-01 00:01:00'));
        $privateTemplate->setTitle('private');

        $secondShift = new Shift();
        $secondShift->setDescription('meiu asdjffs');
        $secondShift->setTitle('shift');
        $secondShift->setStart(new \DateTime('1970-01-01 00:01:00'));
        $secondShift->setEnd(new \DateTime('1970-01-01 00:02:00'));
        $secondShift->setNumberPeople(2);
        $this->setReference('public-plan-template', $template);
        $privateTemplate->addShift($secondShift);

        $manager->persist($publicTemplate);
        $manager->persist($privateTemplate);
        $manager->persist($template);
        $manager->flush();
    }
}
