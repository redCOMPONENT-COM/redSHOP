<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Router helper class
 *
 * @since  2.1.0
 */
class RedshopHelperRouter
{
	/**
	 * @var   array
	 *
	 * @since  2.1.0
	 */
	protected static $menuItems;

	/**
	 * @var   array
	 *
	 * @since  2.1.0
	 */
	protected static $menuItemAssociation = array();

	/**
	 * Get Redshop Menu Items
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public static function getRedshopMenuItems()
	{
		if (is_null(self::$menuItems))
		{
			self::$menuItems = JFactory::getApplication()->getMenu()->getItems('component', 'com_redshop');
		}

		return self::$menuItems;
	}

	/**
	 * Check Menu Query
	 *
	 * @param   object $oneMenuItem Values current menu item
	 * @param   array  $queryItems  Name query check
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public static function checkMenuQuery($oneMenuItem, $queryItems)
	{
		if (empty($oneMenuItem) || empty($queryItems))
		{
			return false;
		}

		foreach ($queryItems as $key => $value)
		{
			if (!isset($oneMenuItem->query[$key])
				|| (is_array($value) && !in_array($oneMenuItem->query[$key], $value))
				|| (!is_array($value) && $oneMenuItem->query[$key] != $value)
			)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Get RedShop Menu Item
	 *
	 * @param   array $queryItems Values query
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public static function getRedShopMenuItem($queryItems)
	{
		$serializeItem = md5(serialize($queryItems));

		if (!array_key_exists($serializeItem, self::$menuItemAssociation))
		{
			self::$menuItemAssociation[$serializeItem] = false;

			foreach (self::getRedshopMenuItems() as $oneMenuItem)
			{
				if (self::checkMenuQuery($oneMenuItem, $queryItems))
				{
					self::$menuItemAssociation[$serializeItem] = $oneMenuItem->id;
					break;
				}
			}
		}

		return self::$menuItemAssociation[$serializeItem];
	}

	/**
	 * Get Item Id
	 *
	 * @param   int $productId  Product Id
	 * @param   int $categoryId Category Id
	 *
	 * @return  mixed
	 *
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public static function getItemId($productId = 0, $categoryId = 0)
	{
		// Get Itemid from Product detail
		if ($productId)
		{
			$result = self::getRedShopMenuItem(array('option' => 'com_redshop', 'view' => 'product', 'pid' => (int) $productId));

			if ($result)
			{
				return $result;
			}
		}

		// Get Itemid from Category detail
		if ($categoryId)
		{
			$result = self::getCategoryItemid($categoryId);

			if ($result)
			{
				return $result;
			}
		}

		$input = JFactory::getApplication()->input;

		if ($input->getCmd('option', '') != 'com_redshop')
		{
			$result = self::getRedShopMenuItem(array('option' => 'com_redshop', 'view' => 'category'));

			if ($result)
			{
				return $result;
			}

			$result = self::getRedShopMenuItem(array('option' => 'com_redshop'));

			if ($result)
			{
				return $result;
			}
		}

		return $input->getInt('Itemid', 0);
	}

	/**
	 * Get Category Itemid
	 *
	 * @param   int $categoryId Category id
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public static function getCategoryItemid($categoryId = 0)
	{
		if (!$categoryId)
		{
			$result = self::getRedShopMenuItem(array('option' => 'com_redshop', 'view' => 'category'));

			if ($result)
			{
				return $result;
			}

			return null;
		}

		$categories = explode(',', $categoryId);

		if ($categories)
		{
			foreach ($categories as $category)
			{
				$result = self::getRedShopMenuItem(
					array('option' => 'com_redshop', 'view' => 'category', 'layout' => 'detail', 'cid' => (int) $category)
				);

				if ($result)
				{
					return $result;
				}
			}
		}

		// Get from Parents
		$categories = RedshopHelperCategory::getCategoryListReverseArray($categoryId);

		if ($categories)
		{
			foreach ($categories as $category)
			{
				$result = self::getCategoryItemid($category->id);

				if ($result)
				{
					return $result;
				}
			}
		}

		return null;
	}

	/**
	 * Method for get menu item id of checkout page
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public static function getCheckoutItemId()
	{
		$itemId       = Redshop::getConfig()->get('DEFAULT_CART_CHECKOUT_ITEMID');
		$shopperGroup = RedshopHelperUser::getShopperGroupData();

		if (!empty($shopperGroup) && $shopperGroup->shopper_group_cart_checkout_itemid != 0)
		{
			$itemId = $shopperGroup->shopper_group_cart_checkout_itemid;
		}

		if ($itemId == 0)
		{
			$itemId = JFactory::getApplication()->input->getInt('Itemid');
		}

		return $itemId;
	}

	/**
	 * Method for get menu item id of cart page
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public static function getCartItemId()
	{
		$itemId           = Redshop::getConfig()->get('DEFAULT_CART_CHECKOUT_ITEMID');
		$shopperGroupData = RedshopHelperUser::getShopperGroupData();

		if (!empty($shopperGroupData) && $shopperGroupData->shopper_group_cart_itemid != 0)
		{
			$itemId = $shopperGroupData->shopper_group_cart_itemid;
		}

		return $itemId;
	}
}
