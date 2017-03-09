<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Manufacturer
 *
 * @since  2.0.3
 */
class RedshopHelperManufacturer
{
	/**
	 * [getManufacturer description]
	 *
	 * @param   [int]  $mid  Id of manufacturer
	 * 
	 * @return [type] [description]
	 */
	public static function getManufacturer($mid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$db->qn(
				['sef_url', 'manufacturer_name']
			)
		)
		->from($db->qn('#__redshop_manufacturer'))
		->where($db->qn('manufacturer_id') . ' = ' . (int) $mid);

		$db->setQuery($query);

		return $db->loadObject();
	}
}
