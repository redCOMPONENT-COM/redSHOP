<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class LoginModelLogin
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelLogin extends RedshopModel
{
	public function __construct()
	{
		parent::__construct();
	}

	public function setlogin($username, $password)
	{
		$app = JFactory::getApplication();

		$credentials             = array();
		$credentials['username'] = $username;
		$credentials['password'] = $password;

		// Perform the login action
		$error = $app->login($credentials);

		if (isset($error->message))
		{
			$msg = "<a href='" . JRoute::_('index.php?option=com_users&view=reset') . "'>" . JText::_('COM_REDSHOP_FORGOT_PWD_LINK') . "</a>";
			$app->enqueuemessage($msg);
		}
	}

	public function ShopperGroupDetail($sid = 0)
	{
		$user = JFactory::getUser();

		if ($sid == 0)
		{
			$query = "SELECT sg.* FROM #__redshop_shopper_group as sg "
			. " LEFT JOIN #__redshop_users_info as ui on sg.`shopper_group_id`= ui.shopper_group_id WHERE ui.user_id = " . (int) $user->id;
		}
		else
		{
			$query = "SELECT sg.* FROM #__redshop_shopper_group as sg WHERE sg.`shopper_group_id`= " . (int) $sid;
		}
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function CheckShopperGroup($username, $shoppergroupid)
	{
		$db = JFactory::getDbo();
		$query = "SELECT sg.`shopper_group_id` FROM (`#__redshop_shopper_group` as sg "
			. " LEFT JOIN #__redshop_users_info as ui on sg.`shopper_group_id`= ui.shopper_group_id) LEFT JOIN #__users as u on ui.user_id = u.id WHERE u.username = "
			. $db->quote($username) . " AND ui.shopper_group_id =" . (int) $shoppergroupid . " AND sg.shopper_group_portal = 1";
		$db->setQuery($query);

		return $db->loadResult();
	}
}
