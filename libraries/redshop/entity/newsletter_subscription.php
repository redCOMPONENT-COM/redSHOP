<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Newsletter Subscription Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class RedshopEntityNewsletter_Subscription extends RedshopEntity
{
	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = null)
	{
		return JTable::getInstance('Newsletter_Subscription', 'Table');
	}

	public static function getUserFullName($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('firstname, lastname, username')
			->from($db->qn('#__redshop_users_info', 'ui'))
			->leftJoin($db->qn('#__users', 'u') . ' ON ' . $db->qn('ui.user_id') . ' = ' . $db->qn('u.id'))
			->where($db->qn('ui.user_id') . ' = ' . $db->q($id))
			->where($db->qn('ui.address_type') . ' = ' . $db->q('BT'));

		$user = $db->setQuery($query)->loadAssoc();

		if (isset($user)) {
			$fullname = $user['firstname'] . " " . $user['lastname'];
		} else {
            $query = $db->getQuery(true)
                ->select('firstname, lastname, username')
                ->from($db->qn('#__redshop_users_info', 'ui'))
                ->leftJoin($db->qn('#__users', 'u') . ' ON ' . $db->qn('ui.user_id') . ' = ' . $db->qn('u.id'))
                ->where($db->qn('ui.users_info_id') . ' = ' . $db->q($id))
                ->where($db->qn('ui.address_type') . ' = ' . $db->q('BT'));

            $user = $db->setQuery($query)->loadAssoc();

            $fullname = $user['firstname'] . " " . $user['lastname'];
		}

		return $fullname;
	}
}
