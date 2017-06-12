<?php

namespace AppBundle\Service\Strategy;

use AppBundle\Entity\Plan;
use AppBundle\Service\Strategy\FormStrategyInterface;
use AppBundle\Service\UserService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AuthStrategy
 */
class AuthStrategy implements FormStrategyInterface {

    /**
     * The user object
     * @var $user
     */
    private $user;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, UserService $userService)
    {
        $this->tokenStorage = $tokenStorage;
        $this->user = $userService->getUser();

    }

    /**
     * @return string
     */
    public function getFormType() {
        return 'AppBundle\Form\PlanType';
    }

    /**
     * @return string
     */
    public function getTwigTemplate(){
        return 'plan/new.html.twig';
    }

    /**
     * @param $formData
     * @return mixed
     */
    public function createPlan($formData)
    {
        $plan = new Plan();
        $plan->setUser($this->user);
        return $plan;
    }

    /**
     * @return string
     */
    public function getByTemplateFormType() {
        return 'AppBundle\Form\ByTemplate\PlanByTemplateType';
    }

    /**
     * @return string
     */
    public function getByTemplateTwigTemplate() {
        return 'plan/by-template/new-by-template.html.twig';
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

        $clone = clone $templatePlan;
        $clone->setIsTemplate(false);
        $clone->setTitle($title);
        $clone->setDate($date);
        $clone->setDescription($description);
        $clone->setUser($this->user);

        return $clone;
    }
}
