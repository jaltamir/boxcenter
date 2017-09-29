<?php

namespace Jaltamir\BoxCoreBundle\Services\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMInvalidArgumentException;
use Jaltamir\BoxCoreBundle\Entity\Assistance;
use Jaltamir\BoxCoreBundle\Entity\Payment;
use Jaltamir\BoxCoreBundle\Entity\Session;
use Jaltamir\BoxCoreBundle\Entity\User;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @DI\Service("box_core.manager.assistance")
 *
 */
class AssistanceManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @DI\InjectParams({
     *     "em"                  = @DI\Inject("doctrine.orm.entity_manager"),
     *     "validator"           = @DI\Inject("validator"),
     *     "translator"           = @DI\Inject("translator"),
     * })
     *
     * @param EntityManager $em
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManager $em, ValidatorInterface $validator, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->translator = $translator;
    }

    /**
     * @param Session $session
     * @param User $user
     * @return Assistance
     */
    public function createAssistance(Session $session, User $user)
    {
        $now = new \DateTime();
        $assistanceRepository = $this->em->getRepository(Assistance::class);
        $paymentRepository = $this->em->getRepository(Payment::class);
        $sessionsPayed = $paymentRepository->countSessionsPayed($session->getStartDateTime(), $user);
        $sessionsUsed = $assistanceRepository->countAssistancesByMonth($session->getStartDateTime(), $user);

        if ($session->getSchedule()->getActivity()->getCapacity() <= $assistanceRepository->countAssistances(
                $session
            )
        ) {
            throw new \RuntimeException($this->translator->trans('El aforo de esta sesión está completo.'));
        }

        if ($assistanceRepository->findOneBy(['session' => $session, 'user' => $user]) instanceof Assistance) {
            throw new \RuntimeException($this->translator->trans('Ya has reservado una plaza para esta sesión.'));
        }

        if ($now >= $session->getStartDateTime()) {
            throw new \RuntimeException($this->translator->trans('No puedes reservar una sesión ya realizada.'));
        }

        if ($now >= (clone $session->getStartDateTime())->modify('-15 minutes')) {
            throw new \RuntimeException($this->translator->trans('Sólo puedes reservar hasta 15 minutos antes del comienzo de la sesión.'));
        }

        if ($sessionsPayed == 0) {
            throw new \RuntimeException($this->translator->trans('No tienes abono para este mes.'));
        }

        if ($sessionsPayed <= $sessionsUsed) {
            throw new \RuntimeException($this->translator->trans('Ya has usado todas las sesiones que tienes contratadas este mes.'));
        }

        $assistance = new Assistance();
        $assistance->setSession($session)
            ->setUser($user);

        $this->em->persist($assistance);
        $this->em->flush();

        return $assistance;
    }

    /**
     * @param Assistance $assistance
     * @param User $user
     */
    public function cancelAssistance(Assistance $assistance, User $user)
    {
        $limit = new \DateTime();
        $limit->modify('+ 30 minutes');

        if ($assistance->getUser()->getId() !== $user->getId())
        {
            throw new \RuntimeException($this->translator->trans('No puedes cancelar esta reserva'));
        }

        if ($limit >= $assistance->getSession()->getStartDateTime()) {
            throw new \RuntimeException($this->translator->trans('Sólo puedes cancelar una reservar hasta 30 minutos antes del comienzo'));
        }

        $this->em->remove($assistance);
        $this->em->flush();
    }
}
