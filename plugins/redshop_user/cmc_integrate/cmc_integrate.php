<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('redshop.library');
JLoader::import('list', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers');
JLoader::import('chimp', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers');

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
		if (empty($data) || empty($data['user_id']))
		{
			$userId = JFactory::getUser()->get('id');
		}
		else
		{
			$userId = $data['user_id'];
		}

		$userInfor = RedshopHelperUser::getUserInformation($userId);

		if (empty($userInfor->email))
		{
			if (empty($userInfor->user_email))
			{
				return false;
			}

			$userInfor->email = $userInfor->user_email;
		}

		$userInfor->merge_fields = $this->getMapping($this->params->get('mapping', ''), (array) $userInfor);

		// Get the users ip address unless the admin is saving his profile in backend
		if (JFactory::getApplication()->isSite())
		{
			$userInfor->merge_fields['OPTINIP'] = $this->getIpAddress();
		}

		// Create mapped data.
		$subscription = CmcHelperUsers::getSubscription($userInfor->email, $this->params->get('listId'));

		// Updating it to mailchimp
		$update = $subscription ? true : false;

		CmcHelperList::subscribe(
			$this->params->get('listId'),
			$userInfor->email,
			$userInfor->firstname,
			$userInfor->lastname,
			$userInfor->merge_fields,
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
		$this->mailchimpConfig = new Registry($pluginParam->params);

		return true;
	}

	/**
	 * Method for get available merge Fields of List
	 *
	 * @return  mixed|string
	 *
	 * @since   1.0.0
	 */
	public function onAjaxCmcIntegrateListSelect()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$app = JFactory::getApplication();

		$listId = $app->input->get('listId', '');

		if (empty($listId))
		{
			echo json_encode(array());
		}

		$mergeFields = CmcHelperList::getMergeFields($listId);

		echo json_encode($mergeFields);

		$app->close();
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
