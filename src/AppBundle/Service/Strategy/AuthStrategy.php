<?php

namespace AppBundle\Service\Strategy;

use AppBundle\Service\Strategy\FormStrategyInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AuthStrategy
 */
class AuthStrategy implements FormStrategyInterface {

    /**
     * The user object
     * @var $user
     */
    private $user;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->user = $this->getUser();

    }

    public function getFormType() {
        return 'AppBundle\Form\PlanType';
    }

    public function getTwigTemplate(){
        return 'plan/new.html.twig';
    }

    public function handleSpecificFields($plan)
    {
        $plan->setUser($this->user);
        return $plan;
    }

    private function getUser(){
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }
}
