<?php

namespace Louvre\BookingBundle\Controller;

use Louvre\BookingBundle\Entity\Commande;
use Louvre\BookingBundle\Form\Type\CommandeInitType;
use Louvre\BookingBundle\Form\Type\CommandeInfoType;
use Louvre\BookingBundle\Form\Type\CommandeRecapType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Louvre\BookingBundle\Form\Type\StripePaiementType;

class BookingController extends Controller
{
    /**
     * @Route("/", name="louvre_booking_index", defaults={"codeCommande" = null})
     * @Route("/{codeCommande}", name="louvre_booking_index_commande")
     */
    public function indexAction(Request $request, $codeCommande)
    {
        $em = $this->getDoctrine()->getManager();
        if ($codeCommande) {
            $commande = $em->getRepository('LouvreBookingBundle:Commande')->findOneByCodeCommande($codeCommande);
            if (!$commande) {
                return $this->redirectToRoute('louvre_booking_index');
            } else {
                if ($commande->getStatus() == Commande::STATUS_PAID) {
                    return $this->redirectToRoute('louvre_booking_paid', array(
                        'codeCommande' => $commande->getCodeCommande()
                    ));
                }
            }
        } else {
            $commande = new Commande();
            $em->persist($commande);
        }

        $form = $this->createForm(CommandeInitType::class, $commande, array('locale' => $request->getLocale()))
            ->add($this->get('translator')->trans('form.button.next'), SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn-default btn btn-primary pull-right')
            ));
        
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($commande->getCodeCommande() == null) {
                $commande->setCodeCommande($this->get('louvre_booking.codecommande')->generateCodeCommande());
            }
            $commande->setStatus(Commande::STATUS_START);
            $em->flush();
            return $this->redirectToRoute('louvre_booking_informations', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        return $this->render('LouvreBookingBundle:Booking:index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/informations/{codeCommande}", name="louvre_booking_informations")
     */
    public function informationsAction(Request $request, Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        if ($commande->getStatus() == Commande::STATUS_PAID) {
            return $this->redirectToRoute('louvre_booking_paid', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        $this->get('louvre_booking.ticketsgenerator')->ticketsGenerator($commande);
        $form = $this->createForm(CommandeInfoType::class, $commande)
            ->add($this->get('translator')->trans('form.button.next'), SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn-default btn btn-primary pull-right'
                )
            ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('louvre_booking.pricegenerator')->priceTicketsGenerator($commande);
            $commande->setStatus(Commande::STATUS_INPROGRESS);
            $em->flush();
            return $this->redirectToRoute('louvre_booking_recapitulative', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        return $this->render('LouvreBookingBundle:Booking:informations.html.twig', array(
            'form' => $form->createView(),
            'commande' => $commande
        ));
    }

    /**
     * @Route("/recapitulative/{codeCommande}", name="louvre_booking_recapitulative")
     */
    public function recapitulativeAction(Request $request, Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        if ($commande->getStatus() == Commande::STATUS_PAID) {
            return $this->redirectToRoute('louvre_booking_paid', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        $form = $this->createForm(CommandeRecapType::class, $commande)
            ->add($this->get('translator')->trans('form.button.next'), SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn-default btn btn-primary pull-right'
                )
            ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('louvre_booking.validatebooking')->validateBooking($commande);
            $em->flush();
            return $this->redirectToRoute('louvre_booking_validate', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        return $this->render('LouvreBookingBundle:Booking:recapitulative.html.twig', array(
            'form' => $form->createView(),
            'commande' => $commande,
            'tickets' => $commande->getTickets()
        ));
    }

    /**
     * @Route("/validate/{codeCommande}", name="louvre_booking_validate")
     */
    public function validateAction(Commande $commande)
    {
        if ($commande->getStatus() == Commande::STATUS_PAID) {
            return $this->redirectToRoute('louvre_booking_paid', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        return $this->render('LouvreBookingBundle:Booking:validate.html.twig', array(
            'commande' => $commande
        ));
    }

    /**
     * @Route("/verified/{token}", name="louvre_booking_verified")
     */
    public function verifiedAction(Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $commande->setStatus(Commande::STATUS_VERIFIED);
        $em->flush();
        return $this->render('LouvreBookingBundle:Booking:verified.html.twig', array(
            'commande' => $commande,
            'tickets' => $commande->getTickets()
        ));
    }

    /**
     * @Route("/topay/{codeCommande}", name="louvre_booking_topay")
     */
    public function topayAction(Commande $commande)
    {
        if ($commande->getStatus() == Commande::STATUS_PAID) {
            return $this->redirectToRoute('louvre_booking_paid', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        if ($commande->getStatus() != Commande::STATUS_VERIFIED) {
            return $this->redirectToRoute('louvre_booking_index_commande', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        $form = $this->createForm(StripePaiementType::class)
            ->add($this->get('translator')->trans('form.button.pay'), SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn-default btn btn-primary pull-right'
                )
            ));
        if ($this->get('louvre_booking.stripepaiement')->stripePaiement($commande)) {
            return $this->redirectToRoute('louvre_booking_paid', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        return $this->render('LouvreBookingBundle:Booking:topay.html.twig', array(
            'form' => $form->createView(),
            'commande' => $commande
        ));
    }

    /**
     * @Route("/paid/{codeCommande}", name="louvre_booking_paid")
     */
    public function paidAction(Commande $commande)
    {
        if ($commande->getStatus() != Commande::STATUS_PAID) {
            return $this->redirectToRoute('louvre_booking_index_commande', array(
                'codeCommande' => $commande->getCodeCommande()
            ));
        }
        return $this->render('LouvreBookingBundle:Booking:paid.html.twig', array(
            'commande' => $commande
        ));
    }
}