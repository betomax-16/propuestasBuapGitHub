<?php

namespace RCMPropuestasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RCMPropuestasBundle\Form\PropuestaType;
use RCMPropuestasBundle\Entity\Propuesta;
use RCMPropuestasBundle\Form\ComentarioType;
use RCMPropuestasBundle\Entity\Comentario;

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
        $this->addFlash('success','Propuesta almacenada correctamente');
        return $this->redirectToRoute('rcm_usuario_homepage');
      }
      return $this->render('RCMPropuestasBundle:Propuesta:agregar.html.twig',  array('form' => $form->createView()));
    }

    public function listaAction()
    {
      $manejador = $this->getDoctrine()->getManager();
      //$propuestas = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->findAll();
      $query = $manejador->createQuery('SELECT p FROM RCMPropuestasBundle:Propuesta p ');
      $paginator = $this->get('knp_paginator');
      $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
      $deleteForm = $this->createFormBuilder()
                         ->setAction($this->generateUrl('rcm_propuesta_eliminar', array('id' => 'ID_PROP')))
                         ->setMethod('DELETE')
                         ->getForm();
      return $this->render('RCMPropuestasBundle:Propuesta:lista.html.twig', array('pagination' => $pagination, 'delete_form_ajax' => $deleteForm->createView()));
    }

    private function crearFormAgregarComentario(Comentario $entidad, $id)
    {
      return $this->createForm(new ComentarioType(), $entidad, array('action' => $this->generateUrl('rcm_comentario_crear', array('id' => $id)), 'method' => 'PUT'));
    }

    public function verAction($id)
    {
      $manejador = $this->getDoctrine()->getManager();
      $propuesta = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->find($id);
      $formComentario = $this->crearFormAgregarComentario(new Comentario(), $propuesta->getId());
      $formDelete = $this->createFormBuilder()
                         ->setAction($this->generateUrl('rcm_comentario_eliminar', array('id' => 'ID_COM')))
                         ->setMethod('DELETE')
                         ->getForm();
      return $this->render('RCMPropuestasBundle:Propuesta:ver.html.twig', array('propuesta' => $propuesta, 'formAgergarComentario' => $formComentario->createView(), 'delete_form_ajax' => $formDelete->createView()));
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

    public function descargaAction($id, Request $request)
    {
      $manejador = $this->getDoctrine()->getManager();
      $propuesta = $manejador->getRepository('RCMPropuestasBundle:Propuesta')->find($id);
      if (!$propuesta) {
        throw $this->createNotFoundException('Propusta no encontrada');
      }
      if (!file_exists($propuesta->getWebPath())) {
        throw $this->createNotFoundException('Archivo propusta no encontrada');
      }

      $content = file_get_contents($propuesta->getWebPath());
      $response = new Response();
      $response->headers->set('Content-Type', 'application/pdf');
      $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $propuesta->getNombreOriginal()));
      $response->headers->set('Content-Length', filesize($propuesta->getWebPath()));
      $response->setContent($content);
      return $response;
    }
}
