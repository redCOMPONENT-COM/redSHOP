<?php
/**
 * @package     Redshop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Paygate\Quickpay;

use Curl\Curl;

defined('_JEXEC') or die;

/**
 * Class AbstractBase
 * @package Redshop\Paygate\Quickpay
 *
 * @since   0.5.0
 */
abstract class AbstractBase
{
	/**
	 * @var string
	 */
	protected $apiKey;

	/**
	 * @var array
	 */
	protected $errors;

	/**
	 * @var string
	 */
	protected $endpoint = 'https://api.quickpay.net';

	/**
	 * @var string
	 */
	protected $version = 'v10';

	/**
	 * AbstractBase constructor.
	 *
	 * @param   string $apiKey API key
	 */
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	/**
	 * @return Curl
	 * @throws \ErrorException
	 */
	protected function getClient()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new Curl;
		$instance->setBasicAuthentication('', $this->apiKey);
		$instance->setHeader('content-type', 'application/json');
		$instance->setHeader('Accept-Version', $this->version);

		return $instance;
	}

	/**
	 * @param   string $method     Method
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return boolean
	 * @throws \ErrorException
	 */
	public function exec($method = '', $parameters = array(), $request = '')
	{
		$this->errors = array();
		$client       = $this->getClient();

		switch (strtolower($method))
		{
			case 'post':
				$client->post($this->endpoint . '/' . $request, $parameters);
				break;
			case 'put':
				$client->put($this->endpoint . '/' . $request, $parameters);
				break;
			case 'get':
				$client->get($this->endpoint . '/' . $request, $parameters);
				break;
			case 'patch':
				$client->patch($this->endpoint . '/' . $request, $parameters);
				break;
			case 'delete':
				$client->delete($this->endpoint . '/' . $request, $parameters);
				break;
		}

		return $this->verify($client);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function post($parameters = array(), $request = '')
	{
		return $this->exec(__FUNCTION__, $parameters, $request);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function put($parameters = array(), $request = '')
	{
		return $this->exec(__FUNCTION__, $parameters, $request);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function get($parameters = array(), $request = '')
	{
		return $this->exec(__FUNCTION__, $parameters, $request);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function patch($parameters = array(), $request = '')
	{
		return $this->exec(__FUNCTION__, $parameters, $request);
	}

	/**
	 * @param   array  $parameters Parameters
	 * @param   string $request    Request
	 *
	 * @return  boolean|\stdClass|null
	 * @throws  \ErrorException
	 */
	public function delete($parameters = array(), $request = '')
	{
		return $this->exec(__FUNCTION__, $parameters, $request);
	}

	/**
	 * @param   Curl $client Client
	 *
	 * @return  boolean
	 */
	private function verify($client)
	{
		switch ($client->httpStatusCode)
		{
			case 400:
				$this->setError($client->response->errors);

				return false;

			// No Authorized
			case 401:
			case 403:
			case 404:
				$this->setError($client->response->message);

				return false;
		}

		return $client->response;
	}

	/**
	 * @param   mixed $error Error
	 *
	 * @return  $this
	 */
	protected function setError($error)
	{
		$this->errors[] = $error;

		return $this;
	}

	/**
	 * @return boolean
	 */
	protected function isError()
	{
		return !empty($this->errors);
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}
}
