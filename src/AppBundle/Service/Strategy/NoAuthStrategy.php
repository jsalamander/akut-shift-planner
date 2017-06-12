<?php

namespace AppBundle\Service\Strategy;

use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Service\Strategy\FormStrategyInterface;

/**
 * Class AuthStrategy
 */
class NoAuthStrategy implements FormStrategyInterface {

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
        $newUser->setPassword(password_hash ($plan->getPassword(), PASSWORD_DEFAULT));
        $newUser->setUsername(hash('md5', $plan->getEmail()));
        $plan->setUser($newUser);

        return $plan;
    }
}
