<?php

namespace RCMPropuestasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends Controller
{
  public function homeAction()
  {
      return $this->render('RCMPropuestasBundle:Usuario:home.html.twig');
  }
}
