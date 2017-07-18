<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlanCollection
 *
 * @ORM\Table(name="plan_collection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanCollectionRepository")
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

