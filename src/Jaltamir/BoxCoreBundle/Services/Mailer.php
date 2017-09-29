<?php

namespace Jaltamir\BoxCoreBundle\Services;

use JMS\DiExtraBundle\Annotation as DI;
use Jaltamir\BoxCoreBundle\Factory\MailerFactory;

/**
 * @DI\Service("box_core.mailer")
 *
 */
class Mailer
{
    const IT_EMAIL = 'dev@jaltamir.com';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var MailerFactory
     */
    private $mailerFactory;

    /**
     * @var string
     */
    private $environment;

    /**
     * @DI\InjectParams({
     *      "mailer"        = @DI\Inject("mailer"),
     *      "mailerFactory" = @DI\Inject("box_core.factory.mailer"),
     *      "environment"   = @DI\Inject("%kernel.environment%")
     * })
     *
     * @param \Swift_Mailer $mailer
     * @param MailerFactory $mailerFactory
     * @param $environment
     */
    public function __construct(\Swift_Mailer $mailer, MailerFactory $mailerFactory, $environment)
    {
        $this->mailer = $mailer;
        $this->mailerFactory = $mailerFactory;
        $this->environment = $environment;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param array $attachments
     * @param array $bcc
     *
     * @return bool
     */
    public function sendMail($to, $subject, $body, array $attachments = [], array $bcc = [])
    {
        if ($this->environment !== 'prod') {
            $to = self::IT_EMAIL;
            $bcc = [];
            $subject = '[TESTING] '.$subject;
        }

        $message = $this->mailerFactory->createMessage($body, $subject, $to, self::IT_EMAIL, $bcc, $attachments);

        return (bool)$this->mailer->send($message);
    }
}
