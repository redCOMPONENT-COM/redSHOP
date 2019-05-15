<?php
/**
 * @package     Redshop\Repositories
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Repositories;

/**
 * @package     Redshop\Repositories
 *
 * @since       2.1.0
 */
class Order
{
	/**
	 * @param   string  $orderNumber  Order number
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0.0
	 */
	public static function getOrderByNumber($orderNumber)
	{
		$db = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__redshop_orders'))
			->where($db->quoteName('order_number') . ' = ' . $db->quote($orderNumber));

		return $db->setQuery($query)->loadObject();
	}
}
