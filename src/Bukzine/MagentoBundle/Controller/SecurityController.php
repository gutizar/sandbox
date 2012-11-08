<?php

namespace Bukzine\MagentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
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
            // Save the password for future reference.
            $clearPass = $user->getPassword();

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
                    \Mage::getSingleton('customer/session')->login($user->getEmail(), $clearPass);

                    return $this->redirect($this->generateUrl('magento_home'));
                }
            }
        }

        return $this->render('BukzineMagentoBundle:Security:register.html.twig', array(
            'form' => $form->createView(),
            'error' => $error
        ));
    }

    public function profileAction()
    {
        $request = $this->getRequest();
        $userApi = $this->get('magento_api_user');

        $loggedInUser = $this->get('security.context')->getToken()->getUser();
        $response = $userApi->getUserById($loggedInUser->getId());

        $error = null;

        if ($response->getSuccess())
        {
            $user = $response->getResponse();
        } 
        else
        {
            // TODO: Do something if the user is not found.
        }

        $form = $this->createForm(new AuthorType(), $user);

        if ($request->getMethod() == 'POST')
        {
            $originalPass = $form->getData()->getPassword();
            $form->bindRequest($request);

            if ($form->isValid())
            {
                if (null == $user->getPassword())
                {
                    $user->setPassword($originalPass);
                }
                else 
                {
                    $encodedPassword = $userApi->encryptPassword(
                        $user->getPassword());

                    $user->setPassword($encodedPassword);
                }

                $return = $userApi->updateUser($user);

                if (!$return->getSuccess())
                {
                    $this->get('session')->setFlash('error',
                        'There was a problem completing your request');

                    $error = 'There was a problem completing your request (' . $return->getError() . ')';
                }
                else
                {
                    $this->get('session')->setFlash('info',
                        'Your profile has been updated');

                    $token = new UsernamePasswordToken(
                        $loggedInUser,
                        $user->getPassword(),
                        'magento',
                        $loggedInUser->getRoles()
                        );

                    $this->container->get('security.context')->setToken($token);

                    return $this->redirect($this->generateUrl('magento_home'));
                }
            }
        }

        return $this->render('BukzineMagentoBundle:Security:profile.html.twig', array(
            'form'  => $form->createView(),
            'error' => $error,
            ));
    }
}

?>