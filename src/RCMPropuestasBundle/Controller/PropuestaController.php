<?php

namespace RCMPropuestasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RCMPropuestasBundle\Form\PropuestaType;
use RCMPropuestasBundle\Entity\Propuesta;

class PropuestaController extends Controller
{
    private function crearFormAgregar(Propuesta $entidad)
    {
      return $this->createForm(new PropuestaType(), $entidad, array('action' => $this->generateUrl('rcm_propuesta_crear'), 'method' => 'PUT'));
    }

    public function agregarAction()
    {
      $propuesta = new Propuesta();
      $form = $this->crearFormAgregar($propuesta);
      return $this->render('RCMPropuestasBundle:Propuesta:agregar.html.twig', array('form' => $form->createView()));
    }

    public function crearAction(Request $request)
    {
      $propuesta = new Propuesta();
      $form = $this->crearFormAgregar($propuesta);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $usuario = $this->getUser();
        $propuesta->setUsuario($usuario);
        $manejador = $this->getDoctrine()->getManager();
        $manejador->persist($propuesta);
        $manejador->flush();
        $this->addFlash('succes','Propuesta almacenada correctamente');
        return $this->redirectToRoute('rcm_usuario_homepage');
      }
      return $this->render('RCMPropuestasBundle:Propuesta:agregar.html.twig',  array('form' => $form->createView()));
    }

    public function listaAction()
    {
      $manejador = $this->getDoctrine()->getManager();
      //solo las que sean publicas
      //tratar de filtrar por usuario
      $propuestas = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->findAll();
      $deleteForm = $this->createFormBuilder()
                         ->setAction($this->generateUrl('rcm_propuesta_eliminar', array('id' => 'ID_PROP')))
                         ->setMethod('DELETE')
                         ->getForm();
      return $this->render('RCMPropuestasBundle:Propuesta:lista.html.twig', array('propuestas' => $propuestas, 'delete_form_ajax' => $deleteForm->createView()));
    }

    public function verAction($id)
    {
      $manejador = $this->getDoctrine()->getManager();
      $propuesta = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->find($id);
      return $this->render('RCMPropuestasBundle:Propuesta:ver.html.twig', array('propuesta' => $propuesta));
    }

    private function crearFormEditar(Propuesta $entidad)
    {
      return $this->createForm(new PropuestaType(), $entidad, array('action' => $this->generateUrl('rcm_propuesta_actualizar', array('id' => $entidad->getId())), 'method' => 'POST'));
    }

    public function editarAction($id)
    {
      $manejador = $this->getDoctrine()->getManager();
      $propuesta = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->find($id);
      $form = $this->crearFormEditar($propuesta);
      return $this->render('RCMPropuestasBundle:Propuesta:editar.html.twig', array('form' => $form->createView()));
    }

    public function actualizarAction($id, Request $request)
    {
      $manejador = $this->getDoctrine()->getManager();
      $propuesta = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->find($id);
      if (!$propuesta) {
        throw $this->createNotFoundException('Propuesta no encontrada');
      }
      $form = $this->crearFormEditar($propuesta);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $manejador->flush();
        $this->addFlash('success','Datos actualizados');
        return $this->redirectToRoute('rcm_propuesta_editar', array('id' => $propuesta->getId()));
      }
      return $this->render('RCMPropuestasBundle:Propuesta:editar.html.twig', array('form' => $form->createView()));
    }

    private function crearFormEliminar(Propuesta $entidad)
    {
      return $this->createFormBuilder()
                  ->setAction($this->generateUrl('rcm_propuesta_eliminar', array('id' => $entidad->getId())))
                  ->setMethod('DELETE')
                  ->getForm();
    }

    public function eliminarAction($id, Request $request)
    {
      $manejador = $this->getDoctrine()->getManager();
      $propuesta = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->find($id);
      if (!$propuesta) {
        throw $this->createNotFoudExceptio('Propuesta no encontrada');
      }
      $form = $this->crearFormEliminar($propuesta);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        if ($request->isXMLHttpRequest()) {
          $this->eliminarPropuesta($propuesta, $manejador);
          return new Response(json_encode(array('message' => 'Propuesta eliminada exitosamente')), 200, array('Content-Type' => 'application/json'));
        }
      }
    }

    private function eliminarPropuesta(Propuesta $entidad, $manejador)
    {
      $manejador->remove($entidad);
      $manejador->flush();
    }
}
