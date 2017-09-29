<?php

namespace Jaltamir\BoxCoreBundle\Services\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Jaltamir\BoxCoreBundle\Entity\Schedule;

class ScheduleManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return ArrayCollection
     */
    public function getSchedules()
    {
        $schedules = $this->em->getRepository(Schedule::class)
            ->findAll();

        return new ArrayCollection($schedules);
    }

    /**
     * @return array
     */
    public function getWeekDaysAtChoices()
    {
        return [
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
        ];
    }
}