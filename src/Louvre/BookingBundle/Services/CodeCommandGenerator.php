<?php

namespace Louvre\BookingBundle\Services;


use Doctrine\ORM\EntityManagerInterface;


class CodeCommandGenerator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function codeIsValid($codeCommande) {
        if($this->em->getRepository('LouvreBookingBundle:Commande')->findOneByCodeCommande($codeCommande)) {
            return true;
        } else {
            return false;
        }
    }

    protected function generateCode() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $codeCommande = str_shuffle($characters);
        $codeCommande = substr($codeCommande, 0, 12);
        return $codeCommande;
    }

    public function generateCodeCommande() {
        do{
            $code = $this->generateCode();
        }
        while($this->codeIsValid($code));
        return $code;
    }
}