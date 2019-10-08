<?php

namespace Louvre\BookingBundle\Services;

use Exception;
use Louvre\BookingBundle\Entity\Commande;
use Symfony\Component\HttpFoundation\RequestStack;

class StripePaiement
{
    private $requestStack;
    
    private $sendCommand;

    private $stripePrivateKey;

    private $session;

    public function __construct(RequestStack $requestStack, $sendCommand, $session, $stripe_private_key)
    {
        $this->requestStack = $requestStack;
        
        $this->sendCommand = $sendCommand;

        $this->stripePrivateKey = $stripe_private_key;

        $this->session = $session;
    }
    
    public function stripePaiement(Commande $commande) {
        
        $request = $this->requestStack->getCurrentRequest();

        if ($commande->getPrice() == 0) {
            $this->sendCommand->sendCommand($commande);
            return true;
        }

        if ($request->isMethod('POST')) {
            \Stripe\Stripe::setApiKey($this->stripePrivateKey);
            try {
                \Stripe\Charge::create(array(
                    'source' => $request->request->get('stripeToken'),
                    'amount' => $commande->getPrice()*100,
                    'currency' => 'eur'
                ));
                $this->sendCommand->sendCommand($commande);
                return true;
            } catch (Exception $e) {
                $this->session->getFlashBag()->set('error', 'topay.msg.error');
                return false;
            }
        }
    }
}