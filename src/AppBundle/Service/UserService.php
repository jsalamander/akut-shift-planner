<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\Strategy\AuthStrategy;
use AppBundle\Service\Strategy\NoAuthStrategy;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;

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
}
