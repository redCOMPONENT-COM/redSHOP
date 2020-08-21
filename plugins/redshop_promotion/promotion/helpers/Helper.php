<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

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
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    public static function getConditionProductAmount(&$promotion, &$cart) {
        $conditionAmount = false;
        $conditionManufacturer = isset($promotion->manufacturer)? false: true;
        $conditionCategory = isset($promotion->category)? false: true;
        $conditionProduct = isset($promotion->product)? false: true;
        $count = 0;

        for ($i = 0; $i < $cart['idx']; $i++) {
            $product = \Redshop\Product\Product::getProductById($cart[$i]['product_id']);

            if (isset($promotion->product) && in_array($product->product_id, $promotion->product)) {
                $conditionProduct = true;
                $count += $cart[$i]['quantity'];
            }

            if (isset($promotion->category) && in_array($product->category_id, $promotion->category)) {
                $conditionCategory = true;
                $count += $cart[$i]['quantity'];
            }

            if (isset($promotion->manufacturer) && in_array($product->manufacturer_id, $promotion->manufacturer)) {
                $conditionManufacturer = true;
                $count += $cart[$i]['quantity'];
            }
        }

        if ($count >= $promotion->condition_amount) {
            $conditionAmount = true;
        }

        $conditionTime = self::checkValidTimePromotion($promotion);

        return $conditionAmount && $conditionProduct && $conditionManufacturer && $conditionCategory && $conditionTime;
    }

    /**
     * @param $promotion
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    protected static function checkValidTimePromotion($promotion) {
        $today = date('yy-m-d');

        $isGreaterThanOrEqualFromDate = true;
        $isLessThanOrEqualToDate = true;

        if (!empty($promotion->from_date)) {
            $isGreaterThanOrEqualFromDate = $today >= $promotion->from_date;
        }
        if (!empty($promotion->to_date)) {
            $isLessThanOrEqualToDate = $today <= $promotion->to_date;
        }

        return $isGreaterThanOrEqualFromDate && $isLessThanOrEqualToDate;
    }

    /**
     * @param $promotion
     * @param $cart
     * @return |null
     * @since  __DEPLOY_VERSION__
     */
    public static function getConditionOrderVolume(&$promotion, &$cart) {
        $orderVolume = $promotion->order_volume ?? 0;
        $conditionOrderVolume = false;

        if ($orderVolume > 0) {
            $operand = '<=';

            switch (trim($operand)) {
                case '<=':
                    $conditionOrderVolume = $orderVolume <= Helper::getCartSubTotalExcludeVAT($cart);
                    break;
                default:
                    break;
            }
        }

        $conditionTime = self::checkValidTimePromotion($promotion);

        return $conditionOrderVolume && $conditionTime;
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

    /**
     * @param $cart
     *
     * @since __DEPLOY_VERSION__
     */
    public static function backupShippingCartInfo(&$cart) {
        $cart['free_shipping_before_promotion'] = $cart['free_shipping'];
        $cart['shipping_before_promotion'] = $cart['shipping'];
        $cart['shipping_tax_before_promotion'] = $cart['shipping_tax'];
    }

    /**
     * @param $cart
     *
     * @since __DEPLOY_VERSION__
     */
    public static function setCartFreeShipping(&$cart) {
        $cart['free_shipping'] = 1;
        $cart['shipping'] = 0;
        $cart['shipping_tax'] = 0;

        # Recalculation for sub & total
        $cart['subtotal'] -= $cart['shipping_before_promotion'] + $cart['shipping_tax_before_promotion'];
        $cart['total'] -= $cart['shipping_before_promotion'] + $cart['shipping_tax_before_promotion'];
    }

    /**
     * @param $promotion_id
     * @param $award_id
     * @param $amount
     *
     * @return array
     *
     * @since __DEPLOY_VERSION__
     */
    public static function prepareProductAward($promotion_id, $award_id, $amount) {
        $product = \Redshop\Product\Product::getProductById($promotion_id, $award_id, $amount);

        return [
            'hidden_attribute_cartimage' => '',
            'product_price_excl_vat' => 0.0,
            'subscription_id' => 0,
            'product_vat' => 0,
            'giftcard_id' => '',
            'product_id' => $award_id,
            'discount_calc_output' => '',
            'discount_calc' => [],
            'product_price' => 0.0,
            'product_old_price' => 0.0,
            'product_old_price_excl_vat' => 0.0,
            'cart_attribute' => [],
            'cart_accessory' => [],
            'quantity' => $amount ?? 1,
            'category_id' => $product->category_id ?? 0,
            'wrapper_id' => 0,
            'wrapper_price' => 0.0,
            'isPromotionAward' => true,
            'promotion_id' => $promotion_id
        ];
    }

    /**
     * @param $cartItem
     * @param $promotion
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    public static function isApplyingPromotion($cartItem, $promotion) {
        return isset($cartItem['promotion_id'])
            && isset($promotion->id)
            && ($cartItem['promotion_id'] == $promotion->id);
    }

    /**
     * @param $cart
     * @param $i
     * @param $promotion
     * @since __DEPLOY_VERSION__
     */
    public static function removingPromotion(&$cart, $i, &$promotion) {
        $promotion->isApplied = false;

        if (isset($cart[$i + 1])) {
            $cart[$i] = $cart[$i + 1];
        } else {
            unset($cart[$i]);
        }
    }

    /**
     * @param $data
     * @param $assoc
     * @return false|string
     * @since  __DEPLOY_VERSION__
     */
    public static function decrypt($data, $assoc = false) {
        return json_decode(base64_decode($data), $assoc);
    }

    /**
     * @param $data
     * @return string
     * @since __DEPLOY_VERSION__
     */
    public static function encrypt($data) {
        return base64_encode(json_encode($data));
    }

    /**
     * @param $promotions
     * @param $i
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    public static function mergingPromotionDistanceIntoCart(&$promotions, $i, &$cart) {
        $flag = true;

        for ($n = 0; $n < count($cart['promotions']); $n++) {
            if ($promotions[$i]->id == $cart['promotions'][$n]->id) {
                $flag = false;
            }
        }

        if ($flag == true) {
            $cart['promotions'][] = $promotions[$i];
        }
    }

    /**
     * @param $promotion
     * @return stdClass
     * @since __DEPLOY_VERSION__
     */
    public static function prepareDataForPromotion(&$promotion) {
        $data = new \stdClass();

        if (isset($promotion->data)) {
            $data =& $promotion->data;
        }

        $data->promotion_type = $data->promotion_type ?? '';

        return $data;
    }
}