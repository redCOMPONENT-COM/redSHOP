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
 * Class Redshop Helper Stock Room
 *
 * @since  1.5
 */
class RedshopHelperStockroom
{
	/**
	 * Check already notified user
	 *
	 * @param   int  $userId         User id
	 * @param   int  $productId      Product id
	 * @param   int  $propertyId     Property id
	 * @param   int  $subPropertyId  Sub property id
	 *
	 * @return mixed
	 */
	public static function isAlreadyNotifiedUser($userId, $productId, $propertyId, $subPropertyId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id')
			->from($db->qn('#__redshop_notifystock_users'))
			->where('product_id = ' . (int) $productId)
			->where('property_id = ' . (int) $propertyId)
			->where('subproperty_id = ' . (int) $subPropertyId)
			->where('user_id =' . (int) $userId)
			->where('notification_status = 0');

		return $db->setQuery($query)->loadResult();
	}
}
