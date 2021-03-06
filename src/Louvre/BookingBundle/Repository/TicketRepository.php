<?php

namespace Louvre\BookingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Louvre\BookingBundle\Entity\Commande;

/**
 * TicketRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TicketRepository extends EntityRepository
{
    public function countTicketsFromDay($date) {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->select('count(t.id)')
            ->leftJoin('t.commande', 'c')
            ->where('c.date=:date')
            ->setParameter('date', $date)
            ->andWhere('c.status>=:status')
            ->setParameter('status', Commande::STATUS_PAID)
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }
}