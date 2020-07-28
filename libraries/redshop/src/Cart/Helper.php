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
 * Cart helper
 *
 * @since  2.1.0
 */
class Helper
{
    /**
     * @var array
     * @since 3.0
     */
    public static $globalVoucher = [];

    /**
     * Method calculate cart price.
     * APPLY_VAT_ON_DISCOUNT = When the discount is a "fixed amount" the
     * final price may vary, depending on if the discount affects "the price+VAT"
     * or just "the price". This CONSTANT will define if the discounts needs to
     * be applied BEFORE or AFTER the VAT is applied to the product price.
     *
     * @param   integer  $userId  Current user ID
     *
     * @return  array
     * @throws  \Exception
     *
     * @since   2.1.0
     */
    public static function calculation($userId = 0)
    {
        $cart = \Redshop\Cart\Helper::getCart();
	    $rsUser = \JFactory::getSession()->get('rs_user');

        $index         = $cart['idx'] ?? 0;
        $vat           = 0;
        $subTotal      = 0;
        $subTotalNoVAT = 0;
        $totalDiscount = ($cart['cart_discount'] ?? 0)
            + ($cart['voucher_discount'] ?? 0)
            + ($cart['coupon_discount'] ?? 0);
        $discountVAT   = 0;
        $shippingVat   = 0;
        $shipping      = 0;

        for ($i = 0; $i < $index; $i++) {
	        $quantity      = $cart[$i]['quantity'] ?? 0;
	        $subTotalNoVAT += $quantity * ($cart[$i]['product_price_excl_vat'] ?? 0);
	        $vatGroupTax = \RedshopHelperTax::getTaxRateByShopperGroup($rsUser['rs_user_shopperGroup'], $rsUser['vatCountry']);

	        if (isset($vatGroupTax) && $vatGroupTax == 0) {
		        $subTotal += $quantity * ($cart[$i]['product_price'] - $cart[$i]['product_vat']);
	        } else {
		        $subTotal += $quantity * ($cart[$i]['product_price'] ?? 0);
	        }

	        $vat += $quantity * ($cart[$i]['product_vat'] ?? 0);
        }

        /* @TODO: Need to check why this variable still exist.
         * $tmparr = array(
         * 'subtotal' => $subTotal,
         * 'tax'      => $vat
         * );
         */

        // Calculate shipping.
        self::calculateShipping($shipping, $shippingVat, $cart, $subTotal, $userId);

        $view = \JFactory::getApplication()->input->getCmd('view');

        if (array_key_exists('shipping', $cart) && $view != 'cart') {
            $shipping = $cart['shipping'];

            if (!isset($cart['shipping_vat'])) {
                $cart['shipping_vat'] = 0;
            }

            $shippingVat = $cart['shipping_vat'];
        }

        $taxExemptAddToCart = \RedshopHelperCart::taxExemptAddToCart();

        if (\Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT')
            && !empty($taxExemptAddToCart)) {
            if (\Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT')) {
                if ($totalDiscount) {
                    $taxAfterDiscount = \RedshopHelperCart::calculateTaxAfterDiscount(
                        $vat,
                        $totalDiscount
                    );

                    // The total minus discount tax difference
                    $subTotal -= $vat - $taxAfterDiscount;
                    $vat      = $taxAfterDiscount;
                }
            } else {
                if (isset($cart['discount_tax']) && !empty($cart['discount_tax'])) {
                    $discountVAT = $cart['discount_tax'];
                    $subTotal    = $subTotal - $cart['discount_tax'];
                } else {
                    $vatData = \RedshopHelperTax::getVatRates();

                    if (null !== $vatData && !empty($vatData->tax_rate)) {
                        $discountVAT = 0;

                        if ((int)$subTotalNoVAT > 0) {
                            $avgVAT      = (($subTotalNoVAT + $vat) / $subTotalNoVAT) - 1;
                            $discountVAT = ($avgVAT * $totalDiscount) / (1 + $avgVAT);
                        }
                    }
                }
            }

            $vat = $vat - $discountVAT;
        }

        $total  = $subTotal + $shipping;
        $result = array($total, $subTotal, $subTotalNoVAT, $shipping);

        if (isset($cart['discount']) === false) {
            $cart['discount'] = 0;
        }

        $result[] = $cart['discount'];
        $result[] = $vat;
        $result[] = $shippingVat;

        return $result;
    }

    /**
     * @return array|mixed
     * @since 3.0
     */
    public static function getCart()
    {
        $cart = \JFactory::getSession()->get('cart', null);

        if (empty($cart)) {
            $cart = [
                'idx' => 0
            ];
        }

        return $cart;
    }

    /**
     * @param   float    $shipping     Shipping rate
     * @param   float    $shippingVat  Shipping VAT
     * @param   array    $cart         Cart data
     * @param   float    $subTotal     Sub total
     * @param   integer  $userId       User ID
     *
     * @return  void
     *
     * @since   2.1.0
     */
    public static function calculateShipping(
        &$shipping,
        &$shippingVat,
        &$cart,
        /** @scrutinizer ignore-unused */ $subTotal = 0.0,
        $userId = 0
    ) {
        // If SHOW_SHIPPING_IN_CART set to no, make shipping Zero
        if (!\Redshop::getConfig()->getBool('SHOW_SHIPPING_IN_CART')
            || !\Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE')) {
            return;
        }

        $index       = $cart['idx'];
        $usersInfoId = 0;

        if (!$userId) {
            $user            = \JFactory::getUser();
            $userId          = $user->id;
            $shippingAddress = \RedshopHelperOrder::getShippingAddress($userId);

            if (!empty($shippingAddress) && !empty($shippingAddress[0])) {
                $usersInfoId = $shippingAddress[0]->users_info_id;
            }
        }

        $numberOfGiftCards = 0;

        for ($i = 0; $i < $index; $i++) {
            if (isset($cart[$i]['giftcard_id']) === true && !empty($cart[$i]['giftcard_id'])) {
                $numberOfGiftCards++;
            }
        }

        if ($numberOfGiftCards == $index) {
            $cart['free_shipping'] = 1;
        } elseif (!isset($cart['free_shipping']) || $cart['free_shipping'] != 1) {
            $cart['free_shipping'] = 0;
        }

        if (isset($cart['free_shipping']) && $cart['free_shipping'] > 0) {
            $shipping = 0.0;

            return;
        }

        if (!isset($cart['voucher_discount'])) {
            $cart['coupon_discount'] = 0;
        }

        $totalDiscount = $cart['cart_discount'];
        $totalDiscount += isset($cart['voucher_discount']) ? $cart['voucher_discount'] : 0.0;
        $totalDiscount += isset($cart['coupon_discount']) ? $cart['coupon_discount'] : 0.0;

        $shippingData = array(
            'order_subtotal' => \Redshop::getConfig()->getString(
                'SHIPPING_AFTER'
            ) == 'total' ? @$cart['product_subtotal_excl_vat'] - $totalDiscount : @$cart['product_subtotal_excl_vat'],
            'users_info_id'  => $usersInfoId
        );

        $defaultShipping = \RedshopHelperCartShipping::getDefault($shippingData);
        $shipping        = $defaultShipping['shipping_rate'];
        $shippingVat     = $defaultShipping['shipping_vat'];
    }

