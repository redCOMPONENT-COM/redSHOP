<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redshop_search
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_redshop_search
 *
 * @since  2.0.7
 */
abstract class ModRedshopSearch
{
	/**
	 * This function will get Category data
	 *
	 * @return array
	 */
	public static function getCategories()
	{
		$shopperGroupId = RedshopHelperUser::getShopperGroup(JFactory::getUser()->id);
		$shopperGroupData = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('id', 'value'))
			->select($db->qn('name', 'text'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('published') . ' = 1')
			->where($db->qn('parent_id') . ' != 0')
			->order($db->qn('name'));

		if (!empty($shopperGroupData) && isset($shopperGroupData[0]) && $shopperGroupData[0]->shopper_group_categories)
		{
			$query->where($db->qn('id') . ' IN(' . $shopperGroupData[0]->shopper_group_categories . ')');
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * This function will get Manufacturer data
	 *
	 * @return array
	 */
	public static function getManufacturers()
	{
		$shopperGroupId = RedshopHelperUser::getShopperGroup(JFactory::getUser()->id);
		$shopperGroupData = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('manufacturer_id', 'value'))
			->select($db->qn('manufacturer_name', 'text'))
			->from($db->qn('#__redshop_manufacturer'))
			->where($db->qn('published') . ' = 1');

		if (!empty($shopperGroupData) && isset($shopperGroupData[0]) && $shopperGroupData[0]->shopper_group_manufactures)
		{
			$query->where($db->qn('manufacturer_id') . ' IN(' . $shopperGroupData[0]->shopper_group_manufactures . ')');
		}

		return $db->setQuery($query)->loadObjectList();
	}
}
