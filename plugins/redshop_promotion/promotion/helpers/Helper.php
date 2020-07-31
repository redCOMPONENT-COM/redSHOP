<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once ('Helper.php');

/**
 * Plugin Promotion Helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param $promotion
     * @param $cart
     * @return |null
     * @since  __DEPLOY_VERSION__
     */
    public static function getConditionOrderVolume(&$promotion, &$cart) {
        $orderVolume = $promotion->order_volume ?? 0;
        $condition = false;

        if ($orderVolume > 0) {
            $operand = '<=';

            switch (trim($operand)) {
                case '<=':
                    $condition = $orderVolume <= Helper::getCartSubTotalExcludeVAT($cart);
                    break;
                default:
                    break;
            }
        }

        return $condition;
    }

    /**
     * @param $cart
     * @return float
     * @since  __DEPLOY_VERSION__
     */
    public static function getCartSubTotalExcludeVAT(&$cart) {
        return $cart['product_subtotal_excl_vat'] ?? 0.0;
    }

    /**
     * @param $promotion
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    public static function isPromotionApplied(&$promotion) {
        return $promotion->isApplied ?? false;
    }
}