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

    public function __construct()
    {
        parent::__construct();
        $this->plans = new ArrayCollection();
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

    public function __toString() {
        return $this->username;
    }
}