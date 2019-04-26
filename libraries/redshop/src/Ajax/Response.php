<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Ajax;

defined('_JEXEC') or die;

/**
 * Ajax response class
 *
 * @since  2.1.0
 */
class Response
{
	/**
	 * @var    array
	 *
	 * @since  2.0.1
	 */
	protected $data = null;

	/**
	 * @var    array
	 *
	 * @since  2.0.1
	 */
	protected $scripts = null;

	/**
	 * @var    array
	 *
	 * @since  2.0.1
	 */
	protected $html = null;

	/**
	 * @var    array
	 *
	 * @since  2.0.1
	 */
	protected $messages;

	/**
	 * @var    boolean
	 *
	 * @since  2.0.1
	 */
	protected $status = true;

	/**
	 * @return static
	 *
	 * @since  2.1.0
	 */
	public static function getInstance()
	{
		static $instance;

		if (null === $instance)
		{
			$instance = new static;
		}

		return $instance;
	}

	/**
	 *
	 * @return string
	 *
	 * @since  2.1.0
	 */
	public function __toString()
	{
		return $this->toJson();
	}

	/**
	 * @return string
	 *
	 * @since  2.1.0
	 */
	public function toJson()
	{
		return json_encode(get_object_vars($this), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}

	/**
	 * @param   string   $message  Message
	 * @param   string   $type     Type
	 * @param   boolean  $alert    Wrap message with alarm styled
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addMessage($message, $type = 'info', $alert = true)
	{
		switch ($type)
		{
			case 'danger':
				$icon = 'fa fa-exclamation-circle';
				break;
			case 'success':
				$icon = 'fa-check-circle';
				break;
			default:
				$icon = 'fa-info-circle';
		}

		if ($alert)
		{
			$message = '<div class="alert alert-' . $type . '" role="alert"><i class="fa ' . $icon . '"></i> ' . $message . '</span>';
		}

		$this->messages[] = $message;

		return $this;
	}

	/**
	 * @param   string $message Message
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addPrimary($message)
	{
		return $this->addMessage($message, 'primary');
	}

	/**
	 * @param   string $message Message
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addSuccess($message)
	{
		return $this->addMessage($message, 'success');
	}

	/**
	 * @param   string $message Message
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addInfo($message)
	{
		return $this->addMessage($message, 'info');
	}

	/**
	 * @param   string $message Message
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addWarning($message)
	{
		return $this->addMessage($message, 'warning');
	}

	/**
	 * @param   string $message Message
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addDanger($message)
	{
		return $this->addMessage($message, 'danger');
	}

	/**
	 * @param   string  $key    Key
	 * @param   mixed   $value  Value
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addData($key, $value)
	{
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * @param   string   $script  Javascript
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addScript($script)
	{
		$this->scripts[] = $script;

		return $this;
	}

	/**
	 * @param   string   $html  Html
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function addHtml($html)
	{
		$this->html[] = $html;

		return $this;
	}

	/**
	 *
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function success()
	{
		$this->status = true;

		return $this;
	}

	/**
	 * @return  $this
	 *
	 * @since   2.1.0
	 */
	public function fail()
	{
		$this->status = false;

		return $this;
	}

	/**
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function isSuccess()
	{
		return $this->status;
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 *
	 * @since  2.1.0
	 */
	public function respond($includeMessageQueue = true)
	{
		if ($includeMessageQueue)
		{
			// Get the message queue if requested and available
			$app = \JFactory::getApplication();

			if (is_callable(array($app, 'getMessageQueue')))
			{
				$messages = $app->getMessageQueue();

				// Build the sorted messages list
				if (is_array($messages) && !empty($messages))
				{
					foreach ($messages as $message)
					{
						if (isset($message['type']) && isset($message['message']))
						{
							$this->addMessage($message['message'], $message['type']);
						}
					}
				}
			}
		}

		header('Content-Type: application/json');
		echo $this->toJson();

		\JFactory::getApplication()->close();
	}
}
