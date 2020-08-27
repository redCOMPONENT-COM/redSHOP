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
 * Cart class
 *
 * @since  2.1.0
 */
class Cart
{
    /**
     * Method for modify cart data.
     *
     * @param array $cart Cart data.
     * @param integer $userId User ID
     *
     * @return  array
     *
     * @since   2.1.0
     */
    public static function modify($cart = array(), $userId = 0)
    {
        $cart = $cart ?? \Redshop\Cart\Helper::getCart();
        $userId = !$userId ? \JFactory::getUser()->id : $userId;
        $cart['user_id'] = $userId;

        $idx = $cart['idx'] ?? 0;

        if (!$idx) {
            return $cart;
        }

        \JPluginHelper::importPlugin('redshop_product');

        for ($i = 0; $i < $idx; $i++) {
            // Skip if this is giftcard
            if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'] > 0) {
                continue;
            }

            # REDSHOP-6083
            $isPromotionAward = false;
            $checks = \Redshop\Plugin\Helper::invoke('redshop_promotion',
                null, 'isProductAwardByPromotion', [$cart[$i]]);

            if (count($checks) > 0) {
                foreach ($checks as $c) {
                    $isPromotionAward = $isPromotionAward || $c;
                }
            }

            if ($isPromotionAward == true) {
                continue;
            }
            # END REDSHOP-6083

            $productId = $cart[$i]['product_id'] ?? 0;
            $quantity = $cart[$i]['quantity'] ?? 0;
            $product = \Redshop\Product\Product::getProductById($productId);
            $hasAttribute = isset($cart[$i]['cart_attribute']) ? true : false;

            // Attribute price
            $price = 0;

            if (!isset($cart['quotation'])) {
                $cart['quotation'] = 0;
            }

            if ((\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || $cart['quotation'] == 1) && !$hasAttribute) {
                $price = $cart[$i]['product_price_excl_vat'];
            }

            if (isset($product->use_discount_calc)) {
                $price = $cart[$i]['discount_calc_price'] ?? 0;
            }

            // Only set price without vat for accessories as product
            $accessoryHasProductWithoutVat = '';

            if (isset($cart['AccessoryAsProduct'])) {
                // Accessory price fix during update
                $accessoryAsProduct = \RedshopHelperAccessory::getAccessoryAsProduct($cart['AccessoryAsProduct']);

                if (isset($accessoryAsProduct->accessory)
                    && isset($accessoryAsProduct->accessory[$cart[$i]['product_id']])
                    && isset($cart[$i]['accessoryAsProductEligible'])) {
                    $accessoryHasProductWithoutVat = '{without_vat}';

                    $accessoryPrice = (float)$accessoryAsProduct->accessory[$cart[$i]['product_id']]->newaccessory_price;
                    $price = \RedshopHelperProductPrice::priceRound($accessoryPrice);
                    $cart[$i]['product_price_excl_vat'] = \RedshopHelperProductPrice::priceRound($accessoryPrice);
                }
            }

            $retAttArr = \RedshopHelperProduct::makeAttributeCart(
                $cart[$i]['cart_attribute'] ?? [],
                (int)($product->product_id ?? 0),
                $userId,
                $price,
                $quantity,
                $accessoryHasProductWithoutVat
            );

            $accessoryAsProductZero = !count($retAttArr[8]) && $price == 0 && !empty($accessoryHasProductWithoutVat);

            // Product + attribute (price)
            $getProductPrice = ($accessoryAsProductZero) ? 0 : $retAttArr[1];

            // Product + attribute (VAT)
            $getProductTax = ($accessoryAsProductZero) ? 0 : $retAttArr[2];
            $productOldPriceNoVat = ($accessoryAsProductZero) ? 0 : $retAttArr[5];

            // Accessory calculation
            $accessories = \RedshopHelperProduct::makeAccessoryCart(
                isset($cart[$i]['cart_accessory']) ? $cart[$i]['cart_accessory'] : array(),
                $product->product_id ?? 0,
                $userId
            );

            // Accessory + attribute (price)
            $accessoryPrice = $accessories[1];

            // Accessory + attribute (VAT)
            $accessoryTax = $accessories[2];

            $productOldPriceNoVat += $accessories[1];

            // ADD WRAPPER PRICE
            $wrapperVat = 0;
            $wrapperPrice = 0;

            if (isset($cart[$i]['wrapper_id'])) {
                $wrappers = \Redshop\Wrapper\Helper::getWrapperPrice(
                    [
                        'product_id' => $cart[$i]['product_id'] ?? 0,
                        'wrapper_id' => $cart[$i]['wrapper_id'] ?? 0
                    ]
                );

                $wrapperVat = $wrappers['wrapper_vat'] ?? 0;
                $wrapperPrice = $wrappers['wrapper_price'] ?? 0;

                $productOldPriceNoVat += $wrapperPrice;
            }

            $productPrice = $accessoryPrice + $getProductPrice + $getProductTax + $accessoryTax + $wrapperPrice + $wrapperVat;
            $productVat = ($getProductTax + $accessoryTax + $wrapperVat);
            $productPriceNoVat = ($getProductPrice + $accessoryPrice + $wrapperPrice);

            if (isset($product->product_type) && $product->product_type == 'subscription') {
                if (!isset($cart[$i]['subscription_id']) || empty($cart[$i]['subscription_id'])) {
                    return array();
                }

                $subscription = \RedshopHelperProduct::getProductSubscriptionDetail(
                    $productId,
                    $cart[$i]['subscription_id']
                );
                $subscriptionVat = 0;
                $subscriptionPrice = $subscription->subscription_price;

                if ($subscriptionPrice) {
                    $subscriptionVat = \RedshopHelperProduct::getProductTax(
                        $product->product_id,
                        $subscriptionPrice
                    );
                }

                $productPrice = $productPrice + $subscriptionPrice + $subscriptionVat;

                $productVat += $subscriptionVat;
                $productPriceNoVat += $subscriptionPrice;
                $productOldPriceNoVat += $subscriptionPrice + $subscriptionVat;
            }

            // Set product price
            if ($productPrice < 0) {
                $productPrice = 0;
            }

            $cart[$i]['product_old_price_excl_vat'] = $productOldPriceNoVat;
            $cart[$i]['product_price_excl_vat'] = $productPriceNoVat;
            $cart[$i]['product_vat'] = $productVat;
            $cart[$i]['product_price'] = $productPrice;

            \RedshopHelperUtility::getDispatcher()->trigger('onBeforeLoginCartSession', array(&$cart, $i));
        }

