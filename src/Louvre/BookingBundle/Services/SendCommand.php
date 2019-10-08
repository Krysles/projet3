<?php

namespace Louvre\BookingBundle\Services;

use Louvre\BookingBundle\Entity\Commande;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManagerInterface;

class SendCommand
{
    private $em;
    
    private $mailer;

    private $templating;

    private $mailer_address;
    
    private $translator;

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer, EngineInterface $templating, $translator, $mailer_address)
    {
        $this->em = $em;
        
        $this->mailer = $mailer;

        $this->templating = $templating;

        $this->mailer_address = $mailer_address;
        
        $this->translator = $translator;
    }

    public function sendCommand(Commande $commande) {

        $commande->setToken(null);
        $commande->setStatus(Commande::STATUS_PAID);
        $this->em->flush();
        $subject = $this->translator->trans('mail.subject');
        $from = $this->mailer_address;
        $to = $commande->getEmail();
        $format = 'text/html';
        $body = $this->templating->render(
            'LouvreBookingBundle:Emails:tickets.html.twig', array(
            'commande' => $commande,
            'tickets' => $commande->getTickets()
        ));
        $this->sendingMail($subject, $from, $to, $format, $body);
    }

    private function sendingMail($subject, $from, $to, $format, $body) {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setContentType($format)
            ->setBody($body);
        $this->mailer->send($message);
    }
}