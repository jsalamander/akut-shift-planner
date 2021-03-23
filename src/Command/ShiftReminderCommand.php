<?php

namespace App\Command;

use App\Entity\Person;
use App\Entity\Plan;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;
use Symfony\Component\Console\Input\InputOption;

class ShiftReminderCommand extends Command
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * count how many people receive a reminder
     */
    private $reminderCounter = 0;

    /**
     * @param EntityManagerInterface $em
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     */
    public function __construct(
        EntityManagerInterface $em,
        \Swift_Mailer $mailer,
        \Twig_Environment $templating
    ) {
        parent::__construct();
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * Configure Command
     */
    protected function configure()
    {
        $this
            ->setName('app:shift-reminder')
            ->setDescription('Remind people that they have an upcoming shift')
            ->setHelp('This command will send a reminder to everyone that has a shift in a specified day range.')
            ->addOption(
                'days',
                null,
                InputOption::VALUE_REQUIRED,
                'Specify how many days before the shift the people will receive a reminder',
                2
            );
    }

    /**
     * Run it
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return  void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $days =  intval($input->getOption('days'));
        if ($days < 0) {
            $days = 0;
        }

        $dueDate = new \DateTime();
        $dueDate->add(new \DateInterval('P' . $days . 'D'));

        $from = new \DateTime($dueDate->format("Y-m-d")." 00:00:00");
        $to   = new \DateTime($dueDate->format("Y-m-d")." 23:59:59");

        $qb = $this->em->getRepository('App:Person')->createQueryBuilder('p');
        $people = $qb
            ->join('p.shift', 's')
            ->join('s.plan', 'plan')
            ->where('plan.date >= :from')
            ->andWhere('plan.date <= :to')
            ->setParameter('from', $from, \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('to', $to, \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery()
            ->getResult();

        foreach ($people as $person) {
            $this->sendReminder($person);
        }

        $output->write('<info>' . $this->reminderCounter . ' People received a reminder</info>');
    }


    /**
     * send the email to the specified person
     * @param Person $person
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function sendReminder(Person $person)
    {
        if ($person->getEmail()) {
            $message = (new \Swift_Message('Shift Reminder'))
                ->setFrom('no-reply@schicht-plan.ch')
                ->setTo($person->getEmail())
                ->setBody(
                    $this->templating->render(
                        'email/shift-reminder.txt.twig',
                        array(
                            'person' => $person
                        )
                    ),
                    'text/plain'
                );

            $this->mailer->send($message);
            $this->reminderCounter += 1;
        }
    }
}