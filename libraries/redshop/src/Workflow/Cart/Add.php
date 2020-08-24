<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Workflow\Cart;

use Joomla\CMS\Factory;
use Redshop\Cart\Helper;

defined('_JEXEC') or die;

/**
 * AddToCart Workflow
 *
 * @since  __DEPLOY_VERION__
 */
class Add
{
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
     * @since   2.1.0
     */
    public static function product(&$cart, $idx, $data = [])
    {
        \Redshop\Attribute\Helper::initAttributeForCart($cart, $idx, $data);
        $productId = $data['product_id'];
        $quantity  = $data['quantity'];
        $product   = \Redshop\Product\Product::getProductById($productId);
        $section = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD;
        $rows    = \RedshopHelperExtrafields::getSectionFieldList($section);

        // Handle individual accessory add to cart price
        $dataAdd = \Redshop\Accessory\Helper::applyConfigAccessoryAsProduct($product, $cart, $idx, $data);

        /*
         * Check if required userfield are filled or not if not than redirect to product detail page...
         * Get product userfield from selected product template...
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
        $discounts = \Redshop\Promotion\Discount\Calculation::productMeasurement($product, $data);

        $calcOutput      = "";
        $calcOutputs     = [];
        $productPriceVAT = 0;

        if (!empty($discounts)) {
            $calcOutput  = $discounts[0];
            $calcOutputs = $discounts[1];

            // Calculate price without VAT
            $data['product_price']                = $discounts[2];
            $cart[$idx]['product_price_excl_vat'] = $discounts[2];
            $cart[$idx]['discount_calc_price']    = $discounts[2];

            $productPriceVAT += $discounts[3];
        }

        // Attribute price added
        $generateAttributeCart = isset($data['cart_attribute']) ?
            $data['cart_attribute'] : \Redshop\Cart\Helper::generateAttribute($data);

        if (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) {
            $templateCart = \RedshopHelperTemplate::getTemplate("quotation_cart");
        } else {
            if (!\Redshop::getConfig()->get('USE_AS_CATALOG')) {
                $templateCart = \RedshopHelperTemplate::getTemplate("cart");
            } else {
                $templateCart = \RedshopHelperTemplate::getTemplate("catalogue_cart");
            }
        }

        $retAttArr = \RedshopHelperProduct::makeAttributeCart(
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

        $data['product_old_price']            = $retAttArr[5] + $retAttArr[6];
        $data['product_old_price_excl_vat']   = $retAttArr[5];
        $data['product_price']                = $retAttArr[1];
        $productPriceVAT                      = $retAttArr[2];
        $cart[$idx]['product_price_excl_vat'] = $retAttArr[1];

        $data['product_price'] += $productPriceVAT;

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

        $selectedAttrId       = $retAttArr[3];
        $isStock              = $retAttArr[4];
        $selectedPropId       = $selectProp[0];
        $notSelectedSubPropId = $retAttArr[8];
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

                $productPriceVAT                      += $subscriptionVat;
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
            $result = \Redshop\Stock\Helper::checkStockAccessory($data);

            if ($result !== true) {
                return \JText::sprintf('COM_REDSHOP_ACCESSORIES_OUT_OF_STOCK', $result);
            }

            $generateAccessoryCart = isset($data['cart_accessory']) ?
                $data['cart_accessory'] : \Redshop\Accessory\Helper::generateAccessoryArray($data);

            if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0)) {
                if (is_bool($generateAccessoryCart)) {
                    return \JText::_('COM_REDSHOP_ACCESSORY_HAS_REQUIRED_ATTRIBUTES');
                }
            }
        }

        $resultAccessories   = \RedshopHelperProduct::makeAccessoryCart($generateAccessoryCart, $product->product_id);
        $accessoryTotalPrice = $resultAccessories[1];
        $accessoryVatPrice   = $resultAccessories[2];

        $cart[$idx]['product_price_excl_vat'] += $accessoryTotalPrice;
        $data['product_price']                += $accessoryTotalPrice + $accessoryVatPrice;
        $data['product_old_price']            += $accessoryTotalPrice + $accessoryVatPrice;
        $data['product_old_price_excl_vat']   += $accessoryTotalPrice;

        $cart[$idx]['product_vat'] = $productPriceVAT + $accessoryVatPrice;

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
            if ($cart[$i]['product_id'] === $data['product_id']) {
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

                    return \Redshop::getConfig()->getString('CART_RESERVATION_MESSAGE') !== '' && \Redshop::getConfig(
                    )->getBool('IS_PRODUCT_RESERVE')
                        ? \Redshop::getConfig()->get('CART_RESERVATION_MESSAGE')
                        : urldecode(\JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));
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

            if ($cart[$idx]['quantity'] <= 0) {
                $msg = \Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') != '' && \Redshop::getConfig()->get(
                    'IS_PRODUCT_RESERVE'
                )
                    ? \Redshop::getConfig()->get('CART_RESERVATION_MESSAGE') : \JText::_(
                        'COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'
                    );

                $result = \RedshopHelperUtility::getDispatcher()->trigger(
                    'onDisplayText',
                    array($cart[$idx]['product_id'], $cart)
                );

                if (!empty($result)) {
                    return $result[0];
                }

                return $msg;
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
     * @since   __DEPLOY_VERSION__
     */
    public static function giftCard(&$cart, $idx, $data = [])
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

        return true;
    }
}