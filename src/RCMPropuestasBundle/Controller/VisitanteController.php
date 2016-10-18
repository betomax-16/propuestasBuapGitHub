<?php

namespace RCMPropuestasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RCMPropuestasBundle\Form\ContactoType;

class VisitanteController extends Controller
{
  public function infoAction()
  {
    return $this->render('RCMPropuestasBundle:Visitante:info.html.twig');
  }

  public function contactoAction()
  {
    $form = $this->createForm(new ContactoType(), null, array('action' => $this->generateUrl('rcm_visitante_email'), 'method' => 'POST'));
    return $this->render('RCMPropuestasBundle:Visitante:contacto.html.twig', array('form' => $form->createView()));
  }

  public function enviarEmailAction(Request $request)
  {
    $form = $this->createForm(new ContactoType(), null, array('action' => $this->generateUrl('rcm_visitante_email'), 'method' => 'POST'));
    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject($form->get('motivo')->getData())
            ->setFrom('betomax1636@gmail.com')
            ->setTo($form->get('email')->getData())
            ->setBody(
                $this->renderView(
                    'RCMPropuestasBundle:Visitante:email/email.txt.twig',
                    array(
                        'ip' => $request->getClientIp(),
                        'nombre' => $form->get('nombre')->getData(),
                        'email' => $form->get('email')->getData(),
                        'mensaje' => $form->get('mensaje')->getData()
                    )
                )
            );

        $mailer->send($message);
        $this->addFlash('success', 'Tu email ha sido enviado. Gracias');
        return $this->redirect($this->generateUrl('rcm_visitante_contacto'));
      }
    }
    return $this->render('RCMPropuestasBundle:Visitante:contacto.html.twig', array('form' => $form->createView()));
  }
}
