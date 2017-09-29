<?php

namespace Jaltamir\BoxCoreBundle\Event;

use Jaltamir\BoxCoreBundle\Services\Manager\ScheduleManager;
use Jaltamir\BoxCoreBundle\Services\Manager\SessionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SessionsSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionManager
     */
    private $sessionManager;

    /**
     * @var ScheduleManager
     */
    private $scheduleManager;

    public function __construct(SessionManager $sessionManager, ScheduleManager $scheduleManager)
    {
        $this->sessionManager = $sessionManager;
        $this->scheduleManager = $scheduleManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            SessionsViewEvent::NAME => 'onSessionsView',
            SessionsGenerateErrorEvent::NAME => 'onSessionsGenerateError',
        ];
    }

    /**
     * @param SessionsViewEvent $event
     */
    public function onSessionsView(SessionsViewEvent $event)
    {
        $from = new \DateTime('Monday this week');
        $to = new \DateTime('Sunday this week');

        $from->setTime(0, 0, 0);
        $to->setTime(23, 59, 59);

        $schedules = $this->scheduleManager->getSchedules();

        $this->sessionManager->generateSessions($schedules, $from, $to);
    }

    /**
     * TODO: To be implemented
     *
     * @param SessionsGenerateErrorEvent $event
     */
    public function onSessionsGenerateError(SessionsGenerateErrorEvent $event)
    {
        //TODO: To be implemented
    }
}