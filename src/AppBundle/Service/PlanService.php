<?php

namespace AppBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Provides Plans based upon the logged in user
 * Class PlanService
 */
class PlanService {

    /**
     * @var $tokenStorage
     * type TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em, UserService $userService)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->userService = $userService;

    }

    /**
     * Select correct plans to display
     * by user
     *
     * @return ArrayCollection
     */
    public function getTemplatePlans()
    {
        $queryBuilder = $this->em->getRepository('AppBundle:Plan')->createQueryBuilder('p');

        $qb = $queryBuilder->where('p.isTemplate = true');
        $qb->andWhere('p.isPublic = true');

        if ($this->userService->getUser()) {
            $qb->andWhere('p.user = :user')->setParameter('user', $this->userService->getUser()->getId());
        }

        return $qb->orderBy('p.title', 'ASC')->getQuery()->getResult();
    }

    public function getUserPlans()
    {
        $queryBuilder = $this->em->getRepository('AppBundle:Plan')->createQueryBuilder('p');
        $queryBuilder->where('p.user = :user')->setParameter('user', $this->userService->getUser()->getId());

        return $queryBuilder->orderBy('p.title', 'ASC')->getQuery()->getResult();
    }

}
