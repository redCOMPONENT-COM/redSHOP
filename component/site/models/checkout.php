<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Economic\RedshopEconomic;
use Redshop\Environment as RedshopEnvironment;

/**
 * Class checkoutModelcheckout
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelCheckout extends RedshopModel
{
    public $_id = null;

    public $_data = null;

    public $_table_prefix = null;

    public $discount_type = null;

    public $_userhelper = null;

    public $_carthelper = null;

    public $_shippinghelper = null;

    public $_order_functions = null;

    public $_producthelper = null;

    public $_redshopMail = null;

    /**
     * RedshopModelCheckout constructor.
     * @throws Exception
     *
     * @since  1.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->_table_prefix    = '#__redshop_';
        $session                = JFactory::getSession();
        $this->_shippinghelper  = shipping::getInstance();
        $this->_order_functions = order_functions::getInstance();
        $this->_redshopMail     = redshopMail::getInstance();

        $user = JFactory::getUser();
        $cart = $session->get('cart');

        if (!empty($cart)) {
            if (!$cart) {
                $cart        = array();
                $cart['idx'] = 0;
            } elseif (isset($cart['idx']) === false) {
                $cart['idx'] = 0;
            }
        }

        $noOFGIFTCARD = 0;
        $idx          = 0;

        if (isset($cart['idx'])) {
            $idx = $cart['idx'];
        }

        for ($i = 0; $i < $idx; $i++) {
            if (isset($cart[$i]['giftcard_id']) === true) {
                if (!is_null($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'] != 0) {
                    $noOFGIFTCARD++;
                }
            }
        }

        if (isset($cart['free_shipping']) === false) {
            $cart['free_shipping'] = 0;
        }

        if ($noOFGIFTCARD == $idx) {
            $cart['free_shipping'] = 1;
        } elseif ($cart['free_shipping'] != 1) {
            $cart['free_shipping'] = 0;
        }

        if ($user->id) {
            $cart = \Redshop\Cart\Cart::modify($cart, $user->id);
        }

        \Redshop\Cart\Helper::setCart($cart);
        RedshopHelperCart::addCartToDatabase();
    }

    /**
     * @param   array  $data  Data for storing
     *
     * @return  boolean|Tableuser_detail
     *
     * @throws  Exception
     */
    public function store($data)
    {
        if (empty($data)) {
            return false;
        }

        $plugin = JPluginHelper::getPlugin('captcha', 'recaptcha');

        $dataCaptcha = (string)$data;

        if ($plugin) {
            $params = new JRegistry($plugin->params);

            if ($params->get('version', '') === '2.0') {
                $dataCaptcha = null;
            }
        }

        // Disable check captcha if in One Step Checkout mode.
        if (!Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE') && !Redshop\Helper\Utility::checkCaptcha(
                $dataCaptcha
            )) {
            return false;
        }

        return $this->storeRedshopUser($data, $this->storeJoomlaUser($data));
    }

    /**
     * @param   array           $data        Array of data
     * @param   object|boolean  $joomlaUser  Joomla! user objecet
     *
     * @return  boolean|Tableuser_detail
     *
     * @throws  Exception
     * @since   2.1.0
     */
    protected function storeRedshopUser($data, $joomlaUser)
    {
        if (!$joomlaUser) {
            return false;
        }

        return RedshopHelperUser::storeRedshopUser($data, $joomlaUser->id);
    }

    /**
     * @param   array  $data  Array of data
     *
     * @return  boolean|JUser|stdClass
     *
     * @throws  Exception
     * @since   2.1.0
     */
    protected function storeJoomlaUser($data)
    {
        if (isset($data['user_id']) && $data['user_id']) {
            return RedshopHelperJoomla::updateJoomlaUser($data);
        }

        return RedshopHelperJoomla::createJoomlaUser($data);
    }

    /**
     * @return boolean|Tableorder_detail
     *
     * @throws Exception
     */
    public function orderplace()
    {
        $app              = JFactory::getApplication();
        $input            = $app->input;
        $post             = $input->post->getArray();
        $Itemid           = $input->post->getInt('Itemid', 0);
        $shop_id          = $input->post->getString('shop_id', "");
        $gls_zipcode      = $input->post->getString('gls_zipcode', "");
        $gls_mobile       = $input->post->getString('gls_mobile', "");
        $customer_message = $input->post->getString('rs_customer_message_ta', "");
        $referral_code    = $input->post->getString('txt_referral_code', "");
	    $userSession = \JFactory::getSession()->get('rs_user');
	    $vatUserNoApplyTax = \RedshopHelperTax::getTaxRateByShopperGroup($userSession['rs_user_shopperGroup'], $userSession['vatCountry']);

        if ($gls_mobile) {
            $shop_id .= '###' . $gls_mobile;
        }

        if ($gls_zipcode) {
            $shop_id .= '###' . $gls_zipcode;
        }

        $user    = JFactory::getUser();
        $session = JFactory::getSession();
        $auth    = $session->get('auth');
        $userId  = $user->id;

        if (!$user->id && $auth['users_info_id']) {
            $userId = -$auth['users_info_id'];
        }

        $isSplit = $session->get('issplit');

        // If user subscribe for the newsletter
        if (isset($post['newsletter_signup']) && $post['newsletter_signup'] == 1) {
            RedshopHelperNewsletter::subscribe();
        }

        // If user unsubscribe for the newsletter

        if (isset($post['newsletter_signoff']) && $post['newsletter_signoff'] == 1) {
            RedshopHelperNewsletter::removeSubscribe();
        }

        $orderPaymentStatus = 'Unpaid';
        $userInfoIds        = $input->getInt('users_info_id');
        $shippingaddresses  = $this->shipaddress($userInfoIds);
        $billingAddresses   = $this->billingaddresses();

        if (isset($shippingaddresses)) {
            $d ["shippingaddress"]                 = $shippingaddresses;
            $d ["shippingaddress"]->country_2_code = RedshopHelperWorld::getCountryCode2(
                $d ["shippingaddress"]->country_code
            );
            $d ["shippingaddress"]->state_2_code   = RedshopHelperWorld::getStateCode2(
                $d ["shippingaddress"]->state_code
            );

            $shippingaddresses->country_2_code = $d ["shippingaddress"]->country_2_code;
            $shippingaddresses->state_2_code   = $d ["shippingaddress"]->state_2_code;
        }

        if (isset($billingAddresses)) {
            $d["billingaddress"] = $billingAddresses;

            if (isset($billingAddresses->country_code)) {
                $d["billingaddress"]->country_2_code = RedshopHelperWorld::getCountryCode2(
                    $billingAddresses->country_code
                );
                $billingAddresses->country_2_code    = $d["billingaddress"]->country_2_code;
            }

            if (isset($billingAddresses->state_code)) {
                $d["billingaddress"]->state_2_code = RedshopHelperWorld::getStateCode2($billingAddresses->state_code);
                $billingAddresses->state_2_code    = $d["billingaddress"]->state_2_code;
            }
        }

        $cart = $session->get('cart');

        if ($cart['idx'] < 1) {
            $msg = JText::_('COM_REDSHOP_EMPTY_CART');
            $app->redirect(JRoute::_('index.php?option=com_redshop&Itemid=' . $Itemid), $msg);
        }

        $shipping_rate_id = '';

        if ($cart['free_shipping'] != 1) {
            $shipping_rate_id = $input->post->getString('shipping_rate_id', "");
        }

        $payment_method_id = $input->post->getString('payment_method_id', "");

        if ($shipping_rate_id && $cart['free_shipping'] != 1) {
            $shipArr              = $this->calculateShipping($shipping_rate_id);
            $cart['shipping']     = $shipArr['order_shipping_rate'];
            $cart['shipping_vat'] = $shipArr['shipping_vat'];
        }

        $cart = RedshopHelperDiscount::modifyDiscount($cart);

        // Get Payment information
        $paymentMethod = RedshopHelperOrder::getPaymentMethodInfo($payment_method_id);
        $paymentMethod = $paymentMethod[0];

        // Se payment method plugin params
        $paymentMethod->params = new JRegistry($paymentMethod->params);

        // Prepare payment Information Object for calculations
        $paymentInfo                              = new stdclass;
        $paymentInfo->payment_price               = $paymentMethod->params->get('payment_price', '');
        $paymentInfo->payment_oprand              = $paymentMethod->params->get('payment_oprand', '');
        $paymentInfo->payment_discount_is_percent = $paymentMethod->params->get('payment_discount_is_percent', '');
        $paymentAmount                            = $cart ['total'];

        if (Redshop::getConfig()->get('PAYMENT_CALCULATION_ON') == 'subtotal') {
            $paymentAmount = $cart ['product_subtotal'];
        }

        $paymentArray  = RedshopHelperPayment::calculate($paymentAmount, $paymentInfo, $cart['total']);
        $cart['total'] = $paymentArray[0];
        \Redshop\Cart\Helper::setCart($cart);

        $order_shipping = Redshop\Shipping\Rate::decrypt($shipping_rate_id);
        $order_status   = 'P';
        $order_subtotal = $cart ['product_subtotal'];
        $cdiscount      = $cart ['coupon_discount'];
        $order_tax      = $cart ['tax'];
        $d['order_tax'] = $order_tax;

        $dispatcher = RedshopHelperUtility::getDispatcher();

        // Add plugin support
        JPluginHelper::importPlugin('redshop_checkout');
        $dispatcher->trigger('onBeforeOrderSave', array(&$cart, &$post, &$order_shipping));

        $tax_after_discount = 0;

        if (isset($cart ['tax_after_discount'])) {
            $tax_after_discount = $cart ['tax_after_discount'];
        }

        $odiscount     = $cart['coupon_discount'] + $cart['voucher_discount'] + $cart['cart_discount'];
        $odiscount_vat = $cart['discount_vat'];

        $d["order_payment_trans_id"] = '';
        $d['discount']               = $odiscount;
        $order_total                 = $cart['total'];

        if ($isSplit) {
            $order_total = $order_total / 2;
        }

        $input->set('order_ship', $order_shipping[3]);

        $paymentElementName = $paymentMethod->element;

        // Check for bank transfer payment type plugin - suffixed using `rs_payment_banktransfer`
        $isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($paymentMethod->element);

        if ($isBankTransferPaymentType || $paymentMethod->element == "rs_payment_eantransfer") {
            $order_status       = $paymentMethod->params->get('verify_status', '');
            $orderPaymentStatus = trim("Unpaid");
        }

        $paymentMethod->element = $paymentElementName;

        $payment_amount = 0;

        if (isset($cart['payment_amount'])) {
            $payment_amount = $cart['payment_amount'];
        }

        $payment_oprand = "";

        if (isset($cart['payment_oprand'])) {
            $payment_oprand = $cart['payment_oprand'];
        }

        $economic_payment_terms_id = $paymentMethod->params->get('economic_payment_terms_id');
        $economic_design_layout    = $paymentMethod->params->get('economic_design_layout');
        $is_creditcard             = $paymentMethod->params->get('is_creditcard', '');
        $is_redirected             = $paymentMethod->params->get('is_redirected', 0);

        $input->set('payment_status', $orderPaymentStatus);

        $d['order_shipping'] = $order_shipping [3];
        Redshop\User\Billing\Billing::setGlobal($billingAddresses);
        $timestamp = time();

        $order_status_log = '';

        // For credit card payment gateway page will redirect to order detail page from plugin
        if ($is_creditcard == 1 && $is_redirected == 0 && $cart['total'] > 0) {
            $order_number = RedshopHelperOrder::generateOrderNumber();

            JPluginHelper::importPlugin('redshop_payment');

            $values['order_shipping'] = $d['order_shipping'];
            $values['order_number']   = $order_number;
            $values['order_tax']      = $d['order_tax'];
            $values['shippinginfo']   = $d['shippingaddress'];
            $values['billinginfo']    = $d['billingaddress'];
            $values['order_total']    = $order_total;
            $values['order_subtotal'] = $order_subtotal;
            $values["order_id"]       = $app->input->get('order_id', 0);
            $values['payment_plugin'] = $paymentMethod->element;
            $values['odiscount']      = $odiscount;
            $paymentResponses         = $dispatcher->trigger(
                'onPrePayment_' . $values['payment_plugin'],
                array($values['payment_plugin'], $values)
            );
            $paymentResponse          = $paymentResponses[0];

            if ($paymentResponse->responsestatus == "Success") {
                $d ["order_payment_trans_id"] = $paymentResponse->transaction_id;
                $order_status_log             = $paymentResponse->message;

                if (!isset($paymentResponse->status)) {
                    $paymentResponse->status = 'C';
                }

                $order_status = $paymentResponse->status;

                if (!isset($paymentResponse->paymentStatus)) {
                    $paymentResponse->paymentStatus = 'Paid';
                }

                $orderPaymentStatus = $paymentResponse->paymentStatus;
            } else {
                if ($values['payment_plugin'] != 'rs_payment_localcreditcard') {
                    $errorMsg = $paymentResponse->message;
                    /** @scrutinizer ignore-deprecated */
                    $this->setError($errorMsg);

                    return false;
                }
            }
        }

        // Get the IP Address
        $ip = RedshopEnvironment::getUserIp();

        /** @var Tableorder_detail $row */
        $row = $this->getTable('order_detail');

        if (!$row->bind($post)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        $shippingVatRate = 0;

        if (array_key_exists(6, $order_shipping)) {
            $shippingVatRate = $order_shipping [6];
        }

        // Start code to track duplicate order number checking
        $order_number = RedshopHelperOrder::generateOrderNumber();

        $random_gen_enc_key      = \Redshop\Crypto\Helper\Encrypt::generateCustomRandomEncryptKey(35);
        $userInfoIds             = $billingAddresses->users_info_id;
        $row->user_id            = $userId;
        $row->order_number       = $order_number;
        $row->user_info_id       = $userInfoIds;
        $row->order_total        = $order_total;
        $row->order_subtotal     = $order_subtotal;
        $row->order_tax          = $order_tax;
        $row->tax_after_discount = $tax_after_discount;
        $row->order_tax_details  = '';
        $row->analytics_status   = 0;
        $row->order_shipping     = $order_shipping [3];
        $row->order_shipping_tax = $shippingVatRate;
        $row->coupon_discount    = $cdiscount;
        $row->shop_id            = $shop_id;
        $row->customer_message   = $customer_message;
        $row->referral_code      = $referral_code;
        $db                      = JFactory::getDbo();

        if ($order_total <= 0) {
            $order_status       = $paymentMethod->params->get('verify_status', '');
            $orderPaymentStatus = 'Paid';
        }

        if (Redshop::getConfig()->get('USE_AS_CATALOG')) {
            $order_status       = 'P';
            $orderPaymentStatus = 'Unpaid';
        }

        $dispatcher->trigger('onOrderStatusChange', array($post, &$order_status));

        // For barcode generation
        $row->order_discount       = $odiscount;
        $row->order_discount_vat   = $odiscount_vat;
        $row->payment_discount     = $payment_amount;
        $row->payment_oprand       = $payment_oprand;
        $row->order_status         = $order_status;
        $row->order_payment_status = $orderPaymentStatus;
        $row->cdate                = $timestamp;
        $row->mdate                = $timestamp;
        $row->ship_method_id       = $shipping_rate_id;
        $row->customer_note        = $post['customer_note'];
        $row->requisition_number   = $post['requisition_number'];
        $row->ip_address           = $ip;
        $row->encr_key             = $random_gen_enc_key;
        $row->discount_type        = $this->discount_type;
        $row->order_id             = $app->input->getInt('order_id', 0);
        $row->barcode              = null;

        if (!$row->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            // Start code to track duplicate order number checking
            $this->deleteOrdernumberTrack();

            return false;
        }

        // Start code to track duplicate order number checking
        $this->deleteOrdernumberTrack();

        // Generate Invoice Number for confirmed credit card payment or for free order
        if (((boolean)Redshop::getConfig()->get('INVOICE_NUMBER_FOR_FREE_ORDER') || $is_creditcard)
            && ('C' == $row->order_status && 'Paid' == $row->order_payment_status)) {
            RedshopHelperOrder::generateInvoiceNumber($row->order_id);
        }

        $orderId = $row->order_id;

        $this->coupon($cart);
        $this->voucher($cart, $orderId);

        $query = $db->getQuery(true);
        $query->update($db->qn('#__redshop_orders'))
            ->set(
                [
                    $db->qn('discount_type') . ' = ' . $db->q($this->discount_type)
                ]
            )
            ->where($db->qn('order_id') . ' = ' . $db->q((int)$orderId));

        $db->setQuery($query);
        $db->execute();

        if (Redshop::getConfig()->get(
                'SHOW_TERMS_AND_CONDITIONS'
            ) == 1 && isset($post['termscondition']) && $post['termscondition'] == 1) {
            RedshopHelperUser::updateUserTermsCondition($userInfoIds, 1);
        }

        // Place order id in quotation table if it Quotation
        if (array_key_exists("quotation_id", $cart) && $cart['quotation_id']) {
            RedshopHelperQuotation::updateQuotationWithOrder($cart['quotation_id'], $row->order_id);
        }

        $session->set('order_id', $orderId);

        // Add order status log
        $rowOrderStatus                = $this->getTable('order_status_log');
        $rowOrderStatus->order_id      = $orderId;
        $rowOrderStatus->order_status  = $order_status;
        $rowOrderStatus->date_changed  = time();
        $rowOrderStatus->customer_note = $order_status_log;
        $rowOrderStatus->store();

        $input->set('order_id', $row->order_id);
        $input->set('order_number', $row->order_number);

        if (!isset($order_shipping [5])) {
            $order_shipping [5] = "";
        }

        $productDeliveryTime = RedshopHelperProduct::getProductMinDeliveryTime($cart[0]['product_id']);
        $input->set('order_delivery', $productDeliveryTime);

        $idx = $cart['idx'] ?? 0;

        for ($i = 0; $i < $idx; $i++) {
            $isGiftCard = 0;
            $productId  = $cart [$i] ['product_id'];
            $product    = \Redshop\Product\Product::getProductById($productId);

            /** @var Tableorder_item_detail $rowItem */
            $rowItem = $this->getTable('order_item_detail');

            if (!$rowItem->bind($post)) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            $rowItem->delivery_time = '';

            if (isset($cart [$i] ['giftcard_id']) && $cart [$i] ['giftcard_id']) {
                $isGiftCard = 1;
            }

            // Product stockroom update
            if (!$isGiftCard) {
                $updateStock                 = RedshopHelperStockroom::updateStockroomQuantity(
                    $productId,
                    $cart [$i] ['quantity']
                );
                $stockroom_id_list           = $updateStock['stockroom_list'];
                $stockroom_quantity_list     = $updateStock['stockroom_quantity_list'];
                $rowItem->stockroom_id       = $stockroom_id_list;
                $rowItem->stockroom_quantity = $stockroom_quantity_list;
            }

            // End product stockroom update

            $values = explode('product_attributes/', $cart[$i]['hidden_attribute_cartimage']);

            if (!empty($cart[$i]['attributeImage']) && file_exists(
                    JPATH_ROOT . '/components/com_redshop/assets/images/mergeImages/' . $cart[$i]['attributeImage']
                )) {
                $rowItem->attribute_image = $orderId . $cart[$i]['attributeImage'];
                $oldMedia                 = JPATH_ROOT . '/components/com_redshop/assets/images/mergeImages/' . $cart[$i]['attributeImage'];
                $newMedia                 = JPATH_ROOT . '/components/com_redshop/assets/images/orderMergeImages/' . $rowItem->attribute_image;
                copy($oldMedia, $newMedia);
            } elseif (!empty($values[1])) {
                $rowItem->attribute_image = $values[1];
            }

            $wrapperPrice = 0;

            if (@$cart[$i]['wrapper_id']) {
                $wrapperPrice = $cart[$i]['wrapper_price'];
            }

            if ($isGiftCard == 1) {
                $giftCardData                    = RedshopEntityGiftcard::getInstance(
                    $cart[$i]['giftcard_id']
                )->getItem();
                $rowItem->product_id             = $cart [$i] ['giftcard_id'];
                $rowItem->order_item_name        = $giftCardData->giftcard_name;
                $rowItem->product_item_old_price = $cart[$i]['product_price'];
            } else {
                $rowItem->product_id             = $productId;
	            $rowItem->product_item_old_price = (isset($vatUserNoApplyTax) && $vatUserNoApplyTax == 0) ? $cart[$i]['product_price_excl_vat'] : $cart[$i]['product_old_price'];
                $rowItem->supplier_id            = $product->manufacturer_id;
                $rowItem->order_item_sku         = $product->product_number;
                $rowItem->order_item_name        = $product->product_name;
            }

	        $rowItem->product_item_price          = (isset($vatUserNoApplyTax) && $vatUserNoApplyTax == 0) ? $cart[$i]['product_price_excl_vat'] : $cart[$i]['product_price'];
            $rowItem->product_quantity            = $cart[$i]['quantity'];
            $rowItem->product_item_price_excl_vat = $cart[$i]['product_price_excl_vat'];
	        $rowItem->product_final_price         = (isset($vatUserNoApplyTax) && $vatUserNoApplyTax == 0) ? ($cart[$i]['product_price_excl_vat'] * $cart[$i]['quantity']) : ($cart[$i]['product_price'] * $cart[$i]['quantity']);
            $rowItem->is_giftcard                 = $isGiftCard;

            $retAttArr     = RedshopHelperProduct::makeAttributeCart(
                $cart[$i]['cart_attribute'],
                $productId,
                0,
                0,
                $cart[$i]['quantity']
            );
            $cartAttribute = $retAttArr[0];

            // For discount calc data
            $cartCalcData = "";

            if (isset($cart[$i]['discount_calc_output'])) {
                $cartCalcData = $cart[$i]['discount_calc_output'];
            }

            $retAccArr                    = RedshopHelperProduct::makeAccessoryCart(
                $cart[$i]['cart_accessory'],
                $productId
            );
            $cartAccessory                = $retAccArr[0];
            $rowItem->order_id            = $orderId;
            $rowItem->user_info_id        = $userInfoIds;
            $rowItem->order_item_currency = Redshop::getConfig()->get('REDCURRENCY_SYMBOL');
            $rowItem->order_status        = $order_status;
            $rowItem->cdate               = $timestamp;
            $rowItem->mdate               = $timestamp;
            $rowItem->product_attribute   = $cartAttribute;
            $rowItem->discount_calc_data  = $cartCalcData;
            $rowItem->product_accessory   = $cartAccessory;
            $rowItem->wrapper_price       = $wrapperPrice;

            if (!empty($cart[$i]['wrapper_id'])) {
                $rowItem->wrapper_id = $cart[$i]['wrapper_id'];
            }

            if (!empty($cart[$i]['reciver_email'])) {
                $rowItem->giftcard_user_email = $cart[$i]['reciver_email'];
            }

            if (!empty($cart[$i]['reciver_name'])) {
                $rowItem->giftcard_user_name = $cart[$i]['reciver_name'];
            }

            if (RedshopHelperProductDownload::checkDownload($rowItem->product_id)) {
                $mediaName = RedshopHelperProduct::getProductMediaName($rowItem->product_id);

                for ($j = 0, $jn = count($mediaName); $j < $jn; $j++) {
                    $product_serial_number = RedshopHelperProduct::getProdcutSerialNumber($rowItem->product_id);
                    RedshopHelperProduct::insertProductDownload(
                        $rowItem->product_id,
                        $user->id,
                        $rowItem->order_id,
                        $mediaName[$j]->media_name,
                        $product_serial_number->serial_number
                    );
                }
            }

            // Import files for plugin
            JPluginHelper::importPlugin('redshop_product');

            if (!$rowItem->store()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }

            // Add plugin support
            $dispatcher->trigger('afterOrderItemSave', array($cart, $rowItem, $i));

            // End

            //@TODO: Fix hard code here!!!
            if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id']) {
                $section_id = 13;
            } else {
                $section_id = 12;
            }

            \RedshopHelperProduct::insertProdcutUserfield($i, $cart, $rowItem->order_item_id, $section_id);

            // My accessory save in table start
            if (count($cart [$i] ['cart_accessory']) > 0) {
                $setPropEqual    = true;
                $setSubpropEqual = true;
                $attArr          = $cart [$i] ['cart_accessory'];

                for ($a = 0, $an = count($attArr); $a < $an; $a++) {
                    $accessory_vat_price = 0;
                    $accessory_attribute = "";

                    $accessoryId         = $attArr[$a]['accessory_id'];
                    $accessory_name      = $attArr[$a]['accessory_name'];
                    $accessory_price     = $attArr[$a]['accessory_price'];
                    $accessory_quantity  = $attArr[$a]['accessory_quantity'];
                    $accessory_org_price = $accessory_price;

                    if ($accessory_price > 0) {
                        $accessory_vat_price = RedshopHelperProduct::getProductTax(
                            $rowItem->product_id,
                            $accessory_price
                        );
                    }

                    $attchildArr = $attArr[$a]['accessory_childs'];

                    for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++) {
                        $prooprand = array();
                        $proprice  = array();

                        $propArr       = $attchildArr[$j]['attribute_childs'];
                        $totalProperty = count($propArr);

                        if ($totalProperty) {
                            $attributeId         = $attchildArr[$j]['attribute_id'];
                            $accessory_attribute .= urldecode($attchildArr[$j]['attribute_name']) . ":<br/>";

                            $rowattitem                    = $this->getTable('order_attribute_item');
                            $rowattitem->order_att_item_id = 0;
                            $rowattitem->order_item_id     = $rowItem->order_item_id;
                            $rowattitem->section_id        = $attributeId;
                            $rowattitem->section           = "attribute";
                            $rowattitem->parent_section_id = $accessoryId;
                            $rowattitem->section_name      = $attchildArr[$j]['attribute_name'];
                            $rowattitem->is_accessory_att  = 1;

                            if ($attributeId > 0) {
                                if (!$rowattitem->store()) {
                                    /** @scrutinizer ignore-deprecated */
                                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                    return false;
                                }
                            }
                        }

                        for ($k = 0; $k < $totalProperty; $k++) {
                            $prooprand[$k] = $propArr[$k]['property_oprand'];
                            $proprice[$k]  = $propArr[$k]['property_price'];
                            $section_vat   = 0;

                            if ($propArr[$k]['property_price'] > 0) {
                                $section_vat = RedshopHelperProduct::getProductTax(
                                    $rowItem->product_id,
                                    $propArr[$k]['property_price']
                                );
                            }

                            $propertyId                    = $propArr[$k]['property_id'];
                            $accessory_attribute           .= urldecode(
                                    $propArr[$k]['property_name']
                                ) . " (" . $propArr[$k]['property_oprand'] . RedshopHelperProductPrice::formattedPrice(
                                    $propArr[$k]['property_price'] + $section_vat
                                ) . ")<br/>";
                            $subpropArr                    = $propArr[$k]['property_childs'];
                            $rowattitem                    = $this->getTable('order_attribute_item');
                            $rowattitem->order_att_item_id = 0;
                            $rowattitem->order_item_id     = $rowItem->order_item_id;
                            $rowattitem->section_id        = $propertyId;
                            $rowattitem->section           = "property";
                            $rowattitem->parent_section_id = $attributeId;
                            $rowattitem->section_name      = $propArr[$k]['property_name'];
                            $rowattitem->section_price     = $propArr[$k]['property_price'];
                            $rowattitem->section_vat       = $section_vat;
                            $rowattitem->section_oprand    = $propArr[$k]['property_oprand'];
                            $rowattitem->is_accessory_att  = 1;

                            if ($propertyId > 0) {
                                if (!$rowattitem->store()) {
                                    /** @scrutinizer ignore-deprecated */
                                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                    return false;
                                }
                            }

                            for ($l = 0, $nl = count($subpropArr); $l < $nl; $l++) {
                                $section_vat = 0;

                                if ($subpropArr[$l]['subproperty_price'] > 0) {
                                    $section_vat = RedshopHelperProduct::getProductTax(
                                        $rowItem->product_id,
                                        $subpropArr[$l]['subproperty_price']
                                    );
                                }

                                $subPropertyId                 = $subpropArr[$l]['subproperty_id'];
                                $accessory_attribute           .= urldecode(
                                        $subpropArr[$l]['subproperty_name']
                                    ) . " (" . $subpropArr[$l]['subproperty_oprand'] . RedshopHelperProductPrice::formattedPrice(
                                        $subpropArr[$l]['subproperty_price'] + $section_vat
                                    ) . ")<br/>";
                                $rowattitem                    = $this->getTable('order_attribute_item');
                                $rowattitem->order_att_item_id = 0;
                                $rowattitem->order_item_id     = $rowItem->order_item_id;
                                $rowattitem->section_id        = $subPropertyId;
                                $rowattitem->section           = "subproperty";
                                $rowattitem->parent_section_id = $propertyId;
                                $rowattitem->section_name      = $subpropArr[$l]['subproperty_name'];
                                $rowattitem->section_price     = $subpropArr[$l]['subproperty_price'];
                                $rowattitem->section_vat       = $section_vat;
                                $rowattitem->section_oprand    = $subpropArr[$l]['subproperty_oprand'];
                                $rowattitem->is_accessory_att  = 1;

                                if ($subPropertyId > 0) {
                                    if (!$rowattitem->store()) {
                                        /** @scrutinizer ignore-deprecated */
                                        $this->setError(
                                        /** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg()
                                        );

                                        return false;
                                    }
                                }
                            }
                        }

                        // FOR ACCESSORY PROPERTY AND SUBPROPERTY PRICE CALCULATION
                        if ($setPropEqual && $setSubpropEqual) {
                            $accessory_priceArr = RedshopHelperProduct::makeTotalPriceByOprand(
                                $accessory_price,
                                $prooprand,
                                $proprice
                            );
                            $setPropEqual       = $accessory_priceArr[0];
                            $accessory_price    = $accessory_priceArr[1];
                        }

                        for ($t = 0, $countProperty = count($propArr), $tn = $countProperty; $t < $tn; $t++) {
                            $subprooprand  = array();
                            $subproprice   = array();
                            $subElementArr = $propArr[$t]['property_childs'];

                            for ($tp = 0, $countElement = count($subElementArr); $tp < $countElement; $tp++) {
                                $subprooprand[$tp] = $subElementArr[$tp]['subproperty_oprand'];
                                $subproprice[$tp]  = $subElementArr[$tp]['subproperty_price'];
                            }

                            if ($setPropEqual && $setSubpropEqual) {
                                $accessory_priceArr = RedshopHelperProduct::makeTotalPriceByOprand(
                                    $accessory_price,
                                    $subprooprand,
                                    $subproprice
                                );
                                $setSubpropEqual    = $accessory_priceArr[0];
                                $accessory_price    = $accessory_priceArr[1];
                            }
                        }
                    }

                    $accdata = $this->getTable('accessory_detail');

                    if ($accessoryId > 0) {
                        $accdata->load($accessoryId);
                    }

                    $accProductinfo                      = \Redshop\Product\Product::getProductById(
                        $accdata->child_product_id
                    );
                    $rowaccitem                          = $this->getTable('order_acc_item');
                    $rowaccitem->order_item_acc_id       = 0;
                    $rowaccitem->order_item_id           = $rowItem->order_item_id;
                    $rowaccitem->product_id              = $accessoryId;
                    $rowaccitem->order_acc_item_sku      = $accProductinfo->product_number;
                    $rowaccitem->order_acc_item_name     = $accessory_name;
                    $rowaccitem->order_acc_price         = $accessory_org_price;
                    $rowaccitem->order_acc_vat           = $accessory_vat_price;
                    $rowaccitem->product_quantity        = $accessory_quantity;
                    $rowaccitem->product_acc_item_price  = $accessory_price;
                    $rowaccitem->product_acc_final_price = ($accessory_price * $accessory_quantity);
                    $rowaccitem->product_attribute       = $accessory_attribute;

                    if ($accessoryId > 0) {
                        if (!$rowaccitem->store()) {
                            /** @scrutinizer ignore-deprecated */
                            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                            return false;
                        }
                    }
                }
            }

            // Storing attribute in database
            if (count($cart [$i] ['cart_attribute']) > 0) {
                $attchildArr = $cart [$i] ['cart_attribute'];

                for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++) {
                    $propArr       = $attchildArr[$j]['attribute_childs'];
                    $totalProperty = count($propArr);

                    if ($totalProperty > 0) {
                        $attributeId                   = $attchildArr[$j]['attribute_id'];
                        $rowattitem                    = $this->getTable('order_attribute_item');
                        $rowattitem->order_att_item_id = 0;
                        $rowattitem->order_item_id     = $rowItem->order_item_id;
                        $rowattitem->section_id        = $attributeId;
                        $rowattitem->section           = "attribute";
                        $rowattitem->parent_section_id = $rowItem->product_id;
                        $rowattitem->section_name      = $attchildArr[$j]['attribute_name'];
                        $rowattitem->is_accessory_att  = 0;

                        if ($attributeId > 0) {
                            if (!$rowattitem->store()) {
                                /** @scrutinizer ignore-deprecated */
                                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                return false;
                            }
                        }

                        for ($k = 0; $k < $totalProperty; $k++) {
                            $section_vat = 0;

                            if ($propArr[$k]['property_price'] > 0) {
                                $section_vat = RedshopHelperProduct::getProductTax(
                                    $rowItem->product_id,
                                    $propArr[$k]['property_price']
                                );
                            }

                            $propertyId = $propArr[$k]['property_id'];

                            //  Product property STOCKROOM update start
                            $updateStock_att             = RedshopHelperStockroom::updateStockroomQuantity(
                                $propertyId,
                                $cart [$i] ['quantity'],
                                "property",
                                $productId
                            );
                            $stockroom_att_id_list       = $updateStock_att['stockroom_list'];
                            $stockroom_att_quantity_list = $updateStock_att['stockroom_quantity_list'];

                            $rowattitem                     = $this->getTable('order_attribute_item');
                            $rowattitem->order_att_item_id  = 0;
                            $rowattitem->order_item_id      = $rowItem->order_item_id;
                            $rowattitem->section_id         = $propertyId;
                            $rowattitem->section            = "property";
                            $rowattitem->parent_section_id  = $attributeId;
                            $rowattitem->section_name       = $propArr[$k]['property_name'];
                            $rowattitem->section_price      = $propArr[$k]['property_price'];
                            $rowattitem->section_vat        = $section_vat;
                            $rowattitem->section_oprand     = $propArr[$k]['property_oprand'];
                            $rowattitem->is_accessory_att   = 0;
                            $rowattitem->stockroom_id       = $stockroom_att_id_list;
                            $rowattitem->stockroom_quantity = $stockroom_att_quantity_list;

                            if ($propertyId > 0) {
                                if (!$rowattitem->store()) {
                                    /** @scrutinizer ignore-deprecated */
                                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                                    return false;
                                }
                            }

                            $subpropArr = $propArr[$k]['property_childs'];

                            for ($l = 0, $nl = count($subpropArr); $l < $nl; $l++) {
                                $section_vat = 0;

                                if ($subpropArr[$l]['subproperty_price'] > 0) {
                                    $section_vat = RedshopHelperProduct::getProductTax(
                                        $rowItem->product_id,
                                        $subpropArr[$l]['subproperty_price']
                                    );
                                }

                                $subPropertyId = $subpropArr[$l]['subproperty_id'];

                                // Product subproperty STOCKROOM update start
                                $updateStock_subatt             = RedshopHelperStockroom::updateStockroomQuantity(
                                    $subPropertyId,
                                    $cart [$i] ['quantity'],
                                    "subproperty",
                                    $productId
                                );
                                $stockroom_subatt_id_list       = $updateStock_subatt['stockroom_list'];
                                $stockroom_subatt_quantity_list = $updateStock_subatt['stockroom_quantity_list'];

                                $rowattitem                     = $this->getTable('order_attribute_item');
                                $rowattitem->order_att_item_id  = 0;
                                $rowattitem->order_item_id      = $rowItem->order_item_id;
                                $rowattitem->section_id         = $subPropertyId;
                                $rowattitem->section            = "subproperty";
                                $rowattitem->parent_section_id  = $propertyId;
                                $rowattitem->section_name       = $subpropArr[$l]['subproperty_name'];
                                $rowattitem->section_price      = $subpropArr[$l]['subproperty_price'];
                                $rowattitem->section_vat        = $section_vat;
                                $rowattitem->section_oprand     = $subpropArr[$l]['subproperty_oprand'];
                                $rowattitem->is_accessory_att   = 0;
                                $rowattitem->stockroom_id       = $stockroom_subatt_id_list;
                                $rowattitem->stockroom_quantity = $stockroom_subatt_quantity_list;

                                if ($subPropertyId > 0) {
                                    if (!$rowattitem->store()) {
                                        /** @scrutinizer ignore-deprecated */
                                        $this->setError(
                                        /** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg()
                                        );

                                        return false;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Store user product subscription detail
            if ($product->product_type == 'subscription') {
                $subscribe           = $this->getTable('product_subscribe_detail');
                $subscription_detail = RedshopHelperProduct::getProductSubscriptionDetail(
                    $productId,
                    $cart[$i]['subscription_id']
                );

                $add_day                    = $subscription_detail->period_type == 'days' ? $subscription_detail->subscription_period : 0;
                $add_month                  = $subscription_detail->period_type == 'month' ? $subscription_detail->subscription_period : 0;
                $add_year                   = $subscription_detail->period_type == 'year' ? $subscription_detail->subscription_period : 0;
                $subscribe->order_id        = $orderId;
                $subscribe->order_item_id   = $rowItem->order_item_id;
                $subscribe->product_id      = $productId;
                $subscribe->subscription_id = $cart[$i]['subscription_id'];
                $subscribe->user_id         = $user->id;
                $subscribe->start_date      = time();
                $subscribe->end_date        = mktime(
                    0,
                    0,
                    0,
                    date('m') + $add_month,
                    date('d') + $add_day,
                    date('Y') + $add_year
                );

                if (!$subscribe->store()) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                    return false;
                }
            }
        }

        /** @var Tableorder_payment $rowpayment */
        $rowpayment = $this->getTable('order_payment');

        if (!$rowpayment->bind($post)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        $rowpayment->order_id          = $orderId;
        $rowpayment->payment_method_id = $payment_method_id;

        $creditCardData = $session->get('ccdata');

        if (!isset($creditCardData['creditcard_code'])) {
            $creditCardData['creditcard_code'] = 0;
        }

        if (!isset($creditCardData['order_payment_number'])) {
            $creditCardData['order_payment_number'] = 0;
        }

        if (!isset($creditCardData['order_payment_expire_month'])) {
            $creditCardData['order_payment_expire_month'] = 0;
        }

        if (!isset($creditCardData['order_payment_expire_year'])) {
            $creditCardData['order_payment_expire_year'] = 0;
        }

        $rowpayment->order_payment_code     = $creditCardData['creditcard_code'];
        $rowpayment->order_payment_cardname = base64_encode($creditCardData['order_payment_name']);
        $rowpayment->order_payment_number   = base64_encode($creditCardData['order_payment_number']);

        // This is ccv code
        $rowpayment->order_payment_ccv      = base64_encode($creditCardData['credit_card_code']);
        $rowpayment->order_payment_amount   = $order_total;
        $rowpayment->order_payment_expire   = $creditCardData['order_payment_expire_month'] . $creditCardData['order_payment_expire_year'];
        $rowpayment->order_payment_name     = $paymentMethod->name;
        $rowpayment->payment_method_class   = $paymentMethod->element;
        $rowpayment->order_payment_trans_id = $d ["order_payment_trans_id"];
        $rowpayment->authorize_status       = "";

        if (!$rowpayment->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        // For authorize status
        JPluginHelper::importPlugin('redshop_payment');
        JDispatcher::getInstance()->trigger(
            'onAuthorizeStatus_' . $paymentMethod->element,
            array($paymentMethod->element, $orderId)
        );

        // Add billing Info
        $userrow = $this->getTable('user_detail');
        $userrow->load($billingAddresses->users_info_id);
        $userrow->thirdparty_email = $post['thirdparty_email'];
        $orderuserrow              = $this->getTable('order_user_detail');

        if (!$orderuserrow->bind($userrow)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        $orderuserrow->order_id     = $orderId;
        $orderuserrow->address_type = 'BT';

        JPluginHelper::importPlugin('redshop_shipping');
        $dispatcher->trigger('onBeforeUserBillingStore', array(&$orderuserrow));

        if (!$orderuserrow->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        // Add shipping Info
        $userrow = $this->getTable('user_detail');

        if (isset($shippingaddresses->users_info_id)) {
            $userrow->load($shippingaddresses->users_info_id);
        } elseif (!empty($GLOBALS['shippingaddresses'])) {
            $userrow = $GLOBALS['shippingaddresses'];
        } else {
            $userrow->load($billingAddresses->users_info_id);
        }

        $orderuserrow = $this->getTable('order_user_detail');

        if (!$orderuserrow->bind($userrow)) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        $orderuserrow->order_id     = $orderId;
        $orderuserrow->address_type = 'ST';

        $dispatcher->trigger('onBeforeUserShippingStore', array(&$orderuserrow, $row));

        if (!$orderuserrow->store()) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

            return false;
        }

        if ($row->order_status == Redshop::getConfig()->get('CLICKATELL_ORDER_STATUS')) {
            \RedshopHelperClickatell::clickatellSMS($orderId);
        }

        if (isset($cart['extrafields_values'])) {
            if (count($cart['extrafields_values']) > 0) {
                RedshopHelperProduct::insertPaymentShippingField($cart, $orderId, 18);
                RedshopHelperProduct::insertPaymentShippingField($cart, $orderId, 19);
            }
        }

        RedshopHelperStockroom::deleteCartAfterEmpty();

        // Economic Integration start for invoice generate and book current invoice
        if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get(
                'ECONOMIC_INVOICE_DRAFT'
            ) != 2) {
            $economicdata['economic_payment_terms_id'] = $economic_payment_terms_id;
            $economicdata['economic_design_layout']    = $economic_design_layout;
            $economicdata['economic_is_creditcard']    = $is_creditcard;
            $payment_name                              = $paymentMethod->element;
            $paymentArr                                = explode("rs_payment_", $paymentMethod->element);

            if (count($paymentArr) > 0) {
                $payment_name = $paymentArr[1];
            }

            $economicdata['economic_payment_method'] = $payment_name;
            RedshopEconomic::createInvoiceInEconomic($row->order_id, $economicdata);

            if (Redshop::getConfig()->getInt('ECONOMIC_INVOICE_DRAFT') == 0) {
                $checkOrderStatus = ($isBankTransferPaymentType) ? 0 : 1;

                $bookinvoicepdf = RedshopEconomic::bookInvoiceInEconomic($row->order_id, $checkOrderStatus);

                if (JFile::exists($bookinvoicepdf)) {
                    Redshop\Mail\Invoice::sendEconomicBookInvoiceMail($row->order_id, $bookinvoicepdf);
                }
            }
        }

        // Send the Order mail before payment
        if (!Redshop::getConfig()->get('ORDER_MAIL_AFTER') || (Redshop::getConfig()->get(
                    'ORDER_MAIL_AFTER'
                ) && $row->order_payment_status == "Paid")) {
            Redshop\Mail\Order::sendMail($row->order_id);
        } elseif (Redshop::getConfig()->get('ORDER_MAIL_AFTER') == 1) {
            // If Order mail set to send after payment then send mail to administrator only.
            Redshop\Mail\Order::sendMail($row->order_id, true);
        }

        if ($row->order_status == "C" && $row->order_payment_status == "Paid") {
            RedshopHelperOrder::sendDownload($row->order_id);
        }

        return $row;
    }

    public function shipaddress($userInfoId)
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__redshop_users_info')
            ->where($db->quoteName('users_info_id') . ' = ' . (int)$userInfoId);

        return $db->setQuery($query)->loadObject();
    }

    /**
     * Method for return billing address.
     *
     * @return  object
     */
    public function billingaddresses()
    {
        $user           = JFactory::getUser();
        $session        = JFactory::getSession();
        $auth           = $session->get('auth');
        $billingAddress = new stdClass;

        if ($user->id) {
            $billingAddress = RedshopHelperOrder::getBillingAddress($user->id);
        } elseif ($auth['users_info_id']) {
            $billingAddress = RedshopHelperOrder::getBillingAddress(-$auth['users_info_id']);
        }

        if ($billingAddress === false || $billingAddress === null) {
            return new stdClass;
        }

        return $billingAddress;
    }

    /**
     * @param   string  $shippingRateId  Shipping rate
     *
     * @return  array
     *
     * @since   2.1.0
     *
     * @deprecated
     */
    public function calculateShipping($shippingRateId)
    {
        return Redshop\Helper\Shipping::calculateShipping($shippingRateId);
    }

    /**
     * Delete order number track
     *
     * @return boolean
     *
     * @since  2.1.0
     */
    public function deleteOrdernumberTrack()
    {
        $db    = JFactory::getDbo();
        $query = 'TRUNCATE TABLE ' . $db->quoteName('#__redshop_ordernumber_track');

        if (!$db->setQuery($query)->execute()) {
            $msg = /** @scrutinizer ignore-deprecated */
                $db->getErrorMsg();
            /** @scrutinizer ignore-deprecated */
            $this->setError($msg);

            return false;
        }

        return true;
    }

    public function coupon($cart)
    {
        $user       = JFactory::getUser();
        $db         = JFactory::getDbo();
        $couponType = array();

        if (isset($cart['coupon'])) {
            if ($this->discount_type) {
                $this->discount_type .= '@';
            }

            foreach ($cart['coupon'] as $coupon) {
                $coupon_id             = $coupon['coupon_id'];
                $coupon_volume         = $coupon['used_coupon'];
                $transaction_coupon_id = 0;
                $couponType[]          = 'c:' . $coupon['coupon_code'];

                if ($coupon['remaining_coupon_discount_old'] <= 0 || $coupon['remaining_coupon_discount_old'] < $cart['coupon_discount']) {
                    $sql = "UPDATE " . $this->_table_prefix . "coupons SET amount_left = amount_left - " . (int)$coupon_volume . " "
                        . "WHERE id = " . (int)$coupon_id;
                    $db->setQuery($sql)->execute();
                }

                $rowcoupon = $this->getTable('transaction_coupon_detail');

                if (!$rowcoupon->bind($cart)) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
                }

                if ($coupon['transaction_coupon_id']) {
                    $transaction_coupon_id = $coupon['transaction_coupon_id'];
                }

                $rowcoupon->transaction_coupon_id = $transaction_coupon_id;
                $rowcoupon->coupon_value          = $coupon['remaining_coupon_discount'];
                $rowcoupon->coupon_code           = $coupon['coupon_code'];
                $rowcoupon->userid                = $user->id;
                $rowcoupon->coupon_id             = $coupon_id;
                $rowcoupon->trancation_date       = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $rowcoupon->published             = 1;

                if (!$rowcoupon->store()) {
                    /** @scrutinizer ignore-deprecated */
                    $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                    return false;
                }
            }

            $this->discount_type = implode('@', $couponType);
        }

        return true;
    }

    public function voucher($cart, $order_id)
    {
        if (!isset($cart['voucher'])) {
            return;
        }

        if ($this->discount_type) {
            $this->discount_type .= '@';
        }

        $user        = JFactory::getUser();
        $voucherType = array();

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        foreach ($cart['voucher'] as $voucher) {
            $voucherId            = $voucher['voucher_id'];
            $voucherVolume        = $voucher['used_voucher'];
            $transactionVoucherId = 0;
            $voucherType[]        = 'v:' . $voucher['voucher_code'];

            $query->clear();
            $query->update($db->quoteName('#__redshop_voucher'))
                ->set(
                    $db->quoteName('voucher_left') . ' = ' . $db->quoteName(
                        'voucher_left'
                    ) . ' - ' . (int)$voucherVolume
                )
                ->where($db->quoteName('id') . ' = ' . (int)$voucherId);

            $db->setQuery($query)->execute();

            if ($voucher['remaining_voucher_discount'] <= 0) {
                continue;
            }

            $table = $this->getTable('transaction_voucher_detail');

            if (!$table->bind($cart)) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());
            }

            if ($voucher['transaction_voucher_id']) {
                $transactionVoucherId = $voucher['transaction_voucher_id'];
            }

            $table->transaction_voucher_id = $transactionVoucherId;
            $table->amount                 = $voucher['remaining_voucher_discount'];
            $table->voucher_code           = $voucher['voucher_code'];
            $table->user_id                = $user->id;
            $table->order_id               = $order_id;
            $table->voucher_id             = $voucherId;
            $table->trancation_date        = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $table->product_id             = $voucher['product_id'];
            $table->published              = 1;

            if (!$table->store()) {
                /** @scrutinizer ignore-deprecated */
                $this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

                return false;
            }
        }

        $this->discount_type .= implode('@', $voucherType);
    }

    /**
     * Method for send giftcard email to customer.
     *
     * @param   int  $orderId  ID of order.
     *
     * @return  void
     *
     * @throws  Exception
     */
    public function sendGiftCard($orderId)
    {
        \Redshop\Mail\Giftcard::sendMail($orderId);
    }

    public function shippingaddresses()
    {
        $user    = JFactory::getUser();
        $session = JFactory::getSession();
        $auth    = $session->get('auth');

        if ($user->id) {
            return RedshopHelperOrder::getShippingAddress($user->id);
        }

        $uid = -$auth['users_info_id'];

        return RedshopHelperOrder::getShippingAddress($uid);
    }

    public function getpaymentmethod()
    {
        $user          = JFactory::getUser();
        $shopper_group = RedshopHelperOrder::getBillingAddress($user->id);
        $query         = "SELECT * FROM " . $this->_table_prefix . "payment_method WHERE published = '1' AND (FIND_IN_SET('" . (int)$shopper_group->shopper_group_id . "', shopper_group) OR shopper_group = '') ORDER BY ordering ASC";
        $this->_db->setQuery($query);

        return $this->_db->loadObjectlist();
    }

    /**
     * @return mixed
     */
    public function validatePaymentCreditCardInfo()
    {
        $session        = JFactory::getSession();
        $creditCardData = $session->get('ccdata');

        $validPayment [0] = 1;
        $validPayment [1] = '';

        if ($creditCardData['selectedCardId'] != '') {
            return $validPayment;
        }

        // The Data should be in the session.
        if (!isset($creditCardData)) {
            $validPayment [0] = 0;
            $validPayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CCDATA');

            return $validPayment;
        }

        if (isset($creditCardData['order_payment_name'])) {
            if (preg_match("/[0-9]+/", $creditCardData['order_payment_name']) == true) {
                $validPayment [0] = 0;
                $validPayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CCNM_FOUND');

                return $validPayment;
            }
        }

        if (!$creditCardData['order_payment_number']) {
            $validPayment [0] = 0;
            $validPayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CCNR_FOUND');

            return $validPayment;
        }

        if ($creditCardData['order_payment_number']) {
            if (!is_numeric($creditCardData['order_payment_number'])) {
                $validPayment [0] = 0;
                $validPayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CCNR_NUM_FOUND');

                return $validPayment;
            }
        }

        if (!$creditCardData['order_payment_expire_month']) {
            $validPayment [0] = 0;
            $validPayment [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_MON_FOUND');

            return $validPayment;
        }

        $creditCardError     = '';
        $creditCardErrorText = '';

        if (!$this->checkCreditCard(
            $creditCardData['order_payment_number'],
            $creditCardData['creditcard_code'],
            $creditCardError,
            $creditCardErrorText
        )) {
            $validPayment [0] = 0;
            $validPayment [1] = $creditCardErrorText;

            return $validPayment;
        }

        return $validPayment;
    }

    public function checkCreditCard($cardnumber, $cardname, &$errornumber, &$errortext)
    {
        /**
         * Define the cards we support. You may add additional card types.
         *
         * Name:      As in the selection box of the form - must be same as user's
         * Length:    List of possible valid lengths of the card number for the card
         * Prefixes:  List of possible prefixes for the card
         *
         * Checkdigit Boolean to say whether there is a check digit
         * Don't forget - all but the last array definition needs a comma separator!
         */

        $cards = array(

            // American Express
            array(
                'name'       => 'amex',
                'length'     => '15',
                'prefixes'   => '34,37',
                'checkdigit' => true
            ),
            array(
                'name'       => 'Diners Club Carte Blanche',
                'length'     => '14',
                'prefixes'   => '300,301,302,303,304,305',
                'checkdigit' => true
            ),

            // Diners Club
            array(
                'name'       => 'diners',
                'length'     => '14,16',
                'prefixes'   => '36,54,55',
                'checkdigit' => true
            ),
            array(
                'name'       => 'Discover',
                'length'     => '16',
                'prefixes'   => '6011,622,64,65',
                'checkdigit' => true
            ),
            array(
                'name'       => 'Diners Club Enroute',
                'length'     => '15',
                'prefixes'   => '2014,2149',
                'checkdigit' => true
            ),
            array(
                'name'       => 'JCB',
                'length'     => '16',
                'prefixes'   => '35',
                'checkdigit' => true
            ),
            array(
                'name'       => 'Maestro',
                'length'     => '12,13,14,15,16,18,19',
                'prefixes'   => '5018,5020,5038,6304,6759,6761',
                'checkdigit' => true
            ),

            // MasterCard
            array(
                'name'       => 'MC',
                'length'     => '16',
                'prefixes'   => '51,52,53,54,55',
                'checkdigit' => true
            ),
            array(
                'name'       => 'Solo',
                'length'     => '16,18,19',
                'prefixes'   => '6334,6767',
                'checkdigit' => true
            ),
            array(
                'name'       => 'Switch',
                'length'     => '16,18,19',
                'prefixes'   => '4903,4905,4911,4936,564182,633110,6333,6759',
                'checkdigit' => true
            ),
            array(
                'name'       => 'Visa',
                'length'     => '13,16',
                'prefixes'   => '4',
                'checkdigit' => true
            ),
            array(
                'name'       => 'Visa Electron',
                'length'     => '16',
                'prefixes'   => '417500,4917,4913,4508,4844',
                'checkdigit' => true
            ),
            array(
                'name'       => 'LaserCard',
                'length'     => '16,17,18,19',
                'prefixes'   => '6304,6706,6771,6709',
                'checkdigit' => true
            )
        );

        $creditCardErrors [0] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_UNKNOWN_CCTYPE');
        $creditCardErrors [1] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CARD_PROVIDED');
        $creditCardErrors [2] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CARD_INVALIDFORMAT');
        $creditCardErrors [3] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CARD_INVALIDNUMBER');
        $creditCardErrors [4] = JText::_('COM_REDSHOP_CHECKOUT_ERR_NO_CARD_WRONGLENGTH');

        // Establish card type
        $cardType = -1;

        for ($i = 0, $in = count($cards); $i < $in; $i++) {
            // See if it is this card (ignoring the case of the string)
            if (strtolower($cardname) == strtolower($cards [$i] ['name'])) {
                $cardType = $i;
                break;
            }
        }

        // If card type not found, report an error
        if ($cardType == -1) {
            $errornumber = 0;
            $errortext   = $creditCardErrors [$errornumber];

            return false;
        }

        // Ensure that the user has provided a credit card number
        if (strlen($cardnumber) == 0) {
            $errornumber = 1;
            $errortext   = $creditCardErrors [$errornumber];

            return false;
        }

        // Remove any spaces from the credit card number
        $cardNo = str_replace(' ', '', $cardnumber);

        // Check that the number is numeric and of the right sort of length.
        if (!preg_match("/^[0-9]{13,19}$/i", $cardNo)) {
            $errornumber = 2;
            $errortext   = $creditCardErrors [$errornumber];

            return false;
        }

        // Now check the modulus 10 check digit - if required
        if ($cards [$cardType] ['checkdigit']) {
            // Running checksum total
            $checksum = 0;

            // Next char to process
            $mychar = "";

            // Takes value of 1 or 2
            $j = 1;

            // Process each digit one by one starting at the right
            // @TODO: access string by curly brace is deprecated php 7.4
            for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {
                // Extract the next digit and multiply by 1 or 2 on alternative digits.
                $calc = $cardNo[$i] * $j;

                // If the result is in two digits add 1 to the checksum total
                if ($calc > 9) {
                    $checksum++;
                    $calc = $calc - 10;
                }

                // Add the units element to the checksum total
                $checksum = $checksum + $calc;

                // Switch the value of j
                if ($j == 1) {
                    $j = 2;
                } else {
                    $j = 1;
                }
            }

            // All done - if checksum is divisible by 10, it is a valid modulus 10.
            // If not, report an error.
            if ($checksum % 10 != 0) {
                $errornumber = 3;
                $errortext   = $creditCardErrors [$errornumber];

                return false;
            }
        }

        // The following are the card-specific checks we undertake.

        // Load an array with the valid prefixes for this card
        $prefix = explode(',', $cards[$cardType]['prefixes']);

        // Now see if any of them match what we have in the card number

        $PrefixValid = false;

        for ($i = 0, $in = count($prefix); $i < $in; $i++) {
            $exp = '/^' . $prefix [$i] . '/';

            if (preg_match($exp, $cardNo)) {
                $PrefixValid = true;
                break;
            }
        }

        // If it isn't a valid prefix there's no point at looking at the length
        if (!$PrefixValid) {
            $errornumber = 3;
            $errortext   = $creditCardErrors [$errornumber];

            return false;
        }

        // See if the length is valid for this card
        $LengthValid = false;
        $lengths     = explode(',', $cards[$cardType]['length']);

        for ($j = 0, $jn = count($lengths); $j < $jn; $j++) {
            if (strlen($cardNo) == $lengths [$j]) {
                $LengthValid = true;
                break;
            }
        }

        // See if all is OK by seeing if the length was valid.
        if (!$LengthValid) {
            $errornumber = 4;
            $errortext   = $creditCardErrors [$errornumber];

            return false;
        }

        // The credit card is in the required format.
        return true;
    }

    /**
     * @param   string  $creditcardNumber  Credit card number
     * @param   string  $type              Type
     *
     * @since  2.1.0
     */
    public function validateCC($creditcardNumber, $type)
    {
        echo \Redshop\Validation\Creditcard::isValid($creditcardNumber, $type);
    }

    public function resetcart()
    {
        $session = JFactory::getSession();
        setcookie("redSHOPcart", "", time() - 3600, "/");
        \Redshop\Cart\Helper::setCart(null);
        $session->set('ccdata', null);
        $session->set('issplit', null);
        $session->set('userfield', null);
        $user = JFactory::getUser();
        RedshopHelperCart::removeCartFromDatabase($cart_id = 0, $user->id, $delCart = true);
    }

    /**
     * Method for get coupon price
     *
     * @return  float
     * @deprecated
     */
    public function getCouponPrice()
    {
        return Redshop\Promotion\Coupon::getCouponPrice();
    }

    public function getCategoryNameByProductId($pid)
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->qn('c.name'))
            ->from($db->qn('#__redshop_product_category_xref', 'pcx'))
            ->leftjoin(
                $db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id')
            )
            ->where($db->qn('pcx.product_id') . ' = ' . $db->q((int)$pid))
            ->where($db->qn('c.name') . ' IS NOT NULL')
            ->order($db->qn('c.id') . ' ASC')
            ->setLimit(0, 1);

        return $db->setQuery($query)->loadResult();
    }

    /**
     * Get Unique order number
     *
     * @return integer
     *
     * @since  2.1.0
     */
    public function getOrdernumber()
    {
        $trackIdTime = $this->getOrdernumberTrack();

        if (!empty($trackIdTime)) {
            $toTime       = strtotime(date('Y-m-d H:i:s'));
            $fromTime     = strtotime($trackIdTime);
            $totalMinutes = round(abs($toTime - $fromTime) / 60, 2);

            if ($totalMinutes > 1) {
                $this->deleteOrdernumberTrack();
                $trackIdTime = "";
            }
        }

        if (!empty($trackIdTime)) {
            return $this->getOrdernumber();
        }

        $this->insertOrdernumberTrack();
        $order_number = RedshopHelperOrder::generateOrderNumber();

        return $order_number;
    }

    /**
     * Count order number track
     *
     * @return mixed
     *
     * @since  2.1.0
     */
    public function getOrdernumberTrack()
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('trackdatetime'))
            ->where($db->quoteName('#__redshop_ordernumber_track'));

        return $db->setQuery($query)->loadResult();
    }

    /**
     * Insert order number track
     *
     * @return boolean
     *
     * @since  2.1.0
     */
    public function insertOrdernumberTrack()
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->insert($db->quoteName('#__redshop_ordernumber_track'))
            ->columns($db->quoteName('trackdatetime'))
            ->values('NOW()');

        if (!$db->setQuery($query)->execute()) {
            $msg = /** @scrutinizer ignore-deprecated */
                $db->getErrorMsg();

            /** @scrutinizer ignore-deprecated */
            $this->setError($msg);

            return false;
        }

        return true;
    }
}
