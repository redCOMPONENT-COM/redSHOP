<?php
/**
 * @package     RedSHOP
 * @subpackage  Discount
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Discount class
 *
 * @since  2.0.3
 */
class RedshopHelperDiscount
{
    /**
     * Method for get discount
     *
     * @param   int  $subTotal  Sub-total amount
     * @param   int  $userId    User ID
     *
     * @return  mixed
     *
     * @since  2.0.3
     */
    public static function getDiscount($subTotal = 0, $userId = 0)
    {
        $user = JFactory::getUser();

        if (!$userId) {
            $userId = $user->id;
        }

        $userData       = RedshopHelperUser::createUserSession($userId);
        $shopperGroupId = (int)$userData['rs_user_shopperGroup'];

        $shopperGroupDiscounts = RedshopEntityShopper_Group::getInstance($shopperGroupId)->getDiscounts();

        if ($shopperGroupDiscounts->isEmpty()) {
            return false;
        }

        $result      = false;
        $currentTime = time();

        foreach ($shopperGroupDiscounts->getAll() as $discount) {
            /** @var RedshopEntityDiscount $discount */
            $potentialDiscount = null;

            // Skip if this discount is not published
            if (!$discount->get('published', false)) {
                continue;
            }

            $startDate = $discount->get('start_date', 0);
            $endDate   = $discount->get('end_date', 0);
            $condition = $discount->get('condition', 0);
            $amount    = $discount->get('amount', 0);

            /**
             * Discount condition
             * 1. Start date and End date not set
             * 2. Had only start date and start date smaller than current time.
             * 3. Had only end date and end date higher than current time.
             * 4. Start date exist and smaller than current time. End date exist and higher than current time.
             */
            if ((!$startDate && !$endDate)
                || ($startDate && !$endDate && $startDate <= $currentTime)
                || (!$startDate && $endDate && $endDate >= $currentTime)
                || ($startDate && $startDate <= $currentTime && $endDate && $endDate >= $currentTime)) {
                if (($condition == 1 && $amount > $subTotal)
                    || ($condition == 2 && $amount == $subTotal)
                    || ($condition == 3 && $amount < $subTotal)) {
                    $potentialDiscount = $discount;
                } else {
                    continue;
                }
            } else {
                continue;
            }

            if (false === $result || $result->get('amount') > $potentialDiscount->get('amount')) {
                $result = $potentialDiscount;
            }
        }

        return $result;
    }

    /**
     * Get discount price from product with check discount date.
     *
     * @param   int  $productId  Product id
     *
     * @return  float
     *
     * @since   2.0.7
     */
    public static function getDiscountPriceBaseDiscountDate($productId)
    {
        $productData = RedshopHelperProduct::getProductById($productId);

        if (empty($productData)) {
            return 0.0;
        }

        $today = time();

        // Convert discount_enddate to middle night
        $productData->discount_enddate = RedshopHelperDatetime::generateTimestamp($productData->discount_enddate);

        if (Redshop::getConfig()->getInt('DISCOUNT_ENABLE') == 0) {
            $productData->discount_price = 0;
        } else {
            if (($productData->discount_enddate == '0' && $productData->discount_stratdate == '0')
                || ((int)$productData->discount_enddate >= $today && (int)$productData->discount_stratdate <= $today)
                || ($productData->discount_enddate == '0' && (int)$productData->discount_stratdate <= $today)) {
                return (float)$productData->discount_price;
            }
        }

        return 0.0;
    }

    /**
     * Add GiftCard To Cart
     *
     * @param   array  $cartItem  Cart item
     * @param   array  $data      User cart data
     *
     * @return  void
     *
     * @since   2.1.0
     */
    public static function addGiftCardToCart(&$cartItem, $data)
    {
        $cartItem['giftcard_id']     = $data['giftcard_id'];
        $cartItem['reciver_email']   = $data['reciver_email'];
        $cartItem['reciver_name']    = $data['reciver_name'];
        $cartItem['customer_amount'] = "";

        if (isset($data['customer_amount'])) {
            $cartItem['customer_amount'] = $data['customer_amount'];
        }

        $giftCard      = RedshopEntityGiftcard::getInstance($data['giftcard_id'])->getItem();
        $giftCardPrice = $giftCard && $giftCard->customer_amount ? $cartItem['customer_amount'] : $giftCard->giftcard_price;

        $fields = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD);

