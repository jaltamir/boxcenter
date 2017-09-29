<?php

namespace Jaltamir\BoxCoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class SessionsGenerateErrorEvent extends Event
{
    const NAME = 'sessions.generate.error';
}