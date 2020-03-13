<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Promotion;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Coupon Helper
 *
 * @since 3.0
 */
class Coupon
{
	/**
	 * Method for get coupon price
	 *
	 * @return  float
	 *
	 * @since   3.0
	 */
	public static function getCouponPrice()
	{
		$cart  = \Redshop\Cart\Helper::getCart();
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

	/**
	 * Method for get user coupons
	 *
	 * @param   integer $uid User ID
	 *
	 * @return  array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function getUserCoupons($uid)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__redshop_coupons')
			->where('published = 1')
			->where('userid = ' . (int) $uid)
			->where('end_date >= ' . $db->q(Factory::getDate()->toSql()))
			->where($db->qn('amount_left') . ' > 0');

		return $db->setQuery($query)->loadObjectList();
	}
}