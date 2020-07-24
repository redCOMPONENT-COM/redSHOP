<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class RedshopModelCart.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelCart extends RedshopModel
{
    public $_id = null;

    public $_data = null;

    /**
     *  Product data
     *
     * @var  [type]
     */
    public $_product = null;

    public $_table_prefix = null;

    public $_template = null;

    public $_r_voucher = 0;

    public $_c_remain = 0;

    public $_globalvoucher = 0;

    public $_producthelper = null;

    public $_carthelper = null;

    public $_userhelper = null;

    public $_objshipping = null;

    public function __construct()
    {
        parent::__construct();

        $this->_table_prefix = '#__redshop_';
        $this->_objshipping  = shipping::getInstance();
        $user                = JFactory::getUser();

        // Remove expired products from cart
        $this->emptyExpiredCartProducts();

        $cart = \Redshop\Cart\Helper::getCart();

        if (!empty($cart)) {
            $cart = \Redshop\Cart\Helper::getCart();

            $userId         = $user->id;
            $userSession    = \JFactory::getSession()->get('rs_user');
            $shopperGroupId = \RedshopHelperUser::getShopperGroup($userId);

            if (array_key_exists('user_shopper_group_id', $cart)) {
                $userArr = \RedshopHelperUser::getVatUserInformation($userId);

                // Removed due to discount issue $userSession['vatCountry']
                if ($cart['user_shopper_group_id'] != $shopperGroupId
                    || (!isset($userSession['vatCountry']) || !isset($userSession['vatState']) || $userSession['vatCountry'] != $userArr->country_code || $userSession['vatState'] != $userArr->state_code)
                ) {
                    $cart                          = \Redshop\Cart\Cart::modify($cart, $userId);
                    $cart['user_shopper_group_id'] = $shopperGroupId;

                    $task = JFactory::getApplication()->input->getCmd('task');

                    if ($task != 'coupon' && $task != 'voucher') {
                        $cart = RedshopHelperDiscount::modifyDiscount($cart);
                    }
                }
            }

            \Redshop\Cart\Helper::setCart($cart);
        }
    }

    /**
     * @return  void
     *
     * @since   1.0
     */
    public function emptyExpiredCartProducts()
    {
        if (Redshop::getConfig()->get('IS_PRODUCT_RESERVE') && Redshop::getConfig()->get('USE_STOCKROOM')) {
            $session     = JFactory::getSession();
            $db          = JFactory::getDbo();
            $query       = $db->getQuery(true);
            $sessionId   = session_id();
            $carttimeout = (int)Redshop::getConfig()->get('CART_TIMEOUT');
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
                        $this->delete($j);
                    }

                    if (count($includedrs) > 0 && !in_array($cart[$j]['product_id'], $includedrs)) {
                        $this->delete($j);
                    }
                }
            }

            RedshopHelperStockroom::deleteExpiredCartProduct();
        }
    }

    public function delete($cartElement)
    {
        $stockroomhelper = rsstockroomhelper::getInstance();
        $cart            = \Redshop\Cart\Helper::getCart();

        if (array_key_exists($cartElement, $cart)) {
            if (array_key_exists('cart_attribute', $cart[$cartElement])) {
                foreach ($cart[$cartElement]['cart_attribute'] as $cartAttribute) {
                    if (array_key_exists('attribute_childs', $cartAttribute)) {
                        foreach ($cartAttribute['attribute_childs'] as $attributeChilds) {
                            if (array_key_exists('property_childs', $attributeChilds)) {
                                foreach ($attributeChilds['property_childs'] as $propertyChilds) {
                                    RedshopHelperStockroom::deleteCartAfterEmpty(
                                        $propertyChilds['subproperty_id'],
                                        'subproperty',
                                        $cart[$cartElement]['quantity']
                                    );
                                }
                            }

                            RedshopHelperStockroom::deleteCartAfterEmpty(
                                $attributeChilds['property_id'],
                                'property',
                                $cart[$cartElement]['quantity']
                            );
                        }
                    }
                }
            }

            $db        = JFactory::getDbo();
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

            RedshopHelperStockroom::deleteCartAfterEmpty(
                $cart[$cartElement]['product_id'],
                'product',
                $cart[$cartElement]['quantity']
            );
            unset($cart[$cartElement]);
            $cart = array_merge(array(), $cart);

            $Index = $cart['idx'] - 1;

            if ($Index > 0) {
                $cart['idx'] = $Index;
            } else {
                $cart        = array();
                $cart['idx'] = 0;
            }
        }

        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * Empty cart
     *
     * @return  boolean
     *
     * @since   2.0.6
     */
    public function emptyCart()
    {
        return RedshopHelperCart::emptyCart();
    }

    /**
     *
     * @return  array|null
     *
     * @since   2.0.6
     */
    public function getData()
    {
        if (empty($this->_data)) {
            if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) {
                $this->_data = RedshopHelperTemplate::getTemplate("quotation_cart");
            } else {
                if (!Redshop::getConfig()->get('USE_AS_CATALOG')) {
                    $this->_data = RedshopHelperTemplate::getTemplate("cart");
                } else {
                    $this->_data = RedshopHelperTemplate::getTemplate("catalogue_cart");
                }
            }
        }

        return $this->_data;
    }

    /**
     * Update cart.
     *
     * @param   array  $data  data in cart
     */
    public function update($data)
    {
        $cart = \Redshop\Cart\Helper::getCart();
        $user = JFactory::getUser();

        $cartElement = $data['cart_index'];
        $newQuantity = intval(abs($data['quantity']) > 0 ? $data['quantity'] : 1);
        $oldQuantity = intval($cart[$cartElement]['quantity']);

        $calculator_price = 0;
        $wrapper_price    = 0;
        $wrapper_vat      = 0;

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
                    $calcdata               = $cart[$cartElement]['discount_calc'];
                    $calcdata['product_id'] = $cart[$cartElement]['product_id'];

                    $discount_cal = \Redshop\Promotion\Discount::discountCalculator($calcdata);

                    $calculator_price  = $discount_cal['product_price'];
                    $product_price_tax = $discount_cal['product_price_tax'];
                }

                // Attribute price
                $retAttArr                  = RedshopHelperProduct::makeAttributeCart(
                    $cart[$cartElement]['cart_attribute'],
                    $cart[$cartElement]['product_id'],
                    $user->id,
                    $calculator_price,
                    $cart[$cartElement]['quantity']
                );
                $product_price              = $retAttArr[1];
                $product_vat_price          = $retAttArr[2];
                $product_old_price          = $retAttArr[5] + $retAttArr[6];
                $product_old_price_excl_vat = $retAttArr[5];

                // Accessory price
                $retAccArr             = RedshopHelperProduct::makeAccessoryCart(
                    $cart[$cartElement]['cart_accessory'],
                    $cart[$cartElement]['product_id']
                );
                $accessory_total_price = $retAccArr[1];
                $accessory_vat_price   = $retAccArr[2];

                if ($cart[$cartElement]['wrapper_id']) {
                    $wrapperArr    = \Redshop\Wrapper\Helper::getWrapperPrice(
                        array(
                            'product_id' => $cart[$cartElement]['product_id'],
                            'wrapper_id' => $cart[$cartElement]['wrapper_id']
                        )
                    );
                    $wrapper_vat   = $wrapperArr['wrapper_vat'];
                    $wrapper_price = $wrapperArr['wrapper_price'];
                }

                if (isset($cart[$cartElement]['subscription_id']) && $cart[$cartElement]['subscription_id'] != "") {
                    $subscription_vat    = 0;
                    $subscription_detail = RedshopHelperProduct::getProductSubscriptionDetail(
                        $cart[$cartElement]['product_id'],
                        $cart[$cartElement]['subscription_id']
                    );
                    $subscription_price  = $subscription_detail->subscription_price;

                    if ($subscription_price) {
                        $subscription_vat = RedshopHelperProduct::getProductTax(
                            $cart[$cartElement]['product_id'],
                            $subscription_price
                        );
                    }

                    $product_vat_price += $subscription_vat;
                    $product_price     = $product_price + $subscription_price;

                    $product_old_price_excl_vat += $subscription_price;
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

                $cart[$cartElement]['product_price']              = $product_price + $product_vat_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
                $cart[$cartElement]['product_old_price']          = $product_old_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
                $cart[$cartElement]['product_old_price_excl_vat'] = $product_old_price_excl_vat + $accessory_total_price + $wrapper_price;
                $cart[$cartElement]['product_price_excl_vat']     = $product_price + $accessory_total_price + $wrapper_price;
                $cart[$cartElement]['product_vat']                = $product_vat_price + $accessory_vat_price + $wrapper_vat;
                JPluginHelper::importPlugin('redshop_product');
                $dispatcher = RedshopHelperUtility::getDispatcher();
                $dispatcher->trigger('onAfterCartUpdate', array(&$cart, $cartElement, $data));
            }
        }

        \Redshop\Cart\Helper::setCart($cart);
    }

    /**
     * @param $data
     * @since __DEPLOY_VERSION__
     */
    public function update_all($data)
    {
        \Redshop\Cart\Helper::updateAll($data);
    }

    /**
     * @return array|bool
     * @throws Exception
     * @deprecated
     * @since __DEPLOY_VERSION__
     */
    public function coupon()
    {
        return \RedshopHelperCartDiscount::applyCoupon();
    }

    /**
     * @return array|bool
     * @throws Exception
     * @deprecated
     * @since __DEPLOY_VERSION__
     */
    public function voucher()
    {
        return \RedshopHelperCartDiscount::applyVoucher();
    }

    /**
     * @param $post
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
    public function redmasscart($post)
    {
        \Redshop\Cart\Helper::redMassCart($post);
    }
    
    /**
     * @param   array  $data  Data
     *
     * @return   array
     * @deprecated
     * @since    2.0.6
     */
    public function changeAttribute($data)
    {
        return \Redshop\Cart\Helper::changeAttribute($data);
    }
}
