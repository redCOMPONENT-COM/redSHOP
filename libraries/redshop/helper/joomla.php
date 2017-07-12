<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Joomla! helper
 *
 * @since  2.0.0.6
 */
class RedshopHelperJoomla
{
	/**
	 * Get redSHOP manifest value
	 *
	 * @param   string $name    Name param
	 * @param   mixed  $default Default return value if value is not exists
	 *
	 * @return  mixed
	 */
	public static function getManifestValue($name, $default = null)
	{
		static $oldManifest;

		if (!isset($oldManifest))
		{
			$db          = JFactory::getDbo();
			$query       = $db->getQuery(true)
				->select('manifest_cache')
				->from($db->qn('#__extensions'))
				->where('type = ' . $db->q('component'))
				->where('element = ' . $db->q('com_redshop'));
			$oldManifest = json_decode($db->setQuery($query)->loadResult(), true);
		}

		if (isset($oldManifest[$name]))
		{
			return $oldManifest[$name];
		}
		else
		{
			return $default;
		}
	}

	/**
	 * Method for create Joomla user.
	 *
	 * @param   array   $data       User data.
	 * @param   boolean $createUser Create user
	 *
	 * @return  boolean|JUser|stdClass       JUser if success. False otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createJoomlaUser($data, $createUser = false)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$createUser = isset($data['createaccount']) ? (boolean) $data['createaccount'] : $createUser;

		// Registration is without account creation REGISTER_METHOD = 1
		// Or Optional account creation
		if (Redshop::getConfig()->get('REGISTER_METHOD') == 1 || (Redshop::getConfig()->get('REGISTER_METHOD') == 2 && !$createUser))
		{
			$user     = new stdClass;
			$user->id = 0;

			return $user;
		}

		$data['password']  = $input->post->get('password1', '', 'RAW');
		$data['password2'] = $input->post->get('password2', '', 'RAW');
		$data['email']     = $data['email1'];
		$data['name']      = $name = $data['firstname'];

		$userParams = JComponentHelper::getParams('com_users');

		// Prevent front-end user to change user group in the form and then being able to register on any Joomla! user group.
		if ($app->isSite())
		{
			$data['groups'] = array($userParams->get('new_usertype', 2));
		}

		// Do a password safety check
		if (Redshop::getConfig()->get('REGISTER_METHOD') == 3)
		{
			// Silent registration
			$betterToken = substr(uniqid(md5(rand()), true), 0, 10);

			$data['username']  = $data['email'];
			$data['password']  = $betterToken;
			$data['password2'] = $betterToken;

			$input->post->set('password1', $betterToken);
		}

		if (trim($data['email']) == "")
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_EMPTY_EMAIL'));

			return false;
		}

		if (trim($data['username']) == "")
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_EMPTY_USERNAME'));

			return false;
		}

		if (RedshopHelperUser::validateUser($data['username']) > 0)
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_USERNAME_ALREADY_EXISTS'));

			return false;
		}

		if (RedshopHelperUser::validateEmail($data['email']) > 0)
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_EMAIL_ALREADY_EXISTS'));

			return false;
		}

		// Check: Password is empty
		if (trim($data['password']) == "")
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_EMPTY_PASSWORD'));

			return false;
		}

		// Check: Password not match
		if ($data['password'] != $data['password2'])
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_PASSWORDS_DO_NOT_MATCH'));

			return false;
		}

		JPluginHelper::importPlugin('redshop_user');
		RedshopHelperUtility::getDispatcher()->trigger('onBeforeCreateJoomlaUser', array(&$data));

		// Get required system objects
		$user = clone JFactory::getUser();

		// If user registration is not allowed, show 403 not authorized.
		if (!$user->bind($data))
		{
			JError::raiseError(500, $user->getError());

			return false;
		}

		$date = JFactory::getDate();
		$user->set('id', 0);
		$user->set('registerDate', $date->toSql());

		// If user activation is turned on, we need to set the activation information
		$activationMethod = $userParams->get('useractivation');

		if ($activationMethod == '1')
		{
			$user->set('activation', JApplication::getHash(JUserHelper::genRandomPassword()));
			$user->set('block', '0');
		}

		$user->set('name', $name);
		$user->name = $name;

		// If there was an error with registration, set the message and display form
		if (!$user->save())
		{
			JError::raiseWarning('', JText::_($user->getError()));

			return false;
		}

		$credentials             = array();
		$credentials['username'] = $data['username'];
		$credentials['password'] = $data['password2'];

		// Perform the login action
		if (!JFactory::getUser()->id)
		{
			$app->login($credentials);
		}

		RedshopHelperUtility::getDispatcher()->trigger('onAfterCreateJoomlaUser', array(&$user));

		return $user;
	}
}
