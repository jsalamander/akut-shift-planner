<?php

namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * EnrollmentValidator constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $shiftId
     * @param Constraint $constraint
     */
    public function validate($shiftId, Constraint $constraint)
    {
        if ($shiftId->getPeople()->count() > $shiftId->getNumberPeople()) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $shiftId)->addViolation();
        }
    }
}