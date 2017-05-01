<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Install
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Install class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopInstall
{
	/**
	 * Method for synchronize Joomla User to redSHOP user
	 *
	 * @return  int   Number of synchronized user.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function synchronizeUser()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('u.*')
			->from($db->qn('#__users', 'u'))
			->leftJoin($db->qn('#__redshopb_users_info', 'ru') . ' ON ' . $db->qn('ru.user_id') . ' = ' . $db->qn('u.id'))
			->where($db->qn('ru.user_id') . ' IS NULL');
		$joomlaUsers = $db->setQuery($query)->loadObjectList();

		if (empty($joomlaUsers))
		{
			return 0;
		}

		$userHelper = rsUserHelper::getInstance();

		foreach ($joomlaUsers as $joomlaUser)
		{
			$name = explode(" ", $joomlaUser->name);

			$post               = array();
			$post['user_id']    = $joomlaUser->id;
			$post['email']      = $joomlaUser->email;
			$post['email1']     = $joomlaUser->email;
			$post['firstname']  = $name[0];
			$post['lastname']   = (isset($name[1]) && $name[1]) ? $name[1] : '';
			$post['is_company'] = (Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2) ? 1 : 0;
			$post['password1']  = '';
			$post['billisship'] = 1;

			$userHelper->storeRedshopUser($post, $joomlaUser->id, 1);
		}

		return count($joomlaUsers);
	}
}
