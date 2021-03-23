<?php

namespace App\Tests\Controller;

use App\Command\DeletePassedPlansCommand;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ReminderCommandTest extends WebTestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'App\DataFixtures\ORM\LoadCommandData'
        ))->getReferenceRepository();
    }

    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $templating = $this->getContainer()->get('twig');
        $em =$this->getContainer()->get('doctrine.orm.entity_manager');
        $mailer =$this->getContainer()->get('mailer');
        $application->add(new DeletePassedPlansCommand($em, $mailer, $templating));

        $command = $application->find('app:shift-reminder');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--days' => 20
        ));

        $output = $commandTester->getDisplay();

        $this->assertEquals("0 People received a reminder", $output);
    }

    public function testExecuteUpcomingPlans()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $templating = $this->getContainer()->get('twig');
        $em =$this->getContainer()->get('doctrine.orm.entity_manager');
        $mailer =$this->getContainer()->get('mailer');
        $application->add(new DeletePassedPlansCommand($em, $mailer, $templating));

        $command = $application->find('app:shift-reminder');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--days' => 30
        ));

        $output = $commandTester->getDisplay();

        $this->assertEquals("1 People received a reminder", $output);
    }
}
