<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates that only a given amount of people can enroll
 *
 * Class EnrollmentValidator
 * @package AppBundle\Validator\Constraints
 */
class EnrollmentValidator extends ConstraintValidator
{

    /**
     * @param string $shiftId
     * @param Constraint $constraint
     */
    public function validate($shiftId, Constraint $constraint)
    {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $shiftId)->addViolation();
    }
}