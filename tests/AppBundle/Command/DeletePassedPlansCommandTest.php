<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Command\DeletePassedPlansCommand;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DeletePassedPlansCommandTest extends WebTestCase
{
    private $fixtures;

    private $fixutreRefs;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCommandData'
        ))->getReferenceRepository();

        $this->fixutreRefs = [
            'admin-collection' => $this->fixtures->getReference('admin-collection'),
            'admin-past-collection' => $this->fixtures->getReference('admin-past-collection'),
            'admin-plan-stored-second' => $this->fixtures->getReference('admin-plan-stored-second'),
            'admin-plan-stored' => $this->fixtures->getReference('admin-plan-stored'),
            'admin-plan-past' => $this->fixtures->getReference('admin-plan-past')
        ];
    }

    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $templating = $this->getContainer()->get('twig');
        $em =$this->getContainer()->get('doctrine.orm.entity_manager');
        $mailer =$this->getContainer()->get('mailer');
        $application->add(new DeletePassedPlansCommand($em, $mailer, $templating));

        $command = $application->find('app:delete-passed-plans');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--dueDays' => 0
        ));

        $output = $commandTester->getDisplay();

        $this->assertEquals("Plans deleted: 2", $output);

        $storedPlan = $em->getRepository("AppBundle:Plan")->findOneById(
            $this->fixtures->getReference('admin-plan-stored')->getId()
        );
        $this->assertEquals($this->fixtures->getReference('admin-shift'), $storedPlan->getShifts()[0]);
        $this->assertEquals($storedPlan->getId(), $this->fixtures->getReference('admin-plan-stored')->getId());

        $deletedPlan = $em->getRepository("AppBundle:Plan")->findOneById(
            $this->fixtures->getReference('admin-plan-past')->getId()
        );
        $this->assertNull($deletedPlan);

        $collection = $em->getRepository("AppBundle:PlanCollection")->findOneById(
            $this->fixtures->getReference('admin-collection')->getId()
        );
        $this->assertEquals($collection->getId(), $this->fixtures->getReference('admin-collection')->getId());

        $passedCollection = $em->getRepository("AppBundle:PlanCollection")->findOneById(
            $this->fixtures->getReference('admin-past-collection')->getId()
        );
        $this->assertNull($passedCollection);


        $passedShift = $em->getRepository("AppBundle:Shift")->findOneById(
            $this->fixtures->getReference('admin-shift-past')->getId()
        );
        $this->assertNull($passedShift);



    }

    public function testOptionsExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $templating = $this->getContainer()->get('twig');
        $em =$this->getContainer()->get('doctrine.orm.entity_manager');
        $mailer =$this->getContainer()->get('mailer');
        $application->add(new DeletePassedPlansCommand($em, $mailer, $templating));

        $command = $application->find('app:delete-passed-plans');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--dueDays' => 50
        ));

        $output = $commandTester->getDisplay();

        $this->assertEquals("Plans deleted: 1", $output);

        $storedPlan = $em->getRepository("AppBundle:Plan")->findOneById(
            $this->fixtures->getReference('admin-plan-stored')->getId()
        );
        $this->assertEquals($storedPlan->getId(), $this->fixtures->getReference('admin-plan-stored')->getId());

        $deletedPlan = $em->getRepository("AppBundle:Plan")->findOneById(
            $this->fixtures->getReference('admin-plan-past')->getId()
        );
        $this->assertNull($deletedPlan);

        $collection = $em->getRepository("AppBundle:PlanCollection")->findOneById(
            $this->fixtures->getReference('admin-collection')->getId()
        );
        $this->assertEquals($collection->getId(), $this->fixtures->getReference('admin-collection')->getId());

        $passedCollection = $em->getRepository("AppBundle:PlanCollection")->findOneById(
            $this->fixtures->getReference('admin-past-collection')->getId()
        );
        $this->assertNull($passedCollection);

    }

    public function testWrongOptionExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $templating = $this->getContainer()->get('twig');
        $em =$this->getContainer()->get('doctrine.orm.entity_manager');
        $mailer =$this->getContainer()->get('mailer');
        $application->add(new DeletePassedPlansCommand($em, $mailer, $templating));

        $command = $application->find('app:delete-passed-plans');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--dueDays' => -1000
        ));

        $output = $commandTester->getDisplay();

        $this->assertEquals("Plans deleted: 2", $output);

        $storedPlan = $em->getRepository("AppBundle:Plan")->findOneById(
            $this->fixtures->getReference('admin-plan-stored')->getId()
        );
        $this->assertEquals($storedPlan->getId(), $this->fixtures->getReference('admin-plan-stored')->getId());

        $deletedPlan = $em->getRepository("AppBundle:Plan")->findOneById(
            $this->fixtures->getReference('admin-plan-past')->getId()
        );
        $this->assertNull($deletedPlan);

        $collection = $em->getRepository("AppBundle:PlanCollection")->findOneById(
            $this->fixtures->getReference('admin-collection')->getId()
        );
        $this->assertEquals($collection->getId(), $this->fixtures->getReference('admin-collection')->getId());

        $passedCollection = $em->getRepository("AppBundle:PlanCollection")->findOneById(
            $this->fixtures->getReference('admin-past-collection')->getId()
        );
        $this->assertNull($passedCollection);

    }
}
