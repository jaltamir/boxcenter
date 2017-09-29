<?php

namespace Jaltamir\BoxCoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Jaltamir\BoxCoreBundle\Entity\Pass;

class PassRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getMainPasses(): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->orderBy('p.id', 'asc')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getPassesAsChoices(int $passSelected = null): array
    {
        $choices = [];

        foreach ($this->findAll() as $pass)
        {
            /** @var Pass $pass */
            if ($passSelected === null || ($passSelected !== null && $pass->getId() === $passSelected))
            {
                $choices[$pass->getNameForFront()] = $pass->getId();
            }
        }

        return $choices;
    }

}
