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
     * @param   array    $cart    Cart data.
     * @param   integer  $userId  User ID
     *
     * @return  array
     *
     * @since   2.1.0
     */
    public static function modify($cart = array(), $userId = 0)
    {
        $cart            = $cart ?? \Redshop\Cart\Helper::getCart();
        $userId          = !$userId ? \JFactory::getUser()->id : $userId;
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

            $productId    = $cart[$i]['product_id'] ?? 0;
            $quantity     = $cart[$i]['quantity'] ?? 0;
            $product      = \Redshop\Product\Product::getProductById($productId);
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

                    $accessoryPrice                     = (float)$accessoryAsProduct->accessory[$cart[$i]['product_id']]->newaccessory_price;
                    $price                              = \RedshopHelperProductPrice::priceRound($accessoryPrice);
                    $cart[$i]['product_price_excl_vat'] = \RedshopHelperProductPrice::priceRound($accessoryPrice);
                }
            }

            $attributeCart = \RedshopHelperProduct::makeAttributeCart(
                $cart[$i]['cart_attribute'] ?? [],
                (int)($product->product_id ?? 0),
                $userId,
                $price,
                $quantity,
                $accessoryHasProductWithoutVat
            );

            $accessoryAsProductZero = !count(
                    $attributeCart[8]
                ) && $price == 0 && !empty($accessoryHasProductWithoutVat);

            // Product + attribute (price)
            $getProductPrice = ($accessoryAsProductZero) ? 0 : $attributeCart[1];

            // Product + attribute (VAT)
            $getProductTax        = ($accessoryAsProductZero) ? 0 : $attributeCart[2];
            $productOldPriceNoVat = ($accessoryAsProductZero) ? 0 : $attributeCart[5];

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
            $wrapperVat   = 0;
            $wrapperPrice = 0;

            if (isset($cart[$i]['wrapper_id'])) {
                $wrappers = \Redshop\Wrapper\Helper::getWrapperPrice(
                    [
                        'product_id' => $cart[$i]['product_id'] ?? 0,
                        'wrapper_id' => $cart[$i]['wrapper_id'] ?? 0
                    ]
                );

                $wrapperVat   = $wrappers['wrapper_vat'] ?? 0;
                $wrapperPrice = $wrappers['wrapper_price'] ?? 0;

                $productOldPriceNoVat += $wrapperPrice;
            }

            $productPrice      = $accessoryPrice + $getProductPrice + $getProductTax + $accessoryTax + $wrapperPrice + $wrapperVat;
            $productVat        = ($getProductTax + $accessoryTax + $wrapperVat);
            $productPriceNoVat = ($getProductPrice + $accessoryPrice + $wrapperPrice);

            if (isset($product->product_type) && $product->product_type == 'subscription') {
                if (!isset($cart[$i]['subscription_id']) || empty($cart[$i]['subscription_id'])) {
                    return array();
                }

                $subscription      = \RedshopHelperProduct::getProductSubscriptionDetail(
                    $productId,
                    $cart[$i]['subscription_id']
                );
                $subscriptionVat   = 0;
                $subscriptionPrice = $subscription->subscription_price;

                if ($subscriptionPrice) {
                    $subscriptionVat = \RedshopHelperProduct::getProductTax(
                        $product->product_id,
                        $subscriptionPrice
                    );
                }

                $productPrice = $productPrice + $subscriptionPrice + $subscriptionVat;

                $productVat           += $subscriptionVat;
                $productPriceNoVat    += $subscriptionPrice;
                $productOldPriceNoVat += $subscriptionPrice + $subscriptionVat;
            }

            // Set product price
            if ($productPrice < 0) {
                $productPrice = 0;
            }

            $cart[$i]['product_old_price_excl_vat'] = $productOldPriceNoVat;
            $cart[$i]['product_price_excl_vat']     = $productPriceNoVat;
            $cart[$i]['product_vat']                = $productVat;
            $cart[$i]['product_price']              = $productPrice;

            \RedshopHelperUtility::getDispatcher()->trigger('onBeforeLoginCartSession', array(&$cart, $i));
        }

        unset($cart[$idx]);

        return $cart;
    }

    /**
     * Method for add product to cart
     *
     * @param   array  $data  Product data
     *
     * @return  mixed         True on success. Error message string if fail.
     * @throws  \Exception
     *
     * @since   2.1.0
     */
    public static function addProduct($data = [])
    {
        /**
         * Step 1: validate data for add to cart
         */
        if (!is_array($data) || count($data) == 0) {
            return false;
        }

        /**
         * Step 2. Add GCard or Product to cart
         */
        $result = \Redshop\Cart\Helper::addItemToCart($data);

        if ($result !== true) {
            return $result;
        }

        /**
         * Step 3. init discount for cart ['discount', 'discount_type', 'cart_discount']
         */
        \Redshop\Cart\Helper::initDiscountForCart();

        /**
         * Step 4. Init Shopper Group for cart [user_shopper_group_id]
         */
        \Redshop\Cart\Helper::initShopperGroupForCart();

        /**
         * Step 5. Init Shipping.
         */
        \Redshop\Cart\Helper::initShippingForCart();

        return true;
    }

    /**
     * Method for add product giftcard to cart
     *
     * @param   array    $cart  Cart data
     * @param   integer  $idx   Index of cart
     * @param   array    $data  Data of product
     *
     * @return  void
     *
     * @since   2.1.0
     */
    public static function addGiftCardProduct(&$cart, $idx, $data = array())
    {
        $sameGiftCard = false;
        $section      = \RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD;
        $rows         = \RedshopHelperExtrafields::getSectionFieldList($section);

        for ($g = 0; $g < $idx; $g++) {
            if ($cart[$g]['giftcard_id'] == $data['giftcard_id']
                && $cart[$g]['reciver_email'] == $data['reciver_email']
                && $cart[$g]['reciver_name'] == $data['reciver_name']) {
                $sameGiftCard = true;

                // Product user field
                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        $productUserField = $row->name;

                        if (isset($cart[$g][$productUserField]) && $data[$productUserField] != $cart[$g][$productUserField]) {
                            $sameGiftCard = false;
                            break;
                        }
                    }
                }

                if (!$sameGiftCard) {
                    continue;
                }

                $cart[$g]['quantity'] += $data['quantity'];
                \RedshopHelperDiscount::addGiftCardToCart($cart[$g], $data);
            }
        }

        if (!$sameGiftCard) {
            $cart[$idx] = array(
                'quantity' => $data['quantity']
            );
            \RedshopHelperDiscount::addGiftCardToCart($cart[$idx], $data);
        }

        $cart['idx'] = $idx + 1;

        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * Method for add normal product to cart
     *
     * @param   array    $cart  Cart data
     * @param   integer  $idx   Index of cart
     * @param   array    $data  Data of product
     *
     * @return  mixed
     * @throws  \Exception
     *
     * @since   __DEPLOY_VERSION__
     */
    public static function addNormalProduct(&$cart, $idx, $data = array())
    {
        $section                                  = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD;
        $rows                                     = \RedshopHelperExtrafields::getSectionFieldList($section);
        $cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage']
            ? $data['hidden_attribute_cartimage'] : null;
        $productId                                = $data['product_id'];
        $quantity                                 = $data['quantity'];
        $product                                  = \RedshopEntityProduct::getInstance($productId);
        $dataAdd                                  = \Redshop\Cart\Helper::handleCartAccessoryPrice(
            $data,
            $product,
            $cart
        );

        /*
         * Check if required user field are filled or not if not than redirect to product detail page...
         * Get product user field from selected product template...
         */
        if (!\Redshop::getConfig()->get('AJAX_CART_BOX')) {
            $fieldRequired = \Redshop\User\Helper::userFieldValidation($data, $dataAdd, $section);

            if ($fieldRequired != "") {
                return $fieldRequired;
            }
        }

        // Get product price
        $data['product_price'] = 0;

        // Discount calculator procedure start
        $discounts = \Redshop\Promotion\Discount::discountCalculatorData($product, $data);

        $calcOutput      = "";
        $calcOutputs     = [];
        $productVatPrice = 0;

        if (!empty($discounts)) {
            $calcOutput  = $discounts->calcOutput;
            $calcOutputs = $discounts->calcOutputs;

            // Calculate price without VAT
            $data['product_price']                = $discounts->calculatorPrice;
            $cart[$idx]['product_price_excl_vat'] = $discounts->calculatorPrice;
            $cart[$idx]['discount_calc_price']    = $discounts->calculatorPrice;

            $productVatPrice += $discounts->productNetPricesTax;
        }

        // Attribute price added
        $generateAttributeCart = isset($data['cart_attribute']) ?
            $data['cart_attribute'] : \Redshop\Cart\Helper::generateAttribute($data);

        $templateCart = \Redshop\Cart\Helper::getCartTemplate();

        $attributeCart = \RedshopHelperProduct::makeAttributeCart(
            $generateAttributeCart,
            $product->product_id,
            0,
            $data['product_price'],
            $quantity,
            $templateCart[0]->template_desc
        );

        $selectProp = \RedshopHelperProduct::getSelectedAttributeArray($data);

        if (\JFactory::getApplication()->input->getString('task') == 'reorder' && !empty($generateAttributeCart)) {
            $propertyReOrderItemArr    = array();
            $subPropertyReOrderItemArr = array();

            foreach ($generateAttributeCart as $idxRe => $itemRe) {
                if (!empty($itemRe['attribute_childs'])) {
                    $propertyReOrderItemArr[] = $itemRe['attribute_childs'][0]['property_id'];

                    if (!empty($itemRe['attribute_childs'][0]['property_childs'])) {
                        $subPropertyReOrderItemArr[] = $itemRe['attribute_childs'][0]['property_childs'][0]['subproperty_id'];
                    } else {
                        $subPropertyReOrderItemArr[] = '';
                    }
                }
            }

            $propertyReOrderItemStr    = implode('##', $propertyReOrderItemArr);
            $subPropertyReOrderItemStr = implode('##', $subPropertyReOrderItemArr);

            $dataReOrder                     = array();
            $dataReOrder['property_data']    = $propertyReOrderItemStr;
            $dataReOrder['subproperty_data'] = $subPropertyReOrderItemStr;
            $selectProp                      = \RedshopHelperProduct::getSelectedAttributeArray($dataReOrder);
        }

        $data['product_old_price']            = $attributeCart[5] + $attributeCart[6];
        $data['product_old_price_excl_vat']   = $attributeCart[5];
        $data['product_price']                = $attributeCart[1];
        $productVatPrice                      = $attributeCart[2];
        $cart[$idx]['product_price_excl_vat'] = $attributeCart[1];

        $data['product_price'] += $productVatPrice;

        if (!empty($selectProp[0])) {
            $attributeImage = $productId;

            if (count($selectProp[0]) == 1) {
                $attributeImage .= '_p' . $selectProp[0][0];
            } else {
                $productAttrImage = implode('_p', $selectProp[0]);
                $attributeImage   .= '_p' . $productAttrImage;
            }

            if (count($selectProp[1]) == 1) {
                $attributeImage .= '_sp' . $selectProp[1][0];
            } else {
                $subAttrImage = implode('_sp', $selectProp[1]);

                if ($subAttrImage) {
                    $attributeImage .= '_sp' . $subAttrImage;
                }
            }

            $cart[$idx]['attributeImage'] = $attributeImage . '.png';
        }

        if (!empty($data['reorder']) && !empty($data['attributeImage'])) {
            $cart[$idx]['attributeImage'] = $data['attributeImage'];
        }

        $selectedAttrId       = $attributeCart[3];
        $isStock              = $attributeCart[4];
        $selectedPropId       = $selectProp[0];
        $notSelectedSubPropId = $attributeCart[8];
        $productPreOrder      = $product->preorder;

        // Check for the required attributes if selected
        $handleMessage = \Redshop\Attribute\Helper::handleRequiredSelectedAttributeCartMessage(
            $data,
            $dataAdd,
            $selectedAttrId,
            $selectedPropId,
            $notSelectedSubPropId
        );

        if (!empty($handleMessage)) {
            return $handleMessage;
        }

        // Check for product or attribute in stock
        if (!$isStock) {
            if (($productPreOrder == "global" && !\Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
                || ($productPreOrder == "no") || ($productPreOrder == "" && !\Redshop::getConfig()->get(
                        'ALLOW_PRE_ORDER'
                    ))) {
                return urldecode(\JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));
            }
        }

        $cart[$idx]['subscription_id'] = 0;

        if ($product->product_type === 'subscription') {
            if (isset($data['subscription_id']) && $data['subscription_id'] != "") {
                $subscription      = \RedshopHelperProduct::getProductSubscriptionDetail(
                    $data['product_id'],
                    $data['subscription_id']
                );
                $subscriptionPrice = $subscription->subscription_price;
                $subscriptionVat   = 0;

                if ($subscriptionPrice) {
                    $subscriptionVat = \RedshopHelperProduct::getProductTax($data['product_id'], $subscriptionPrice);
                }

                $productVatPrice                      += $subscriptionVat;
                $data['product_price']                = $data['product_price'] + $subscriptionPrice + $subscriptionVat;
                $data['product_old_price']            = $data['product_old_price'] + $subscriptionPrice + $subscriptionVat;
                $data['product_old_price_excl_vat']   += $subscriptionPrice;
                $cart[$idx]['product_price_excl_vat'] += $subscriptionPrice;
                $cart[$idx]['subscription_id']        = $data['subscription_id'];
            } else {
                return urldecode(\JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR_SUBSCRIPTION_PLAN'));
            }
        }

        // Accessory price
        if (\Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')) {
            if (isset($data['accessory_data'])) {
                // Append previously added accessories as products
                if (!empty($cart['AccessoryAsProduct'][0])) {
                    $data['accessory_data']       = $cart['AccessoryAsProduct'][0] . '@@' . $data['accessory_data'];
                    $data['acc_quantity_data']    = $cart['AccessoryAsProduct'][1] . '@@' . $data['acc_quantity_data'];
                    $data['acc_attribute_data']   = $cart['AccessoryAsProduct'][2] . '@@' . $data['acc_attribute_data'];
                    $data['acc_property_data']    = $cart['AccessoryAsProduct'][3] . '@@' . $data['acc_property_data'];
                    $data['acc_subproperty_data'] = $cart['AccessoryAsProduct'][4] . '@@' . $data['acc_subproperty_data'];
                }

                $cart['AccessoryAsProduct'] = array(
                    $data['accessory_data'],
                    $data['acc_quantity_data'],
                    $data['acc_attribute_data'],
                    $data['acc_property_data'],
                    $data['acc_subproperty_data']
                );
            }

            $generateAccessoryCart        = array();
            $data['accessory_data']       = "";
            $data['acc_quantity_data']    = "";
            $data['acc_attribute_data']   = "";
            $data['acc_property_data']    = "";
            $data['acc_subproperty_data'] = "";
        } else {
            $generateAccessoryCart = isset($data['cart_accessory']) ?
                $data['cart_accessory'] : \Redshop\Accessory\Helper::generateAccessoryArray($data);

            if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0)) {
                if (is_bool($generateAccessoryCart)) {
                    return \JText::_('COM_REDSHOP_ACCESSORY_HAS_REQUIRED_ATTRIBUTES');
                }

                return false;
            }
        }

        $resultAccessories   = \RedshopHelperProduct::makeAccessoryCart($generateAccessoryCart, $product->product_id);
        $accessoryTotalPrice = $resultAccessories[1];
        $accessoryVatPrice   = $resultAccessories[2];

        $cart[$idx]['product_price_excl_vat'] += $accessoryTotalPrice;
        $data['product_price']                += $accessoryTotalPrice + $accessoryVatPrice;
        $data['product_old_price']            += $accessoryTotalPrice + $accessoryVatPrice;
        $data['product_old_price_excl_vat']   += $accessoryTotalPrice;

        $cart[$idx]['product_vat'] = $productVatPrice + $accessoryVatPrice;

        // ADD WRAPPER PRICE
        $wrapperPrice = 0;
        $wrapperVat   = 0;

        if (isset($data['sel_wrapper_id']) && $data['sel_wrapper_id']) {
            $wrapperArr = \Redshop\Wrapper\Helper::getWrapperPrice(
                array('product_id' => $data['product_id'], 'wrapper_id' => $data['sel_wrapper_id'])
            );

            $wrapperVat   = $wrapperArr['wrapper_vat'];
            $wrapperPrice = $wrapperArr['wrapper_price'];
        }

        $cart[$idx]['product_vat']            += $wrapperVat;
        $data['product_price']                += $wrapperPrice + $wrapperVat;
        $data['product_old_price']            += $wrapperPrice + $wrapperVat;
        $data['product_old_price_excl_vat']   += $wrapperPrice;
        $cart[$idx]['product_price_excl_vat'] += $wrapperPrice;

        // Checking For same Product and update Quantity
        $selectAcc = \RedshopHelperProduct::getSelectedAccessoryArray($data);
        $selectAtt = \RedshopHelperProduct::getSelectedAttributeArray($data);

        $sameProduct = false;

        \JPluginHelper::importPlugin('redshop_product');

        for ($i = 0; $i < $idx; $i++) {
            if ($cart[$i]['product_id'] == $data['product_id']) {
                $sameProduct = true;

                if (isset($data['subscription_id']) && $cart[$i]['subscription_id'] != $data['subscription_id']) {
                    $sameProduct = false;
                }

                if (isset($data['sel_wrapper_id']) && $cart[$i]['wrapper_id'] != $data['sel_wrapper_id']) {
                    $sameProduct = false;
                }

                $prevSelectAtt = \Redshop\Attribute\Helper::getSelectedCartAttributeArray($cart[$i]['cart_attribute']);

                $diff1 = array_diff($prevSelectAtt[0], $selectAtt[0]);
                $diff2 = array_diff($selectAtt[0], $prevSelectAtt[0]);

                if (count($diff1) > 0 || count($diff2) > 0) {
                    $sameProduct = false;
                }

                if (!empty($discounts)
                    && ($cart[$i]["discount_calc"]["calcWidth"] !== $data["calcWidth"]
                        || $cart[$i]["discount_calc"]["calcDepth"] !== $data["calcDepth"])
                ) {
                    $sameProduct = false;
                }

                $diff1 = array_diff($prevSelectAtt[1], $selectAtt[1]);
                $diff2 = array_diff($selectAtt[1], $prevSelectAtt[1]);

                if (count($diff1) > 0 || count($diff2) > 0) {
                    $sameProduct = false;
                }

                $prevSelectAcc = \Redshop\Accessory\Helper::getSelectedCartAccessoryArray($cart[$i]['cart_accessory']);

                $diff1 = array_diff($prevSelectAcc[0], $selectAcc[0]);
                $diff2 = array_diff($selectAcc[0], $prevSelectAcc[0]);

                if (count($diff1) > 0 || count($diff2) > 0) {
                    $sameProduct = false;
                }

                $diff1 = array_diff($prevSelectAcc[1], $selectAcc[1]);
                $diff2 = array_diff($selectAcc[1], $prevSelectAcc[1]);

                if (count($diff1) > 0 || count($diff2) > 0) {
                    $sameProduct = false;
                }

                $diff1 = array_diff($prevSelectAcc[2], $selectAcc[2]);
                $diff2 = array_diff($selectAcc[2], $prevSelectAcc[2]);

                if (count($diff1) > 0 || count($diff2) > 0) {
                    $sameProduct = false;
                }

                // Discount calculator
                $arrayDiffCalc = array_diff_assoc($cart[$i]['discount_calc'], $calcOutputs);

                if (count($arrayDiffCalc) > 0) {
                    $sameProduct = false;
                }

                /**
                 * Previous comment stated it is not used anymore.
                 * Changing it for another purpose. It can intercept and decide whether added product should be added as same or new product.
                 */
                \RedshopHelperUtility::getDispatcher()->trigger(
                    'checkSameCartProduct',
                    array(&$cart, $data, &$sameProduct, $i)
                );

                // Product userfield
                if (!empty($rows)) {
                    $puf = 1;

                    foreach ($rows as $row) {
                        $productUserField = $row->name;
                        $addedUserField   = isset($data[$productUserField]) ? $data[$productUserField] : '';

                        if (isset($cart[$i][$productUserField]) && $addedUserField !== $cart[$i][$productUserField]) {
                            $puf = 0;
                        }
                    }

                    if ($puf !== 1) {
                        $sameProduct = false;
                    }
                }

                if ($sameProduct) {
                    $newQuantity     = $cart[$i]['quantity'] + $data['quantity'];
                    $newCartQuantity = \Redshop\Stock\Helper::checkQuantityInStock($cart[$i], $newQuantity);

                    if ($newQuantity > $newCartQuantity) {
                        $cart['notice_message'] = $newCartQuantity . ' ' . \JText::_(
                                'COM_REDSHOP_AVAILABLE_STOCK_MESSAGE'
                            );
                    } else {
                        $cart['notice_message'] = '';
                    }

                    if ($newCartQuantity != $cart[$i]['quantity']) {
                        $cart[$i]['quantity'] = $newCartQuantity;

                        /*
                         * Trigger the event of redSHOP product plugin support on Same product is going to add into cart
                         *
                         * Usually redSHOP update quantity
                         */
                        \RedshopHelperUtility::getDispatcher()->trigger('onSameCartProduct', array(&$cart, $data, $i));

                        \Redshop\Cart\Helper::setCart($cart);
                        $data['cart_index']    = $i;
                        $data['quantity']      = $newCartQuantity;
                        $data['checkQuantity'] = $newCartQuantity;

                        /** @var \RedshopModelCart $cartModel */
                        $cartModel = \RedshopModel::getInstance('cart', 'RedshopModel');
                        $cartModel->update($data);

                        return true;
                    }

                    if (\Redshop::getConfig()->getString('CART_RESERVATION_MESSAGE') !== ''
                        && \Redshop::getConfig()->getBool('IS_PRODUCT_RESERVE')) {
                        return \Redshop::getConfig()->get('CART_RESERVATION_MESSAGE');
                    }
                }
            }
        }

        // Set product price
        $data['product_price'] = $data['product_price'] < 0 ? 0 : $data['product_price'];

        $perProductTotal = $product->minimum_per_product_total;

        if ($data['product_price'] < $perProductTotal) {
            return \JText::_('COM_REDSHOP_PER_PRODUCT_TOTAL') . " " . $perProductTotal;
        }

        if (!$sameProduct) {
            // SET VALVUES INTO SESSION CART
            $cart[$idx]['giftcard_id']                = '';
            $cart[$idx]['product_id']                 = $data['product_id'];
            $cart[$idx]['discount_calc_output']       = $calcOutput;
            $cart[$idx]['discount_calc']              = $calcOutputs;
            $cart[$idx]['product_price']              = $data['product_price'];
            $cart[$idx]['product_old_price']          = $data['product_old_price'];
            $cart[$idx]['product_old_price_excl_vat'] = $data['product_old_price_excl_vat'];
            $cart[$idx]['cart_attribute']             = $generateAttributeCart;

            $cart[$idx]['cart_accessory'] = $generateAccessoryCart;

            if (isset($data['hidden_attribute_cartimage'])) {
                $cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage'];
            }

            $cart[$idx]['quantity'] = 0;

            $newQuantity            = $data['quantity'];
            $cart[$idx]['quantity'] = \Redshop\Stock\Helper::checkQuantityInStock($cart[$idx], $newQuantity);

            if ($newQuantity > $cart[$idx]['quantity']) {
                $cart['notice_message'] = $cart[$idx]['quantity'] . " " . \JText::_(
                        'COM_REDSHOP_AVAILABLE_STOCK_MESSAGE'
                    );
            } else {
                $cart['notice_message'] = "";
            }

            if ($cart[$idx]['quantity'] <= 0
                && (\Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') != '')
                && (\Redshop::getConfig()->get('IS_PRODUCT_RESERVE'))
            ) {
                return \Redshop::getConfig()->get('CART_RESERVATION_MESSAGE');
            }

            $cart[$idx]['category_id']   = $data['category_id'];
            $cart[$idx]['wrapper_id']    = !empty($data['sel_wrapper_id']) ? $data['sel_wrapper_id'] : 0;
            $cart[$idx]['wrapper_price'] = $wrapperPrice + $wrapperVat;

            /**
             * Implement new plugin support before session update
             * trigger the event of redSHOP product plugin support on Before cart session is set - on prepare cart session
             */
            \RedshopHelperUtility::getDispatcher()->trigger('onBeforeSetCartSession', array(&$cart, $data, $idx));

            $cart['idx'] = $idx + 1;

            foreach ($rows as $row) {
                $fieldName = $row->name;
                $dataTxt   = (isset($data[$fieldName])) ? $data[$fieldName] : '';
                $tmpTxt    = strpbrk($dataTxt, '`');

                if ($tmpTxt) {
                    $dataTxt = str_replace('`', ',', $dataTxt);
                }

                $cart[$idx][$fieldName] = $dataTxt;
            }
        }

        \Redshop\Cart\Helper::setCart($cart);

        return true;
    }

    /**
     * Method check different country and state
     *
     * @param   object   $rate
     * @param   integer  $user_id
     * @param   integer  $users_info_id
     * @param   array    $post
     *
     * @return  boolean
     *
     * @since   2.1.4
     */
    public static function isDiffCountryState($rate, $users_info_id = 0, $post = array())
    {
        if ($users_info_id) {
            $shippingAddresses = \RedshopHelperShipping::getShippingAddress($users_info_id);

            $stateCode   = $shippingAddresses->state_code;
            $countryCode = $shippingAddresses->country_code;
        } else {
            $anonymous = $post['anonymous'];

            if ($anonymous['bill_is_ship']) {
                $stateCode   = $anonymous['BT']['state_code'];
                $countryCode = $anonymous['BT']['country_code'];
            } else {
                $stateCode   = $anonymous['ST']['state_code'];
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
