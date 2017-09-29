<?php

namespace Jaltamir\BoxCoreBundle\Factory;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("box_core.factory.mailer")
 *
 */
class MailerFactory
{
    /**
     * @param mixed       $body
     * @param string      $subject
     * @param string|null $to
     * @param string|null $from
     * @param array       $bbcs
     * @param array       $attachments
     * @param string|null $fromAlias
     * @param string|null $toAlias
     *
     * @return \Swift_Message
     *
     * @throws \InvalidArgumentException
     */
    public function createMessage($body, $subject, $to, $from, array $bbcs = [], array $attachments = [], $fromAlias = null, $toAlias = null)
    {
        if (!is_string($subject) || !is_string($body))
        {
            throw new \InvalidArgumentException('Subject and body must be an string');
        }

        /** @var \Swift_Message $message */
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom($from, $fromAlias);
        $message->setTo($to, $toAlias);
        $message->setBody($body, 'text/html');

        foreach ($bbcs as $address)
        {
            $message->addBcc($address);
        }

        foreach ($attachments as $attachment)
        {
            if (!$attachment instanceof \Swift_Attachment)
            {
                throw new \InvalidArgumentException('An attachment received is not valid');
            }

            $message->attach($attachment);
        }

        return $message;
    }
}
