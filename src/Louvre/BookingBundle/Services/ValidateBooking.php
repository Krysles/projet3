<?php

namespace Louvre\BookingBundle\Services;

use Louvre\BookingBundle\Entity\Commande;
use Symfony\Component\Templating\EngineInterface;

class ValidateBooking
{
    private $mailer;

    private $templating;

    private $generateTokenForOrder;
    
    private $mailer_address;

    private $translator;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, $generateTokenForOrder, $translator, $mailer_address)
    {
        $this->mailer = $mailer;

        $this->templating = $templating;

        $this->generateTokenForOrder = $generateTokenForOrder;
        
        $this->translator = $translator;

        $this->mailer_address = $mailer_address;

    }

    public function validateBooking(Commande $commande) {
        $subject = $this->translator->trans('mail.subject');
        $from = $this->mailer_address;
        $to = $commande->getEmail();
        $commande->setToken($this->generateTokenForOrder->generateToken($commande));
        $commande->setStatus(Commande::STATUS_VALIDATE);
        $format = 'text/html';
        $body = $this->templating->render(
            'LouvreBookingBundle:Emails:confirme.html.twig', array(
                'token' => $commande->getToken()
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