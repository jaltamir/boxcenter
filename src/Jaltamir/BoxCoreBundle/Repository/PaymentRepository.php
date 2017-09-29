<?php

namespace Jaltamir\BoxCoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Jaltamir\BoxCoreBundle\Entity\Pass;
use Jaltamir\BoxCoreBundle\Entity\Payment;
use Jaltamir\BoxCoreBundle\Entity\User;

class PaymentRepository extends EntityRepository
{
    /**
     * @param User $user
     * @param int $number
     * @return array
     */
    public function findLast(User $user, int $number = 10): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('p.user', ':user'))
            ->setParameter('user', $user->getId())
            ->orderBy('p.dateSubscribed', 'desc')
            ->setMaxResults($number);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param \DateTime $date
     * @param User $user
     * @return int
     */
    public function countSessionsPayed(\DateTime $date, User $user)
    {
        $sessionsPayed = 0;
        $from = clone $date;
        $to = clone $date;

        $from->setDate($date->format('Y'), $date->format('m'), 1)->setTime(0, 0, 0);
        $to->setDate($date->format('Y'), $date->format('m') + 1, 1)->setTime(0, 0, 0);

        $qb = $this->createQueryBuilder('p');

        $qb->where(
            $qb->expr()->andX()->addMultiple(
                [
                    $qb->expr()->eq('p.user', ':user'),
                    $qb->expr()->gte('p.dateSubscribed', ':from'),
                    $qb->expr()->lt('p.dateSubscribed', ':to'),
                ]
            )
        )
            ->setParameter('from', $from->format('Y-m-d H:i:s'))
            ->setParameter('to', $to->format('Y-m-d H:i:s'))
            ->setParameter('user', $user->getId());

        foreach ($qb->getQuery()->getResult() as $payment) {
            /** @var Payment $payment */
            $sessionsPayed += $payment->getPass()->getNumSessions();
        }

        return $sessionsPayed;
    }

}
