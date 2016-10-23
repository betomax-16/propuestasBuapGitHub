<?php

namespace RCMPropuestasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RCMPropuestasBundle\Entity\Usuario;
use RCMPropuestasBundle\Form\UsuarioType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;

class UsuarioController extends Controller
{
  public function homeAction()
  {
    return $this->render('RCMPropuestasBundle:Usuario:home.html.twig');
  }

  private function crearFormAgregar(Usuario $entidad)
  {
    return $this->createForm(new UsuarioType(), $entidad, array('action' => $this->generateUrl('rcm_usuario_crear'), 'method' => 'PUT'));
  }

  public function agregarAction()
  {
    $usuario = new Usuario();
    $form = $this->crearFormAgregar($usuario);
    return $this->render('RCMPropuestasBundle:Admin:agregar.html.twig', array('form' => $form->createView()));
  }

  public function crearAction(Request $request)
  {
    $usuario = new Usuario();
    $form = $this->crearFormAgregar($usuario);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $password = $form->get('password')->getData();
      $passwordConstraint = new Assert\NotBlank();
      $errorList = $this->get('validator')->validate($password, $passwordConstraint);
      if (count($errorList) == 0) {
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($usuario, $password);
        $usuario->setPassword($encoded);
        $manejador = $this->getDoctrine()->getManager();
        $manejador->persist($usuario);
        $manejador->flush();
        $this->addFlash('success','Usuario registrado exitosamente');
        return $this->redirectToRoute('rcm_usuario_lista');
      }
      else {
        $errorMessage = new FormError($errorList[0]->getMessage());
        $form->get('password')->addError($errorMessage);
      }
    }
    return $this->render('RCMPropuestasBundle:Admin:agregar.html.twig', array('form' => $form->createView()));
  }

  public function listaAction()
  {
    $manejador = $this->getDoctrine()->getManager();
    $usuarios = $manejador->getRepository('RCMPropuestasBundle:Usuario')->findAll();
    $formDelete = $this->createFormBuilder()
                ->setAction($this->generateUrl('rcm_usuario_eliminar', array('id' => 'ID_USER')))
                ->setMethod('DELETE')
                ->getForm();
    return $this->render('RCMPropuestasBundle:Admin:listaUsuarios.html.twig', array('usuarios' => $usuarios, 'delete_form_ajax' => $formDelete->createView()));
  }

  public function verAction($id)
  {
    $manejador = $this->getDoctrine()->getManager();
    $usuario = $manejador->getRepository('RCMPropuestasBundle:Usuario')->find($id);
    if (!$usuario) {
      throw $this->createNotFoundException('Usuario no encontrado');
    }
    $formDelete = $this->crearFormEliminar($usuario);
    return $this->render('RCMPropuestasBundle:Admin:usuario.html.twig', array('usuario' => $usuario, 'delete_form' => $formDelete->createView()));
  }

  public function editarAction($id)
  {
    $manejador = $this->getDoctrine()->getManager();
    $usuario = $manejador->getRepository('RCMPropuestasBundle:Usuario')->find($id);
    if (!$usuario) {
      throw $this->createNotFoundException('Usuario no encontrado');
    }
    $form = $this->crearFormEditar($usuario);
    return $this->render('RCMPropuestasBundle:Usuario:editar.html.twig', array('form' => $form->createView(), 'foto' => $usuario->getWebPath()));
  }

  private function crearFormEditar(Usuario $entidad)
  {
    return $this->createForm(new UsuarioType(),
                            $entidad,
                            array('action' => $this->generateUrl('rcm_usuario_actualizar', array('id' => $entidad->getId())),
                                  'method' => 'PUT'));
  }

  public function actualizarAction($id, Request $request)
  {
    $manejador = $this->getDoctrine()->getManager();
    $usuario = $manejador->getRepository('RCMPropuestasBundle:Usuario')->find($id);
    if (!$usuario) {
      throw $this->createNotFoudExceptio('Usuario no encontrado');
    }
    $form = $this->crearFormEditar($usuario);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $password = $form->get('password')->getData();
      if (!empty($password)) {
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($usuario, $password);
        $usuario->setPassword($encoded);
      }
      else {
        $recoverPass = $this->recoverPass($id);
        $usuario->setPassword($recoverPass[0]['password']);
      }
      $manejador->flush();
      $this->addFlash('success','Datos modificados exitosamente');
      return $this->redirectToRoute('rcm_usuario_editar', array('id' => $usuario->getId()));
    }
    return $this->render('RCMPropuestasBundle:Usuario:editar.html.twig', array('form' => $form->createView(), 'foto' => $usuario->getWebPath()));
  }

  private function recoverPass($id)
  {
    $manejador = $this->getDoctrine()->getManager();
    $query = $manejador->createQuery('SELECT u.password FROM RCMPropuestasBundle:Usuario u WHERE u.id = :id')->setParameter('id', $id);
    return $query->getResult();
  }

  public function crearFormEliminar(Usuario $entidad)
  {
    return $this->createFormBuilder()
                ->setAction($this->generateUrl('rcm_usuario_eliminar', array('id' => $entidad->getId())))
                ->setMethod('DELETE')
                ->getForm();
  }

  public function eliminarAction($id, Request $request)
  {
    $manejador = $this->getDoctrine()->getManager();
    $usuario = $manejador->getRepository('RCMPropuestasBundle:Usuario')->find($id);
    if (!$usuario) {
      throw $this->createNotFoundException('Usuario no encontrado');
    }
    $form = $this->crearFormEliminar($usuario);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if ($request->isXMLHttpRequest()) {
        $this->eliminarUsuario($usuario, $manejador);
        return new Response(json_encode(array('message' => 'Usuario eliminado exitosamente.')), 200, array('Content-Type' => 'application/json'));
      }
      $this->eliminarUsuario($usuario, $manejador);
      $this->addFlash('success', 'Usuario eliminado exitosamente.');
      return $this->redirectToRoute('rcm_usuario_lista');
    }
  }

  private function eliminarUsuario(Usuario $entidad, $manejador)
  {
    $manejador->remove($entidad);
    $manejador->flush();
  }
}
