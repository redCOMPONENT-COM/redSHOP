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
 * Plugins RedSHOP CMC Integrate
 *
 * @since  1.0.0
 */
class PlgRedshop_UserCmc_Integrate extends JPlugin
{
	/**
	 * @var  bool  True for auto-load language
	 */
	protected $autoloadLanguage = true;

	/**
	 * @var  Registry
	 */
	protected $mailchimpConfig;

	/**
	 * @var  string
	 */
	protected $listId;

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

			$data = array(
				'user_id'       => $user->id,
				'email'         => $user->email,
				'email_address' => $user->email,
				'name'          => $user->name
			);
		}

		if (empty($data['email']))
		{
			if (empty($data['email_address']))
			{
				return false;
			}

			$data['email'] = $data['email_address'];
		}

		$name      = explode(' ', $data['name']);
		$firstName = $name[0];
		$lastName  = '';

		if (count($name) > 1)
		{
			unset($name[0]);
			$lastName = implode(' ', $name);
		}

		$mergeVars = array(
			'FNAME'     => $firstName,
			'LNAME'     => $lastName,
			'INTERESTS' => '',
			'GROUPINGS' => array()
		);

		// Get the users ip address unless the admin is saving his profile in backend
		if (JFactory::getApplication()->isSite())
		{
			$mergeVars['OPTINIP'] = $this->getIpAddress();
		}

		$mappedData = $this->getMapping($this->mailchimpConfig->get('mapfields'), $data);

		if (!empty($mappedData))
		{
			$mergeVars = array_merge($mappedData, $mergeVars);
		}

		$data = array_merge($data, $mergeVars);

		// Create mapped data.
		$subscription = CmcHelperUsers::getSubscription($data['email'], $this->listId);

		// Updating it to mailchimp
		$update = $subscription ? true : false;

		CmcHelperList::subscribe(
			$this->listId,
			$data['email'],
			$firstName,
			$lastName,
			CmcHelperList::mergeVars($data, $this->listId),
			'html',
			$update,
			true
		);

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
		if (!JComponentHelper::isEnabled('com_cmc'))
		{
			return false;
		}

		// Load Compojoom library
		require_once JPATH_LIBRARIES . '/compojoom/include.php';

		JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

		// Load Mailchimp API
		$mailChimpApi = JComponentHelper::getParams('com_cmc')->get('api_key');

		if (empty($mailChimpApi))
		{
			return false;
		}

		// Load Mailchimp List ID configuration
		$pluginParam = JPluginHelper::getPlugin('user', 'cmc');
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

	/**
	 * Creates an array with the mapped data
	 *
	 * @param   string  $raw   - the raw mapping definition as taken out of the params
	 * @param   array   $user  - array with the user data
	 *
	 * @return array
	 *
	 * @since  3.0.1
	 */
	private function getMapping($raw, $user)
	{
		if (!$raw)
		{
			return array();
		}

		$lines = explode("\n", trim($raw));
		$groups = array();

		foreach ($lines as $line)
		{
			$map = explode('=', $line);

			if (strstr($map[1], ':'))
			{
				$parts = explode(':', $map[1]);
				$field = explode(' ', $user[$parts[0]]);

				$value = trim($field[(int) $parts[1]]);
			}
			else
			{
				$value = $user[trim($map[1])];
			}

			$groups[$map[0]] = $value;
		}

		return $groups;
	}
}
