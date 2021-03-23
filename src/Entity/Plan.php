<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid;

/**
 * Plan
 *
 * @ORM\Table(name="plan")
 * @ORM\Entity(repositoryClass="App\Repository\PlanRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Plan
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 80,
     *      minMessage = "plan.title.min_length",
     *      maxMessage = "plan.title.max_length",
     *      groups={"new_from_template", "Default"}
     * )
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Date(groups={"new_from_template", "Default"})
     * @Assert\GreaterThanOrEqual("today", groups={"new_from_template", "Default"})
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"new_from_template", "Default"})
     * @Assert\Length(
     *      min = 2,
     *      max = 500,
     *      minMessage = "plan.description.min_length",
     *      maxMessage = "plan.description.max_length",
     *      groups={"new_from_template", "Default"}
     * )
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @Assert\Count(
     *      min = 1,
     *      max = 100,
     *      minMessage = "plan.shifts.count_min",
     *      maxMessage = "plan.shifts.count_max",
     * )
     *
     * @Assert\Valid
     * @ORM\OrderBy({"orderIndex" = "ASC"})
     * @ORM\OneToMany(targetEntity="Shift", mappedBy="plan", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $shifts;

    /**
     * @var int
     *
     * @ORM\Column(name="is_template", type="boolean", nullable=true)
     */
    private $isTemplate = false;

    /**
     * Indicates if this plan should be open for the public
     * @var int
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=true)
     */
    private $isPublic = false;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="plans", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="PlanCollection", inversedBy="plans")
     */
    private $planCollection;

    public function __construct()
    {
        $this->shifts = new ArrayCollection();
        $this->planCollection = new ArrayCollection();
        $this->setId(Uuid::uuid4()->toString());
    }

    /**
     * @return int
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param int $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
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

    /**
     * @return mixed
     */
    public function getPlanCollection()
    {
        return $this->planCollection;
    }

    /**
     * @param mixed $planCollection
     */
    public function setPlanCollection($planCollection)
    {
        $this->planCollection = $planCollection;
    }

    /**
     * @return int
     */
    public function getIsTemplate()
    {
        return $this->isTemplate;
    }

    /**
     * @param int $isTemplate
     */
    public function setIsTemplate($isTemplate)
    {
        $this->isTemplate = $isTemplate;
    }


    /**
     * Get string
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id (uuid)
     *
     * @return string
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Plan
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Plan
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Plan
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add shift
     *
     * @param \App\Entity\Shift $shift
     *
     * @return Plan
     */
    public function addShift(\App\Entity\Shift $shift)
    {
        $shift->setPlan($this);
        $this->shifts[] = $shift;

        return $this;
    }

    /**
     * Remove shift
     *
     * @param \App\Entity\Shift $shift
     */
    public function removeShift(\App\Entity\Shift $shift)
    {
        $this->shifts->removeElement($shift);
        $shift->setPlan(null);
    }

    /**
     * Get shifts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShifts()
    {
        return $this->shifts;
    }

    /**
     * Set shifts
     * @param $shifts
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setShifts($shifts)
    {
        foreach ($shifts as $shift) {
            $shift->setPlan($this);
            $this->addShift($shift);
        }
    }


    public function __toString() {
        return $this->title;
    }

    public function __clone() {
        if ($this->id) {
            $this->setId(null);

            $mClone = new ArrayCollection();
            foreach ($this->shifts as $item) {
                $itemClone = clone $item;
                $itemClone->setPlan($this);
                $mClone->add($itemClone);
            }
            $this->shifts = $mClone;
        }
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }
}
