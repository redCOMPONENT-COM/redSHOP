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
                    $priceList          = \RedshopHelperProduct_Attribute::getPropertyPrice(
                    /** @scrutinizer ignore-type */ $accessoriesProperty,
                                                    $data['quantity'],
                                                    'property',
                                                    $userId
                    );

                    if (!empty($priceList) && $priceList != new \stdClass) {
                        $propertyPrice = $priceList->product_price;
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
                                $priceList   = \RedshopHelperProduct_Attribute::getPropertyPrice(
                                    $subSubProperty,
                                    $data['quantity'],
                                    'subproperty',
                                    $userId
                                );

                                if (!empty($priceList) && $priceList != new \stdClass) {
                                    $subPropertyPrice = $priceList->product_price;
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
        $db              = JFactory::getDbo();

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
                $msg = sprintf(JText::_('COM_REDSHOP_PRODUCT_IS_NOT_PUBLISHED'), $product->product_name, $productId);
                /** @scrutinizer ignore-deprecated */
                JError::raiseWarning(20, $msg);
                continue;
            }

            if ($product->not_for_sale > 0) {
                $msg = sprintf(JText::_('COM_REDSHOP_PRODUCT_IS_NOT_FOR_SALE'), $product->product_name, $productId);
                /** @scrutinizer ignore-deprecated */
                JError::raiseWarning(20, $msg);
                continue;
            }

            if ($product->expired == 1) {
                $msg = sprintf(JText::_('COM_REDSHOP_PRODUCT_IS_EXPIRED'), $product->product_name, $productId);
                /** @scrutinizer ignore-deprecated */
                JError::raiseWarning(20, $msg);
                continue;
            }

            $data["product_id"] = $productId;

            if (isset($post["mod_quantity"]) && $post["mod_quantity"] !== "") {
                $data["quantity"] = $post["mod_quantity"];
            } else {
                $data["quantity"] = 1;
            }

            \Redshop\Cart\Cart::addProduct($data);
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
                            $property           = RedshopHelperProduct_Attribute::getAttributeProperties(
                                $acc_property_data[$ip]
                            );
                            $pricelist          = RedshopHelperProduct_Attribute::getPropertyPrice(
                                $acc_property_data[$ip],
                                $cart[$idx]['quantity'],
                                'property'
                            );

                            if (isset($pricelist->product_price)) {
                                $property_price = $pricelist->product_price;
                            } else {
                                $property_price = $property[0]->property_price;
                            }

                            if (count($property) > 0 && JFile::exists(
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
                                        $subproperty       = RedshopHelperProduct_Attribute::getAttributeSubProperties(
                                            $acc_subproperty_data[$isp]
                                        );
                                        $pricelist         = RedshopHelperProduct_Attribute::getPropertyPrice(
                                            $acc_subproperty_data[$isp],
                                            $cart[$idx]['quantity'],
                                            'subproperty'
                                        );

                                        if (count($pricelist) > 0) {
                                            $subproperty_price = $pricelist->product_price;
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
        $user = JFactory::getUser();

        if (empty($cart)) {
            $cart        = array();
            $cart['idx'] = 0;
            \Redshop\Cart\Helper::setCart($cart);
            $cart = \Redshop\Cart\Helper::getCart();
        }

        $idx           = (int)($cart['idx']);
        $quantity_all  = $data['quantity_all'];
        $quantity      = explode(",", $quantity_all);
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
                    $accessoryAsProdut           = \RedshopHelperAccessory::getAccessoryAsProduct(
                        $cart['AccessoryAsProduct']
                    );
                    $accessoryAsProdutWithoutVat = false;

                    if (isset($accessoryAsProdut->accessory)
                        && isset($accessoryAsProdut->accessory[$cart[$i]['product_id']])
                        && isset($cart[$i]['accessoryAsProductEligible'])
                    ) {
                        $accessoryAsProdutWithoutVat = '{without_vat}';
                        $accessoryPrice              = (float)$accessoryAsProdut->accessory[$cart[$i]['product_id']]->newaccessory_price;

                        $productPriceInit                   = \RedshopHelperProductPrice::priceRound($accessoryPrice);
                        $cart[$i]['product_vat']            = 0;
                        $cart[$i]['product_price_excl_vat'] = \RedshopHelperProductPrice::priceRound($accessoryPrice);
                    }

                    $cart[$i]['quantity'] = \Redshop\Stock\Helper::checkQuantityInStock($cart[$i], $quantity[$i]);

                    $cart[$i]['cart_accessory'] = self::updateAccessoryPrices($cart[$i], $cart[$i]['quantity']);
                    $cart[$i]['cart_attribute'] = self::updateAccessoryPrices($cart[$i], $cart[$i]['quantity']);

                    // Discount calculator
                    if (!empty($cart[$i]['discount_calc'])) {
                        $calcdata               = $cart[$i]['discount_calc'];
                        $calcdata['product_id'] = $cart[$i]['product_id'];

                        $discount_cal = \Redshop\Promotion\Discount::discountCalculator($calcdata);

                        $calculator_price = $discount_cal['product_price'];
                    }

                    $dispatcher->trigger('onBeforeCartItemUpdate', array(&$cart, $i, &$calculator_price));

                    // Attribute price
                    $retAttArr = \RedshopHelperProduct::makeAttributeCart(
                        $cart[$i]['cart_attribute'],
                        $cart[$i]['product_id'],
                        $user->id,
                        $productPriceInit,
                        $totalQuantity,    // Total Quantity based discount applied here
                        $accessoryAsProdutWithoutVat
                    );

                    $accessoryAsProductZero     = (count(
                            $retAttArr[8]
                        ) == 0 && $productPriceInit == 0 && ($accessoryAsProdutWithoutVat !== false));
                    $product_price              = ($accessoryAsProductZero) ? 0 : $retAttArr[1];
                    $product_vat_price          = ($accessoryAsProductZero) ? 0 : $retAttArr[2];
                    $product_old_price          = ($accessoryAsProductZero) ? 0 : $retAttArr[5] + $retAttArr[6];
                    $product_old_price_excl_vat = ($accessoryAsProductZero) ? 0 : $retAttArr[5];

                    // Accessory price
                    $retAccArr             = \RedshopHelperProduct::makeAccessoryCart(
                        $cart[$i]['cart_accessory'],
                        $cart[$i]['product_id']
                    );
                    $accessory_total_price = $retAccArr[1];
                    $accessory_vat_price   = $retAccArr[2];

                    $wrapper_price = 0;
                    $wrapper_vat   = 0;

                    if ($cart[$i]['wrapper_id']) {
                        $wrapperArr    = \Redshop\Wrapper\Helper::getWrapperPrice(
                            array('product_id' => $cart[$i]['product_id'], 'wrapper_id' => $cart[$i]['wrapper_id'])
                        );
                        $wrapper_vat   = $wrapperArr['wrapper_vat'];
                        $wrapper_price = $wrapperArr['wrapper_price'];
                    }

                    $subscription_vat = 0;

                    if (isset($cart[$i]['subscription_id']) && $cart[$i]['subscription_id'] != "") {
                        $productId           = $cart[$i]['product_id'];
                        $subscription_detail = \RedshopHelperProduct::getProductSubscriptionDetail(
                            $productId,
                            $cart[$i]['subscription_id']
                        );
                        $subscription_price  = $subscription_detail->subscription_price;

                        if ($subscription_price) {
                            $subscription_vat = \RedshopHelperProduct::getProductTax($productId, $subscription_price);
                        }

                        $product_vat_price += $subscription_vat;
                        $product_price     = $product_price + $subscription_price;

                        $product_old_price_excl_vat += $subscription_price;
                    }

                    $cart[$i]['product_price']              = $product_price + $product_vat_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
                    $cart[$i]['product_old_price']          = $product_old_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
                    $cart[$i]['product_old_price_excl_vat'] = $product_old_price_excl_vat + $accessory_total_price + $wrapper_price;
                    $cart[$i]['product_price_excl_vat']     = $product_price + $accessory_total_price + $wrapper_price;
                    $cart[$i]['product_vat']                = $product_vat_price + $accessory_vat_price + $wrapper_vat;

                    $dispatcher->trigger('onAfterCartItemUpdate', array(&$cart, $i, $data));
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
        $attArr = $data['cart_accessory'];

        for ($i = 0, $in = count($attArr); $i < $in; $i++) {
            $attchildArr = $attArr[$i]['accessory_childs'];

            $attArr[$i]['accessory_quantity'] = $newQuantity;

            for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++) {
                $propArr = $attchildArr[$j]['attribute_childs'];

                for ($k = 0, $kn = count($propArr); $k < $kn; $k++) {
                    $pricelist = RedshopHelperProduct_Attribute::getPropertyPrice(
                        $propArr[$k]['property_id'],
                        $newQuantity,
                        'property'
                    );

                    if (count($pricelist) > 0) {
                        $propArr[$k]['property_price'] = $pricelist->product_price;
                    } else {
                        $pricelist                     = RedshopHelperProduct::getProperty(
                            $propArr[$k]['property_id'],
                            'property'
                        );
                        $propArr[$k]['property_price'] = $pricelist->product_price;
                    }

                    $subpropArr = $propArr[$k]['property_childs'];

                    for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++) {
                        $pricelist = RedshopHelperProduct_Attribute::getPropertyPrice(
                            $subpropArr[$l]['subproperty_id'],
                            $newQuantity,
                            'subproperty'
                        );

                        if (count($pricelist) > 0) {
                            $subpropArr[$l]['subproperty_price'] = $pricelist->product_price;
                        } else {
                            $pricelist                           = RedshopHelperProduct::getProperty(
                                $subpropArr[$l]['subproperty_id'],
                                'subproperty'
                            );
                            $subpropArr[$k]['subproperty_price'] = $pricelist->product_price;
                        }
                    }

                    $propArr[$k]['property_childs'] = $subpropArr;
                }

                $attchildArr[$j]['attribute_childs'] = $propArr;
            }

            $attArr[$i]['accessory_childs'] = $attchildArr;
        }

        return $attArr;
    }

    /**
     * @param array $data
     * @param int $newQuantity
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function updateAttributePrices($data = [], $newQuantity = 1)
    {
        $attArr = $data['cart_attribute'];

        for ($i = 0, $in = count($attArr); $i < $in; $i++) {
            $propArr = $attArr[$i]['attribute_childs'];

            for ($k = 0, $kn = count($propArr); $k < $kn; $k++) {
                $pricelist = RedshopHelperProduct_Attribute::getPropertyPrice(
                    $propArr[$k]['property_id'],
                    $newQuantity,
                    'property'
                );

                if (count($pricelist) > 0) {
                    $propArr[$k]['property_price'] = $pricelist->product_price;
                } else {
                    $pricelist                     = RedshopHelperProduct::getProperty(
                        $propArr[$k]['property_id'],
                        'property'
                    );
                    $propArr[$k]['property_price'] = $pricelist->product_price;
                }

                $subpropArr = $propArr[$k]['property_childs'];

                for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++) {
                    $pricelist = RedshopHelperProduct_Attribute::getPropertyPrice(
                        $subpropArr[$l]['subproperty_id'],
                        $newQuantity,
                        'subproperty'
                    );

                    if (count($pricelist) > 0) {
                        $subpropArr[$l]['subproperty_price'] = $pricelist->product_price;
                    } else {
                        $pricelist                           = RedshopHelperProduct::getProperty(
                            $subpropArr[$l]['subproperty_id'],
                            'subproperty'
                        );
                        $subpropArr[$k]['subproperty_price'] = $pricelist->product_price;
                    }
                }

                $propArr[$k]['property_childs'] = $subpropArr;
            }

            $attArr[$i]['attribute_childs'] = $propArr;
        }

        return $attArr;
    }
}
