<?php

namespace Jaltamir\BoxCoreBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class SessionRepository extends EntityRepository
{
    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return ArrayCollection
     */
    public function findBetweenDates(\DateTime $from, \DateTime $to): ArrayCollection
    {
        $qb = $this->createQueryBuilder('s');

        $qb->where($qb->expr()->andX()->addMultiple([
            $qb->expr()->gte('s.startDateTime', ':start'),
            $qb->expr()->lte('s.startDateTime', ':to'),
        ]));

        $qb->setParameter(':start', $from->format('Y-m-d H:i:s'))
            ->setParameter(':to', $to->format('Y-m-d H:i:s'));

        return new ArrayCollection($qb->getQuery()->getResult());
    }
}