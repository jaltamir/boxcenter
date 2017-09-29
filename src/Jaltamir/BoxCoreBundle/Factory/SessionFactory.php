<?php

namespace Jaltamir\BoxCoreBundle\Factory;

use Jaltamir\BoxCoreBundle\Entity\Schedule;
use Jaltamir\BoxCoreBundle\Entity\Session;

class SessionFactory
{
    public function create(Schedule $schedule, \DateTime $start)
    {
        $session = new Session();

        $session->setSchedule($schedule)
            ->setStartDateTime($start);

        return $session;
    }
}