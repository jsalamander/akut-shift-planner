<?php

namespace AppBundle\Service\Strategy;

use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Service\Strategy\FormStrategyInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AuthStrategy
 */
class NoAuthStrategy implements FormStrategyInterface {

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return string
     */
    public function getFormType() {
        return 'AppBundle\Form\PlanUnauthenticatedType';
    }

    /**
     * @return string
     */
    public function getTwigTemplate(){
        return 'plan/new-unauth.html.twig';
    }

    /**
     * @param $formData
     * @return Plan
     */
    public function createPlan($formData)
    {
        $shifts = $formData['shifts'];
        $date = $formData['date'];
        $title = $formData['description'];
        $description = $formData['title'];

        $plan = new Plan();
        $plan->setDate($date);
        $plan->setDescription($description);
        $plan->setShifts($shifts);
        $plan->setTitle($title);
        
        $password = $formData['password'];
        $email = $formData['email'];
        return $this->generateNewUserForPlan($plan, $email, $password);
    }

    /**
     * @return string
     */
    public function getByTemplateFormType() {
        return 'AppBundle\Form\ByTemplate\PlanByTemplateUnauthenticatedType';
    }

    /**
     * @return string
     */
    public function getByTemplateTwigTemplate() {
        return 'plan/by-template/new-by-template-unauth.html.twig';
    }

    /**
     * @param $plan
     * @return Plan
     */
    private function generateNewUserForPlan($plan, $email, $password) {
        $newUser = new User();
        $newUser->setEmail($email);
        $pwHash = $this->encoder->encodePassword($newUser, $password);
        $newUser->setPassword($pwHash);
        $newUser->setUsername(bin2hex(random_bytes(100)));
        $plan->setUser($newUser);

        return $plan;
    }

    /**
     * @param $formData
     * @return Plan
     */
    public function handleSpecificFieldsByTemplate($formData)
    {
        $templatePlan = $formData['templates'];
        $title = $formData['title'];
        $description = $formData['description'];
        $date = $formData['date'];
        $email = $formData['email'];
        $password = $formData['password'];

        $clone = clone $templatePlan;
        $clone->setIsTemplate(false);
        $clone->setTitle($title);
        $clone->setDate($date);
        $clone->setDescription($description);
        $clone = $this->generateNewUserForPlan($clone, $email, $password);

        return $clone;
    }
}
