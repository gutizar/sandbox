<?php

namespace Bukzine\MagentoBundle\Api;

use Bukzine\MagentoBundle\Api\ApiResponse;
use Bukzine\MagentoBundle\Entity\Author;

class UserApi
{
	protected $client;

	public function __construct($endpoint)
	{
		$this->client = new \SoapClient($endpoint);
	}

	public function getUserById($id)
	{
		$session = $this->client->login('danielg', 'admin123');

		$response = new ApiResponse();

		try
		{
			$userInfo = $this->client->call($session, 'customer.info', $id);
			$response->setResponse($this->mapUser2Symfony($userInfo));
			$response->setSuccess(true);
		}
		catch (\Exception $ex)
		{
			$response->setSuccess(false);
			$response->setError($ex->getMessage());
		}
		

		return $response;
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

	public function updateUser($user)
	{
		$session = $this->client->login('danielg', 'admin123');

		$userData = $this->mapUser2Magento($user);

		$response = new ApiResponse();

		try
		{
			$response->setSuccess(
				$this->client->call($session, 'customer.update', array(
					'customerId' => $user->getId(),
					'customerData' => $userData
			)));
		}
		catch (\Exception $ex)
		{
			$response->setResponse(false);
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

	private function mapUser2Symfony($userInfo)
	{
		// TODO: Use JMSSerializationBundle to avoid this mappings
		$author = new Author();
		$author->setId($userInfo['customer_id']);
		$author->setFirstname($userInfo['firstname']);
		$author->setLastname($userInfo['lastname']);
		$author->setEmail($userInfo['email']);
		$author->setPassword($userInfo['password_hash']);
		$author->setTwitterAccount($userInfo['twitter_account']);
		
		return $author;
	}

	private function mapUser2Magento($user)
	{
		$userInfo = array();
		$userInfo['firstname'] = $user->getFirstname();
		$userInfo['lastname'] = $user->getLastname();
		$userInfo['email'] = $user->getEmail();
		$userInfo['password_hash'] = $user->getPassword();
		$userInfo['twitter_account'] = $user->getTwitterAccount();

		return $userInfo;
	}
}