<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

defined('_JEXEC') or die;

/**
 * Discount Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Quantity
{
    /**
     * @param $data
     * @return float|int
     * @since __DEPLOY_VERSION__
     */
    public static function getTotalQuantityFromCart($data) {
        $totalQuantity  = $data['quantity_all'];
        $quantity       = explode(",", $totalQuantity);
        return array_sum($quantity);
    }

    public static function makeQuantityValid($quantity, $cartItem) {
        $quantity = ($quantity < 0) ? $cartItem['quantity']: $quantity[$i];
        return intval(abs($quantity) > 0 ? $quantity : 1);
    }
}