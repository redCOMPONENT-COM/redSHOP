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
 * Cart helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Step
{
    public static function applyPromotion(&$promotion, &$cart) {
        $data = new \stdClass();

        if (isset($promotion->data)) {
            $data =& $promotion->data;
        }

        $data->promotion_type = $data->promotion_type ?? '';

        /*
         * Step 1: check is promotion applied or not
         * Step 2:  if Yes, check is satisfied condition, if NO, remove and mark it's not applied
         *          else No, check is satisfied condition, if YES, add up and mark it's applied
         */

        $promotion->isApplied = $promotion->isApplied ?? false;

        switch($data->promotion_type) {
            case 'amount_product':
                $condition = Helper::getConditionProductAmount($data, $cart);

                if (!$promotion->isApplied && $condition) {
                    self::applyPromotionProductAmount($promotion, $cart);
                }

                if ($promotion->isApplied && !$condition) {
                    self::removeAppliedPromotion($promotion, $cart);
                }

                break;
            case 'volume_order':
                $condition = Helper::getConditionOrderVolume($data, $cart);

                if (!$promotion->isApplied && $condition) {
                    self::applyPromotionVolumeOrder($promotion, $cart);
                }

                if ($promotion->isApplied && !$condition) {
                    self::removeAppliedPromotion($promotion, $cart);
                }

                break;
            default:
                break;
        }
    }

    /**
     * @param $promotion
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    public static function applyPromotionProductAmount(&$promotion, &$cart) {
        self::applyPromotionVolumeOrder($promotion,$cart);
    }

    /**
     * @param $promotion
     * @param $cart
     * @since  __DEPLOY_VERSION__
     */
    protected static function applyPromotionVolumeOrder(&$promotion, &$cart) {
        $promotion->isApplied = true;
        $idx = $cart['idx']++;
        $productAwardId = $promotion->data->product_award ?? 0;
        $productAwardAmount = $promotion->data->award_amount ?? 0;
        $cart[$idx] = Helper::prepareProductAward($promotion->id, $productAwardId, $productAwardAmount);
        self::applyPromotionFreeShipping($promotion->data, $cart);
        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * @param $promotion
     * @param $cart
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    protected static function applyPromotionFreeShipping(&$promotion, &$cart) {
        if (!empty($promotion->free_shipping) && ($promotion->free_shipping == 'true')) {
            # Save current value of shipping & tax
            Helper::backupShippingCartInfo($cart);

            # Set free shipping
            Helper::setCartFreeShipping($cart);

            return true;
        }

        return false;
    }

    /**
     * @param $promotion
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    public static function removeAppliedPromotion(&$promotion, &$cart) {
        $promotion->isApplied = false;
        $unCount = 0;
        for ($i = 0; $i < $cart['idx']; $i++) {

            while (isset($cart[$i]['promotion_id']) &&
                ($cart[$i]['promotion_id'] == $promotion->id)) {
                $unCount++;
                $promotion->isApplied = false;

                if (isset($cart[$i + 1])) {
                    $cart[$i] = $cart[$i + 1];
                } else {
                    unset($cart[$i]);
                }
            }
        }

        if ($unCount > 0) {
            $cart['idx'] = $cart['idx'] - $unCount;
        }

        self::removePromotionFreeShipping($promotion, $cart);
        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * @param $promotion
     * @param $cart
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    protected static function removePromotionFreeShipping(&$promotion, &$cart) {
        $cart['free_shipping'] = $cart['free_shipping_before_promotion'] ?? $cart['free_shipping'];
        $cart['shipping'] = $cart['shipping_before_promotion'] ?? $cart['shipping'];
        $cart['shipping_tax'] = $cart['shipping_tax_before_promotion'] ?? $cart['shipping_tax'];

        if (!empty($cart['shipping_before_promotion']) && !empty($cart['shipping_tax_before_promotion']))
        {
            $cart['subtotal'] += $cart['shipping_before_promotion'] + $cart['shipping_tax_before_promotion'];
            $cart['total'] += $cart['shipping_before_promotion'] + $cart['shipping_tax_before_promotion'];
        }
    }

    /**
     * @param $cart
     * @return array|mixed
     * @since  __DEPLOY_VERSION__
     */
    public static function getPromotionsFromCart(&$cart) {
        $cart['promotions'] = $cart['promotions'] ?? [];

        return $cart['promotions'];
    }
}