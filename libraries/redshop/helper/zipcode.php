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
 * Class Redshop Helper Zipcode
 *
 * @since  1.5
 */
class RedshopHelperZipcode
{
	/**
	 * [autofillcityname description]
	 *
	 * @param   [string]  $zipcode  Zipcode
	 * 
	 * @return  [mixed]
	 */
	public static function getCityNameByZipcode($zipcode)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$input = JFactory::getApplication()->input;
		$zipcode = $input->getString('q', '');
		$zipcode = trim($zipcode);

		$query->select($db->qn('city_name'))
			->from($db->qn('#__redshop_zipcode'))
			->where($db->qn('zipcode') . ' = ' . $db->q($zipcode));

		$db->setQuery($query);

		return $db->loadResult();
	}
}
