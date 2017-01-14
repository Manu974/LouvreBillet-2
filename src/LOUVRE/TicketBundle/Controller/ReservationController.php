<?php

// src/LOUVRE/TicketBundle/Controller/ReservationController.php

namespace LOUVRE\TicketBundle\Controller;

use LOUVRE\TicketBundle\Entity\Billet;
use LOUVRE\TicketBundle\Entity\Visiteur;
use LOUVRE\TicketBundle\Form\BilletType;
use LOUVRE\TicketBundle\Form\VisiteurType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;




class ReservationController extends Controller
{
   
    public function reservationAction(Request $request)
    {

        $billet= new Billet();
        $billet->setDatedevisite(new \Datetime('now', new \DateTimeZone('Europe/Paris')));//prérempli le champs date de visite avec la date d'aujourd'hui
        $billet->setCodereservation($this->container->get('louvre_ticket.codereservation')->codeReservation());
        $form = $this->get('form.factory')->create(BilletType::class, $billet);
        

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($billet);
            $em->flush();

            return $this->redirectToRoute('louvre_ticket_commandepage', [
                'code'=>$billet->getCodereservation(),
            ]);
        }

        return $this->render('LOUVRETicketBundle:Ticket:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function deleteAction($code,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $billet=  $em->getRepository('LOUVRETicketBundle:Billet')->findOneBy(['codereservation'=>$code]);
        
        if (is_null($billet)) {
            throw new NotFoundHttpException("La réservation d'id {$code} n'existe pas.");
        }

        $em->remove($billet);
        $em->flush();

        return $this->redirectToRoute('louvre_ticket_reservationpage');
    }
}
