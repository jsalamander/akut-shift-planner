<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\Strategy\AuthStrategy;
use AppBundle\Service\Strategy\NoAuthStrategy;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Strategy to decide whether to handle the form for a user or an anon
 * Class NewPlanFormStrategy
 */
class FormStrategyService {

    /**
     * @var null
     * type AppBundle\Service\Strategy\FormStrategyInterface
     */
    private $strategy = NULL;

    public function __construct(AuthStrategy $authStrategy, NoAuthStrategy $noAuthStrategy, TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;

        if($this->getUser()) {
            $this->strategy = $authStrategy;
        } else {
            $this->strategy = $noAuthStrategy;
        }
    }

    /**
     * @return string
     */
    public function getFormType() {
        return $this->strategy->getFormType();
    }

    /**
     * @return string
     */
    public function getTwigTemplate() {
        return $this->strategy->getTwigTemplate();
    }

    public function handleSpecificFields($plan) {
        return $this->strategy->handleSpecificFields($plan);
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
