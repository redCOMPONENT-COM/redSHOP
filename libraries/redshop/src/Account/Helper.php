<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Account;

defined('_JEXEC') or die;

/**
 * Account helper
 *
 * @since  2.1.0
 */
class Helper
{
	/**
	 * Method for get reserve discount
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public static function getReserveDiscount()
	{
		$userId = \JFactory::getUser()->id;
		$db     = \JFactory::getDbo();
		$query  = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_coupons_transaction'))
			->where($db->qn('userid') . ' = ' . $userId)
			->where($db->qn('coupon_value') . ' > 0');

		$result         = $db->setQuery($query)->loadObject();
		$remainDiscount = 0;

		if (null !== $result)
		{
			$remainDiscount = $result->coupon_value;
		}

		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_product_voucher_transaction'))
			->where($db->qn('user_id') . ' = ' . $userId)
			->where($db->qn('amount') . ' > 0');

		$result = $db->setQuery($query)->loadObject();

		if ($result)
		{
			$remainDiscount += $result->amount;
		}

		return $remainDiscount;
	}

	/**
	 * Method for get list of downloadable product on specific user
	 *
	 * @param   integer  $userId  User ID
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getDownloadProductList($userId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('pd.*')
			->select($db->qn('p.product_name'))
			->from($db->qn('#__redshop_product_download', 'pd'))
			->innerJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pd.product_id'))
			->innerJoin($db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('pd.order_id'))
			->where($db->qn('pd.user_id') . ' = ' . (int) $userId)
			->where($db->qn('o.order_payment_status') . ' = ' . $db->quote('Paid'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Method for get remaining coupon amount of specific user
	 *
	 * @param   integer $userId     User Id
	 * @param   string  $couponCode Coupon code
	 *
	 * @return  float
	 *
	 * @since   2.1.0
	 */
	public static function getUnusedCouponAmount($userId, $couponCode)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('coupon_value'))
			->from($db->qn('#__redshop_coupons_transaction'))
			->where($db->qn('userid') . ' = ' . (int) $userId)
			->where($db->qn('coupon_code') . ' = ' . $db->quote($couponCode));

		return (float) $db->setQuery($query)->loadResult();
	}
}