        foreach ($fields as $field) {
            $dataTxt = (isset($data[$field->name])) ? $data[$field->name] : '';
            $tmpText = strpbrk($dataTxt, '`');

            if ($tmpText) {
                $tmpData = explode('`', $dataTxt);

                if (is_array($tmpData)) {
                    $dataTxt = implode(",", $tmpData);
                }
            }

            $cartItem[$field->name] = $dataTxt;
        }

        $cartItem['product_price']          = $giftCardPrice;
        $cartItem['product_price_excl_vat'] = $giftCardPrice;
        $cartItem['product_vat']            = 0;
        $cartItem['product_id']             = '';
    }

    /**
     * Re-calculate the Voucher/Coupon value when the product is already discount
     *
     * @param   float  $value  Voucher/Coupon value
     * @param   array  $cart   Cart array
     *
     * @return  float          Voucher/Coupon value
     *
     * @since   2.1.0
     */
    public static function calculateAlreadyDiscount($value, $cart)
    {
        $idx = 0;

        if (isset($cart['idx'])) {
            $idx = $cart['idx'];
        }

        $percent = ($value * 100) / $cart['product_subtotal'];

        for ($i = 0; $i < $idx; $i++) {
            $productPriceArray = RedshopHelperProductPrice::getNetPrice($cart[$i]['product_id']);

            // If the product is already discount
            if ($productPriceArray['product_price_saving_percentage'] > 0 && empty($cart[$i]['cart_attribute'])) {
                $amount = $percent * $productPriceArray['product_price'] / 100;
                $value  -= $amount * $cart[$i]['quantity'];
            }
        }

        return $value < 0 ? 0 : $value;
    }

    /**
     * Method for calculate discount.
     *
     * @param   string  $type   Type of discount
     * @param   array   $types  List of type
     *
     * @return  float
     *
     * @since   2.1.0
     */
    public static function calculate($type, $types)
    {
        if (empty($types)) {
            return 0;
        }

        $value    = $type == 'voucher' ? 'voucher_value' : 'coupon_value';
        $discount = 0;

        $idx = count($types);

        for ($i = 0; $i < $idx; $i++) {
            $discount += $types[$i][$value];
        }

        return $discount;
    }

    /**
     * Method for modify discount
     *
     * @param   array  $cart  Cart data.
     *
     * @return  mixed
     *
     * @throws  Exception
     * @since __DEPLOY_VERSION__
     */
    public static function modifyDiscount($cart)
    {
        $calculations                      = \Redshop\Cart\Helper::calculation($cart);
        $cart['product_subtotal']          = $calculations[1];
        $cart['product_subtotal_excl_vat'] = $calculations[2];

        $couponIndex  = !empty($cart['coupon']) && is_array($cart['coupon']) ? count($cart['coupon']) : 0;
        $voucherIndex = !empty($cart['voucher']) && is_array($cart['voucher']) ? count($cart['voucher']) : 0;

        $discountAmount = 0;

        if (Redshop::getConfig()->getBool('DISCOUNT_ENABLE')) {
            $discountAmount = Redshop\Cart\Helper::getDiscountAmount($cart);

            if ($discountAmount > 0) {
                $cart = RedshopHelperCartSession::getCart();
            }
        }

        if (!isset($cart['quotation_id']) || (isset($cart['quotation_id']) && !$cart['quotation_id'])) {
            $cart['cart_discount'] = $discountAmount;
        }

        // Calculate voucher discount
        $voucherDiscount = 0;

        if (array_key_exists('voucher', $cart)) {
            if (count($cart['voucher']) > 1) {
                foreach ($cart['voucher'] as $cartVoucher) {
                    $voucherDiscount += $cartVoucher['voucher_value'];
                }
            } else {
                if (!empty($cart['voucher'][0]['voucher_value'])) {
                    $voucherDiscount = $cart['voucher'][0]['voucher_value'];
                } else {
                    for ($v = 0; $v < $voucherIndex; $v++) {
                        $voucherCode = $cart['voucher'][$v]['voucher_code'];

                        unset($cart['voucher'][$v]);

                        $cart = RedshopHelperCartDiscount::applyVoucher($cart, $voucherCode);
                    }

                    $voucherDiscount = RedshopHelperDiscount::calculate('voucher', $cart['voucher']);

                    $voucherDiscount = empty($voucherDiscount) ? $cart['voucher_discount'] : $voucherDiscount;
                }
            }
        }

        $cart['voucher_discount'] = $voucherDiscount;

        // Calculate coupon discount
        $couponDiscount = 0;

        if (array_key_exists('coupon', $cart)) {
            if (count($cart['coupon']) > 1) {
                foreach ($cart['coupon'] as $cartCoupon) {
                    $couponDiscount += $cartCoupon['coupon_value'];
                }
            } else {
                if (!empty($cart['coupon'][0]['coupon_value']) && (int)Redshop::getConfig()->get(
                        'DISCOUNT_TYPE'
                    ) !== 2) {
                    $couponDiscount = $cart['coupon'][0]['coupon_value'];
                } else {
                    for ($c = 0; $c < $couponIndex; $c++) {
                        $couponCode = $cart['coupon'][$c]['coupon_code'];

                        unset($cart['coupon'][$c]);

                        $cart = RedshopHelperCartDiscount::applyCoupon($cart, $couponCode);
                    }

                    $couponDiscount = RedshopHelperDiscount::calculate('coupon', $cart['coupon']);

                    $couponDiscount = empty($couponDiscount) ? $cart['coupon_discount'] : $couponDiscount;
                }
            }
        }

        $cart['coupon_discount'] = $couponDiscount;

        $codeDiscount  = $voucherDiscount + $couponDiscount;
        $totalDiscount = $cart['cart_discount'] + $codeDiscount;

        $calculations      = \Redshop\Cart\Helper::calculation((array)$cart);
        $tax         = $calculations[5];
        $discountVAT = 0;
        $chktag      = RedshopHelperCart::taxExemptAddToCart();

        if (Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT') && !empty($chktag)) {
            if (Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT')) {
                $cart['tax_after_discount'] = $tax;
            } else {
                $vatData = RedshopHelperUser::getVatUserInformation();

                if (!empty($vatData->tax_rate)) {
                    $productPriceExclVAT = (float)$cart['product_subtotal_excl_vat'];
                    $productVAT          = (float)$cart['product_subtotal'] - $cart['product_subtotal_excl_vat'];

                    if ($productPriceExclVAT > 0) {
                        $avgVAT      = (($productPriceExclVAT + $productVAT) / $productPriceExclVAT) - 1;
                        $discountVAT = ($avgVAT * $totalDiscount) / (1 + $avgVAT);
                    }
                }
            }
        }

        $cart['total'] = $calculations[0] - $totalDiscount;
        $cart['total'] = $cart['total'] < 0 ? 0 : $cart['total'];

        $cart['subtotal'] = $calculations[1] + $calculations[3] - $totalDiscount;
        $cart['subtotal'] = $cart['subtotal'] < 0 ? 0 : $cart['subtotal'];

        $cart['subtotal_excl_vat'] = $calculations[2] + ($calculations[3] - $calculations[6]) - ($totalDiscount - $discountVAT);
        $cart['subtotal_excl_vat'] = $cart['total'] <= 0 ? 0 : $cart['subtotal_excl_vat'];

        $cart['product_subtotal']          = $calculations[1];
        $cart['product_subtotal_excl_vat'] = $calculations[2];
        $cart['shipping']                  = $calculations[3];
        $cart['tax']                       = $tax;
        $cart['sub_total_vat']             = $tax + $calculations[6];
        $cart['discount_vat']              = $discountVAT;
        $cart['shipping_tax']              = $calculations[6];
        $cart['discount_ex_vat']           = $totalDiscount - $discountVAT;
        $cart['mod_cart_total']            = Redshop\Cart\Module::calculate((array)$cart);

        \JFactory::getSession()->set('cart', $cart);

        return $cart;
    }
}
