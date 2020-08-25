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
            ->where($db->qn('id') . ' = ' . (int)$cart['coupon_id'])
            ->where($db->qn('code') . ' = ' . $db->quote($cart['coupon_code']));

        $row = $db->setQuery($query)->loadObject();

        if (!$row) {
            return 0;
        }

        return $row->type == 1 ? (float)(($cart['product_subtotal'] * $row->value) / 100) : (float)$row->value;
    }

    /**
     * Method for get user coupons
     *
     * @param   integer  $uid  User ID
     *
     * @return  mixed
     *
     * @since 3.0.1
     */
    public static function getUserCoupons($uid)
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__redshop_coupons')
            ->where('published = 1')
            ->where('userid = ' . (int)$uid)
            ->where('end_date >= ' . $db->q(Factory::getDate()->toSql()))
            ->where($db->qn('amount_left') . ' > 0');

        return \Redshop\DB\Tool::safeSelect($db, $query, true);
    }

    /**
     * @param $coupon
     * @param $cart
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    public static function isCouponApplied(&$coupon, &$cart) {
        if ((count($cart['coupon']) <= 0) || empty($coupon)) {
            return true;
        }

        foreach ($cart['coupon'] as $cartCoupon) {
            if ($coupon->id == $cartCoupon['coupon_id']) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return mixed|null
     * @since  __DEPLOY_VERSION__
     */
    public static function getUserTransactions($uid) {
        $db = \Joomla\CMS\Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('SUM(' . $db->qn('coupon_value') . ') AS usertotal')
            ->from($db->qn('#__redshop_coupons_transaction'))
            ->where($db->qn('userid') . ' = ' . (int)$uid)
            ->group($db->qn('userid'));

        // Set the query and load the result.
        return $db->setQuery($query)->loadResult();
    }
}