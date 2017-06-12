<?php

namespace AppBundle\Service;

use AppBundle\Service\Strategy\AuthStrategy;
use AppBundle\Service\Strategy\NoAuthStrategy;

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

    public function createStrategy($user = null) {
        if($user) {
            $this->strategy = new AuthStrategy();
        } else {
            $this->strategy = new NoAuthStrategy();
        }
    }

    public function getFormType() {
        return $this->strategy->getFormType();
    }

    public function getTwigTemplate() {
        return $this->strategy->getTwigTemplate();
    }
}
