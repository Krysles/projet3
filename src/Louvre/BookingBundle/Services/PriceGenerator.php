<?php

namespace Louvre\BookingBundle\Services;

use Louvre\BookingBundle\Entity\Commande;

class PriceGenerator
{
    const MAX_AGE_BABY = 3;
    const MAX_AGE_CHILD = 11;
    const MAX_AGE_ADULT = 59;
    const MIN_AGE_SENIOR = 60;

    const PRICE_REDUCED = array(
        'name' => 'constant.reduced',
        'price' => 10
    );
    const PRICE_BABY = array(
        'name' => 'constant.baby',
        'price' => 0
    );
    const PRICE_CHILD = array(
        'name' => 'constant.child',
        'price' => 8
    );
    const PRICE_ADULT = array(
        'name' => 'constant.adult',
        'price' => 16
    );
    const PRICE_SENIOR = array(
        'name' => 'constant.senior',
        'price' => 12
    );

    const HALFDAY = 0.5;
    
    public function priceTicketsGenerator(Commande $commande) {
        $commande->setPrice(0);
        foreach ($commande->getTickets() as $ticket) {
            $priceTicket = $this->calculatePrice($ticket);
            if ($commande->getHalfDay()) {
                $ticket->setPrice($priceTicket['price'] * self::HALFDAY);
                $ticket->setPriceName($priceTicket['name']);
                $commande->setPrice($ticket->getPrice() + $commande->getPrice());
            } else {
                $ticket->setPrice($priceTicket['price']);
                $ticket->setPriceName($priceTicket['name']);
                $commande->setPrice($ticket->getPrice() + $commande->getPrice());
            }
        }
    }

    private function calculatePrice($ticket) {
        if ($ticket->getReducedPrice() AND ($this->calculateAge($ticket) > self::MAX_AGE_CHILD)) {
            return $price = self::PRICE_REDUCED;
        } else {
            $ticket->setReducedPrice(false);
            return $price = $this->calculatePriceFromAge($this->calculateAge($ticket));
        }
    }

    private function calculatePriceFromAge($age) {
        if ($age <= self::MAX_AGE_BABY) {
            return self::PRICE_BABY;
        } elseif ($age <= self::MAX_AGE_CHILD) {
            return self::PRICE_CHILD;
        } elseif ($age <= self::MAX_AGE_ADULT) {
            return self::PRICE_ADULT;
        } elseif ($age >= self::MIN_AGE_SENIOR) {
            return self::PRICE_SENIOR;
        }
    }

    private function calculateAge($ticket) {
        $dateFrom = $ticket->getBirthDate();
        $dateNow = new \DateTime();
        $interval = $dateNow->diff($dateFrom);
        $age = $interval->format('%y');
        return $age;
    }
}