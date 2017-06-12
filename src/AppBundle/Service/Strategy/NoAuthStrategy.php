<?php

namespace AppBundle\Service\Strategy;

use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Service\Strategy\FormStrategyInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AuthStrategy
 */
class NoAuthStrategy implements FormStrategyInterface {

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getFormType() {
        return 'AppBundle\Form\PlanUnauthenticatedType';
    }

    public function getTwigTemplate(){
        return 'plan/new-unauth.html.twig';
    }

    /**
     * @param Plan $plan
     * @return Plan
     */
    public function handleSpecificFields($plan)
    {
        $newUser = new User();
        $newUser->setEmail($plan->getEmail());
        $pwHash = $this->encoder->encodePassword($newUser, $plan->getPassword());
        $newUser->setPassword($pwHash);
        $newUser->setUsername(bin2hex(random_bytes(100)));
        $plan->setUser($newUser);

        return $plan;
    }
}
