<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.1.0
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Virtue Mart integrate
 *
 * @since  2.1.0
 */
class RedshopHelperVirtuemart
{
	/**
	 * @var  array
	 *
	 * @since  2.1.0
	 */
	protected static $vmShopperGroups = array();

	/**
	 * @var  array
	 *
	 * @since  2.1.0
	 */
	protected static $shopperGroups = array();

	/**
	 * Method for get shopper group ID
	 *
	 * @param   integer  $id  ID of virtue mart shopper group
	 *
	 * @return  string        Name of virtue mart.
	 *
	 * @since  2.1.0
	 */
	public static function getVirtuemartShopperGroups($id)
	{
		if (!array_key_exists($id, self::$vmShopperGroups))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('shopper_group_name'))
				->from($db->qn('#__virtuemart_shoppergroups'))
				->where($db->qn('virtuemart_shoppergroup_id') . ' = ' . $id);

			self::$vmShopperGroups[$id] = $db->setQuery($query)->loadResult();
		}

		return self::$vmShopperGroups[$id];
	}

	/**
	 * Method for get redshop shopper group base on name
	 *
	 * @param   string  $name  ID of virtue mart shopper group
	 *
	 * @return  string         Name of redshop shopper group.
	 *
	 * @since  2.1.0
	 */
	public static function getRedshopShopperGroups($name = '')
	{
		if ($name == 'COM_VIRTUEMART_SHOPPERGROUP_DEFAULT')
		{
			$name = JText::_('COM_REDSHOP_IMPORT_VM_SHOPPERGROUP_DEFAULT');
		}
		elseif ($name == 'COM_VIRTUEMART_SHOPPERGROUP_GUEST')
		{
			$name = JText::_('COM_REDSHOP_IMPORT_VM_SHOPPERGROUP_GUEST');
		}

		if (!array_key_exists($name, self::$shopperGroups))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('shopper_group_id', 'id'))
				->from($db->qn('#__redshop_shopper_group'))
				->where($db->qn('shopper_group_name') . ' = ' . $db->quote($name));

			self::$shopperGroups[$name] = $db->setQuery($query)->loadResult();
		}

		return self::$shopperGroups[$name];
	}
}
