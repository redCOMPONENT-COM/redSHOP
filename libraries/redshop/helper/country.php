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
 * Class Redshop Helper Country
 *
 * @since  1.5
 */
class RedshopHelperCountry
{
	/**
	 * Get country data
	 *
	 * @param   int  $cid  country id
	 *
	 * @return mixed
	 */
	public static function getCountryNameById($cid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn('country_name'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('id') . (int) $cid);

		$db->setQuery($query);

		return $db->loadObject();
	}
}
