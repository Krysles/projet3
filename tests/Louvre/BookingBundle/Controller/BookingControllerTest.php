<?php

namespace Tests\Louvre\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');

        $form = $crawler->selectButton('commande_init[Suivant]')->form();
        $form['commande_init[date]'] = (new \DateTime('-1 day'))->format('d/m/Y');
        $form['commande_init[nbTickets]'] = 1;
        $form['commande_init[halfDay]'] = 0;
        $crawler = $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());

        $form['commande_init[date]'] = '16/10/2017';
        $crawler = $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();
        $this->assertEquals('Louvre\BookingBundle\Controller\BookingController::informationsAction', $client->getRequest()->attributes->get('_controller'));
    }

    public function testInformationsAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/informations/numerodecom1');

        $form = $crawler->selectButton('commande_info[Suivant]')->form();
        $form['commande_info[tickets][0][name]'] = 'GILLES';
        $form['commande_info[tickets][0][firstName]'] = 'Christophe';
        $form['commande_info[tickets][0][country]'] = 'FR';
        $form['commande_info[tickets][0][birthDate]'] = '16/10/2018';
        $form['commande_info[tickets][0][reducedPrice]'] = 1;
        $crawler = $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());
        
        $form['commande_info[tickets][0][birthDate]'] = '16/10/1982';
        $crawler = $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        
        $client->followRedirect();
        $this->assertEquals('Louvre\BookingBundle\Controller\BookingController::recapitulativeAction', $client->getRequest()->attributes->get('_controller'));
        
    }
    
    public function testRecapitulativeAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/recapitulative/numerodecom1');

        $form = $crawler->selectButton('commande_recap[Suivant]')->form();
        $form['commande_recap[email]'] = 'domaine@';
        $crawler = $client->submit($form);
        $this->assertGreaterThan(0, $crawler->filter('span.help-block')->count());

        $form['commande_recap[email]'] = 'domaine@fai.fr';
        $crawler = $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();
        $this->assertEquals('Louvre\BookingBundle\Controller\BookingController::validateAction', $client->getRequest()->attributes->get('_controller'));
    }
    
    public function testVerified()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/verified/tokendelacommande2');
    }

    public function testToPay()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/topay/numerodecom2');

        $form = $crawler->selectButton('stripe_paiement[Payer]')->form();
    }
}