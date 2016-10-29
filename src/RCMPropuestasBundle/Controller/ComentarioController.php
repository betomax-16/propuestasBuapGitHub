<?php

namespace RCMPropuestasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RCMPropuestasBundle\Entity\Comentario;
use RCMPropuestasBundle\Form\ComentarioType;

class ComentarioController extends Controller
{
  private function crearFormAgregar($id, Comentario $entidad)
  {
    return $this->createForm(new ComentarioType(), $entidad, array('action' => $this->generateUrl('rcm_comentario_crear', array('id' => $id)), 'method' => 'PUT'));
  }

  public function agregarAction($id)
  {
    $manejador = $this->getDoctrine()->getManager();
    $propuesta = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->find($id);
    $comentario = new Comentario();
    $form = $this->crearFormAgregar($propuesta->getId(), $comentario);
    return $this->render('RCMPropuestasBundle:Comentario:agregar.html.twig', array('form' => $form->createView()));
  }

  public function crearAction($id, Request $request)
  {
    $comentario = new Comentario();
    $form = $this->crearFormAgregar($id, $comentario);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $manejador = $this->getDoctrine()->getManager();
      $propuesta = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->find($id);
      $comentario->setUsuario($this->getUser());
      $comentario->setPropuesta($propuesta);
      $manejador->persist($comentario);
      $manejador->flush();
      return $this->redirectToRoute('rcm_propuesta_ver', array('id' => $id));
    }
    return $this->render('RCMPropuestasBundle:Propuesta:ver.html.twig', array('propuesta' => $comentario->getPropuesta(), 'formAgergarComentario' => $form->createView()));
  }

  private function crearFormEliminar(Comentario $entidad)
  {
    return $this->createFormBuilder()
                ->setAction($this->generateUrl('rcm_comentario_eliminar', array('id' => $entidad->getId())))
                ->setMethod('DELETE')
                ->getForm();
  }

  public function eliminarAction($id, Request $request)
  {
    $manejador = $this->getDoctrine()->getManager();
    $comentario = $manejador->getRepository('RCMPropuestasBundle:Comentario')->find($id);
    if (!$comentario) {
      throw $this->createNotFoudExceptio('Propuesta no encontrada');
    }
    $form = $this->crearFormEliminar($comentario);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if ($request->isXMLHttpRequest()) {
        $this->eliminarComentario($comentario, $manejador);
        return new Response(json_encode(array('message' => 'Comentario eliminado exitosamente')), 200, array('Content-Type' => 'application/json'));
      }
    }
  }

  private function eliminarComentario(Comentario $entidad, $manejador)
  {
    $manejador->remove($entidad);
    $manejador->flush();
  }
}
