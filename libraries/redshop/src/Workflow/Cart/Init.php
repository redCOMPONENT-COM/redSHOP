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
    public static function cart(&$cart) {
        \Redshop\Promotion\Discount::initDiscountForCart($cart);
        \Redshop\ShopperGroup\Helper::initShopperGroupForCart($cart);
        \Redshop\Shipping\Helper::initShippingForCart($cart);
    }

    /**
     * @param $post
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function post(&$post) {
        $post = empty($post) ? \Redshop\IO\Input::getArray('post') : $post;
        $post['quantity'] = round($post['quantity']);
    }
}