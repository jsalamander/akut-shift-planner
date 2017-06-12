<?php

namespace AppBundle\Service;

use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Service\Strategy\AuthStrategy;
use AppBundle\Service\Strategy\NoAuthStrategy;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    public function __construct(TokenStorageInterface $tokenStorage, UserPasswordEncoderInterface $encoder)
    {
        $this->tokenStorage = $tokenStorage;
        $this->encoder = $encoder;
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
     * Check if the submitted password matches the one
     * of the corresponding/generated user
     *
     * @param $plan Plan
     * @param $password string
     *
     * @return bool
     */
    public function checkOneTimeUserPassword($plan, $password) {
        if ($this->encoder->isPasswordValid($plan->getUser(), $password)) {
            //authenticate
            return true;
        } else {
            return false;
        }
    }
}
