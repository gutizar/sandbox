<?php

namespace Bukzine\MagentoBundle\Api;

class UserApi
{
	protected $client;

	public function __construct($endpoint)
	{
		$this->client = new \SoapClient($endpoint);
	}

	public function getUser($id)
	{
		$session = $this->client->login('danielg', 'admin123');
		$result = $this->client->call($session, 'customer.info', $id);

		return $result;
	}
}