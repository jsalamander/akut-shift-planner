<?php

namespace AppBundle\Validator\Constraints;

use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates a given email and checks if a user has registered with it
 *
 * Class EmailUsedValidator
 * @package AppBundle\Validator\Constraints
 */
class EmailUsedValidator extends ConstraintValidator
{

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * EmailUsedValidator constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param string $email
     * @param Constraint $constraint
     */
    public function validate($email, Constraint $constraint)
    {
        if ($this->userManager->findUserByEmail($email)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $email)
                ->addViolation();
        }
    }
}