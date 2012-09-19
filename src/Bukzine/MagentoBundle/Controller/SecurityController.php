<?php

namespace Bukzine\MagentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction()
    {
    	$peticion = $this->getRequest();

		$sesion = $peticion->getSession();

		$error = $peticion->attributes->get(
			SecurityContext::AUTHENTICATION_ERROR,
			$sesion->get(SecurityContext::AUTHENTICATION_ERROR)
		);

        return $this->render('BukzineMagentoBundle:Security:login.html.twig', array(
        	'error' => $error
        	));
    }

    public function loginFormAction()
    {
    	$peticion = $this->getRequest();

		$sesion = $peticion->getSession();

		$error = $peticion->attributes->get(
			SecurityContext::AUTHENTICATION_ERROR,
			$sesion->get(SecurityContext::AUTHENTICATION_ERROR)
		);

        return $this->render('BukzineMagentoBundle:Security:loginForm.html.twig', array(
        	'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
        	'error' => $error
        ));
    }
}

?>