<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Shipping;

defined('_JEXEC') or die;

/**
 * Class Helper
 * @package Redshop\Shipping
 * @since   __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    public static function initShippingForCart(&$cart) {
        $cart['free_shipping'] = 0;
    }
}