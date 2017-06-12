<?php

namespace AppBundle\Form\Strategy;

use AppBundle\Form\Strategy\FormStrategyInterface;

/**
 * Class AuthStrategy
 */
class NoAuthStrategy implements FormStrategyInterface {

    public function getForm() {
        return false;
    }
}
