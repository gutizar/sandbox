<?php

namespace Bukzine\MagentoBundle\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginListener
{
	private $context, $router, $user;

	public function __construct(SecurityContext $context, Router $router)
	{
		$this->context = $context;
		$this->router = $router;
	}

	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
	{
		$token = $event->getAuthenticationToken();
		$this->user = $token->getUser();
	}

	public function onKernelResponse(FilterResponseEvent $event)
	{
		if (null != $this->user)
		{
			if ($this->context->isGranted('ROLE_MAGENTO_5'))
		{
			$portada = $this->router->generate('magento_home');
		} 
		else 
		{
			$portada = 'http://shop.sandbox.local/index.php/customer/account/';
		}

			$event->setResponse(new RedirectResponse($portada));
			$event->stopPropagation();	
		}
	}
}

?>