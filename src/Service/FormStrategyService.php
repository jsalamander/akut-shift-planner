<?php

namespace App\Service;

use App\Entity\Plan;
use App\Service\Strategy\AuthStrategy;
use App\Service\Strategy\NoAuthStrategy;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Strategy to decide whether to handle the form for a user or an anon
 * Class NewPlanFormStrategy
 */
class FormStrategyService {

    /**
     * @var null
     * type App\Service\Strategy\FormStrategyInterface
     */
    private $strategy = NULL;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        AuthStrategy $authStrategy,
        NoAuthStrategy $noAuthStrategy,
        TokenStorageInterface $tokenStorage,
        UserService $userService,
        UserManagerInterface $userManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->userManager = $userManager;

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
     * @param $form
     * @return \App\Entity\Plan
     */
    public function createPlan($form) {
        return $this->strategy->createPlan($form);
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
