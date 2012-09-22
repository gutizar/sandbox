<?php

namespace Bukzine\MagentoBundle\Api;

use Bukzine\MagentoBundle\Api\ApiResponse;

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

	public function createUser($user)
	{
		$session = $this->client->login('danielg', 'admin123');

		$userData = array(
			'email' => $user->getEmail(),
			'firstname' => $user->getFirstname(),
			'lastname' => $user->getLastname(),
			'password_hash' => $user->getPassword(),
			'twitter_account' => $user->getTwitterAccount(),
			'website_id' => 1,
			'store_id' => 1,
			'group_id' => 5,
		);

		$response = new ApiResponse();

		try 
		{
			$userId = $this->client->call($session, 'customer.create', array($userData));
			$response->setResponse($userId);
			$response->setSuccess(true);
		}
		catch (\Exception $ex)
		{
			$response->setSuccess(false);
			$response->setError($ex->getMessage());
		}
		
		return $response;
	}

	public function encryptPassword($password)
	{
		$core = \Mage::getModel('core/encryption');
		$helper = \Mage::helper('core');
		$core->setHelper($helper);
		return $core->getHash($password, 2);
	}
}