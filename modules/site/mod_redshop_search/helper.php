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
		$shopperGroupData = rsUserHelper::getInstance()->getShopperGroupList($shopperGroupId);
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
		$shopperGroupData = rsUserHelper::getInstance()->getShopperGroupList($shopperGroupId);
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

	/**
	 * Get products custom fields
	 *
	 * @param   array  $productFields  Product custom fields
	 *
	 * @return  array
	 */
	public static function getCustomFields($productFields = array())
	{
		if (empty($productFields))
		{
			return array();
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('fv.field_value'))
			->select($db->qn('fv.field_id'))
			->select($db->qn('fv.field_name'))
			->select($db->qn('f.title'))
			->from($db->qn('#__redshop_fields', 'f'))
			->leftJoin($db->qn('#__redshop_fields_value', 'fv') . ' ON ' . $db->qn('f.id') . ' = ' . $db->qn('fv.field_id'))
			->where($db->qn('f.name') . ' IN (' . implode(',', $db->q($productFields)) . ')');

		$data   = $db->setQuery($query)->loadObjectList();
		$result = array();

		foreach ($data as $key => $value)
		{
			$result[$value->field_id]['title'] = $value->title;
			$result[$value->field_id]['value'][$value->field_value] = $value->field_name;
		}

		return $result;
	}
}
