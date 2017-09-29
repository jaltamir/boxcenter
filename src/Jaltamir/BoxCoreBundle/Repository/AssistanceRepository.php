<?php

namespace Jaltamir\BoxCoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Jaltamir\BoxCoreBundle\Entity\Pass;
use Jaltamir\BoxCoreBundle\Entity\Session;
use Jaltamir\BoxCoreBundle\Entity\User;

class AssistanceRepository extends EntityRepository
{
    /**
     * @param Session $session
     * @return int
     */
    public function countAssistances(Session $session)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('count(a.id)')
            ->where($qb->expr()->eq('a.session', ':session'))
            ->setParameter('session', $session->getId());

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param \DateTime $date
     * @param User $user
     * @return int
     */
    public function countAssistancesByMonth(\DateTime $date, User $user)
    {
        $from = clone $date;
        $to = clone $date;

        $from->setDate($date->format('Y'), $date->format('m'), 1)->setTime(0, 0, 0);
        $to->setDate($date->format('Y'), $date->format('m') + 1, 1)->setTime(0, 0, 0);

        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->join('a.session', 'session');

        $qb->where(
            $qb->expr()->andX()->addMultiple(
                [
                    $qb->expr()->eq('a.user', ':user'),
                    $qb->expr()->gte('session.startDateTime', ':from'),
                    $qb->expr()->lt('session.startDateTime', ':to'),
                ]
            )
        )
            ->setParameter('from', $from->format('Y-m-d H:i:s'))
            ->setParameter('to', $to->format('Y-m-d H:i:s'))
            ->setParameter('user', $user->getId());

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param User $user
     * @param int $months
     * @return array
     */
    public function getAssistancesLastMonths(User $user, int $months = 1): array
    {
        $from = new \DateTime('first day this month');
        $from->modify("- $months months");
        $from->setTime(0, 0, 0);

        $qb = $this->createQueryBuilder('a')
            ->join('a.session', 'session');

        $qb->where(
            $qb->expr()->andX()->addMultiple(
                [
                    $qb->expr()->eq('a.user', ':user'),
                    $qb->expr()->gte('session.startDateTime', ':from'),
                ]
            )
        )
            ->setParameter('from', $from->format('Y-m-d H:i:s'))
            ->setParameter('user', $user->getId());

        $qb->orderBy('session.startDateTime', 'desc');

        return $qb->getQuery()->getResult();
    }

}
