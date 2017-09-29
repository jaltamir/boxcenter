<?php

namespace Jaltamir\BoxCoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Jaltamir\BoxCoreBundle\Entity\Base\BaseEntity;

/**
 * @ApiResource()
 *
 * @ORM\Entity(repositoryClass="Jaltamir\BoxCoreBundle\Repository\SessionRepository")
 * @ORM\Table()
 *
 */
class Session extends BaseEntity
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
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $startDateTime;

    /**
     * @ORM\ManyToOne(targetEntity="Jaltamir\BoxCoreBundle\Entity\Schedule", fetch="EAGER")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id", nullable=false)
     *
     * @var Schedule
     */
    private $schedule;

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
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * @param $startDateTime
     * @return $this
     */
    public function setStartDateTime(\DateTime $startDateTime)
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    /**
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param Schedule $schedule
     * @return $this
     */
    public function setSchedule(Schedule $schedule)
    {
        $this->schedule = $schedule;

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

    public function getStartDateTimeFormat()
    {
        return $this->startDateTime->format('Y/m/d H:i');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s : %s', $this->schedule->getActivity()->getName(), $this->getStartDateTimeFormat());
    }


}