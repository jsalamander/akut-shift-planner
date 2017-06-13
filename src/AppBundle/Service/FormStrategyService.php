<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\Strategy\AuthStrategy;
use AppBundle\Service\Strategy\NoAuthStrategy;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    public function __construct(
        AuthStrategy $authStrategy,
        NoAuthStrategy $noAuthStrategy,
        TokenStorageInterface $tokenStorage,
        UserService $userService
    ) {
        $this->tokenStorage = $tokenStorage;

        if($userService->getUser()) {
            $this->strategy = $authStrategy;
        } else {
            $this->strategy = $noAuthStrategy;
        }
    }

    /**
     * @return string
     */
    public function getFormType() {
        return $this->strategy->getFormType();
    }

    /**
     * @return string
     */
    public function getByTemplateFormType()
    {
        return $this->strategy->getByTemplateFormType();
    }

    /**
     * @return string
     */
    public function getTwigTemplate() {
        return $this->strategy->getTwigTemplate();
    }

    /**
     * @return string
     */
    public function getByTemplateTwigTemplate() {
        return $this->strategy->getByTemplateTwigTemplate();
    }

    /**
     * Save new user or/and make sure both entities are connected
     *
     * @param $formData
     * @return \AppBundle\Entity\Plan
     */
    public function createPlan($formData) {
        return $this->strategy->createPlan($formData);
    }

    /**
     * @param $formData
     * @return Plan
     */
    public function handleSpecificFieldsByTemplate($formData)
    {
        return $this->strategy->handleSpecificFieldsByTemplate($formData);
    }
}
