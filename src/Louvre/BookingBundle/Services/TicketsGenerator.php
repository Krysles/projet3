<?php

namespace Louvre\BookingBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Louvre\BookingBundle\Entity\Ticket;

class TicketsGenerator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function ticketsGenerator($commande) {
        if($commande->getTickets()) {
            $commande->addTicket(new Ticket());
        }
        foreach($commande->getTickets() as $key => $ticket) {
            $i=0;
            while($i<$commande->getNbTickets()) {
                if (!$commande->getTickets()[$i]) {
                    $commande->addTicket(new Ticket());
                } elseif($commande->getNbTickets()<=$key) {
                    $commande->removeTicket($ticket);
                    $this->em->remove($ticket);
                }
                $i++;
            }
        }
    }
}