    /**
     * Method for get default quantity
     *
     * @param   integer  $productId  Product ID
     * @param   string   $html       Template html
     *
     * @return  integer
     * @throws \Exception
     *
     * @since   2.1.0
     */
    public static function getDefaultQuantity($productId = 0, $html = "")
    {
        $template = \Redshop\Template\Helper::getAddToCart($html);
        $cartForm = null !== $template ? $template->template_desc : "";

        if (strpos($cartForm, "{addtocart_quantity_selectbox}") === false) {
            return 1;
        }

        $quantitySelected = 1;
        $product          = \Redshop\Product\Product::getProductById($productId);

        if ((\Redshop::getConfig()->getString('DEFAULT_QUANTITY_SELECTBOX_VALUE') != ""
                && $product->quantity_selectbox_value == '') || $product->quantity_selectbox_value != '') {
            $selectBoxValue = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value
                : \Redshop::getConfig()->get('DEFAULT_QUANTITY_SELECTBOX_VALUE');
            $quantityBoxes  = explode(",", $selectBoxValue);
            $quantityBoxes  = array_merge(array(), array_unique($quantityBoxes));

            sort($quantityBoxes);

            foreach ($quantityBoxes as $quantityBox) {
                if (intVal($quantityBox) && intVal($quantityBox) != 0) {
                    $quantitySelected = intVal($quantityBox);
                    break;
                }
            }
        }

        return $quantitySelected;
    }

    /**
     * Method for get discount amount fromm cart
     *
     * @param   array    $cart    Cart data
     * @param   integer  $userId  User ID
     *
     * @return  float
     *
     * @since   2.1.0
     */
    public static function getDiscountAmount($cart = array(), $userId = 0)
    {
        $cart     = empty($cart) ? \Cart\Helper::getCart() : $cart;
        $userId   = empty($userId) ? \JFactory::getUser()->id : $userId;
        $discount = \RedshopHelperDiscount::getDiscount($cart['product_subtotal'], $userId);

        $discountAmountFinal = 0;
        $discountVAT         = 0;

        if (!empty($discount) && isset($cart)) {
            $productSubtotal = $cart['product_subtotal'] + ($cart['shipping'] ?? 0);

            // Discount total type
            if (isset($discount->discount_type) && $discount->discount_type == 0) {
                // 100% discount
                if ($discount->discount_amount > $productSubtotal) {
                    $discountAmount = $productSubtotal;
                } else {
                    $discountAmount = $discount->discount_amount;
                }

                $discountPercent = ($discountAmount * 100) / $productSubtotal;
            } // Discount percentage price
            else {
                $discountPercent = isset($discount->discount_amount) ? $discount->discount_amount : 0;
            }

            // Apply even products already on discount
            if (\Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT')) {
                $discountAmountFinal = $discountPercent * $productSubtotal / 100;
            } else {
                /*
                 * Checking which discount is the best
                 * Example 2 products in cart, 1 product 0% - 1 product 15%
                 * Cart total order discount of 10% for value over 1000, now that discount will be added to both products,
                 * so the product with 15% will now have 25% and the product with 0% will have 10%.
                 * The product with 25% should only have 15% discount as it's best practice and most logical setup
                */

                $idx = 0;

                if (isset($cart['idx'])) {
                    $idx = $cart['idx'];
                }

                for ($i = 0; $i < $idx; $i++) {
                    $productPrice = \RedshopHelperProductPrice::getNetPrice($cart[$i]['product_id']);

                    // Product already discount
                    if ($productPrice['product_discount_price'] > 0) {
                        // Restore to the origigal price
                        $cart[$i]['product_price']          = $productPrice['product_old_price'];
                        $cart[$i]['product_price_excl_vat'] = $productPrice['product_old_price_excl_vat'];
                        $cart[$i]['product_vat']            = $productPrice['product_old_price'] - $productPrice['product_old_price_excl_vat'];
                    }

                    // Checking the product discount < total discount => get total discount
                    if ($productPrice['product_price_saving_percentage'] <= $discountPercent) {
                        $discountAmount = $discountPercent * $productPrice['product_price'] / 100;
                    } // Keep product discount
                    else {
                        $discountAmount = $productPrice['product_price_saving'];
                    }

                    // With quantity
                    $discountAmountFinal += $discountAmount * $cart[$i]['quantity'];
                }
            }

            if (\Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT') && !\Redshop::getConfig()->getBool(
                    'APPLY_VAT_ON_DISCOUNT'
                )) {
                $discountVAT = $discountAmountFinal * \Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT');
            }

            $cart['discount_tax'] = $discountVAT;

            \Redshop\Cart\Helper::setCart($cart);
        }

