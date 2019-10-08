<?php

namespace Tests\Louvre\BookingBundle\Services;

use Louvre\BookingBundle\Entity\Ticket;
use Louvre\BookingBundle\Services\PriceGenerator;
use Louvre\BookingBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PriceGeneratorTest extends KernelTestCase
{
    /**
     * @dataProvider dataCommandes
     */
    
    public function testpriceTicketsGenerator($halfday, $birthday, $price) {

        $commande = new Commande();
        $commande->setHalfDay($halfday);
        
        $ticket = new Ticket();
        $ticket->setBirthDate(new \DateTime($birthday));
        $commande->addTicket($ticket);
        
        $priceTicketGenerator = new PriceGenerator();
        $priceTicketGenerator->priceTicketsGenerator($commande);

        foreach ($commande->getTickets() as $ticket) {
            $ticket = $ticket->getPrice();
            $this->assertEquals($price, $ticket);
        }
    }
    
    public function dataCommandes()
    {
        return array(
            array(
                'halfday' => false,
                'birthday' => '1982-10-16',
                'price' => 16
            ),
            array(
                'halfday' => true,
                'birthday' => '1982-10-16',
                'price' => 8
            )
        );
    }
}