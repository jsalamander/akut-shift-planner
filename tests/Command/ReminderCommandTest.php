<?php

namespace App\Tests\Controller;

use App\Command\DeletePassedPlansCommand;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class ReminderCommandTest extends WebTestCase
{

    use FixturesTrait;

    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'App\DataFixtures\LoadCommandData'
        ))->getReferenceRepository();
    }

    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $templating = self::$container->get('twig');
        $em =self::$container->get('doctrine.orm.entity_manager');
        $mailer =self::$container->get('mailer');
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

        $templating = self::$container->get('twig');
        $em =self::$container->get('doctrine.orm.entity_manager');
        $mailer =self::$container->get('mailer');
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
