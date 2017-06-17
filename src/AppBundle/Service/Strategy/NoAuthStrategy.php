<?php

namespace AppBundle\Service\Strategy;

use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Service\Strategy\FormStrategyInterface;
use AppBundle\Service\UserService;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class AuthStrategy
 */
class NoAuthStrategy implements FormStrategyInterface {

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        UserManager $userManager,
        UserService $userService
    ) {
        $this->encoder = $encoder;
        $this->userManager = $userManager;
        $this->userService = $userService;
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
     * @param $form
     * @return Plan
     */
    public function createPlan($form)
    {
        $password = $form->get('password')->getData();
        $email = $form->get('email')->getData();
        return $this->generateNewUserForPlan($form->getData(), $email, $password);
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
     * @param $plan Plan
     * @param $email
     * @param $password
     *
     * @return Plan
     */
    private function generateNewUserForPlan($plan, $email, $password) {

        //since we use uuid we can generate one before it gets saved
        $plan->setId(Uuid::uuid4()->toString());
        $user = $this->userManager->createUser();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $this->userManager->updateUser($user, true);
        $this->userService->emailPlanLink($email, $plan);
        $plan->setUser($user);

        return $plan;
    }

    /**
     * @param $form
     * @return Plan
     */
    public function handleSpecificFieldsByTemplate($form)
    {
        $password = $form->get('password')->getData();
        $email = $form->get('email')->getData();
        $orgPlan = $form->get('templates')->getData();
        $formPlan = $form->getData();
        $clone = clone $orgPlan;
        $clone->setIsTemplate(false);
        $clone->setTitle($formPlan->getTitle());
        $clone->setDate($formPlan->getDate());
        $clone->setDescription($formPlan->getDescription());
        $clone = $this->generateNewUserForPlan($clone, $email, $password);
        return $clone;
    }
}
