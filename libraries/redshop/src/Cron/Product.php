<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.1.0
 */

namespace Redshop\Cron;

/**
 * Class Product
 *
 * @since       2.1.0
 */
class Product
{
	/**
	 * @return mixed
	 */
	public static function removeExpiredSales()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->quoteName('#__redshop_product'))
			->set($db->quoteName('product_on_sale') . ' = 0')
			->where($db->quoteName('discount_enddate') . ' <= ' . time());

		return $db->setQuery($query)->execute();
	}
}
