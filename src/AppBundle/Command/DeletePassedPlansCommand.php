<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Kernel;
use Twig\Environment;

class DeletePassedPlansCommand extends Command
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
     * @var Kernel
     */
    private $kernel;

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * DeletePassedPlansCommand constructor.
     * @param EntityManagerInterface $em
     * @param \Swift_Mailer $mailer
     * @param Kernel $kernel
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
            ->setName('app:delete-passed-plans')
            ->setDescription('Delete passed plans')
            ->setHelp('Delete all plans which are in the past. Deletes all linked data as well')
        ;
    }

    /**
     * Run it
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return  void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Delete passed plans',
            '===================',
            '',
        ]);

        $qb = $this->em->getRepository('AppBundle:Plan')->createQueryBuilder('p');
        $passedPlans = $qb->where('p.date < :today')
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getResult();

        if ($passedPlans) {
            try {
                $this->deleteEachPlan($passedPlans);
            } catch(\Exception $e){
                $output->write('deleting failed. Sent error via email to admin');
                $this->sendFailedEmail($e->getMessage());
            }
        } else {
            $output->write('Nothing to delete');
        }
    }


    /**
     * @param $plans
     */
    private function deleteEachPlan($plans)
    {
        foreach ($plans as $plan) {
            $this->em->remove($plan);
        }
        $this->em->flush();
    }

    /**
     * Send an email with the error output to the admin
     */
    private function sendFailedEmail($error)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('no-reply@schicht-plan.ch')
            ->setTo('admin@schicht-plan.ch')
            ->setBody(
                $this->templating->render(
                    'email/plan-deletion-failed.txt.twig',
                    array(
                        'error' => $error
                    )
                ),
                'text/plain'
            );

        $this->mailer->send($message);
    }
}