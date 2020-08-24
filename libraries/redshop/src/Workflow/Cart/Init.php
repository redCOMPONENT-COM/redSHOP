<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Workflow\Cart;

defined('_JEXEC') or die;

/**
 * Class Init
 * @package Redshop\Workflow\Cart
 * @since   __DEPLOY_VERSION__
 */
class Init
{
    /**
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    public static function on(&$cart) {
        \Redshop\Promotion\Discount::initDiscountForCart($cart);
        \Redshop\ShopperGroup\Helper::initShopperGroupForCart($cart);
        \Redshop\Shipping\Helper::initShippingForCart($cart);
    }
}