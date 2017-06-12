<?php

namespace AppBundle\Service\Strategy;

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
}
