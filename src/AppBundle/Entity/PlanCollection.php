<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PlanCollection
 *
 * @ORM\Table(name="plan_collection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanCollectionRepository")
 * @UniqueEntity("title")
 */
class PlanCollection
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Id
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="planCollections", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\OneToMany(targetEntity="Plan", mappedBy="planCollection", cascade={"persist"})
     */
    private $plans;

    public function __construct()
    {
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


    /**
     * Set title
     *
     * @param string $title
     *
     * @return PlanCollection
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}

