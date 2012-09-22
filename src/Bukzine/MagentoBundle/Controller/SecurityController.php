<?php

namespace Bukzine\MagentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Bukzine\MagentoBundle\Entity\Author;
use Bukzine\MagentoBundle\Form\Type\AuthorType;

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

    public function registerAction()
    {
        $request = $this->getRequest();

        $user = new Author();
        $form = $this->createForm(new AuthorType(), $user);

        $error = '';

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $userApi = $this->get('magento_api_user');
                $encodedPassword = $userApi->encryptPassword(
                    $user->getPassword());

                $user->setPassword($encodedPassword);

                $return = $userApi->createUser($user);

                if (!$return->getSuccess())
                {
                    $this->get('session')->setFlash('error',
                        'There was a problem completing your request');

                    $error = 'There was a problem completing your request (' . $return->getError() . ')';
                }
                else
                {
                    $this->get('session')->setFlash('info',
                        'Congratulations, you are not registered in Bukzine');

                    // TODO: create user authentication token

                    return $this->redirect($this->generateUrl('magento_home'));
                }
            }
        }

        return $this->render('BukzineMagentoBundle:Security:register.html.twig', array(
            'form' => $form->createView(),
            'error' => $error
        ));
    }
}

?>