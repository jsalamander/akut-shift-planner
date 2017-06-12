<?php

namespace AppBundle\Form\Strategy;

use AppBundle\Form\Strategy\FormStrategyInterface;

/**
 * Class AuthStrategy
 */
class AuthStrategy implements FormStrategyInterface {

    public function getForm() {
        return true;
    }
}
