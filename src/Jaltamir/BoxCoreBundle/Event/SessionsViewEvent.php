<?php

namespace Jaltamir\BoxCoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class SessionsViewEvent extends Event
{
    const NAME = 'sessions.view';
}