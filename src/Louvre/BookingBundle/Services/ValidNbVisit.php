<?php

namespace Louvre\BookingBundle\Services;


use Doctrine\ORM\EntityManagerInterface;

class ValidNbVisit
{
    private $em;

    const LIMIT_TICKETS = 1000;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function check($day, $nbTickets) {
        $nbTicketsFromDay = $this->em->getRepository('LouvreBookingBundle:Ticket')->countTicketsFromDay($day);
        
        $nbTicketsFromVisit = $nbTickets;
        
        if (($nbTicketsFromDay + $nbTicketsFromVisit) > self::LIMIT_TICKETS) {
            return true;
        } else {
            return false;
        }
    }
}