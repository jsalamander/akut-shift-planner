<?php

namespace AppBundle\Service\Strategy;

use AppBundle\Service\Strategy\FormStrategyInterface;

/**
 * Class AuthStrategy
 */
class AuthStrategy implements FormStrategyInterface {

    public function getFormType() {
        return 'AppBundle\Form\PlanType';
    }

    public function getTwigTemplate(){
        return 'plan/new.html.twig';
    }
}