        return $discountAmountFinal;
    }

    /**
     * @param $cart
     *
     * @return mixed
     * @since 3.0
     */
    public static function setCart($cart)
    {
        return \JFactory::getSession()->set('cart', $cart);
    }

    /**
     * Method for generate attribute array
     *
     * @param   array    $data    Data of attributes
     * @param   integer  $userId  ID of user
     *
     * @return  array
     *
     * @since   2.1.0
     */
    public static function generateAttribute($data, $userId = 0)
    {
        if (empty($data) || !array_key_exists('attribute_data', $data) || empty($data['attribute_data'])) {
            return array();
        }

        $result = array();

        $attributes         = explode('##', $data['attribute_data']);
        $propertiesData     = explode('##', $data['property_data']);
        $subPropertiesDatas = !empty($data['subproperty_data']) ? explode('##', $data['subproperty_data']) : null;

        foreach ($attributes as $attrIndex => $attributeId) {
            $propertiesOprand                     = array();
            $propertiesPrice                      = array();
            $accPropertyCart                      = array();
            $attribute                            = \Redshop\Product\Attribute::getProductAttribute(0, 0, $attributeId);
            $result[$attrIndex]['attribute_id']   = $attributeId;
            $result[$attrIndex]['attribute_name'] = $attribute[0]->text;

            if ($attribute[0]->text != "" && !empty($data['property_data']) && !empty($propertiesData[$attrIndex])) {
                $accessoriesPropertiesData = explode(',,', $propertiesData[$attrIndex]);

                foreach ($accessoriesPropertiesData as $propIndex => $accessoriesProperty) {
                    $accSubpropertyCart = array();
                    $property           = \RedshopHelperProduct_Attribute::getAttributeProperties(
                        $accessoriesPropertiesData[$propIndex]
                    );
                    $prices          = \RedshopHelperProduct_Attribute::getPropertyPrice(
                    /** @scrutinizer ignore-type */ $accessoriesProperty,
                                                    $data['quantity'],
                                                    'property',
                                                    $userId
                    );

                    if (!empty($prices) && $prices != new \stdClass) {
                        $propertyPrice = $prices->product_price;
                    } else {
                        $propertyPrice = $property[0]->property_price;
                    }

                    $accPropertyCart[$propIndex] = array(
                        'property_id'     => $property[0]->property_id,
                        'attribute_id'    => $property[0]->attribute_id,
                        'property_name'   => $property[0]->text,
                        'property_oprand' => $property[0]->oprand,
                        'property_price'  => $propertyPrice,
                    );

                    $propertiesOprand[$propIndex] = $property[0]->oprand;
                    $propertiesPrice[$propIndex]  = $propertyPrice;

                    if (!empty($subPropertiesDatas)) {
                        $subPropertiesData = explode(',,', $subPropertiesDatas[$attrIndex]);

                        if (isset($subPropertiesData[$propIndex]) && $subPropertiesData[$propIndex] != "") {
                            $subSubPropertyData = explode('::', $subPropertiesData[$propIndex]);

                            foreach ($subSubPropertyData as $supPropIndex => $subSubProperty) {
                                if (!$subSubProperty)  {
                                    continue;
                                }

                                $subproperty = \RedshopHelperProduct_Attribute::getAttributeSubProperties(
                                    $subSubProperty
                                );
                                $prices   = \RedshopHelperProduct_Attribute::getPropertyPrice(
                                    $subSubProperty,
                                    $data['quantity'],
                                    'subproperty',
                                    $userId
                                );

                                if (!empty($prices) && $prices != new \stdClass) {
                                    $subPropertyPrice = $prices->product_price;
                                } else {
                                    $subPropertyPrice = $subproperty[0]->subattribute_color_price;
                                }

                                $accSubpropertyCart[$supPropIndex] = array(
                                    'subproperty_id'            => $subSubProperty,
                                    'subproperty_name'          => $subproperty[0]->text,
                                    'subproperty_oprand'        => $subproperty[0]->oprand,
                                    'subattribute_color_title'  => $subproperty[0]->subattribute_color_title,
                                    'subattribute_color_number' => $subproperty[0]->subattribute_color_number,
                                    'subproperty_price'         => $subPropertyPrice,
                                );
                            }
                        }
                    }

                    $accPropertyCart[$propIndex]['property_childs'] = $accSubpropertyCart;
                }
            }

            if (!empty($accPropertyCart)) {
                $result[array_search(
                    $accPropertyCart[0]['attribute_id'],
                    $attributes
                )]['attribute_childs'] = $accPropertyCart;
            }
        }

        return $result;
    }

    /**
     * Method check product subscription
     *
     * @return  boolean
     *
     * @since   3.0
     */
    public static function checkProductSubscription()
    {
        // @Todo Check product subscrition
        return false;
    }

    /**
     * @param $productId
     * @param $cart
     * @param int $voucherRemaining
     *
     * @return array
     * @since 3.0
     */
    public static function getCartProductPrice($productId, $cart, $voucherRemaining = 0)
    {
        $productList         = array();
        $affectedProductIds  = array();
        $idx                 = $cart['idx'];
        $productPrice        = 0;
        $productPriceExclVat = 0;
        $quantity            = 0;
        $productIds          = explode(',', $productId);
        $productIds          = \Joomla\Utilities\ArrayHelper::toInteger($productIds);
        $productQuantity     = 0;

        for ($v = 0; $v < $idx; $v++) {
            if (($voucherRemaining > 0) && ($voucherRemaining < $cart[$v]['quantity'])) {
                $cart[$v]['quantity'] = $voucherRemaining;
            }

            if (in_array($cart[$v]['product_id'], $productIds) || self::$globalVoucher) {
                // Set Quantity based on discount type - i.e Multiple or Single.
                $productQuantity = (\Redshop::getConfig()->get('DISCOUNT_TYPE') == 4) ? $cart[$v]['quantity'] : 1;

                $productPrice         += ($cart[$v]['product_price'] * $productQuantity);
                $productPriceExclVat  += $cart[$v]['product_price_excl_vat'] * $productQuantity;
                $affectedProductIds[] = $cart[$v]['product_id'];

                $quantity += $productQuantity;
            }
        }

        $productList['product_ids']            = implode(',', $affectedProductIds);
        $productList['product_price']          = $productPrice;
        $productList['product_price_excl_vat'] = $productPriceExclVat;
        $productList['product_quantity']       = (\Redshop::getConfig()->get('DISCOUNT_TYPE') == 4) ? $quantity : $productQuantity;

        return $productList;
    }

    /**
     * @return int
     * @since 3.0
     */
    public static function getTotalQuantity()
    {
        $cart = self::getCart();

        if (!isset($cart['idx']) || $cart['idx'] === 0) {
            return 0;
        }

        $cart['totalQuantity'] = 0;

        for ($i = 0; $i < (int)$cart['idx']; $i++) {
            $cart['totalQuantity'] += (int)($cart[$i]['quantity'] ?? 0);
        }

        self::setCart($cart);

        return $cart['totalQuantity'];
    }

    /**
     * @param $post
     * @throws \Exception
     * @since __DEPLOY_VERSION__
     */
    public static function redMassCart($post)
    {
        $data            = array();
        $products_number = explode("\n", $post["numbercart"]);
        $db              = \JFactory::getDbo();

        foreach ($products_number as $productNumber) {
            $productNumber = trim($productNumber);

            if ($productNumber === '') {
                continue;
            }

            $query   = $db->getQuery(true)
                ->select('product_id, published, not_for_sale, expired, product_name')
                ->from($db->qn('#__redshop_product'))
                ->where('product_number = ' . $db->quote($productNumber));
            $product = $db->setQuery($query)->loadObject();

            if (!$product) {
                continue;
            }

            $productId = $product->product_id;

            if ($product->published == 0) {
                $msg = sprintf(\JText::_('COM_REDSHOP_PRODUCT_IS_NOT_PUBLISHED'), $product->product_name, $productId);
                /** @scrutinizer ignore-deprecated */
                \JError::raiseWarning(20, $msg);
                continue;
            }

            if ($product->not_for_sale > 0) {
                $msg = sprintf(\JText::_('COM_REDSHOP_PRODUCT_IS_NOT_FOR_SALE'), $product->product_name, $productId);
                /** @scrutinizer ignore-deprecated */
                \JError::raiseWarning(20, $msg);
                continue;
            }

            if ($product->expired == 1) {
                $msg = sprintf(\JText::_('COM_REDSHOP_PRODUCT_IS_EXPIRED'), $product->product_name, $productId);
                /** @scrutinizer ignore-deprecated */
                \JError::raiseWarning(20, $msg);
                continue;
            }

            $data["product_id"] = $productId;

            if (isset($post["mod_quantity"]) && $post["mod_quantity"] !== "") {
                $data["quantity"] = $post["mod_quantity"];
            } else {
                $data["quantity"] = 1;
            }

            \Redshop\Cart\Cart::add($data);
            \RedshopHelperCart::ajaxRenderModuleCartHtml();
        }
    }

    /**
     * @param   array  $data  Data
     *
     * @return   array
     *
     * @since    __DEPLOY_VERSION__
     */
    public static function changeAttribute($data)
    {
        $imageName = '';
        $type      = '';
        $cart      = \Redshop\Cart\Helper::getCart();

        $generateAttributeCart = array();
        $productId             = $data['product_id'];
        $idx                   = $data['cart_index'];

        if (isset($data['attribute_id_prd_' . $productId . '_0'])) {
            $attribute_data = $data['attribute_id_prd_' . $productId . '_0'];

            for ($ia = 0, $countAttribute = count($attribute_data); $ia < $countAttribute; $ia++) {
                $accPropertyCart                              = array();
                $attribute                                    = \Redshop\Product\Attribute::getProductAttribute(
                    0,
                    0,
                    $attribute_data[$ia]
                );
                $generateAttributeCart[$ia]['attribute_id']   = $attribute_data[$ia];
                $generateAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

                if ($attribute[0]->text != "" && isset($data['property_id_prd_' . $productId . '_0_' . $attribute_data[$ia]])) {
                    $acc_property_data = $data['property_id_prd_' . $productId . '_0_' . $attribute_data[$ia]];

                    for ($ip = 0, $countProperty = count($acc_property_data); $ip < $countProperty; $ip++) {
                        if ($acc_property_data[$ip] != 0) {
                            $accSubpropertyCart = array();
                            $property_price     = 0;
                            $property           = \RedshopHelperProduct_Attribute::getAttributeProperties(
                                $acc_property_data[$ip]
                            );
                            $prices          = \RedshopHelperProduct_Attribute::getPropertyPrice(
                                $acc_property_data[$ip],
                                $cart[$idx]['quantity'],
                                'property'
                            );

                            if (isset($prices->product_price)) {
                                $property_price = $prices->product_price;
                            } else {
                                $property_price = $property[0]->property_price;
                            }

                            if (count($property) > 0 && \JFile::exists(
                                    REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property[0]->property_image
                                )) {
                                $type      = 'product_attributes';
                                $imageName = $property[0]->property_image;
                            }

                            $accPropertyCart[$ip]['property_id']     = $acc_property_data[$ip];
                            $accPropertyCart[$ip]['property_name']   = $property[0]->text;
                            $accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
                            $accPropertyCart[$ip]['property_price']  = $property_price;

                            if (isset($data['subproperty_id_prd_' . $productId . '_0_' . $attribute_data[$ia] . '_' . $acc_property_data[$ip]])) {
                                $acc_subproperty_data = $data['subproperty_id_prd_' . $productId . '_0_' . $attribute_data[$ia] . '_' . $acc_property_data[$ip]];
                                $countSubProperty     = count($acc_subproperty_data);

                                for ($isp = 0; $isp < $countSubProperty; $isp++) {
                                    if ($acc_subproperty_data[$isp] != 0) {
                                        $subproperty_price = 0;
                                        $subproperty       = \RedshopHelperProduct_Attribute::getAttributeSubProperties(
                                            $acc_subproperty_data[$isp]
                                        );
                                        $prices         = \RedshopHelperProduct_Attribute::getPropertyPrice(
                                            $acc_subproperty_data[$isp],
                                            $cart[$idx]['quantity'],
                                            'subproperty'
                                        );

                                        if (count($prices) > 0) {
                                            $subproperty_price = $prices->product_price;
                                        } else {
                                            $subproperty_price = $subproperty[0]->subattribute_color_price;
                                        }

                                        if (count($subproperty) > 0 && JFile::exists(
                                                REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subproperty[0]->subattribute_color_image
                                            )) {
                                            $type      = 'subcolor';
                                            $imageName = $subproperty[0]->subattribute_color_image;
                                        }

                                        $accSubpropertyCart[$isp]['subproperty_id']           = $acc_subproperty_data[$isp];
                                        $accSubpropertyCart[$isp]['subproperty_name']         = $subproperty[0]->text;
                                        $accSubpropertyCart[$isp]['subproperty_oprand']       = $subproperty[0]->oprand;
                                        $accSubpropertyCart[$isp]['subattribute_color_title'] = $subproperty[0]->subattribute_color_title;
                                        $accSubpropertyCart[$isp]['subproperty_price']        = $subproperty_price;
                                    }
                                }
                            }

                            $accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
                        }
                    }
                }

                $generateAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
            }
        }

        $cart[$idx]['cart_attribute'] = $generateAttributeCart;

        if (!empty($imageName) && !empty($type)) {
            $cart[$idx]['hidden_attribute_cartimage'] = REDSHOP_FRONT_IMAGES_ABSPATH . $type . "/" . $imageName;
        }

        // @TODO Do we need setCart back ?

        return $cart;
    }

    /**
     * @param $data
     * @since __DEPLOY_VERSION__
     */
    public static function updateAll($data) {
        \JPluginHelper::importPlugin('redshop_product');
        $dispatcher = \RedshopHelperUtility::getDispatcher();

        $cart = \Redshop\Cart\Helper::getCart();
        $user = \Joomla\CMS\Factory::getUser();

        if (empty($cart)) {
            $cart        = array();
            $cart['idx'] = 0;
            \Redshop\Cart\Helper::setCart($cart);
            $cart = \Redshop\Cart\Helper::getCart();
        }

        $idx           = (int)($cart['idx']);
        $totalQuantity  = $data['quantity_all'];
        $quantity      = explode(",", $totalQuantity);
        $totalQuantity = array_sum($quantity);

        for ($i = 0; $i < $idx; $i++) {
            if ($quantity[$i] < 0) {
                $quantity[$i] = $cart[$i]['quantity'];
            }

            $quantity[$i] = intval(abs($quantity[$i]) > 0 ? $quantity[$i] : 1);

            if ($quantity[$i] != $cart[$i]['quantity']) {
                if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id']) {
                    $cart[$i]['quantity'] = $quantity[$i];
                } else {
                    // Reinit price
                    $productPriceInit = 0;

                    // Accessory price fix during update
                    $accessoryAsProduct           = \RedshopHelperAccessory::getAccessoryAsProduct(
                        $cart['AccessoryAsProduct']
                    );
                    $accessoryAsProductWithoutVat = false;

                    if (isset($accessoryAsProduct->accessory)
                        && isset($accessoryAsProduct->accessory[$cart[$i]['product_id']])
                        && isset($cart[$i]['accessoryAsProductEligible'])
                    ) {
                        $accessoryAsProductWithoutVat = '{without_vat}';
                        $accessoryPrice              = (float)$accessoryAsProduct->accessory[$cart[$i]['product_id']]->newaccessory_price;

                        $productPriceInit                   = \RedshopHelperProductPrice::priceRound($accessoryPrice);
                        $cart[$i]['product_vat']            = 0;
                        $cart[$i]['product_price_excl_vat'] = \RedshopHelperProductPrice::priceRound($accessoryPrice);
                    }

                    $cart[$i]['quantity'] = \Redshop\Stock\Helper::checkQuantityInStock($cart[$i], $quantity[$i]);

                    $cart[$i]['cart_accessory'] = self::updateAccessoryPrices($cart[$i], $cart[$i]['quantity']);
                    $cart[$i]['cart_attribute'] = self::updateAccessoryPrices($cart[$i], $cart[$i]['quantity']);

                    // Discount calculator
                    if (!empty($cart[$i]['discount_calc'])) {
                        $calculateData               = $cart[$i]['discount_calc'];
                        $calculateData['product_id'] = $cart[$i]['product_id'];

                        $discount = \Redshop\Promotion\Discount::discountCalculator($calculateData);

                        $calculationPrice = $discountl['product_price'];
                    }

                    $dispatcher->trigger('onBeforeCartItemUpdate', array(&$cart, $i, &$calculationPrice));

                    // Attribute price
                    $returnAttributePrices = \RedshopHelperProduct::makeAttributeCart(
                        $cart[$i]['cart_attribute'],
                        $cart[$i]['product_id'],
                        $user->id,
                        $productPriceInit,
                        $totalQuantity,    // Total Quantity based discount applied here
                        $accessoryAsProductWithoutVat
                    );

                    $accessoryAsProductZero     = (count(
                            $returnAttributePrices[8]
                        ) == 0 && $productPriceInit == 0 && ($accessoryAsProductWithoutVat !== false));
                    $productPrice              = ($accessoryAsProductZero) ? 0 : $returnAttributePrices[1];
                    $productPriceVAT          = ($accessoryAsProductZero) ? 0 : $returnAttributePrices[2];
                    $productOldPrice          = ($accessoryAsProductZero) ? 0 : $returnAttributePrices[5] + $returnAttributePrices[6];
                    $productOldPriceExcludedVat = ($accessoryAsProductZero) ? 0 : $returnAttributePrices[5];

                    // Accessory price
                    $retAccesssoryPrices             = \RedshopHelperProduct::makeAccessoryCart(
                        $cart[$i]['cart_accessory'],
                        $cart[$i]['product_id']
                    );
                    
                    $accessoryTotalPrice = $retAccesssoryPrices[1];
                    $accessoryPriceVat   = $retAccesssoryPrices[2];

                    $wrapperPrice = 0;
                    $wrapperVAT   = 0;

                    if ($cart[$i]['wrapper_id']) {
                        $wrapperArr    = \Redshop\Wrapper\Helper::getWrapperPrice(
                            array('product_id' => $cart[$i]['product_id'], 'wrapper_id' => $cart[$i]['wrapper_id'])
                        );
                        $wrapperVAT   = $wrapperArr['wrapper_vat'];
                        $wrapperPrice = $wrapperArr['wrapper_price'];
                    }

                    $subscriptionVat = 0;

                    if (isset($cart[$i]['subscription_id']) && $cart[$i]['subscription_id'] != "") {
                        $productId           = $cart[$i]['product_id'];
                        $subscriptionDetail = \RedshopHelperProduct::getProductSubscriptionDetail(
                            $productId,
                            $cart[$i]['subscription_id']
                        );
                        $subscriptionPrice  = $subscriptionDetail->subscription_price;

                        if ($subscriptionPrice) {
                            $subscriptionVat = \RedshopHelperProduct::getProductTax($productId, $subscriptionPrice);
                        }

                        $productPriceVAT += $subscriptionVat;
                        $productPrice     = $productPrice + $subscriptionPrice;

                        $productOldPriceExcludedVat += $subscriptionPrice;
                    }

                    $cart[$i]['product_price']              = $productPrice + $productPriceVAT + $accessoryTotalPrice + $accessoryPriceVat + $wrapperPrice + $wrapperVAT;
                    $cart[$i]['product_old_price']          = $productOldPrice + $accessoryTotalPrice + $accessoryPriceVat + $wrapperPrice + $wrapperVAT;
                    $cart[$i]['product_old_price_excl_vat'] = $productOldPriceExcludedVat + $accessoryTotalPrice + $wrapperPrice;
                    $cart[$i]['product_price_excl_vat']     = $productPrice + $accessoryTotalPrice + $wrapperPrice;
                    $cart[$i]['product_vat']                = $productPriceVAT + $accessoryPriceVat + $wrapperVAT;

                    $dispatcher->trigger('onAfterCartItemUpdate', [&$cart, $i, $data]);
                }
            }
        }

        unset($cart[$idx]);

        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * @param array $data
     * @param int $newQuantity
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function updateAccessoryPrices($data = [], $newQuantity = 1) {
        $accessories = $data['cart_accessory'];

        for ($i = 0, $in = count($accessories); $i < $in; $i++) {
            $accessoryChildren = $accessories[$i]['accessory_childs'];

            $accessories[$i]['accessory_quantity'] = $newQuantity;

            for ($j = 0, $jn = count($accessoryChildren); $j < $jn; $j++) {
                $accessoryChild = $accessoryChildren[$j]['attribute_childs'];

                for ($k = 0, $kn = count($accessoryChild); $k < $kn; $k++) {
                    $prices = \RedshopHelperProduct_Attribute::getPropertyPrice(
                        $accessoryChild[$k]['property_id'],
                        $newQuantity,
                        'property'
                    );

                    if (count($prices) > 0) {
                        $accessoryChild[$k]['property_price'] = $prices->product_price;
                    } else {
                        $prices = \RedshopHelperProduct::getProperty(
                            $accessoryChild[$k]['property_id'],
                            'property'
                        );
                        $accessoryChild[$k]['property_price'] = $prices->product_price;
                    }

                    $subProperties = $accessoryChild[$k]['property_childs'];

                    for ($l = 0, $ln = count($subProperties); $l < $ln; $l++) {
                        $prices = \RedshopHelperProduct_Attribute::getPropertyPrice(
                            $subProperties[$l]['subproperty_id'],
                            $newQuantity,
                            'subproperty'
                        );

                        if (count($prices) > 0) {
                            $subProperties[$l]['subproperty_price'] = $prices->product_price;
                        } else {
                            $prices                           = \RedshopHelperProduct::getProperty(
                                $subProperties[$l]['subproperty_id'],
                                'subproperty'
                            );
                            $subProperties[$k]['subproperty_price'] = $prices->product_price;
                        }
                    }

                    $accessoryChild[$k]['property_childs'] = $subProperties;
                }

                $accessoryChildren[$j]['attribute_childs'] = $accessoryChild;
            }

            $accessories[$i]['accessory_childs'] = $accessoryChildren;
        }

        return $accessories;
    }

    /**
     * @param array $data
     * @param int $newQuantity
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function updateAttributePrices($data = [], $newQuantity = 1)
    {
        $accessories = $data['cart_attribute'];

        for ($i = 0, $in = count($accessories); $i < $in; $i++) {
            $accessoryChild = $accessories[$i]['attribute_childs'];

            for ($k = 0, $kn = count($accessoryChild); $k < $kn; $k++) {
                $prices = \RedshopHelperProduct_Attribute::getPropertyPrice(
                    $accessoryChild[$k]['property_id'],
                    $newQuantity,
                    'property'
                );

                if (count($prices) > 0) {
                    $accessoryChild[$k]['property_price'] = $prices->product_price;
                } else {
                    $prices = \RedshopHelperProduct::getProperty(
                        $accessoryChild[$k]['property_id'],
                        'property'
                    );
                    $accessoryChild[$k]['property_price'] = $prices->product_price;
                }

                $subProperties = $accessoryChild[$k]['property_childs'];

                for ($l = 0, $ln = count($subProperties); $l < $ln; $l++) {
                    $prices = \RedshopHelperProduct_Attribute::getPropertyPrice(
                        $subProperties[$l]['subproperty_id'],
                        $newQuantity,
                        'subproperty'
                    );

                    if (count($prices) > 0) {
                        $subProperties[$l]['subproperty_price'] = $prices->product_price;
                    } else {
                        $prices = \RedshopHelperProduct::getProperty(
                            $subProperties[$l]['subproperty_id'],
                            'subproperty'
                        );
                        $subProperties[$k]['subproperty_price'] = $prices->product_price;
                    }
                }

                $accessoryChild[$k]['property_childs'] = $subProperties;
            }

            $accessories[$i]['attribute_childs'] = $accessoryChild;
        }

        return $accessories;
    }

    /**
     * @param $data
     * @throws \Exception
     * @since __DEPLOY_VERSION__
     */
    public static function updateCart($data) {
        $cart = \Redshop\Cart\Helper::getCart();
        $user = \JFactory::getUser();

        $cartElement = $data['cart_index'];
        $newQuantity = intval(abs($data['quantity']) > 0 ? $data['quantity'] : 1);
        $oldQuantity = intval($cart[$cartElement]['quantity']);

        $calculatorPrice = 0;
        $wrapperPrice    = 0;
        $wrapperVAT      = 0;

        if ($newQuantity <= 0) {
            $newQuantity = 1;
        }

        if ($newQuantity != $oldQuantity) {
            if (isset($cart[$cartElement]['giftcard_id']) && $cart[$cartElement]['giftcard_id']) {
                $cart[$cartElement]['quantity'] = $newQuantity;
            } else {
                if (array_key_exists('checkQuantity', $data)) {
                    $cart[$cartElement]['quantity'] = $data['checkQuantity'];
                } else {
                    $cart[$cartElement]['quantity'] = \Redshop\Stock\Helper::checkQuantityInStock(
                        $cart[$cartElement],
                        $newQuantity
                    );
                }

                if ($newQuantity > $cart[$cartElement]['quantity']) {
                    $cart['notice_message'] = $cart[$cartElement]['quantity'] . " " . JTEXT::_(
                            'COM_REDSHOP_AVAILABLE_STOCK_MESSAGE'
                        );
                } else {
                    $cart['notice_message'] = "";
                }

                $cart[$cartElement]['cart_accessory'] = \Redshop\Cart\Helper::updateAccessoryPrices(
                    $cart[$cartElement],
                    $cart[$cartElement]['quantity']
                );
                $cart[$cartElement]['cart_attribute'] = \Redshop\Cart\Helper::updateAccessoryPrices(
                    $cart[$cartElement],
                    $cart[$cartElement]['quantity']
                );

                // Discount calculator
                if (!empty($cart[$cartElement]['discount_calc'])) {
                    $calculateData               = $cart[$cartElement]['discount_calc'];
                    $calculateData['product_id'] = $cart[$cartElement]['product_id'];

                    $discountCalculation = \Redshop\Promotion\Discount::discountCalculator($calculateData);

                    $calculatorPrice  = $discountCalculation['product_price'];
                    $productPriceTax = $discountCalculation['product_price_tax'];
                }

                // Attribute price
                $cartAttributes                  = \RedshopHelperProduct::makeAttributeCart(
                    $cart[$cartElement]['cart_attribute'],
                    $cart[$cartElement]['product_id'],
                    $user->id,
                    $calculatorPrice,
                    $cart[$cartElement]['quantity']
                );
                $productPrice             = $cartAttributes[1];
                $productPriceVAT          = $cartAttributes[2];
                $productOldPrice          = $cartAttributes[5] + $cartAttributes[6];
                $productOldPriceNoVAT = $cartAttributes[5];

                // Accessory price
                $cartAccessories             = \RedshopHelperProduct::makeAccessoryCart(
                    $cart[$cartElement]['cart_accessory'],
                    $cart[$cartElement]['product_id']
                );
                $accessoryTotalPrice = $cartAccessories[1];
                $accessoryPriceVAT   = $cartAccessories[2];

                if ($cart[$cartElement]['wrapper_id']) {
                    $wrapperArr    = \Redshop\Wrapper\Helper::getWrapperPrice(
                        array(
                            'product_id' => $cart[$cartElement]['product_id'],
                            'wrapper_id' => $cart[$cartElement]['wrapper_id']
                        )
                    );
                    $wrapperVAT   = $wrapperArr['wrapper_vat'];
                    $wrapperPrice = $wrapperArr['wrapper_price'];
                }

                if (isset($cart[$cartElement]['subscription_id']) && $cart[$cartElement]['subscription_id'] != "") {
                    $subscriptionVAT    = 0;
                    $subscriptionDetail = \RedshopHelperProduct::getProductSubscriptionDetail(
                        $cart[$cartElement]['product_id'],
                        $cart[$cartElement]['subscription_id']
                    );
                    $subscriptionPrice  = $subscriptionDetail->subscription_price;

                    if ($subscriptionPrice) {
                        $subscriptionVAT = \RedshopHelperProduct::getProductTax(
                            $cart[$cartElement]['product_id'],
                            $subscriptionPrice
                        );
                    }

                    $productPriceVAT += $subscriptionVAT;
                    $productPrice     = $productPrice + $subscriptionPrice;

                    $productOldPriceNoVAT += $subscriptionPrice;
                }

                if (isset($cart['voucher']) && is_array($cart['voucher'])) {
                    $maxVoucher = count($cart['voucher']);
                    for ($i = 0; $i < $maxVoucher; $i++)
                    {
                        $voucherQuantity = '';
                        for ($j = 0; $j < $cart['idx']; $j++)
                        {
                            $voucherProductIds = explode(",", $cart['voucher'][$i]['product_id']);

                            if (!in_array($cart[$j]['product_id'], $voucherProductIds)) {
                                continue;
                            }

                            $voucherQuantity +=  $cart[$j]['quantity'];
                        }

                        if (\Redshop::getConfig()->get('DISCOUNT_TYPE') == 4) {
                            $voucherData = \Redshop\Promotion\Voucher::getVoucherData($cart['voucher'][$i]['voucher_code']);
                            $cart['voucher'][$i]['voucher_value'] = $voucherData->total * $voucherQuantity;

                            if ($voucherData->type == 'Percentage') {
                                $cart['voucher'][$i]['voucher_value'] = ($cart['product_subtotal'] * $voucherData->total) / (100);
                            }

                            $cart['voucher'][$i]['used_voucher'] = $voucherQuantity;
                        }
                    }
                }

                $cart[$cartElement]['product_price']              = $productPrice + $productPriceVAT + $accessoryTotalPrice + $accessoryPriceVAT + $wrapperPrice + $wrapperVAT;
                $cart[$cartElement]['product_old_price']          = $productOldPrice + $accessoryTotalPrice + $accessoryPriceVAT + $wrapperPrice + $wrapperVAT;
                $cart[$cartElement]['product_old_price_excl_vat'] = $productOldPriceNoVAT + $accessoryTotalPrice + $wrapperPrice;
                $cart[$cartElement]['product_price_excl_vat']     = $productPrice + $accessoryTotalPrice + $wrapperPrice;
                $cart[$cartElement]['product_vat']                = $productPriceVAT + $accessoryPriceVAT + $wrapperVAT;
                \JPluginHelper::importPlugin('redshop_product');
                $dispatcher = \RedshopHelperUtility::getDispatcher();
                $dispatcher->trigger('onAfterCartUpdate', array(&$cart, $cartElement, $data));
            }
        }

        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * @param $cartEleement
     * @since __DEPLOY_VERSION__
     */
    public static function removeItemCart($cartElement) {
        $cart = \Redshop\Cart\Helper::getCart();

        if (array_key_exists($cartElement, $cart)) {
            if (array_key_exists('cart_attribute', $cart[$cartElement])) {
                foreach ($cart[$cartElement]['cart_attribute'] as $cartAttribute) {
                    if (array_key_exists('attribute_childs', $cartAttribute)) {
                        foreach ($cartAttribute['attribute_childs'] as $attributeChilds) {
                            if (array_key_exists('property_childs', $attributeChilds)) {
                                foreach ($attributeChilds['property_childs'] as $propertyChilds) {
                                    \RedshopHelperStockroom::deleteCartAfterEmpty(
                                        $propertyChilds['subproperty_id'],
                                        'subproperty',
                                        $cart[$cartElement]['quantity']
                                    );
                                }
                            }

                            \RedshopHelperStockroom::deleteCartAfterEmpty(
                                $attributeChilds['property_id'],
                                'property',
                                $cart[$cartElement]['quantity']
                            );
                        }
                    }
                }
            }

            $db        = \JFactory::getDbo();
            $query     = $db->getQuery(true)
                ->select('voucher_id')
                ->from($db->qn('#__redshop_product_voucher_xref'))
                ->where($db->qn('product_id') . ' = ' . $db->q((int)$cart[$cartElement]['product_id']));
            $voucherId = $db->setQuery($query)->loadResult();

            if (!empty($voucherId)) {
                $countVoucher = count($cart['voucher']);
                if ($countVoucher > 1) {
                    for ($i = 0; $i < $countVoucher; $i++) {
                        if ($cart['voucher'][$i]['voucher_id'] == $voucherId) {
                            unset($cart['voucher'][$i]);
                        }
                    }
                } else {
                    for ($i = 0; $i < $countVoucher; $i++) {
                        if ($cart['voucher'][$i]['voucher_id'] == $voucherId) {
                            unset($cart['voucher']);
                        }
                    }
                }
            }

            \RedshopHelperStockroom::deleteCartAfterEmpty(
                $cart[$cartElement]['product_id'],
                'product',
                $cart[$cartElement]['quantity']
            );

            unset($cart[$cartElement]);
            $cart = array_merge(array(), $cart);

            $index = $cart['idx'] - 1;

            if ($index > 0) {
                $cart['idx'] = $index;
            } else {
                $cart        = array();
                $cart['idx'] = 0;
            }
        }

        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    public static function emptyExpiredCartProducts()
    {
        if (\Redshop::getConfig()->get('IS_PRODUCT_RESERVE') && \Redshop::getConfig()->get('USE_STOCKROOM')) {
            $session     = \JFactory::getSession();
            $db          = \JFactory::getDbo();
            $query       = $db->getQuery(true);
            $sessionId   = session_id();
            $carttimeout = (int)\Redshop::getConfig()->get('CART_TIMEOUT');
            $time        = time() - ($carttimeout * 60);

            $query->select($db->quoteName('product_id'))
                ->from($db->quoteName('#__redshop_cart'))
                ->where($db->quoteName('session_id') . ' = ' . $db->quote($sessionId))
                ->where($db->quoteName('section') . ' = ' . $db->quote('product'));
            $db->setQuery($query);
            $includedrs = $db->loadColumn();

            $query->where($db->quoteName('time') . ' < ' . $db->quote($time));

            $db->setQuery($query);
            $deletedrs = $db->loadColumn();

            $cart = $session->get('cart');

            if ($cart) {
                $idx = (int)(isset($cart['idx']) ? $cart['idx'] : 0);

                for ($j = 0; $j < $idx; $j++) {
                    if (count($deletedrs) > 0 && in_array($cart[$j]['product_id'], $deletedrs)) {
                        self::removeItemCart($j);
                    }

                    if (count($includedrs) > 0 && !in_array($cart[$j]['product_id'], $includedrs)) {
                        self::removeItemCart($j);
                    }
                }
            }

            \RedshopHelperStockroom::deleteExpiredCartProduct();
        }
    }

    /**
     * @since __DEPLOY_VERSION__
     */
    public static function setUserDocumentToSession() {
        $session = \Joomla\CMS\Factory::getSession();
        $post = \Joomla\CMS\Factory::getApplication()->input->post->getArray();
        $userDocuments = $session->get('userDocument', []);
        $condition = isset($userDocuments[$post['product_id']]);

        if ($condition) {
            unset($userDocuments[$post['product_id']]);
            $session->set('userDocument', $userDocuments);
        }
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function routingAfterAddToCart() {
        $app = \Joomla\CMS\Factory::getApplication();
        $cart = \Redshop\Cart\Helper::getCart();
        $post = $app->input->post->getArray();
        $itemId = \RedshopHelperRouter::getCartItemId();

        $link = \JRoute::_(
            'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&Itemid=' . $itemId,
            false
        );

        // Call add method of modal to store product in cart session
        $userField = $app->input->get('userfield');

        if (!$userField) {
            if ($isAjaxCartBox && isset($post['ajax_cart_box'])) {
                $link = \JRoute::_(
                    'index.php?option=com_redshop&view=cart&ajax_cart_box='
                    . $post['ajax_cart_box'] . '&tmpl=component&Itemid=' . $itemId,
                    false
                );
            } else {
                if (\Redshop::getConfig()->getInt('ADDTOCART_BEHAVIOUR') === 1) {
                    $link = \JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $itemId, false);
                } else {
                    if (isset($cart['notice_message']) && !empty($cart['notice_message'])) {
                        $app->enqueueMessage($cart['notice_message'], 'warning');
                    }

                    $app->enqueueMessage(\JText::_('COM_REDSHOP_PRODUCT_ADDED_TO_CART'), 'message');
                    $link = \JRoute::_($_SERVER['HTTP_REFERER'], false);
                }
            }
        }

        $app->redirect($link);
    }

    /**
     * @param $result
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function addToCartErrorHandler($result) {
        $app = \Joomla\CMS\Factory::getApplication();
        $post = $app->input->post->getArray();

        if (!is_bool($result) || (is_bool($result) && !$result)) {
            $errorMessage = $result ? $result : \JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

            // Set Error Message
            $app->enqueueMessage($errorMessage, 'error');

            if (\Redshop::getConfig()->getBool('AJAX_CART_BOX')) {
                echo '`0`' . $errorMessage;
                $app->close();
            } else {
                $itemData = \RedshopHelperProduct::getMenuInformation(0, 0, '', 'product&pid=' . $post['product_id']);

                if (count($itemData) > 0) {
                    $productItemId = $itemData->id;
                } else {
                    $productItemId = \RedshopHelperRouter::getItemId(
                        $post['product_id'],
                        \RedshopProduct::getInstance($post['product_id'])->cat_in_sefurl
                    );
                }

                // Directly redirect if error found
                $app->redirect(
                    \JRoute::_(
                        'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&cid='
                        . $post['category_id'] . '&Itemid=' . $productItemId,
                        false
                    )
                );
            }
        }
    }

    /**
     * @param $action
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    public static function checkCondition($action) {
        return \Redshop\Cart\Check::checkCondition($action);
    }
}
