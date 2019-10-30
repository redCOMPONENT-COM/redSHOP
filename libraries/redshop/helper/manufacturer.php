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
 * Class Redshop Helper for Manufacturer Methods
 *
 * @since  1.5
 */
class RedshopHelperManufacturer
{
	/**
	 * Function getmanufacturers.
	 *
	 * @return array
	 */
	public static function getManufacturers()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn(array('id', 'name')))
			->from($db->qn('#__redshop_manufacturer'));

		return $db->setQuery($query)->loadObjectlist();
	}
}
