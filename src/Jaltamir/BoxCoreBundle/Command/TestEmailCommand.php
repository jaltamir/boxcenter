<?php

namespace Jaltamir\BoxCoreBundle\Command;

use Jaltamir\BoxCoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestEmailCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('box:test:email')
            ->setDescription('Testing email');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $emailManager = $this->getContainer()->get('box_core.manager.email');

        $previousUser = $em->getRepository(User::class)->findOneBy(
            [
                'flagAdmin' => true,
                'email' => 'info@jaltamir.com',
            ]
        );

        if ($previousUser instanceof User) {
            $output->writeln('Sending email to main admin..');
            $emailManager->sendMailWelcome($previousUser);
            $output->writeln('Email sended');
        }
    }
}
