<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

use Joomla\Registry\Registry;

/**
 * Plugins RedSHOP User - Joomlamailer Integrate
 *
 * @since  1.0.0
 */
class PlgRedshop_UserJoomlamailer_Integrate extends JPlugin
{
	/**
	 * @var  bool  True for auto-load language
	 */
	protected $autoloadLanguage = true;

	/**
	 * @var  joomlamailerMCAPI
	 */
	protected $api;

	/**
	 * @var  Registry
	 */
	protected $mailchimpConfig;

	protected $listId;

	/**
	 * Constructor - note in Joomla 2.5 PHP4.x is no longer supported so we can use this.
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * autoAcymailingSubscription function
	 *
	 * @param   bool   $isNew  To know that user is new or not
	 * @param   array  $data   data for trigger
	 *
	 * @return  boolean
	 */
	public function addNewsLetterSubscription($isNew, $data = array())
	{
		if (!$this->check())
		{
			return false;
		}

		// Prepare data for subscriber
		if (empty($data))
		{
			$user = JFactory::getUser();

			// In case: Current user don't have email.
			if (empty($user->email))
			{
				return false;
			}

			$userId = $user->id;
			$email  = $user->email;
			$name   = $user->name;
		}
		else
		{
			$userId = $data['user_id'];
			$email  = $data['email'];
			$name   = $data['name'];
		}

		if (empty($email))
		{
			if (empty($data['email_address']))
			{
				return false;
			}

			$email = $data['email_address'];
		}

		$name      = explode(' ', $name);
		$firstName = $name[0];
		$lastName  = '';

		if (count($name) > 1)
		{
			unset($name[0]);
			$lastName = implode(' ', $name);
		}

		// Check if the user is already activated and is subscribed
		$isSubscribed = false;
		$usersList    = $this->api->listsForEmail($data['email']);

		if (!empty($usersList) && in_array($this->listId, $usersList))
		{
			$isSubscribed = true;
		}

		$merges = array();

		$db = JFactory::getDbo();

		$mergeVars = array(
			'FNAME' => $firstName,
			'LNAME' => $lastName,
			'INTERESTS' => '',
			'GROUPINGS' => array()
		);

		// Get the users ip address unless the admin is saving his profile in backend
		if (JFactory::getApplication()->isSite())
		{
			$mergeVars['OPTINIP'] = $this->getIpAddress();
		}

		$mergeVars = array_merge($mergeVars, $merges);
		$emailType = '';
		$doubleOptin = $updateExisting = $replaceInterests = $sendWelcome = false;

		if ($isSubscribed === false)
		{
			// Subscribe the user
			$this->api->listSubscribe($this->listId, $email, $mergeVars, $emailType, $doubleOptin, $updateExisting, $replaceInterests, $sendWelcome);
			$query = $db->getQuery(true)
				->insert($db->qn('#__joomailermailchimpintegration'))
				->set(
					array(
						$db->qn('userid') . ' = ' . $db->quote($userId),
						$db->qn('email') . ' = ' . $db->quote($email),
						$db->qn('listid') . ' = ' . $db->quote($this->listId)
					)
				);

			try
			{
				$db->setQuery($query)->execute();
			}
			catch (Exception $e)
			{
			}
		}
		else
		{
			$this->api->listUpdateMember($this->listId, $email, $mergeVars, '', true);
		}

		return true;
	}

	/**
	 * Method for check JoomlaMailer extension exist.
	 *
	 * @return  bool
	 *
	 * @since  1.0.0
	 */
	public function check()
	{
		if (!class_exists('joomlamailerMCAPI'))
		{
			if (!file_exists(JPATH_ROOT . '/administrator/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php'))
			{
				return false;
			}

			require_once JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php';
		}

		// Load Mailchimp API
		$mailChimpApi = JComponentHelper::getParams('com_joomailermailchimpintegration')->get('params.MCapi');

		if (empty($mailChimpApi))
		{
			return false;
		}

		$this->api = new joomlamailerMCAPI($mailChimpApi);

		// Load Mailchimp List ID configuration
		$pluginParam = JPluginHelper::getPlugin('user', 'joomlamailer');
		$pluginParam = new Registry($pluginParam->params);
		$listId      = $pluginParam->get('listid', '');

		if (empty($listId))
		{
			return false;
		}

		$this->listId = $listId;
		$this->mailchimpConfig = $pluginParam;

		return true;
	}

	/**
	 * Return IP of client.
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 */
	private function getIpAddress()
	{
		$keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');

		foreach ($keys as $key)
		{
			if (array_key_exists($key, $_SERVER) === true)
			{
				foreach (explode(',', $_SERVER[$key]) as $ip)
				{
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false)
					{
						return $ip;
					}
				}
			}
		}

		return '';
	}
}
