<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Promotion;

defined('_JEXEC') or die;

/**
 * Coupon Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Coupon
{
	/**
	 * Method for get coupon price
	 *
	 * @return  float
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCouponPrice()
	{
		$cart  = \RedshopHelperCartSession::getCart();
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn(array('value', 'type')))
			->from($db->qn('#__redshop_coupons'))
			->where($db->qn('id') . ' = ' . (int) $cart['coupon_id'])
			->where($db->qn('code') . ' = ' . $db->quote($cart['coupon_code']));

		$row = $db->setQuery($query)->loadObject();

		if (!$row)
		{
			return 0;
		}

		return $row->type == 1 ? (float) (($cart['product_subtotal'] * $row->value) / 100) : (float) $row->value;
	}
}