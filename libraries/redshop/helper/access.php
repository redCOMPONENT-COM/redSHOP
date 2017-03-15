<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
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
	 * Check access level of an user
	 *
	 * @param   integer  $groupId  Group ID of an user
	 *
	 * @return  array
	 *
	 * @since  2.0.3
	 */
	public static function checkAccessOfUser($groupId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('a.section_name'))
			->from($db->qn('#__redshop_accessmanager', 'a'))
			->where($db->qn('a.view') . ' = 1')
			->where($db->qn('a.gid') . ' = ' . (int) $groupId);

		$db->setQuery($query);

		return $db->loadColumn();
	}

	/**
	 * Check access level of a group users
	 *
	 * @param   string   $view     View name
	 * @param   string   $task     Have 3 options: add/ edit/ remove
	 * @param   integer  $groupId  Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public static function checkGroupAccess($view, $task, $groupId)
	{
		switch ($task)
		{
			case 'add':
				self::getGroupAccessTaskAdd($view, $groupId);
				break;
			case 'edit':
				self::getGroupAccessTaskEdit($view, $groupId);
				break;
			case 'remove':
				self::getGroupAccessTaskDelete($view, $groupId);
				break;
			default:
				self::getGroupAccess($view, $groupId);
				break;
		}
	}

	/**
	 * Get access level of group users
	 *
	 * @param   string   $view     View name
	 * @param   integer  $groupId  Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public static function getGroupAccess($view, $groupId)
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();

		if ($view == "shipping_rate_detail" || $view == "shipping_rate" || $view == "shipping_detail")
		{
			$view = "shipping";
		}
		elseif ($view == "tax_group_detail" || $view == "tax_detail" || $view == "tax")
		{
			$view = "tax_group";
		}
		elseif ($view == "prices_detail" || $view == "prices" || $view == "mass_discount" || $view == "mass_discounts")
		{
			$view = "product";
		}
		elseif ($view == "addorder_detail")
		{
			$view = "order";
		}
		elseif ($view == "user_detail")
		{
			$view = "user";
		}
		elseif ($view == "export")
		{
			$view = "import";
		}
		elseif ($view == "voucher_detail")
		{
			$view = "voucher";
		}
		elseif ($view == "coupon_detail")
		{
			$view = "coupon";
		}

		$query = $db->getQuery(true)
			->select($db->qn('a.view'))
			->from($db->qn('#__redshop_accessmanager', 'a'))
			->where($db->qn('a.section_name') . ' = ' . $db->quote($view))
			->where($db->qn('a.gid') . ' = ' . (int) $groupId);

		$db->setQuery($query);
		$accessview = $db->loadResult();

		if ($accessview != 1)
		{
			$msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
			$app->redirect($_SERVER['HTTP_REFERER'], $msg);
		}
	}

	/**
	 * Get access level of group add users
	 *
	 * @param   string   $view     View name
	 * @param   integer  $groupId  Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public static function getGroupAccessTaskAdd($view, $groupId)
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();

		if ($view == "shipping_rate_detail" || $view == "shipping_rate" || $view == "shipping_detail")
		{
			$view = "shipping";
		}
		elseif ($view == "tax_group_detail" || $view == "tax_detail" || $view == "tax")
		{
			$view = "tax_group";
		}
		elseif ($view == "prices_detail" || $view == "prices" || $view == "mass_discount" || $view == "mass_discounts")
		{
			$view = "product";
		}
		elseif ($view == "addorder_detail")
		{
			$view = "order";
		}
		elseif ($view == "user_detail")
		{
			$view = "user";
		}
		elseif ($view == "export")
		{
			$view = "import";
		}
		elseif ($view == "voucher_detail")
		{
			$view = "voucher";
		}
		elseif ($view == "coupon_detail")
		{
			$view = "coupon";
		}

		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->qn('#__redshop_accessmanager', 'a'))
			->where($db->qn('a.section_name') . ' = ' . $db->quote(str_replace('_detail', '', $view)))
			->where($db->qn('a.gid') . ' = ' . (int) $groupId);

		$db->setQuery($query);
		$accessView = $db->loadObjectList();

		if ($accessView[0]->add != 1)
		{
			$msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
			$app->redirect($_SERVER['HTTP_REFERER'], $msg);
		}
	}

	/**
	 * Get access level of group edit users
	 *
	 * @param   string   $view     View name
	 * @param   integer  $groupId  Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public static function getGroupAccessTaskEdit($view, $groupId)
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();

		if ($view == "shipping_rate_detail" || $view == "shipping_rate" || $view == "shipping_detail")
		{
			$view = "shipping";
		}
		elseif ($view == "tax_group_detail" || $view == "tax_detail" || $view == "tax")
		{
			$view = "tax_group";
		}
		elseif ($view == "prices_detail" || $view == "prices" || $view == "mass_discount" || $view == "mass_discounts")
		{
			$view = "product";
		}
		elseif ($view == "addorder_detail")
		{
			$view = "order";
		}
		elseif ($view == "user_detail")
		{
			$view = "user";
		}
		elseif ($view == "export")
		{
			$view = "import";
		}
		elseif ($view == "voucher_detail")
		{
			$view = "voucher";
		}
		elseif ($view == "coupon_detail")
		{
			$view = "coupon";
		}

		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->qn('#__redshop_accessmanager', 'a'))
			->where($db->qn('a.section_name') . ' = ' . $db->quote(str_replace('_detail', '', $view)))
			->where($db->qn('a.gid') . ' = ' . (int) $groupId);

		$db->setQuery($query);
		$accessView = $db->loadObjectList();

		if ($accessView[0]->edit != 1)
		{
			$msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
			$app->redirect($_SERVER['HTTP_REFERER'], $msg);
		}
	}

	/**
	 * Get access level of group delete users
	 *
	 * @param   string   $view     View name
	 * @param   integer  $groupId  Group ID
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public static function getGroupAccessTaskDelete($view, $groupId)
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();

		if ($view == "shipping_rate_detail" || $view == "shipping_rate" || $view == "shipping_detail")
		{
			$view = "shipping";
		}
		elseif ($view == "tax_group_detail" || $view == "tax_detail" || $view == "tax")
		{
			$view = "tax_group";
		}
		elseif ($view == "prices_detail" || $view == "prices" || $view == "mass_discount" || $view == "mass_discounts")
		{
			$view = "product";
		}
		elseif ($view == "addorder_detail")
		{
			$view = "order";
		}
		elseif ($view == "user_detail")
		{
			$view = "user";
		}
		elseif ($view == "export")
		{
			$view = "import";
		}
		elseif ($view == "voucher_detail")
		{
			$view = "voucher";
		}
		elseif ($view == "coupon_detail")
		{
			$view = "coupon";
		}

		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->qn('#__redshop_accessmanager', 'a'))
			->where($db->qn('a.section_name') . ' = ' . $db->quote(str_replace('_detail', '', $view)))
			->where($db->qn('a.gid') . ' = ' . (int) $groupId);

		$db->setQuery($query);
		$accessView = $db->loadObjectList();

		if ($accessView[0]->delete != 1)
		{
			$msg = JText::_('COM_REDSHOP_DONT_HAVE_PERMISSION');
			$app->redirect($_SERVER['HTTP_REFERER'], $msg);
		}
	}

	/**
	 * Check permission for Products shopper group can access or can't access
	 *
	 * @param   int  $pid  Product id that need to be checked
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
	 * @param   int  $cid  Category id that need to be checked.
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

		if ($shopperGroupData = $userHelper->getShopperGroupList($shopperGroupId))
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
}
