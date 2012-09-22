<?php

namespace Bukzine\MagentoBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Author
{
	/**
	 * @var integer $id
	 *
	 */
	private $id;

	/**
	 * @var string $email
	 *
	 * @Assert\Email()
	 */
	private $email;

	/**
	 * @var string $firstname
	 *
	 * @Assert\MaxLength(255)
	 */
	private $firstname;

	/**
	 * @var string $lastname
	 *
	 * @Assert\MaxLength(255)
	 */
	private $lastname;

	/**
	 * @var string $password
	 *
	 * @Assert\MaxLength(255)
	 */
	private $password;

	/**
	 * @var string $twitterAccount
	 *
	 * @Assert\MaxLength(255)
	 */
	private $twitterAccount;


	/**
	 * [getId() description here]
	 *
	 * @return integer [description]
	 */
	public function getId()
	{
	    return $this->id;
	}
	
	/**
	 * [setId() description here]
	 *
	 * @param integer $id [description]
	 */
	public function setId($newId)
	{
	    $this->id = $newId;
	}


	/**
	 * [getEmail() description here]
	 *
	 * @return string [description]
	 */
	public function getEmail()
	{
	    return $this->email;
	}
	
	/**
	 * [setEmail() description here]
	 *
	 * @param string $email [description]
	 */
	public function setEmail($newEmail)
	{
	    $this->email = $newEmail;
	}

	/**
	 * [getFirstname() description here]
	 *
	 * @return string [description]
	 */
	public function getFirstname()
	{
	    return $this->firstname;
	}
	
	/**
	 * [setFirstname() description here]
	 *
	 * @param  string $firstname [description]
	 */
	public function setFirstname($newFirstname)
	{
	    $this->firstname = $newFirstname;
	}


	/**
	 * [getLastname() description here]
	 *
	 * @return [type] [description]
	 */
	public function getLastname()
	{
	    return $this->lastname;
	}
	
	/**
	 * [setLastname() description here]
	 *
	 * @param [type] $lastname [description]
	 */
	public function setLastname($newLastname)
	{
	    $this->lastname = $newLastname;
	}

	/**
	 * [getPassword() description here]
	 *
	 * @return string [description]
	 */
	public function getPassword()
	{
	    return $this->password;
	}
	
	/**
	 * [setPassword() description here]
	 *
	 * @param  string $password [description]
	 */
	public function setPassword($newPassword)
	{
	    $this->password = $newPassword;
	}

	/**
	 * [getTwitterAccount() description here]
	 *
	 * @return string Author Twitter account
	 */
	public function getTwitterAccount()
	{
	    return $this->twitterAccount;
	}
	
	/**
	 * [setTwitterAccount() description here]
	 *
	 * @param  string $twitterAccount Author Twitter account
	 */
	public function setTwitterAccount($newTwitterAccount)
	{
	    $this->twitterAccount = $newTwitterAccount;
	}
}