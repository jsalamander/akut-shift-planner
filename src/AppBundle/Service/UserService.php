<?php

namespace AppBundle\Service;

use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Provides the User object if authenticated
 * Class UserService
 */
class UserService {

    /**
     * @var $tokenStorage
     * type TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var Template
     */
    private $templating;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserPasswordEncoderInterface $encoder,
        SessionInterface $session,
        \Swift_Mailer $mailer,
        TranslatorInterface $translator,
        \Twig_Environment $templating,
        EntityManagerInterface $em
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->encoder = $encoder;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->templating = $templating;
        $this->em = $em;
    }

    /**
     * Get the user object
     * @return mixed|void
     */
    public function getUser(){
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }


    /**
     * Send the plan link via email to the creator
     * don't send it to logged in user
     *
     * @param Plan $plan
     * @param string $email
     * @return void
     */
    public function emailPlanLink($email, $plan)
    {
        if (!$plan->getIsTemplate() && !$this->getUser()) {
            $message = new \Swift_Message($this->translator->trans('email_subject'));

            $message->setFrom('no-reply@schicht-plan.ch')
                ->setTo($email)
                ->setBody(
                    $this->templating->render(
                        'email/plan-password.html.twig',
                        array(
                            'plan_id' => $plan->getId()
                        )
                    ),
                    'text/html'
                )->addPart(
                    $this->templating->render(
                        'email/plan-password.txt.twig',
                        array(
                            'plan_id' => $plan->getId()
                        )
                    ),
                    'text/plain'
                );
            $this->mailer->send($message);
        }
    }
}