        unset($cart[$idx]);

        return $cart;
    }

    /**
     * Method for add product to cart
     *
     * @param array $post Product data
     *
     * @return  mixed         True on success. Error message string if fail.
     * @throws  \Exception
     *
     * @since   __DEPLOY_VERSION__
     */
    public static function add($post = [])
    {
        $cart = \Redshop\Cart\Helper::getCart();
        \Redshop\Workflow\Cart\Init::cart($cart);
        \Redshop\Workflow\Cart\Init::post($post);
        \Redshop\Plugin\Helper::invoke('redshop_product',
            '', 'onBeforeAddProductToCart', [&$post]);

        $result = !empty($post['giftcard_id'])
            ? \Redshop\Workflow\Cart\Add::giftCard($cart, (int)$cart['idx'], $post)
            : \Redshop\Workflow\Cart\Add::product($cart, (int)$cart['idx'], $post);

        if (true !== $result) {
            return $result;
        }

        \Redshop\Cart\Helper::setCart($cart);
        return true;
    }

    /**
     * Method check different country and state
     *
     * @param object $rate
     * @param integer $user_id
     * @param integer $users_info_id
     * @param array $post
     *
     * @return  boolean
     *
     * @since   2.1.4
     */
    public static function isDiffCountryState($rate, $users_info_id = 0, $post = array())
    {
        if ($users_info_id) {
            $shippingAddresses = \RedshopHelperShipping::getShippingAddress($users_info_id);

            $stateCode = $shippingAddresses->state_code;
            $countryCode = $shippingAddresses->country_code;
        } else {
            $anonymous = $post['anonymous'];

            if ($anonymous['bill_is_ship']) {
                $stateCode = $anonymous['BT']['state_code'];
                $countryCode = $anonymous['BT']['country_code'];
            } else {
                $stateCode = $anonymous['ST']['state_code'];
                $countryCode = $anonymous['ST']['country_code'];
            }
        }

        if ((!empty($rate->shipping_rate_country) && $rate->shipping_rate_country != $countryCode) ||
            (!empty($rate->shipping_rate_state) && $rate->shipping_rate_state != $stateCode)) {
            return true;
        }

        return false;
    }
}
