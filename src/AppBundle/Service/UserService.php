<?php

namespace AppBundle\Service;

use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserPasswordEncoderInterface $encoder,
        Session $session
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->encoder = $encoder;
        $this->session = $session;
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
     * Authenticate one time user
     *
     * @param $user User
     */
    private function authenticate($user) {
        if ($user->hasRole('ROLE_ONE_TIME_USER')) {
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_main', serialize($token));
        } else {
            throw new AccessDeniedException('You\'re not a one time user');
        }
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
            $this->authenticate($plan->getUser());
            return true;
        } else {
            return false;
        }
    }
}
