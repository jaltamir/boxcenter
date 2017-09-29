<?php

namespace Jaltamir\BoxCoreBundle\Command;

use Jaltamir\BoxCoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateFirstAdminCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('box:create:first-admin')
            ->setDescription('First Admin creation');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $passwordEncoder = $this->getContainer()->get('security.password_encoder');

        $output->writeln('Creating first admin');

        $previousUser = $em->getRepository(User::class)->findOneBy(
            [
                'flagAdmin' => true,
                'email' => 'info@jaltamir.com',
            ]
        );

        if ($previousUser instanceof User) {
            $output->writeln('There is an admin already registered');

            return;
        }

        $user = new User();

        $user->setEmail('info@jaltamir.com');

        $password = $passwordEncoder->encodePassword($user, '141084');

        $user->setPassword($password);
        $user->setFlagAdmin(true);

        $em->persist($user);
        $em->flush();

        $output->writeln('The admin has been created');
    }
}
