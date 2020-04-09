<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Ajax helper class
 *
 * @since  2.1.0
 */
class Shipping
{
    /**
     * @param   string  $shippingRateId  Shipping rate
     *
     * @return  array
     *
     * @since   3.0
     */
    public static function calculateShipping($shippingRateId)
    {
        $shipArr        = array();
        $order_shipping = \Redshop\Shipping\Rate::decrypt($shippingRateId);

        if (!isset($order_shipping[3])) {
            return $shipArr;
        }

        $shipArr['order_shipping_rate'] = $order_shipping[3];

        if (array_key_exists(6, $order_shipping)) {
            $shipArr['shipping_vat'] = $order_shipping [6];
        }

        return $shipArr;
    }
}