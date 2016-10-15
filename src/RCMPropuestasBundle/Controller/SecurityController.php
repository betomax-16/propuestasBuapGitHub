<?php

namespace RCMPropuestasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
  public function loginAction()
  {
    $authenticationUtils = $this->get('security.authentication_utils');
    $error = $authenticationUtils->getLastAuthenticationError();
    $lasUserName = $authenticationUtils->getLastUsername();
    return $this->render('RCMPropuestasBundle:Security:login.html.twig', array('last_username' => $lasUserName, 'error' => $error));
  }

  public function loginCheckAction()
  {
    # code...
  }
}
