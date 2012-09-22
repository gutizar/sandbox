<?php

namespace Bukzine\MagentoBundle\Api;

class ApiResponse
{
	/**
	 * @var object api call response object
	 */
	private $response;

	/**
	 * @var boolean api call successful
	 */
	private $success;

	/**
	 * @var string error message, if any
	 */
	private $error;

	/**
	 * [getError() description here]
	 *
	 * @return [type] [description]
	 */
	public function getError()
	{
	    return $this->error;
	}
	
	/**
	 * [setError() description here]
	 *
	 * @param [type] $error [description]
	 */
	public function setError($newError)
	{
	    $this->error = $newError;
	}

	/**
	 * [getSuccess() description here]
	 *
	 * @return [type] [description]
	 */
	public function getSuccess()
	{
	    return $this->success;
	}
	
	/**
	 * [setSuccess() description here]
	 *
	 * @param [type] $success [description]
	 */
	public function setSuccess($newSuccess)
	{
	    $this->success = $newSuccess;
	}

	/**
	 * [getResponse() description here]
	 *
	 * @return [type] [description]
	 */
	public function getResponse()
	{
	    return $this->response;
	}
	
	/**
	 * [setResponse() description here]
	 *
	 * @param [type] $response [description]
	 */
	public function setResponse($newResponse)
	{
	    $this->response = $newResponse;
	}	
}