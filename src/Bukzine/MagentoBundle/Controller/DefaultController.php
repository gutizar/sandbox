<?php

namespace Bukzine\MagentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('BukzineMagentoBundle:Default:index.html.twig', array('name' => $name));
    }

    public function homeAction()
    {
    	$userApi = $this->get('magento_api_user');

    	$details = $userApi->getUser('2');

        return $this->render('BukzineMagentoBundle:Default:home.html.twig', array(
        	'details' => $details,
        ));
    }
}
