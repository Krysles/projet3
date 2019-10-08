<?php

namespace BookingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Louvre\BookingBundle\Entity\Commande;

class LoadCommandeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $comm = new Commande();
        $manager->persist($comm);
        $comm->setDate(new \DateTime("2036/11/21"));
        $comm->setNbTickets(1);
        $comm->setHalfDay(0);
        $comm->setCodeCommande('numerodecom1');
        $comm->setStatus(10);
        $comm->setToken('tokendelacommande1');
        $comm->setPrice(16);

        $comm2 = new Commande();
        $manager->persist($comm2);
        $comm2->setDate(new \DateTime("2036/11/21"));
        $comm2->setNbTickets(1);
        $comm2->setHalfDay(0);
        $comm2->setCodeCommande('numerodecom2');
        $comm2->setStatus(40);
        $comm2->setToken('tokendelacommande2');
        $comm2->setPrice(16);
        
        $manager->flush();
    }
}