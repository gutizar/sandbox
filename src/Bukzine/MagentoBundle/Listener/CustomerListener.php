<?php

namespace Bukzine\MagentoBundle\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Monolog\Logger;

class CustomerListener
{
	private $logger;

	public function __construct(Logger $logger)
	{
		$this->logger = $logger;
	}

	public function synchronize()
	{
		$logger->info('You are in the symfony listener');
	}
}

?>