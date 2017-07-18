<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\OneToMany(targetEntity="Plan", mappedBy="user", cascade={"persist"})
     */
    private $plans;

    /**
     *
     * @ORM\OneToMany(targetEntity="PlanCollection", mappedBy="user", cascade={"persist"})
     */
    private $planCollections;

    /**
     * @var $people
     * @ORM\OneToMany(targetEntity="Person", mappedBy="user")
     */
    private $people;

    public function __construct()
    {
        parent::__construct();
        $this->plans = new ArrayCollection();
        $this->people = new ArrayCollection();
    }

    /**
     * @return User
     * @param \AppBundle\Entity\Person
     */
    public function addPerson($person)
    {
        $this->people[] = $person;

        return $this;
    }

    /**
     * @param mixed $people
     */
    public function getPeople($people)
    {
        $this->people = $people;
    }

    /**
     * Remove person (enrollment)
     *
     * @param \AppBundle\Entity\Person
     */
    public function removePerson(\AppBundle\Entity\Person $person)
    {
        $this->people->removeElement($person);
    }

    /**
     * Add plan
     *
     * @param \AppBundle\Entity\Plan
     *
     * @return User
     */
    public function addPlan(\AppBundle\Entity\Plan $plan)
    {
        $this->plans[] = $plan;

        return $this;
    }

    /**
     * Remove plan
     *
     * @param \AppBundle\Entity\Plan
     */
    public function removePlan(\AppBundle\Entity\Plan $plan)
    {
        $this->plans->removeElement($plan);
    }

    /**
     * Get plans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlans()
    {
        return $this->plans;
    }

    /**
     * Add planCollection
     *
     * @param \AppBundle\Entity\PlanCollection
     *
     * @return User
     */
    public function addPlanCollection(\AppBundle\Entity\PlanCollection $planCollection)
    {
        $this->planCollections[] = $planCollection;

        return $this;
    }

    /**
     * Remove planCollection
     *
     * @param \AppBundle\Entity\Plan
     */
    public function removePlanCollection(\AppBundle\Entity\PlanCollection $planCollection)
    {
        $this->planCollections->removeElement($planCollection);
    }

    /**
     * Get plansCollections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanCollections()
    {
        return $this->plans;
    }

    public function __toString() {
        return $this->email;
    }
}