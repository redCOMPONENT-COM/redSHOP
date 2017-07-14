<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Access Level
 *
 * @since  2.0.3
 */
class RedshopHelperAccess
{
	/**
	 * @var  array
	 *
	 * @since  2.0.3
	 */
	protected static $portalCategories = array();

	/**
	 * Check permission for Products shopper group can access or can't access
	 *
	 * @param   int $pid Product id that need to be checked
	 *
	 * @return  boolean
	 *
	 * @since   2.0.3
	 */
	public static function checkPortalProductPermission($pid = 0)
	{
		if (!$pid)
		{
			return false;
		}

		$product = RedshopProduct::getInstance($pid);

		if (empty($product) || empty($product->categories))
		{
			return false;
		}

		foreach ($product->categories as $cid)
		{
			$checkPermission = self::checkPortalCategoryPermission($cid);

			if (!$checkPermission)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Check permission for Categories shopper group can access or can't access
	 *
	 * @param   int $cid Category id that need to be checked.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.3
	 */
	public static function checkPortalCategoryPermission($cid = 0)
	{
		if (array_key_exists($cid, static::$portalCategories))
		{
			return true;
		}

		$user           = JFactory::getUser();
		$userHelper     = rsUserHelper::getInstance();
		$shopperGroupId = RedshopHelperUser::getShopperGroup($user->id);

		if ($shopperGroupData = Redshop\Helper\ShopperGroup::generateList($shopperGroupId))
		{
			if (isset($shopperGroupData[0]) && $shopperGroupData[0]->shopper_group_categories)
			{
				$shopperCategories = explode(',', $shopperGroupData[0]->shopper_group_categories);

				if (array_search((int) $cid, $shopperCategories) !== false)
				{
					static::$portalCategories = $shopperCategories;

					return true;
				}
			}
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('shopper_group_id'))
			->from($db->qn('#__redshop_shopper_group'))
			->where('FIND_IN_SET(' . $db->quote($cid) . ', shopper_group_categories)')
			->where($db->qn('shopper_group_id') . ' != ' . (int) $shopperGroupId);

		if ($db->setQuery($query)->loadResult())
		{
			return false;
		}

		return true;
	}

	/**
	 * Method for check if user can view this object or not
	 *
	 * @param   string  $target  Target name
	 * @param   int     $userId  ID of user. If null, use current user.
	 *
	 * @return  boolean          True on success. False otherwise.
	 *
	 * @since  2.0.6
	 */
	public static function canView($target = '', $userId = 0)
	{
		return self::canDo($target, 'view', $userId);
	}

	/**
	 * Method for check if user can create this object or not
	 *
	 * @param   string  $target  Target name
	 * @param   int     $userId  ID of user. If null, use current user.
	 *
	 * @return  boolean          True on success. False otherwise.
	 *
	 * @since  2.0.6
	 */
	public static function canCreate($target = '', $userId = 0)
	{
		return self::canDo($target, 'create', $userId);
	}

	/**
	 * Method for check if user can edit this object or not
	 *
	 * @param   string  $target  Target name
	 * @param   int     $userId  ID of user. If null, use current user.
	 *
	 * @return  boolean          True on success. False otherwise.
	 *
	 * @since  2.0.6
	 */
	public static function canEdit($target = '', $userId = 0)
	{
		return self::canDo($target, 'edit', $userId);
	}

	/**
	 * Method for check if user can delete this object or not
	 *
	 * @param   string  $target  Target name
	 * @param   int     $userId  ID of user. If null, use current user.
	 *
	 * @return  boolean          True on success. False otherwise.
	 *
	 * @since  2.0.6
	 */
	public static function canDelete($target = '', $userId = 0)
	{
		return self::canDo($target, 'delete', $userId);
	}

	/**
	 * Method for check if user can have permission this object or not
	 *
	 * @param   string  $target  Target name
	 * @param   string  $task    Permission name
	 * @param   int     $userId  ID of user. If null, use current user.
	 *
	 * @return  boolean          True on success. False otherwise.
	 *
	 * @since  2.0.6
	 */
	public static function canDo($target = '', $task = '', $userId = 0)
	{
		if (!$userId)
		{
			$user = JFactory::getUser();
		}
		else
		{
			$user = JFactory::getUser($userId);
		}

		return $user->authorise($target . '.' . $task, 'com_redshop.backend');
	}

	/**
	 * Check access level of an user
	 *
	 * @param   integer $groupId Group ID of an user
	 *
	 * @return  array
	 *
	 * @since  2.0.3
	 *
	 * @deprecated  2.0.6  This function has been removed.
	 */
	public static function checkAccessOfUser($groupId)
	{
		return array();
	}

	/**
	 * Check access level of a group users
	 *
	 * @param   string  $view    View name
	 * @param   string  $task    Have 3 options: add/ edit/ remove
	 * @param   integer $groupId Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 *
	 * @deprecated  2.0.6  This function has been removed.
	 */
	public static function checkGroupAccess($view, $task, $groupId)
	{
		// @TODO: Need to remove at next release after 2.0.6
	}

	/**
	 * Get access level of group users
	 *
	 * @param   string  $view    View name
	 * @param   integer $groupId Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 *
	 * @deprecated  2.0.6  This function has been removed.
	 */
	public static function getGroupAccess($view, $groupId)
	{
		// @TODO: Need to remove at next release after 2.0.6
	}

	/**
	 * Get access level of group add users
	 *
	 * @param   string  $view    View name
	 * @param   integer $groupId Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 *
	 * @deprecated  2.0.6  This function has been removed.
	 */
	public static function getGroupAccessTaskAdd($view, $groupId)
	{
		// @TODO: Need to remove at next release after 2.0.6
	}

	/**
	 * Get access level of group edit users
	 *
	 * @param   string  $view    View name
	 * @param   integer $groupId Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 *
	 * @deprecated  2.0.6  This function has been removed.
	 */
	public static function getGroupAccessTaskEdit($view, $groupId)
	{
		// @TODO: Need to remove at next release after 2.0.6
	}

	/**
	 * Get access level of group delete users
	 *
	 * @param   string  $view    View name
	 * @param   integer $groupId Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 *
	 * @deprecated  2.0.6  This function has been removed.
	 */
	public static function getGroupAccessTaskDelete($view, $groupId)
	{
		// @TODO: Need to remove at next release after 2.0.6
	}
}
