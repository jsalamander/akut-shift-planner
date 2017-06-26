<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Enrollment extends Constraint
{
    public $message = 'The shift is full. You can\'t enroll anymore.';
}