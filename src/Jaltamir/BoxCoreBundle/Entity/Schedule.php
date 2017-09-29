<?php

namespace Jaltamir\BoxCoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Jaltamir\BoxCoreBundle\Entity\Base\BaseEntity;

/**
 * @ApiResource()
 *
 * @ORM\Entity()
 * @ORM\Table()
 *
 */
class Schedule extends BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     * @var \DateTime
     */
    private $startTime;

    /**
     * @ORM\ManyToOne(targetEntity="Jaltamir\BoxCoreBundle\Entity\Activity")
     * @ORM\JoinColumn(name="activity_id", referencedColumnName="id", nullable=false)
     *
     * @var Activity
     */
    private $activity;

    /**
     * @var integer
     *
     * @ORM\Column(type="json_array", nullable=false)
     */
    private $weekDay;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @var boolean
     */
    private $active = true;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param $startTime
     * @return $this
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * @return Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param Activity $activity
     * @return $this
     */
    public function setActivity(Activity $activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return array
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * @param array $weekDay
     * @return $this
     */
    public function setWeekDay(array $weekDay)
    {
        $this->weekDay = $weekDay;

        return $this;
    }

    /**
     * @param $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    public function __toString()
    {
        return (string)$this->activity;
    }

}