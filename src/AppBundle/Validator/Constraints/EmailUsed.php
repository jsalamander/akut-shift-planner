<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailUsed extends Constraint
{
    public $message = 'The Email "{{ string }}" is already in use';
}