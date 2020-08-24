<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\ShopperGroup;

defined('_JEXEC') or die;

/**
 * Class Helper
 * @package Redshop\ShopperGroup
 * @since   __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    public static function initShopperGroupForCart(&$cart) {
        $user = \Joomla\CMS\Factory::getUser();

        if (!isset($cart['user_shopper_group_id']) || (isset($cart['user_shopper_group_id']) && $cart['user_shopper_group_id'] == 0)) {
            $cart['user_shopper_group_id'] = \RedshopHelperUser::getShopperGroup($user->id);
        }
    }
}