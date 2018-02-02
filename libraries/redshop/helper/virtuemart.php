<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Virtue Mart integrate
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperVirtuemart
{
	/**
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $vmShopperGroups = null;

	/**
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $shopperGroups = null;

	/**
	 * Method for get shopper group ID
	 *
	 * @param   integer  $id  ID of virtue mart shopper group
	 *
	 * @return  string        Name of virtue mart.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getVirtuemartShopperGroups($id)
	{
		if (is_null(self::$vmShopperGroups))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('shopper_group_name', 'name'))
				->select($db->qn('shopper_group_name', 'id'))
				->from($db->qn('#__virtuemart_shoppergroups'))
				->order($db->qn('id') . ' ASC');
			self::$vmShopperGroups = $db->setQuery($query)->loadObjectList('id');
		}

		return isset(self::$vmShopperGroups[$id]) ? self::$vmShopperGroups[$id]->name : '';
	}

	/**
	 * Method for get redshop shopper group base on name
	 *
	 * @param   string  $name  ID of virtue mart shopper group
	 *
	 * @return  string         Name of redshop shopper group.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getRedshopShopperGroups($name = '')
	{
		if (is_null(self::$shopperGroups))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('shopper_group_id', 'id'))
				->select($db->qn('shopper_group_id', 'name'))
				->from($db->qn('#__redshop_shopper_group'))
				->order($db->qn('id') . ' ASC');
			self::$shopperGroups = $db->setQuery($query)->loadObjectList('name');
		}

		if ($name == 'COM_VIRTUEMART_SHOPPERGROUP_DEFAULT')
		{
			$name = JText::_('COM_REDSHOP_IMPORT_VM_SHOPPERGROUP_DEFAULT');
		}
		elseif ($name == 'COM_VIRTUEMART_SHOPPERGROUP_GUEST')
		{
			$name = JText::_('COM_REDSHOP_IMPORT_VM_SHOPPERGROUP_GUEST');
		}

		return isset(self::$shopperGroups[$name]) ? self::$shopperGroups[$name]->id : '';
	}
}
