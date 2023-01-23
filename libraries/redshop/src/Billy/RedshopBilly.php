<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2022 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       3.0.4
 */

namespace Redshop\Billy;

use Joomla\Registry\Registry;
use Redshop\Template\Helper;
use RedshopHelperUtility;

defined('_JEXEC') or die;

/**
 * Library for Redshop Billy.
 * This Library provide methods for interact with E-Invoicing and support to orders.
 * For more information about E-invoicing: https://en.wikipedia.org/wiki/Electronic_invoicing
 * Using: RedshopBilly::<method>
 *
 * @since  3.0.3
 */
class RedshopBilly
{
    /**
     * The dispatcher to trigger events
     *
     * @var  \JEventDispatcher
     */
    public static $dispatcher;

    /**
     * Import Stock from Billy
     *
     * @param   object  $productRow  Product Info
     *
     * @return  array
     *
     * @since   3.0.4
     */
    public static function importStockFromBilly($productRow)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $bil = array('product_number' => $productRow->product_number);

        return \RedshopHelperUtility::getDispatcher()->trigger('getProductStock', array($bil));
    }

    /**
     * Currently it will import plugin: billy, then
     * pre-define a dispatcher to trigger event in other methods.
     *
     * @return  void
     *
     * @since  3.0.4
     */
    public static function importBilly()
    {
        \JPluginHelper::importPlugin('billy');
    }

    /**
     * Method to create Invoice and send mail in Billy
     *
     * @param   object $orderData Order data
     *
     * @return  array
     *
     * @since   3.0.4
     */
    public static function renewInvoiceInBilly($orderData)
    {
        $invoiceHandle = array();

        if ($orderData->is_billy_booked == 0) {
            // Delete existing draft invoice from Billy
            if ($orderData->billy_invoice_no) {
                $deleted = self::deleteInvoiceInBilly($orderData);
            }
            
            $orderData->renewInvoice = 1;
            $invoiceHandle           = self::createInvoiceInBilly($orderData->order_id);

            if ($invoiceHandle == true) {
                \JFactory::getApplication('administrator')->enqueueMessage(\JText::_('COM_REDSHOP_BILLY_RENEW_INVOICE_SUCCES') . $orderData->order_id, 'message');
            }
        }

        return $invoiceHandle;
    }

    /**
     * Method to delete invoice in Billy
     *
     * @param   array $orderData Order data to delete
     *
     * @return  void
     *
     * @since   3.0.4
     */
    public static function deleteInvoiceInBilly($orderData = array())
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        if ($orderData->billy_invoice_no) {
            $bil['invoiceHandle'] = $orderData->billy_invoice_no;
            $deletedInBilly 	  = \RedshopHelperUtility::getDispatcher()->trigger('deleteInvoice', array($bil));
            
            if ($deletedInBilly[0] === true) {
                self::updateInvoiceNumber($orderData->order_id, 0);

                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Update invoice number
     *
     * @param   integer $orderId   Order ID
     * @param   integer $invoiceNo Invoice number
     *
     * @return  void
     *
     * @since   3.0.4
     */
    public static function updateInvoiceNumber($orderId = 0, $invoiceNo = 0)
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->update($db->qn('#__redshop_orders'))
            ->set($db->qn('billy_invoice_no') . ' = ' . $db->quote($invoiceNo))
            ->where($db->qn('order_id') . ' = ' . (int) $orderId);
        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Update Invoice payment method in billy
     *
     * @param   integer $orderId Order ID
     *
     * @return  mixed
     *
     * @since   3.0.3
     */
    public static function updatePaymentTermsInBilly($orderId)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $orderDetail = \RedshopEntityOrder::getInstance($orderId)->getItem();
        $paymentInfo = \RedshopEntityOrder::getInstance($orderId)->getPayment()->getItem();
    
        if (!empty($paymentInfo)) {
            // Get plugin params
            $plugin      = \JPluginHelper::getPlugin('billy', 'billy');
            $billyParams = new \JRegistry($plugin->params);		
            $paymentName = $paymentInfo->payment_method_class;
            
            if ($paymentName == 'rs_payment_banktransfer') {
                $billyPluginPaymentDays = $billyParams->get('billy_payment_days_banktransfer1');
                $paymenttermsMode       = (int) $billyParams->get('billy_paymenttermsmode_banktransfer1', '');
                
                if ($paymenttermsMode == "0") {
                    $invoicePaymentTermsMode = 'date';
                } else if ($paymenttermsMode == "1") {
                    $invoicePaymentTermsMode = 'net';
                } else if ($paymenttermsMode == "2") {
                    $invoicePaymentTermsMode = 'netEndOfMonth';
                } else {
                    $invoicePaymentTermsMode = '';
                }
            } else if ($paymentName == 'rs_payment_banktransfer2') {
                $billyPluginPaymentDays = $billyParams->get('billy_payment_days_banktransfer2');
                $paymenttermsMode       = (int) $billyParams->get('billy_paymenttermsmode_banktransfer2', '');
                
                if ($paymenttermsMode == "0") {
                    $invoicePaymentTermsMode = 'date';
                } else if ($paymenttermsMode == "1") {
                    $invoicePaymentTermsMode = 'net';
                } else if ($paymenttermsMode == "2") {
                    $invoicePaymentTermsMode = 'netEndOfMonth';
                } else {
                    $invoicePaymentTermsMode = '';
                }
            } else if ($paymentName == 'rs_payment_banktransfer_discount') {
                $billyPluginPaymentDays = $billyParams->get('billy_payment_days_banktransfer_discount');
                $paymenttermsMode       = (int) $billyParams->get('billy_paymenttermsmode_banktransfer_discount', '');
                
                if ($paymenttermsMode == "0") {
                    $invoicePaymentTermsMode = 'date';
                } else if ($paymenttermsMode == "1") {
                    $invoicePaymentTermsMode = 'net';
                } else if ($paymenttermsMode == "2") {
                    $invoicePaymentTermsMode = 'netEndOfMonth';
                } else {
                    $invoicePaymentTermsMode = '';
                }
            } else if ($paymentName == 'rs_payment_cashtransfer') {
                $billyPluginPaymentDays = $billyParams->get('billy_payment_days_cashtransfer');
                $paymenttermsMode       = (int) $billyParams->get('billy_paymenttermsmode_cashtransfer', '');
                
                if ($paymenttermsMode == "0") {
                    $invoicePaymentTermsMode = 'date';
                } else if ($paymenttermsMode == "1") {
                    $invoicePaymentTermsMode = 'net';
                } else if ($paymenttermsMode == "2") {
                    $invoicePaymentTermsMode = 'netEndOfMonth';
                } else {
                    $invoicePaymentTermsMode = '';
                }
            } else if ($paymentName == 'rs_payment_cashsale') {
                $billyPluginPaymentDays = $billyParams->get('billy_payment_days_cashsale');
                $paymenttermsMode       = (int) $billyParams->get('billy_paymenttermsmode_cashsale', '');
                
                if ($paymenttermsMode == "0") {
                    $invoicePaymentTermsMode = 'date';
                } else if ($paymenttermsMode == "1") {
                    $invoicePaymentTermsMode = 'net';
                } else if ($paymenttermsMode == "2") {
                    $invoicePaymentTermsMode = 'netEndOfMonth';
                } else {
                    $invoicePaymentTermsMode = '';
                }
            } else if ($paymentName == 'rs_payment_eantransfer') {
                $billyPluginPaymentDays = $billyParams->get('billy_payment_days_ean');
                $paymenttermsMode       = (int) $billyParams->get('billy_paymenttermsmode_ean', '');
                
                if ($paymenttermsMode == "0") {
                    $invoicePaymentTermsMode = 'date';
                } else if ($paymenttermsMode == "1") {
                    $invoicePaymentTermsMode = 'net';
                } else if ($paymenttermsMode == "2") {
                    $invoicePaymentTermsMode = 'netEndOfMonth';
                } else {
                    $invoicePaymentTermsMode = '';
                }
            } else {
                $invoicePaymentTermsMode = '';
                $billyPluginPaymentDays = '';
            }
        }

        if ($orderDetail->billy_invoice_no) {
            $bil['invoice_paymenttermdays'] = $billyPluginPaymentDays;			
            $bil['invoice_paymenttermmode'] = $invoicePaymentTermsMode;
            $bil['billy_invoice_no'] 		= $orderDetail->billy_invoice_no;

            \RedshopHelperUtility::getDispatcher()->trigger('updateInvoicePayment', array($bil));
        }		
    }

    /**
     * Create Invoice in billy
     *
     * @param   integer $orderId Order ID
     * @param   $orderId
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public static function createInvoiceInBilly($orderId)
    {
        $orderEntity = \RedshopEntityOrder::getInstance($orderId);

        // Order is not valid.
        if (!$orderEntity->isValid()) {
            return true;
        }

        // Order already booked or already has invoice number.
        if ($orderEntity->get('is_billy_booked') != 0 || !empty($orderEntity->get('billy_invoice_no'))) {
            return true;
        }

        // If using Dispatcher, must call plugin Economic first
        self::importBilly();

        $order            = $orderEntity->getItem();		
        $userBillingInfo  = $orderEntity->getBilling();
        $userShippinginfo = $orderEntity->getShipping();
        $orderItem        = \RedshopHelperOrder::getOrderItemDetail($orderId);
        $debtorNumber     = self::createUserInBilly($userBillingInfo->getItem());
        $paymentInfo 	  = \RedshopEntityOrder::getInstance($orderId)->getPayment()->getItem();

        if (count($debtorNumber) > 0 && $debtorNumber[0]) {
            $cdate    = date("Y-m-d", $orderEntity->get('cdate'));

            $bil['name']               = $userBillingInfo->get('firstname') . " " 
                                            . $userBillingInfo->get('lastname');
            $bil['isvat']              = ($orderEntity->get('order_tax') != 0) ? 1 : 0;
            $bil['email']              = $userBillingInfo->get('user_email');
            $bil['phone']              = $userBillingInfo->get('phone');
            $bil['currency_code']      = \Redshop::getConfig()->get('CURRENCY_CODE');
            $bil['order_number']       = $orderEntity->get('order_number');
            $bil['amount']             = $orderEntity->get('order_total');
            $bil['debtorHandle']       = $debtorNumber[0];
            $bil['user_info_id']       = $userBillingInfo->get('users_info_id');
            $bil['customer_note']      = $orderEntity->get('customer_note');
            $bil['requisition_number'] = $orderEntity->get('requisition_number');
            $bil['vatzone']            = self::getBillyTaxZone($userBillingInfo->get('country_code'));
            $bil['cdate'] 			   = $cdate;
            $bil['order_id']           = $orderEntity->get('order_id');
            $bil['setAttname']         = 0;

            if ($userBillingInfo->get('is_company == 1')) {
                $bil['setAttname'] = 1;
            }

            if (!empty($paymentInfo)) {
                $paymentName = $paymentInfo->payment_method_class;
                // Get plugin params
                $plugin      = \JPluginHelper::getPlugin('billy', 'billy');
                   $billyParams = new \JRegistry($plugin->params);

                if ($paymentName == 'rs_payment_banktransfer') {
                    $billyPluginPaymentDays = $billyParams->get('billy_payment_days_banktransfer1');
                    $paymenttermsMode       = (int) $billyParams->get('billy_paymenttermsmode_banktransfer1', '');

                    if ($paymenttermsMode == "0") {
                        $invoicePaymentTermsMode = 'date';
                    }
                    if ($paymenttermsMode == "1") {
                        $invoicePaymentTermsMode = 'net';
                    }
                    if ($paymenttermsMode == "2") {
                        $invoicePaymentTermsMode = 'netEndOfMonth';
                    }
                }
                if ($paymentName == 'rs_payment_banktransfer2') {
                    $billyPluginPaymentDays = $billyParams->get('billy_payment_days_banktransfer2');
                    $paymenttermsMode         = (int) $billyParams->get('billy_paymenttermsmode_banktransfer2', '');

                    if ($paymenttermsMode == "0") {
                        $invoicePaymentTermsMode = 'date';
                    }
                    if ($paymenttermsMode == "1") {
                        $invoicePaymentTermsMode = 'net';
                    }
                    if ($paymenttermsMode == "2") {
                        $invoicePaymentTermsMode = 'netEndOfMonth';
                    }
                }
                if ($paymentName == 'rs_payment_banktransfer_discount') {
                    $billyPluginPaymentDays = $billyParams->get('billy_payment_days_banktransfer_discount');
                    $paymenttermsMode         = (int) $billyParams->get('billy_paymenttermsmode_banktransfer_discount', '');

                    if ($paymenttermsMode == "0") {
                        $invoicePaymentTermsMode = 'date';
                    }
                    if ($paymenttermsMode == "1") {
                        $invoicePaymentTermsMode = 'net';
                    }
                    if ($paymenttermsMode == "2") {
                        $invoicePaymentTermsMode = 'netEndOfMonth';
                    }
                }

                if ($paymentName == 'rs_payment_eantransfer') {
                    $billyPluginPaymentDays = $billyParams->get('billy_payment_days_ean');
                    $paymenttermsMode         = (int) $billyParams->get('billy_paymenttermsmode_ean', '');
                    if ($paymenttermsMode == "0") {
                        $invoicePaymentTermsMode = 'date';
                    }
                    if ($paymenttermsMode == "1") {
                        $invoicePaymentTermsMode = 'net';
                    }
                    if ($paymenttermsMode == "2") {
                        $invoicePaymentTermsMode = 'netEndOfMonth';
                    }
                }
            }

            $bil['invoice_paymenttermdays'] = $billyPluginPaymentDays;			
            $bil['invoice_paymenttermmode'] = $invoicePaymentTermsMode;

            $attribute_as_product_in_billy = $billyParams->get('attribute_as_product_in_billy');
                
            if ($attribute_as_product_in_billy == 2) {
                $lines = self::getInvoiceLineInBillyAsProduct($orderItem, $orderEntity->get('user_id'));
            } else {
                $lines = self::getInvoiceLineInBilly($orderItem, $orderEntity->get('user_id'));
            }

            // get Shipping Line
            $lines = self::getInvoiceShippingLineInBilly($orderEntity->get('ship_method_id'), $lines);

            // get discount Line
            $isVatDiscount = 0;

            if ((float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') 
                && $orderEntity->get('order_discount') != "0.00" 
                && $orderEntity->get('order_tax') 
                && !empty($orderEntity->get('order_discount'))) 
            {
                $totalDiscount        = $orderEntity->get('order_discount');
                $vatRateTotalDiscount = (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $totalDiscount;
                $vatRateAfterDiscount = 1 + (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT');
                $discountVat          = $vatRateTotalDiscount / $vatRateAfterDiscount;
                $orderDiscountVAT     = $totalDiscount - $discountVat;
                $isVatDiscount        = 1;
            }

            if ((float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') 
                && $orderEntity->get('special_discount_amount') != "0.00" 
                && $orderEntity->get('order_tax') 
                && !empty($orderEntity->get('special_discount_amount'))) 
            {
                $totalDiscount            = $orderEntity->get('special_discount_amount');
                $vatRateTotalDiscount     = (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $totalDiscount;
                $vatRateAfterDiscount     = 1 + (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT');
                $discountVat              = $vatRateTotalDiscount / $vatRateAfterDiscount;
                $specialDiscountAmountVAT = $totalDiscount - $discountVat;
                $isVatDiscount            = 1;
            }

            if ((float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') 
                && $orderEntity->get('payment_discount') != "0.00" 
                && $orderEntity->get('order_tax') 
                && !empty($orderEntity->get('payment_discount'))) 
            {
                $totalDiscount            = $orderEntity->get('payment_discount');
                $vatRateTotalDiscount     = (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $totalDiscount;
                $vatRateAfterDiscount     = 1 + (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT');
                $discountVat              = $vatRateTotalDiscount / $vatRateAfterDiscount;
                $paymentDiscountAmountVAT = $totalDiscount - $discountVat;
                $isVatDiscount            = 1;
            }

            $order->order_total_discount = $orderDiscountVAT + $specialDiscountAmountVAT + $paymentDiscountAmountVAT;

            if ($order->order_total_discount) {
                $lines = self::getInvoiceDiscountLineInBilly($order, $lines, $data, 0, $isVatDiscount);
            }
                
            $lines1 = array();
                
            foreach($lines as $a => $line){
                $line->description = trim($line->description);
                $lines1[]          = $line;
            }
                
            $lines = $lines1;

            // Finally create Invoice
            $invoiceHandle = \RedshopHelperUtility::getDispatcher()->trigger('createInvoice', array($bil, $orderEntity, $lines));
                
            if (count($invoiceHandle) > 0 && $invoiceHandle[0]) {
                $invoiceNo = $invoiceHandle[0];
                self::updateInvoiceNumber($orderId, $invoiceNo);
            }

            return $invoiceNo;
        } else {
            return "COM_REDSHOP_BILLY_USER_NOT_SAVED_IN_BILLY";
        }

        return true;
    }

    /**
     * Create a user in Billy
     *
     * @param   object $row  Data to create user
     * @param   array  $data Data of Billy
     *
     * @return  array
     *
     * @since   3.0.4
     */
    public static function createUserInBilly($row = array())
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        // Get billy user Id from redSHOP user
        $db                   = \JFactory::getDbo();
        $selSQL               = "SELECT billy_id from `#__redshop_billy_relation` WHERE redshop_id = '".$row->users_info_id."' AND relation_type='user'";
        $db->setQuery($selSQL);
        $billyId 			  = $db->loadResult();

        $bil                  = array();
        $bil['billy_user_id'] = $billyId;
        $bil['user_id']       = $row->user_id;
        $bil['user_info_id']  = $row->users_info_id;
        $debtorHandle         = \RedshopHelperUtility::getDispatcher()->trigger('debtorFindByNumber', array($bil));

        $bil['currency_code'] = \Redshop::getConfig()->get('CURRENCY_CODE');
        // vatzone is probertly not used in Billy?
        $bil['vatzone']       = self::getBillyTaxZone($row->country_code);
        $bil['email']         = $row->user_email;

        if ($row->is_company == 1) {
            if ($row->vat_number != "") {
                $bil['vatnumber'] = $row->vat_number;
            }

            if ($row->ean_number  != "") {
                $bil['ean_number'] = $row->ean_number;
            }
            
            $bil['type'] = 'company';
        }
        else {
            $bil['vatnumber'] = "";
            $bil['type'] 	  = 'person';
        }

        $name = $row->firstname . ' ' . $row->lastname;

        if ($row->is_company == 1 && $row->company_name != '') {
            $name = $row->company_name;
        }

        $bil['name']    	 	= $name;
        $bil['contact_name'] 	= $row->firstname . ' ' . $row->lastname;
        $bil['phone']   	 	= $row->phone;
        $bil['address'] 	 	= $row->address;
        $bil['zipcode'] 	 	= $row->zipcode;
        $bil['city']    	 	= $row->city;
        $bil['country'] 	 	= \RedshopHelperWorld::getCountryCode2($row->country_code);
        $bil['country_id']      = $row->country_code;
        $bil['eco_user_number'] = "";
        $bil['newuserFlag']     = false;

        if ($debtorHandle && count($debtorHandle) > 0) {
            if ($debtorHandle[0]->id) {
                $bil['eco_user_number'] = $debtorHandle[0]->id;
                $debitorNumber 			= $debtorHandle[0]->id;
                $returnDebtor[0] 		= $debtorHandle[0]->id;
                $bilDebtorNumber 		= \RedshopHelperUtility::getDispatcher()->trigger('storeDebtor', array($bil));
                $debitorNumber 			= $bilDebtorNumber[0];
                $returnDebtor 			= $bilDebtorNumber;
            } else {
                $bil['newuserFlag'] = true;
                $bilDebtorNumber 	= \RedshopHelperUtility::getDispatcher()->trigger('storeDebtor', array($bil));
                $debitorNumber 		= $bilDebtorNumber[0];
                $returnDebtor 		= $bilDebtorNumber;
            }
        } else {
            $bil['newuserFlag'] = true;
            $bilDebtorNumber    = \RedshopHelperUtility::getDispatcher()->trigger('storeDebtor', array($bil));
            $debitorNumber      = $bilDebtorNumber[0];
            $returnDebtor       = $bilDebtorNumber;
        }
        
        // Store User Billy number in Database
        if ($debitorNumber) {
            if (!$billyId) {
                 $sql = "INSERT INTO `#__redshop_billy_relation` (`relation_type`, `redshop_id`, `billy_id`)
                VALUES ('user', '".$row->users_info_id."', '".$debitorNumber."')";
                $db->setQuery($sql);
                $db->query();
            } else {
                $sql = "UPDATE `#__redshop_billy_relation` SET `billy_id` = '".$debitorNumber."' WHERE
                 `redshop_id` = '".$row->users_info_id."' AND `relation_type` ='user'";
                $db->setQuery($sql);
                $db->query();
            }
        }

        return $returnDebtor;
    }

    /**
     * Get billy Tax zone
     *
     * @param   string $countryCode Country code
     *
     * @return  string
     *
     * @since   3.0.4
     */
    public static function getBillyTaxZone($countryCode = "")
    {
        if ($countryCode == \Redshop::getConfig()->get('SHOP_COUNTRY'))
        {
            $taxzone = 'HomeCountry';
        }
        elseif (self::isEuCountry($countryCode))
        {
            $taxzone = 'EU';
        }
        else
        {
            // Non EU Country
            $taxzone = 'Abroad';
        }

        return $taxzone;
    }

    /**
     * Check country is belong to EU
     *
     * @param   string $country Country code
     *
     * @return  boolean
     *
     * @since   3.0.4
     */
    public static function isEuCountry($country)
    {
        $euCountry = array('AUT', 'BGR', 'BEL', 'CYP', 'CZE', 'DEU', 'DNK', 'ESP', 'EST', 'FIN',
            'FRA', 'FXX', 'GBR', 'GRC', 'HUN', 'IRL', 'ITA', 'LVA', 'LTU', 'LUX',
            'MLT', 'NLD', 'POL', 'PRT', 'ROM', 'SVK', 'SVN', 'SWE');

        return in_array($country, $euCountry);
    }

    /**
     * Method to create Invoice line in Billy as Product
     *
     * @param   array   $orderItem Order Item
     * @param   string  $invoiceNo Invoice Number
     * @param   integer $userId    User ID
     *
     * @return  void
     *
     * @since   3.0.4
     */
    public static function createInvoiceLineInBillyAsProduct($orderItem = array(), $invoiceNo = "", $userId = 0)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        $bil = array();

        for ($i = 0, $in = count($orderItem); $i < $in; $i++)
        {
            $displayWrapper = "";

            $productId = $orderItem[$i]->product_id;
            $product   = \Redshop::product((int) $productId);

            self::createProductInBilly($product);

            if ($orderItem[$i]->wrapper_id) {
                $wrapper = \RedshopHelperProduct::getWrapper($orderItem[$i]->product_id, $orderItem[$i]->wrapper_id);

                if (count($wrapper) > 0) {
                    $wrapperName    = $wrapper[0]->name;
                    $displayWrapper = "\n" . \JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName . "(" . $orderItem[$i]->wrapper_price . ")";
                }
            }

            // Fetch Accessory from Order Item
            $displayAccessory = self::makeAccessoryOrder($invoiceNo, $orderItem[$i], $userId);

            $bil['updateInvoice']  = 0;
            $bil['invoiceHandle']  = $invoiceNo;
            $bil['order_item_id']  = $orderItem[$i]->order_item_id;
            $bil['product_number'] = $orderItem[$i]->order_item_sku;

            $discountCalc = "";

            if ($orderItem[$i]->discount_calc_data) {
                $discountCalc = $orderItem[$i]->discount_calc_data;
                $discountCalc = str_replace("<br />", "\n", $discountCalc);
                $discountCalc = "\n" . $discountCalc;
            }

            // Product user field Information
            $pUserfield     = \RedshopHelperProduct::getuserfield($orderItem[$i]->order_item_id);
            $displayWrapper = $displayWrapper . "\n" . strip_tags($pUserfield);

            $bil['product_name']     = $orderItem[$i]->order_item_name . $displayWrapper . $discountCalc . $displayAccessory;
            $bil['product_price']    = $orderItem[$i]->product_item_price_excl_vat;
            $bil['product_quantity'] = $orderItem[$i]->product_quantity;
            $bil['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

            // Collect Order Attribute Items
            $orderItemAttData = \RedshopHelperOrder::getOrderItemAttributeDetail($orderItem[$i]->order_item_id, 0, 
                "attribute", $orderItem[$i]->product_id);

            if (count($orderItemAttData) > 0) {
                $attributeId = $orderItemAttData[0]->section_id;
                $productId   = $orderItem[$i]->product_id;

                $orderPropData = \RedshopHelperOrder::getOrderItemAttributeDetail($orderItem[$i]->order_item_id, 0, "property", $attributeId);

                if (count($orderPropData) > 0) {
                    $propertyId = $orderPropData[0]->section_id;

                    // Collect Attribute Property
                    $orderProperty = \RedshopHelperProduct_Attribute::getAttributeProperties($propertyId, $attributeId, $productId);

                    $propertyNumber = $orderProperty[0]->property_number;
                    $propertyName   = $orderPropData[0]->section_name;

                    if ($propertyNumber) {
                        $bil['product_number'] = $propertyNumber;
                    }

                    $bil['product_name'] = $orderItem[$i]->order_item_name . " " . $propertyName . $displayWrapper . $discountCalc;
                }
            }

            \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($bil));
        }
    }

    /**
     * Create product in Billy
     *
     * @param   object  $row  Data to create
     *
     * @return  array
     *
     * @since   3.0.4
     */
    public static function createProductInBilly($row = array())
    {
        $db       = \JFactory::getDbo();
        $selSQL   = "SELECT billy_id from `#__redshop_billy_relation` WHERE redshop_id = '" . $row->product_number . "' AND relation_type='product'";
        $db->setQuery($selSQL);
        $billyId = $db->loadResult();

        if (empty($billyId)) {
            // Get plugin params
            $plugin                    = \JPluginHelper::getPlugin('billy', 'billy');
            $billyParams               = new \JRegistry($plugin->params);
            $attributeAsProductInBilly = $billyParams->get('attribute_as_product_in_billy');
        
            if ($attributeAsProductInBilly == 2 && self::getTotalProperty($row->product_id) > 0) {
                return;
            }

            $bil                   = array();
            $bil['product_desc']   = utf8_encode(substr(strip_tags($row->product_desc), 0, 499));
            $bil['product_s_desc'] = utf8_encode(substr(strip_tags($row->product_s_desc), 0, 499));

            $bilProductGroupNumber = self::createProductGroupInBilly($row);
        
            if (isset($bilProductGroupNumber[0])) {
                $bil['product_group'] = $bilProductGroupNumber[0];
            }
        
            $isNoVat = (int) $billyParams->get('billy_redshop_no_vat_id');		
        
            if ($isNoVat == $row->product_tax_group_id) {
                $bil['product_tax_id'] = $billyParams->get('default_billy_tax_group_without_vat');
            } else {
                $bil['product_tax_id'] = $billyParams->get('default_billy_tax_group');
            }
        
            $bil['product_number'] = trim($row->product_number);
            $bil['product_name']   = addslashes($row->product_name);
            $bil['product_price']  = $row->product_price;
            $bil['product_volume'] = $row->product_volume;		
            $bil['billy_id'] 	   = $billyId;
            $bil['product_stock']  = \RedshopHelperStockroom::getStockroomTotalAmount($row->product_id);
            $bil['currency_code']  = \Redshop::getConfig()->get('CURRENCY_CODE');

            $BillyProductId        = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($bil));		

            if (!empty($billyId)) {	
                $bil['eco_prd_number'] = $BillyProductId[0];
            } else {
                $bil['eco_prd_number'] = "";
            }

            $bilProductNumber 	  = \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($bil));
            $bilProductNumber 	  = $bilProductNumber[0];
        
            // Store Product Billy number in Database
            if ($bilProductNumber) {
                if (!$billyId) {
                    $sql = "INSERT INTO `#__redshop_billy_relation` (`relation_type`, `redshop_id`, `billy_id`)
                    VALUES ('product', '".$row->product_number."', '".$bilProductNumber."')";
                    $db->setQuery($sql);
                    $db->query();
                } else {
                    $sql = "UPDATE `#__redshop_billy_relation` SET `billy_id` = '".$bilProductNumber."' WHERE
                            `redshop_id` = '".$row->product_number."' AND `relation_type` ='product'";
                    $db->setQuery($sql);
                    $db->query();
                }
            }
        
        return $bilProductNumber;
        }
    }

    /**
     * Get Total Property
     *
     * @param   integer $productId Product ID
     *
     * @return  integer
     *
     * @since   3.0.4
     */
    public static function getTotalProperty($productId)
    {
        // Collect Attributes
        $attribute   = \Redshop\Product\Attribute::getProductAttribute($productId);
        $attributeId = $attribute[0]->value;

        // Collect Property
        $property = \RedshopHelperProduct_Attribute::getAttributeProperties(0, $attributeId, $productId);

        return count($property);
    }

    /**
     * Create Product Group in Billy
     *
     * @param   object   $row         Data to create
     * @param   integer  $isShipping  Shipping flag
     * @param   integer  $isDiscount  Discount flag
     * @param   integer  $isVat       VAT flag
     *
     * @return  null/array
     *
     * @since   3.0.4
     */
    public static function createProductGroupInBilly($row = array(), $isShipping = 0, $isDiscount = 0, $isVat = 0)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        // Get plugin params
        $plugin      = \JPluginHelper::getPlugin('billy', 'billy');
           $billyParams = new \JRegistry($plugin->params);
        $row = (object) $row;

        $bilProductGroupNumber         = new \stdClass;
        $bilProductGroupNumber->Number = 1;
        $accountGroup			       = array();
        $defaultBillyAccountGroup	   = $billyParams->get('default_billy_account_group');
        $defaultBillyAccountNoVatGroup = $billyParams->get('default_billy_account_group_without_vat');	
        $isNoVat					   = (int) $billyParams->get('billy_redshop_no_vat_id', '');

        if (count($row) > 0 && $row->billy_accountgroup_id != 0) {
            $accountGroup = self::getAllAccountsFromBilly($row->billy_accountgroup_id);
        }
        else {
            if ($isShipping) {
                if ($isVat) {
                    $accountGroup = self::getAllAccountsFromBilly($defaultBillyAccountGroup);
                }
                else {
                    $accountGroup = self::getAllAccountsFromBilly($defaultBillyAccountNoVatGroup);
                }
            }
            else if ($isDiscount) {
                if ($isVat) {
                    $accountGroup = self::getAllAccountsFromBilly($defaultBillyAccountGroup);
                }
                else {
                    $accountGroup = self::getAllAccountsFromBilly($defaultBillyAccountNoVatGroup);
                }
            } else {
                if ($isNoVat == $row->product_tax_group_id) {
                    $accountGroup = self::getAllAccountsFromBilly($defaultBillyAccountNoVatGroup);
                } else {
                    $accountGroup = self::getAllAccountsFromBilly($defaultBillyAccountGroup);
                }
            }
        }

        if (count($accountGroup) > 0) {
            $bil['productgroup_id']   = $accountGroup->id;
            $bil['productgroup_name'] = $accountGroup->name;
            $bil['producttaxrate_id'] = $accountGroup->taxRateId;
            $bil['eco_prdgro_number'] = "";

            if (isset($accountGroup->id) != "") {
                $bil['eco_prdgro_number'] = $accountGroup->id;
            }
            $bilProductGroupNumber = \RedshopHelperUtility::getDispatcher()->trigger('storeProductGroup', array($bil));
        }
        return $bilProductGroupNumber;
    }

    /**
     * Make Accessory Order
     *
     * @param   string  $invoiceNo Invoice number
     * @param   object  $orderItem Order item
     * @param   integer $userId    User ID
     *
     * @return  integer
     *
     * @since   3.0.3
     */
    public static function makeAccessoryOrder($invoiceNo, $orderItem, $userId = 0)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $bil              = array();
        $displayAccessory = "";
        $setPrice         = 0;
        $orderItem        = (object) $orderItem;
        $orderItemData    = \RedshopHelperOrder::getOrderItemAccessoryDetail($orderItem->order_item_id);

        if (count($orderItemData) > 0) {
            $displayAccessory .= "\n" . \JText::_("COM_REDSHOP_ACCESSORY");

            for ($i = 0, $in = count($orderItemData); $i < $in; $i++)
            {
                $product = self::getProductByNumber($orderItemData[$i]->order_acc_item_sku);

                if (count($product) > 0) {
                    self::createProductInBilly($product);
                }

                $accessoryQuantity = " (" . \JText::_('COM_REDSHOP_ACCESSORY_QUANTITY_LBL') . " " . $orderItemData[$i]->product_quantity . ") ";
                $displayAccessory  .= "\n" . urldecode($orderItemData[$i]->order_acc_item_name)
                    . " (" . ($orderItemData[$i]->order_acc_price + $orderItemData[$i]->order_acc_vat) . ")" . $accessoryQuantity;

                $setPrice += $orderItemData[$i]->product_acc_item_price;

                $bil['updateInvoice']    = 0;
                $bil['invoiceHandle']    = $invoiceNo;
                $bil['order_item_id']    = $orderItem->order_item_id;
                $bil['product_number']   = $orderItemData[$i]->order_acc_item_sku;
                $bil['product_name']     = $orderItemData[$i]->order_acc_item_name;
                $bil['product_price']    = $orderItemData[$i]->product_acc_item_price;
                $bil['product_quantity'] = $orderItemData[$i]->product_quantity;
                $bil['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
                $invoiceLineNo           = \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($bil));

                $displayAttribute = self::makeAttributeOrder($invoiceNo, $orderItem, 1, $orderItemData[$i]->product_id, $userId);
                $displayAccessory .= $displayAttribute;

                if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') != 0)
                {
                    $orderItemData[$i]->product_acc_item_price -= $displayAttribute;
                    $displayAttribute                          = '';
                }

                if (count($invoiceLineNo) > 0 && $invoiceLineNo[0]->Number)
                {
                    $bil['updateInvoice']    = 1;
                    $bil['invoiceHandle']    = $invoiceNo;
                    $bil['order_item_id']    = $invoiceLineNo[0]->Number;
                    $bil['product_number']   = $orderItemData[$i]->order_acc_item_sku;
                    $bil['product_name']     = $orderItemData[$i]->order_acc_item_name . $displayAttribute;
                    $bil['product_price']    = $orderItemData[$i]->product_acc_item_price;
                    $bil['product_quantity'] = $orderItemData[$i]->product_quantity;
                    $bil['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

                    $invoiceLineNo = \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($bil));
                }
            }
        }

        $displayAccessory = $setPrice;

        return $displayAccessory;
    }

    /**
     * Get product number
     *
     * @param   string $productNumber Product Number
     *
     * @return  object
     *
     * @since   3.0.3
     */
    public static function getProductByNumber($productNumber = '')
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_product'))
            ->where($db->qn('product_number') . ' = ' . $db->quote($productNumber));
        $db->setQuery($query);

        return $db->loadObject();
    }

    /**
     * Make Attribute Order
     *
     * @param   string  $invoiceNo       Invoice number
     * @param   object  $orderItem       Order Item
     * @param   integer $isAccessory     Is accessory
     * @param   integer $parentSectionId Parent Section ID
     * @param   integer $userId          User ID
     *
     * @return  integer
     *
     * @since   3.0.3
     */
    public static function makeAttributeOrder($invoiceNo, $orderItem, $isAccessory = 0, $parentSectionId = 0, $userId = 0)
    {
        $displayAttribute = "";
        $setPrice         = 0;
        $orderItem        = (object) $orderItem;
        $checkShowVAT     = Helper::isApplyAttributeVat('', $userId);
        $orderItemAttData = \RedshopHelperOrder::getOrderItemAttributeDetail($orderItem->order_item_id, $isAccessory, "attribute", $parentSectionId);

        if (count($orderItemAttData) > 0)
        {
            $product = \Redshop::product((int) $parentSectionId);

            for ($i = 0, $in = count($orderItemAttData); $i < $in; $i++)
            {
                $attribute          = \Redshop\Product\Attribute::getProductAttribute(0, 0, $orderItemAttData[$i]->section_id);
                $hideAttributePrice = 0;

                if (count($attribute) > 0)
                {
                    $hideAttributePrice = $attribute[0]->hide_attribute_price;
                }

                $displayAttribute .= "\n" . urldecode($orderItemAttData[$i]->section_name) . " : ";
                $orderPropData    = \RedshopHelperOrder::getOrderItemAttributeDetail(
                    $orderItem->order_item_id,
                    $isAccessory, "property",
                    $orderItemAttData[$i]->section_id
                );

                for ($p = 0, $pn = count($orderPropData); $p < $pn; $p++)
                {
                    $property      = \RedshopHelperProduct_Attribute::getAttributeProperties($orderPropData[$p]->section_id);
                    $virtualNumber = "";

                    if (count($property) > 0 && $property[0]->property_number)
                    {
                        $virtualNumber = "[" . $property[0]->property_number . "]";

                        if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') != 0)
                        {
                            $orderPropData[$p]->virtualNumber = $property[0]->property_number;
                            self::createPropertyInBilly($product, $property[0]);
                        }
                    }

                    $disPrice = "";

                    if (!$hideAttributePrice)
                    {
                        $propertyPrice = $orderPropData[$p]->section_price;

                        if (!empty($checkShowVAT))
                        {
                            $propertyPrice = $orderPropData[$p]->section_price + $orderPropData[$p]->section_vat;
                        }

                        $disPrice = " (" . $orderPropData[$p]->section_oprand . \RedshopHelperProductPrice::formattedPrice($propertyPrice) . ")";
                    }

                    $displayAttribute .= urldecode($orderPropData[$p]->section_name) . $disPrice . $virtualNumber;

                    if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') != 0)
                    {
                        $setPrice += $orderPropData[$p]->section_price;
                        self::createAttributeInvoiceLineInBilly($invoiceNo, $orderItem, array($orderPropData[$p]));
                    }

                    $orderSubPropertyData = \RedshopHelperOrder::getOrderItemAttributeDetail(
                        $orderItem->order_item_id,
                        $isAccessory,
                        "subproperty",
                        $orderPropData[$p]->section_id
                    );

                    if (count($orderSubPropertyData) > 0)
                    {
                        foreach ($orderSubPropertyData as $aData)
                        {
                            $subproperty   = \RedshopHelperProduct_Attribute::getAttributeSubProperties($aData->section_id);
                            $virtualNumber = "";

                            if (count($subproperty) > 0 && $subproperty[0]->subattribute_color_number)
                            {
                                $virtualNumber = "[" . $subproperty[0]->subattribute_color_number . "]";

                                if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') != 0)
                                {
                                    $aData->virtualNumber = $subproperty[0]->subattribute_color_number;
                                    self::createSubpropertyInBilly($product, $subproperty[0]);
                                }
                            }

                            $disPrice = "";

                            if (!$hideAttributePrice)
                            {
                                $subpropertyPrice = $aData->section_price;

                                if (!empty($checkShowVAT))
                                {
                                    $subpropertyPrice = $aData->section_price + $aData->section_vat;
                                }

                                $disPrice = " (" . $aData->section_oprand . \RedshopHelperProductPrice::formattedPrice($subpropertyPrice) . ")";
                            }

                            $displayAttribute .= "\n" . urldecode($aData->section_name) . $disPrice . $virtualNumber;

                            if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') != 0)
                            {
                                $setPrice += $aData->section_price;
                            }
                        }

                        if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') != 0)
                        {
                            self::createAttributeInvoiceLineInBilly($invoiceNo, $orderItem, $orderSubPropertyData);
                        }
                    }
                }
            }
        }

        if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') != 0) {
            $displayAttribute = $setPrice;
        }

        return $displayAttribute;
    }

    /**
     * Create property product in billy
     *
     * @param   object  $productRow  Product data
     * @param   object  $row         Data property
     *
     * @return  array
     *
     * @since   3.0.3
     */
    public static function createPropertyInBilly($productRow = null, $row = null)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $bil                   = array();
        $bil['product_desc']   = '';
        $bil['product_s_desc'] = '';

        $bilProductGroupNumber = self::createProductGroupInBilly($productRow);

        if (isset($bilProductGroupNumber[0]->Number)) {
            $bil['product_group'] = $bilProductGroupNumber[0]->Number;
        }

        $bil['product_number'] = $row->property_number;

        if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') == 2) {
            $bil['product_name'] = addslashes($productRow->product_name) . " " . addslashes($row->property_name);

            $string = trim($productRow->product_price . $row->oprand . $row->property_price);
            eval('$bil["product_price"] = ' . $string . ';');
        } else {
            $bil['product_name']  = addslashes($row->property_name);
            $bil['product_price'] = $row->property_price;
        }

        $bil['product_volume'] = 1;
        $debtorHandle          = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($bil));
        $bil['eco_prd_number'] = "";

        if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "") {
            $bil['eco_prd_number'] = $debtorHandle[0]->Number;
        }

        $bil['product_stock'] = \RedshopHelperStockroom::getStockroomTotalAmount($row->property_id, "property");

        return \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($bil));
    }

    /**
     * Create Attribute Invoice Line In Billy
     *
     * @param   string $invoiceNo           Invoice number
     * @param   object $orderItem           Order Item
     * @param   array  $orderAttributeItems Order Attribute Item
     *
     * @return  void
     *
     * @since   3.0.3
     */
    public static function createAttributeInvoiceLineInBilly($invoiceNo, $orderItem, $orderAttributeItems)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        $bil       = array();
        $orderItem = (object) $orderItem;

        for ($i = 0, $in = count($orderAttributeItems); $i < $in; $i++)
        {
            $bil[$i]['invoiceHandle']    = $invoiceNo;
            $bil[$i]['order_item_id']    = $orderItem->order_item_id;
            $bil[$i]['product_number']   = $orderAttributeItems[$i]->virtualNumber;
            $bil[$i]['product_name']     = $orderAttributeItems[$i]->section_name;
            $bil[$i]['product_price']    = $orderAttributeItems[$i]->section_price;
            $bil[$i]['product_quantity'] = $orderItem->product_quantity;
            $bil[$i]['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

            \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($bil[$i]));
        }
    }

    /**
     * Create Sub Property in Billy
     *
     * @param   object  $productRow  Product info
     * @param   object  $row         Data of property
     *
     * @return  array
     *
     * @since   3.0.3
     */
    public static function createSubpropertyInBilly($productRow = null, $row = null)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        $bil = array();

        $bil['product_desc']   = '';
        $bil['product_s_desc'] = '';

        $bilProductGroupNumber = self::createProductGroupInBilly($productRow);

        if (isset($bilProductGroupNumber[0]->Number)) {
            $bil['product_group'] = $bilProductGroupNumber[0]->Number;
        }

        $bil['product_number'] = $row->subattribute_color_number;

        if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_BILLY') == 2) {
            $bil['product_name'] = addslashes($row->subattribute_color_name);
            $string              = trim($productRow->product_price . $row->oprand . $row->subattribute_color_price);
            eval('$bil["product_price"] = ' . $string . ';');
        } else {
            $bil['product_name']  = addslashes($row->subattribute_color_name);
            $bil['product_price'] = $row->subattribute_color_price;
        }

        $bil['product_volume'] = 1;
        $debtorHandle          = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($bil));
        $bil['eco_prd_number'] = "";

        if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "") {
            $bil['eco_prd_number'] = $debtorHandle[0]->Number;
        }

        $bil['product_stock'] = \RedshopHelperStockroom::getStockroomTotalAmount($row->subattribute_color_id, "subproperty");

        return \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($bil));
    }

    /**
     * Create Invoice Line In Billy
     *
     * @param   array   $orderItem Order Items
     * @param   string  $invoiceNo Invoice Number
     * @param   integer $userId    User ID
     *
     * @return  void
     *
     * @since   3.0.4
     */
    public static function getInvoiceLineInBilly($orderItem = array(), $userId = 0)
    {
        // If using Dispatcher, must call plugin Economic first
        self::importBilly();

        // Get plugin params
        $plugin                    = \JPluginHelper::getPlugin('billy', 'billy');
           $billyParams               = new \JRegistry($plugin->params);
        $attributeAsProductInBilly = $billyParams->get('attribute_as_product_in_billy');
        
        if ($attributeAsProductInBilly == 2) {
            return;
        }

        $lines = array();
        
        for ($i = 0, $in = count($orderItem); $i < $in; $i++) {
            $displaywrapper   = "";
            $displayattribute = "";
            $displayaccessory = "";

            // Create Gift Card Entry for invoice
            if ($orderItem[$i]->is_giftcard) {
                self::createGiftCardInvoiceLineInBilly($orderItem[$i], $userId);

                continue;
            }

            $productId 		       = $orderItem[$i]->product_id;
            $product    		   = \Redshop::product((int) $productId);
            $product->product_name = $orderItem[$i]->order_item_name;

            self::createProductInBilly($product);

            if ($orderItem[$i]->wrapper_id) {
                $wrapper = \RedshopHelperProduct::getWrapper($orderItem[$i]->product_id, $orderItem[$i]->wrapper_id);

                if (count($wrapper) > 0) {
                    $wrapperName = $wrapper[0]->wrapper_name;
                
                    $displayWrapper = "\n" . \JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName . "(" . $orderItem[$i]->wrapper_price . ")";
                }
            }

            $bil ['order_item_id']  = $orderItem[$i]->order_item_id;
            $bil ['product_number'] = $orderItem[$i]->order_item_sku;
            $product_tax 			= ($orderItem[$i]->product_item_price - $orderItem[$i]->product_item_price_excl_vat) * $orderItem[$i]->product_quantity;

            // Get billy product Id from redshop number
            $db     = \JFactory::getDbo();
            $selSQL = "SELECT billy_id from `#__redshop_billy_relation` WHERE redshop_id = '".$orderItem[$i]->order_item_sku."' AND relation_type='product'";
            $db->setQuery($selSQL);
            
            $billyId 			     = $db->loadResult();
            $bil['billy_id'] 	     = $billyId;
            $BillyproductId  	     = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($bil));
            $BillyproductId 	     = $BillyproductId[0];
            $bil['billy_product_id'] = $BillyproductId;

            $discountCalc 			 = "";

            if ($orderItem[$i]->discountCalc_data) {
                $discountCalc = $orderItem[$i]->discount_calc_data;
                $discountCalc = str_replace("<br />", "", $discountCalc);
                $discountCalc = "" . $discountCalc;
            }

            // Product user field Information - Get HREF values before striping out html
            $productUserfield = \RedshopHelperProduct::getuserfield($orderItem[$i]->order_item_id);			
            $dom 		      = new \DOMDocument;
            $dom->loadHTML($productUserfield);
            $userFieldLabel   = true;
            
            foreach ($dom->getElementsByTagName('span') as $node) {
                if ($userFieldLabel) {
                    $printLabel     = $node->nodeValue;
                    $userFieldLabel = false;
                }
            }
            
            foreach ($dom->getElementsByTagName('a') as $node) {
                if ($node->hasAttribute( 'href' )) {
                    $fileName = $node->nodeValue;
                }
            }
            
            $displaywrapper = $displaywrapper . "" . $printLabel . $fileName . "\n";

            $bil ['product_name']     = $orderItem[$i]->order_item_name . $displaywrapper . $displayattribute . $discountCalc . $displayaccessory;
            $bil ['product_price']    = $orderItem[$i]->product_item_price_excl_vat;
            $bil ['product_quantity'] = $orderItem[$i]->product_quantity;
            $bil ['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

            $displayattribute         = self::makeAttributeOrder($orderItem[$i], 0, $orderItem[$i]->product_id, $userId);
            
            if ($attributeAsProductInBilly != 0) {
                $orderItem[$i]->product_item_price_excl_vat -= $displayattribute;
                $displayattribute = '';
            }

            $displayaccessory = self::makeAccessoryOrder( $orderItem[$i], $userId);

            $orderItem[$i]->product_item_price_excl_vat -= $displayaccessory;
            $displayaccessory = '';
            $j = $i;

            $lineItems = new \stdclass();
            $lineItems->productId 	= $BillyproductId;
            $lineItems->unitPrice 	= round($orderItem[$i]->product_item_price_excl_vat,2);
            $lineItems->description = $displaywrapper . $displayattribute . $discountCalc . $displayaccessory;
            $lineItems->quantity 	= $orderItem[$i]->product_quantity;
            $lineItems->priority 	= $j++;
            $lines[] = $lineItems;		
        }

        return $lines;
    }

    /**
     * Create Invoice line in Billy for GiftCard
     *
     * @param   array  $orderItem Order Item
     * @param   string $invoiceNo Invoice Number
     *
     * @return  void
     *
     * @since   3.0.3
     */
    public static function createGiftCardInvoiceLineInBilly($orderItem = array(), $invoiceNo = "")
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $product                   = new \stdClass;
        $orderItem                 = (object) $orderItem;
        $product->product_id       = $orderItem->product_id;
        $product->product_number   = "gift_" . $orderItem->product_id . "_" . $orderItem->order_item_name;
        $orderItem->order_item_sku = $product->product_number;
        $product->product_name     = $orderItem->order_item_name;
        $product->product_price    = $orderItem->product_item_price_excl_vat;
        $giftData                  = \RedshopEntityGiftcard::getInstance($orderItem->product_id)->getItem();
        $product->accountgroup_id  = $giftData->accountgroup_id;
        $product->product_volume   = 0;

        self::createProductInBilly($product);

        $bil                     = array();
        $bil['updateInvoice']    = 0;
        $bil['invoiceHandle']    = $invoiceNo;
        $bil['order_item_id']    = $orderItem->order_item_id;
        $bil['product_number']   = $orderItem->order_item_sku;
        $bil['product_name']     = $orderItem->order_item_name;
        $bil['product_price']    = $orderItem->product_item_price_excl_vat;
        $bil['product_quantity'] = $orderItem->product_quantity;
        $bil['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

        \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($bil));
    }

    /**
     * Method to create Invoice line for shipping in Billy
     *
     * @param   string $shipMethodId Shipping method ID
     * @param   string $invoiceNo    Invoice Number
     *
     * @return  void
     *
     * @since   3.0.4
     */
    public function getInvoiceShippingLineInBilly($shipMethodId = "", $lines = array())
    {
        if ($shipMethodId != "") {
            $orderShipping = \Redshop\Shipping\Rate::decrypt(str_replace(" ", "+", $shipMethodId));

            if (count($orderShipping) > 5) {
                $shippingNShortname = (strlen($orderShipping[1]) > 15) ? substr($orderShipping[1], 0, 15) : $orderShipping[1];
                $shippingNumber     = $shippingNShortname . ' ' . $orderShipping[4];
                $shippingName       = $orderShipping[2];
                $shippingRate       = $orderShipping[3];

                $isVat = 0;

                if (isset($orderShipping[6]) && $orderShipping[6] != 0) {
                    $isVat        = 1;
                    $shippingRate = $shippingRate - $orderShipping[6];
                }

                if (isset($orderShipping[7]) && $orderShipping[7] != '') {
                    $shippingNumber = $orderShipping[7];
                }

                echo $isVat;
                echo "<br/>";

                $bilShippingrateNumber = self::createShippingRateInBilly($shippingNumber, $shippingName, $shippingRate, $isVat);

                if (isset($bilShippingrateNumber) && $bilShippingrateNumber) {
                    $lineItems->productId 	= $bilShippingrateNumber;
                    $lineItems->unitPrice 	= round($shippingRate,2);
                    $lineItems->description = '';
                    $lineItems->quantity  	= 1;
                    $lineItems->priority  	= 30;
                    $lines[]                = $lineItems;
                }
            }
        }

        return $lines;
    }

    /**
     * Create Shipping rate in billy
     *
     * @param   integer $shippingNumber Shipping Number
     * @param   string  $shippingName   Shipping Name
     * @param   integer $shippingRate   Shipping Rate
     * @param   integer $isVat          VAT flag
     *
     * @return  array
     *
     * @since   3.0.4
     */
    public static function createShippingRateInBilly($shippingNumber, $shippingName, $shippingRate = 0, $isVat = 1)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        
        $bil                   = array();
        $bil['product_desc']   = "";
        $bil['product_s_desc'] = "";

        $bilProductGroupNumber = self::createProductGroupInBilly(array(), 1, 0, $isVat);
        
        if (isset($bilProductGroupNumber[0])) {
            $bil['product_group'] = $bilProductGroupNumber[0];
        } else {
            $bil['product_group']  = '';
        }

        if (strlen($shippingNumber) > 25) {
            $shippingNumber = substr($shippingNumber, 0, 25);
        }

        // Get plugin params
        $plugin      = \JPluginHelper::getPlugin('billy', 'billy');
           $billyParams = new \JRegistry($plugin->params);
        
        if ($isVat) {
            $bil['product_tax_id'] = $billyParams->get('default_billy_tax_group');
        } else {
            $bil['product_tax_id'] = $billyParams->get('default_billy_tax_group_without_vat');
        }

        $bil['product_number'] = $shippingNumber;
        $bil['product_name']   = addslashes($shippingName);
        $bil['product_price']  = $shippingRate;
        $bil['product_volume'] = 1;

        // get billy product Id from redShop number
        $db     = \JFactory::getDbo();
        $selSQL = "SELECT billy_id from `#__redshop_billy_relation` WHERE redshop_id = '".$shippingNumber."' AND relation_type='product'";
        $db->setQuery($selSQL);
        
        $billyId 		 	   = $db->loadResult();
        $bil['billy_id'] 	   = $billyId;
        $BillyproductId	 	   = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($bil));
        $BillyproductId  	   = $BillyproductId[0];

        $bil['eco_prd_number'] = "";

        if ($BillyproductId) {
            $bil['eco_prd_number'] = $BillyproductId;
        }

        $bil['product_stock']  = 1;
        $currency 			   = \Redshop::getConfig()->get('CURRENCY_CODE');
        $bil['currency_code']  = $currency;
        $bilShippingRateNumber = \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($bil));
        $bilShippingRateNumber = $bilShippingRateNumber[0];
        
        // Store Product Billy number in Database
        if ($bilShippingRateNumber) {
            if (!$billyId) {
                $sql = "INSERT INTO `#__redshop_billy_relation` (`relation_type`, `redshop_id`, `billy_id`)
                VALUES ('product', '".$shippingNumber."', '".$bilShippingRateNumber."')";
                $db->setQuery($sql);
                $db->query();
            } else {
                $sql = "UPDATE `#__redshop_billy_relation` SET `billy_id` = '".$bilShippingRateNumber."' WHERE
                        `redshop_id` = '".$shippingNumber."' AND `relation_type` ='product'";
                $db->setQuery($sql);
                $db->query();
            }
        }
        
        return $bilShippingRateNumber;
    }

    /**
     * Method to create Invoice line for discount in Billy
     *
     * @param   array   $orderDetail       Order detail
     * @param   string  $invoiceNo         Invoice Number
     * @param   array   $data              Data
     * @param   integer $isPaymentDiscount Is payment discount or not
     * @param   integer $isVatDiscount     Is VAT discount or not
     *
     * @return  void
     *
     * @since   3.0.3
     */
    public static function getInvoiceDiscountLineInBilly($orderDetail = array(), $lines = array(), $data = array(), $isPaymentDiscount, $isVatDiscount)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        
        $bilProductGroupNumber = self::createProductGroupInBilly(array(), 0, 1, $isVatDiscount);

        if (isset($bilProductGroupNumber[0])) {
            $bil['product_group'] = $bilProductGroupNumber[0];
        }

        $discount     	   = $orderDetail->order_total_discount;
        $productName 	   = \JText::_('COM_REDSHOP_ORDER_DISCOUNT');
        $productNumber     = "discount-item-999";

        if ($orderDetail->special_discount > 0) {
            $discountProcent = $orderDetail->special_discount . " %";
        } else {
            $discountProcent = "";
        }


        if ($isPaymentDiscount) {
            $productNumber = "discount-charges-990";
            $productName   = ($orderDetail->payment_oprand == '+') ? \JText::_('PAYMENT_CHARGES_LBL') : \JText::_('PAYMENT_DISCOUNT_LBL');
            $discount      = ($orderDetail->payment_oprand == "+") ? (0 - $orderDetail->payment_discount) : $orderDetail->payment_discount;
        }

        // Get plugin params
        $plugin      = \JPluginHelper::getPlugin('billy', 'billy');
           $billyParams = new \JRegistry($plugin->params);
        
        if ($isVatDiscount) {
            $bil['product_tax_id'] = $billyParams->get('default_billy_tax_group');
        } else {
            $bil['product_tax_id'] = $billyParams->get('default_billy_tax_group_without_vat');
        }

        $bil['product_number']   = $productNumber;
        $bil['product_name']     = $productName;
        $bil['order_item_id']    = "";
        $bil['product_desc']     = "";
        $bil['product_s_desc']   = "";
        $bil['product_id']       = $discount_short;
        $bil['product_quantity'] = 1;
        $bil['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
        $bil['product_price']    = round((0 - $discount),2);
        $bil['product_volume']   = 1;

        // get billy product Id from redSHOP number
        $db     = \JFactory::getDbo();
        $selSQL = "SELECT billy_id from `#__redshop_billy_relation` WHERE redshop_id = '".$productNumber."' AND relation_type='product'";
        $db->setQuery($selSQL);
        $billyId         = $db->loadResult();
        $bil['billy_id'] = $billyId;
        $billyProductId  = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($bil));

        $billyProductId        = $billyProductId[0];
        $bil['eco_prd_number'] = "";

        if ($billyProductId) {
            $bil['eco_prd_number'] = $billyProductId;
        }

        $bil['product_stock'] = 1;
        $bil['currency_code'] = \Redshop::getConfig()->get('CURRENCY_CODE');
        $bilDiscountNumber 	  = \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($bil));
        $bilDiscountNumber 	  = $bilDiscountNumber[0];
        
        // Store Product Billy number in Database
        if ($bilDiscountNumber) {
            if (!$billyId) {
                 $sql = "INSERT INTO `#__redshop_billy_relation` (`relation_type`, `redshop_id`, `billy_id`)
                VALUES ('product', '".$productNumber."', '".$bilDiscountNumber."')";
                $db->setQuery($sql);
                $db->query();
            } else {
                $sql = "UPDATE `#__redshop_billy_relation` SET `billy_id` = '".$bilDiscountNumber."' WHERE
                        `redshop_id` = '".$productNumber."' AND `relation_type` ='product'";
                $db->setQuery($sql);
                $db->query();
            }
        }

        $lineItems->productId   = $bilDiscountNumber;
        $lineItems->unitPrice   = round((0 - $discount),2);
        $lineItems->description = $discountProcent;
        $lineItems->quantity    = 1;
        $lineItems->priority    = 25;
        $lines[]                = $lineItems;
        
        return $lines;
    }

    /**
     * Update booking is_billy_booked in db
     *
     * @param   integer $orderId Order ID
     *
     * @return  void
     *
     * @since   3.0.4
     */
    public static function updateIsBooked($orderId = 0)
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->update($db->qn('#__redshop_orders'))
            ->set($db->qn('is_billy_booked') . ' = 1')
            ->where($db->qn('order_id') . ' = ' . (int) $orderId);
        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Update booking billy_bookinvoice_date in db
     *
     * @param   integer $orderId Order ID
     *
     * @return  void
     *
     * @since   3.0.4
     */
    public static function updateBookInvoiceDate($orderId = 0)
    {
        $db = \JFactory::getDbo();

        $billyBookInvoiceDate = $today = date("Y-m-d");
        $query = $db->getQuery(true)
            ->update($db->qn('#__redshop_orders'))
            ->set($db->qn('billy_bookinvoice_date') . ' = ' . $db->quote($billyBookInvoiceDate))
            ->where($db->qn('order_id') . ' = ' . $orderId);

        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Update booking invoice number in db
     *
     * @param   integer $orderId           Order ID
     * @param   integer $bookInvoiceNumber Booking invoice number
     *
     * @return  void
     *
     * @since   3.0.4
     */
    public static function updateBookInvoiceNumber($orderId = 0, $bookInvoiceNumber = 0)
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->update($db->qn('#__redshop_orders'))
            ->set($db->qn('bookinvoice_number') . ' = ' . (int) $bookInvoiceNumber)
            ->where($db->qn('order_id') . ' = ' . (int) $orderId);
        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Method to book invoice and send mail in Billy
     *
     * @param   integer $orderId          Order ID
     * @param   integer $checkOrderStatus Check Order status
     * @param   integer $bookInvoiceDate  Booking invoice date
     *
     * @return  string
     *
     * @since   3.0.3
     */
    public static function bookInvoiceInBilly($orderId, $data = array())
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        $orderEntity = \RedshopEntityOrder::getInstance($orderId);

        if (!$orderEntity->isValid()) {
            return '';
        }

        if (empty($orderEntity->get('billy_invoice_no')) || $orderEntity->get('is_booked') != 0) {
            return '';
        }

        $billyPlugin         = \JPluginHelper::getPlugin('billy', 'billy');
        $billyPluginParams   = new \JRegistry($billyPlugin->params);
        $billyBookStatus     = $billyPluginParams->get('billy_book_status','');
        $billyInvoiceDraft 	 = $billyPluginParams->get('billy_invoice_draft','0');
        $billyBookEanInvoice = $billyPluginParams->get('billy_book_ean_invoice','0');
        $paymentMethodEan    = $billyPluginParams->get('billy_payment_method_id_ean');
        $order               = $orderEntity->getItem();

        if (($billyInvoiceDraft && in_array($orderEntity->order_status, $billyBookStatus)) 
                && ($order->billy_invoice_no != '' && $order->is_billy_booked == 0) 
                || $order->is_billy_cashbook == 0) {
            $userBillingInfo             = \RedshopEntityOrder::getInstance($orderId)->getBilling()->getItem();
            // get billy user Id from redhsop user
            $db                          = \JFactory::getDbo();
            $selSQL                      = "SELECT billy_id from `#__redshop_billy_relation` WHERE redshop_id = '" . $userBillingInfo->users_info_id . "' AND relation_type='user'";
            $db->setQuery($selSQL);
            $billyId 			         = $db->loadResult();

            $paymentInfo                 = \RedshopEntityOrder::getInstance($order->order_id)->getPayment()->getItem();
            $currency                    = \Redshop::getConfig()->get('CURRENCY_CODE');

            $bil                         = array();
            $bil['billy_user_id']        = $billyId;
            $bil['invoiceHandle']        = $order->billy_invoice_no;
            $debtorHandle                = \RedshopHelperUtility::getDispatcher()->trigger('debtorFindByNumber', array($bil));
            $bil['debtorHandle']         = $debtorHandle[0]->id;
            $bil['currency_code']        = $currency;
            $bil['amount']               = $order->order_total;
            $bil['order_number']         = $order->order_number;
            $bil['order_id']             = $order->order_id;
            $bil['order_payment_status'] = $order->order_payment_status;
            $bil['ean_number']           = $userBillingInfo->ean_number;
            $bil['is_billy_booked']      = $order->is_billy_booked;
            $currectInvoiceData          = \RedshopHelperUtility::getDispatcher()->trigger('checkDraftInvoice', array($bil));
            $bil['currency_code']        = $currectInvoiceData[0]->currencyId;
            $bil['order_transfee']       = $paymentInfo->order_transfee;

            // Change Email subject and body depeding of payment plugin
            if (!empty($paymentInfo)) {
                $paymentName = $paymentInfo->payment_method_class;
                if ($paymentName == 'rs_payment_epayv2') {
                    $billyInvoiceEmailSubject = $billyPluginParams->get('billy_invoice_email_subject_creditcard');
                    $billyInvoiceEmailBody    = $billyPluginParams->get('billy_invoice_email_body_creditcard', '');
                } else if ($paymentName == 'bambora') {
                    $billyInvoiceEmailSubject = $billyPluginParams->get('billy_invoice_email_subject_creditcard');
                    $billyInvoiceEmailBody    = $billyPluginParams->get('billy_invoice_email_body_creditcard', '');
                } else if ($paymentName == 'rs_payment_paypal') {
                    $billyInvoiceEmailSubject = $billyPluginParams->get('billy_invoice_email_subject_creditcard');
                    $billyInvoiceEmailBody    = $billyPluginParams->get('billy_invoice_email_body_creditcard', '');
                } else {
                    $billyInvoiceEmailSubject = $billyPluginParams->get('billy_invoice_email_subject_other');
                    $billyInvoiceEmailBody 	 = $billyPluginParams->get('billy_invoice_email_body_other', '');
                }
            }

            $bil['billy_invoice_email_subject'] = $billyInvoiceEmailSubject;			
            $bil['billy_invoice_email_body']    = $billyInvoiceEmailBody;

            if (count($currectInvoiceData) > 0 && trim($currectInvoiceData[0]->invoiceNo) == $order->order_number) {
                if ($userBillingInfo->is_company == 1 && $userBillingInfo->company_name != '') {
                    $bil['name'] = $userBillingInfo->company_name;
                } else {
                    $bil['name'] = $userBillingInfo->firstname . " " . $userBillingInfo->lastname;
                }

                if (count($data) > 0) {
                    if ($data['bookwithCashbook'] == 1 || $data['onlybook'] == 1) {
                        $bookhandle = \RedshopHelperUtility::getDispatcher()->trigger('CurrentInvoiceBook', array($bil));
    
                        self::updateBookInvoiceDate((int) $order->order_id);
                        self::updateIsBooked($orderId);
                    } else {
                        $bookhandle = $currectInvoiceData;
                    }
                } elseif ($order->is_billy_booked == 1 && $order->is_billy_cashbook == 0) {
                    $bookhandle = $currectInvoiceData;
                } else {
                    $bookhandle = \RedshopHelperUtility::getDispatcher()->trigger('CurrentInvoiceBook', array($bil));
                    
                    self::updateBookInvoiceDate((int) $order->order_id);	
                    self::updateIsBooked($orderId);
                }

                if (count($bookhandle) > 0 && isset($bookhandle[0]->id)) {
                    $defaultBillyAccountGroup = $billyPluginParams->get('default_billy_account_group','');
                            
                    if (!empty($default_billy_account_group)) {
                        $accountgroup = self::getAllAccountsFromBilly($defaultBillyAccountGroup);
                    } else {
                        $d['group_type'] = 'liability';
                        $accountgroup    = \RedshopHelperUtility::getDispatcher()->trigger('getProductGroup', $d);
                    }

                    $cashAccountId 		  = $accountgroup->id;
                    $bil['cashAccountId'] = $cashAccountId;
                    $bil['contactId'] 	  = $bookhandle[0]->contactId;

                    if (!empty($paymentInfo)) {
                        $paymentName = $paymentInfo->payment_method_class;							

                        if ($paymentName == 'rs_payment_banktransfer') {
                            $bil['bankAccountId'] = $billyPluginParams->get('billy_cashbook_account_banktransfer1');
                        } else if ($paymentName == 'rs_payment_banktransfer2') {
                            $bil['bankAccountId'] = $billyPluginParams->get('billy_cashbook_account_banktransfer2');
                        } else if ($paymentName == 'rs_payment_banktransfer_discount') {
                            $bil['bankAccountId'] = $billyPluginParams->get('billy_cashbook_account_banktransfer_discount');
                        } else if ($paymentName == 'rs_payment_eantransfer') {
                            $bil['bankAccountId'] = $billyPluginParams->get('billy_cashbook_account_ean');
                        } else if ($paymentName == 'rs_payment_epayv2') {
                            $bil['bankAccountId'] = $billyPluginParams->get('billy_cashbook_account_epayv2');
                        } else if ($paymentName == 'bambora') {
                            $bil['bankAccountId'] = $billyPluginParams->get('billy_cashbook_account_bambora');
                        } else if ($paymentName == 'rs_payment_paypal') {
                            $bil['bankAccountId'] = $billyPluginParams->get('billy_cashbook_account_paypal');
                        } else {
                            $bil['bankAccountId'] = $billyPluginParams->get('billy_default_bank_account');
                        }
                    }

                    if (count($data) > 0) {
                        $bil['onlycashbook'] 	 = $data['onlycashbook'];
                        $bil['bookwithCashbook'] = $data['bookwithCashbook'];
                        $bil['onlybook']		 = $data['onlybook'];
                    }

                    \RedshopHelperUtility::getDispatcher()->trigger('bookInvoice', array($bil));							
                }
            }
            
            if ($order->is_billy_booked == 0 && $paymentName == 'rs_payment_eantransfer' 
                    && $billyBookEanInvoice == 1 && !empty($paymentMethodEan)) {
                $resEan = \RedshopHelperUtility::getDispatcher()->trigger('bookEan', $order->billy_invoice_no);
            }
        }

        return;
    }

    /**
     * Create payment cashbook in Billy
     *
     * @param   integer $orderId          Order ID
     * @param   integer $orderdetail      Order detail
     * @param   integer $data
     *
     * @return  string
     *
     * @since   3.0.4
     */
    public function createCashbookEntry($orderId, $orderDetail, $data)
    {
        // If using Dispatcher, must call plugin Economic first
        self::importBilly();

        $currency 					 = \Redshop::getConfig()->get('CURRENCY_CODE');
        $bil['onlycashbook'] 		 = $data['onlycashbook'];
        $bil['bookwithCashbook']	 = $data['bookwithCashbook'];
        $bil['onlybook'] 			 = $data['onlybook'];
        $bil['invoiceHandle'] 		 = $orderDetail->billy_invoice_no;
        $bil['amount']        		 = $orderDetail->order_total;
        $bil['order_number']  		 = $orderDetail->order_number;
        $bil['order_id']      		 = $orderDetail->order_id;
        $bil['order_payment_status'] = $orderDetail->order_payment_status;
        $bil['is_billy_booked'] 	 = $orderDetail->is_billy_booked;
        $currectInvoiceData 		 = \RedshopHelperUtility::getDispatcher()->trigger('checkDraftInvoice', array($bil));
        $bil['currency_code'] 		 = $currectInvoiceData[0]->currencyId;
        $bookhandle 				 = $currectInvoiceData;

        // Get plugin params
        $plugin      = \JPluginHelper::getPlugin('billy', 'billy');
           $billyParams = new \JRegistry($plugin->params);
        
        if (count($bookhandle) > 0 && isset($bookhandle[0]->id)) {
            $defaultBillyAccountGroup = $billyParams->get('default_billy_account_group','');
            
            if ($defaultBillyAccountGroup != '') {
                $accountGroup = self::getAllAccountsFromBilly($defaultBillyAccountGroup);
            } else {
                $d['group_type'] = 'liability';
                $accountGroup    = \RedshopHelperUtility::getDispatcher()->trigger('getProductGroup', array($d));
            }

            $cashAccountId 		   = $accountGroup->id;
            $bil['cashAccountId']  = $cashAccountId;
            $bil['contactId'] 	   = $bookhandle[0]->contactId;					
            $paymentInfo 		   = \RedshopEntityOrder::getInstance($orderId)->getPayment()->getItem();
            $paymentFee 		   = $paymentInfo->order_transfee;
            $bil['order_transfee'] = $paymentFee;
            
            if (count($paymentInfo) > 0) {
                $paymentName = $paymentInfo->payment_method_class;
                
                if ($paymentName == 'rs_payment_banktransfer') {
                    $bil['bankAccountId']   = $billyParams->get('billy_cashbook_account_banktransfer1');
                } else if ($paymentName == 'rs_payment_banktransfer2') {
                    $bil['bankAccountId']   = $billyParams->get('billy_cashbook_account_banktransfer2');
                } else if ($paymentName == 'rs_payment_banktransfer_discount') {
                    $bil['bankAccountId']   = $billyParams->get('billy_cashbook_account_banktransfer_discount');
                } else if ($paymentName == 'rs_payment_eantransfer') {
                    $bil['bankAccountId']   = $billyParams->get('billy_cashbook_account_ean');
                } else if ($paymentName == 'rs_payment_epayv2') {
                    $bil['bankAccountId']   = $billyParams->get('billy_cashbook_account_epayv2');
                } else if ($paymentName == 'rs_payment_bambora') {
                    $bil['bankAccountId']   = $billyParams->get('billy_cashbook_account_bambora');
                } else if ($paymentName == 'rs_payment_paypal') {
                    $bil['bankAccountId']   = $billyParams->get('billy_cashbook_account_paypal');
                } else {
                    $bil['bankAccountId']   = $billyParams->get('billy_default_bank_account');
                }
            }
        }
        
        $cashbook = \RedshopHelperUtility::getDispatcher()->trigger('createCashbookEntry', array($bil));
    }

    /**
     * Method to Re send invoice from Billy
     * 
     * @param   array   $orderId
     *
     * @return  mixed
     *
     * @since   3.0.3
     */
    public function ReSendInvoice($orderId)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        
        $orderDetail = \RedshopEntityOrder::getInstance($orderId)->getItem();
        $paymentInfo = \RedshopEntityOrder::getInstance($orderId)->getPayment()->getItem();
    
        if (!empty($paymentInfo)) {
            // Get plugin params
            $plugin      = \JPluginHelper::getPlugin('billy', 'billy');
            $billyParams = new \JRegistry($plugin->params);
            $paymentName = $paymentInfo->payment_method_class;

            // Change Email subject and body depeding of payment plugin			
            if ($paymentName == 'rs_payment_epayv2') {
                $billyInvoiceEmailSubject = $billyParams->get('billy_invoice_email_subject_creditcard');
                $billyInvoiceEmailBody    = $billyParams->get('billy_invoice_email_body_creditcard', '');
            } else if ($paymentName == 'bambora') {
                $billyInvoiceEmailSubject = $billyParams->get('billy_invoice_email_subject_creditcard');
                $billyInvoiceEmailBody    = $billyParams->get('billy_invoice_email_body_creditcard', '');
            } else if ($paymentName == 'rs_payment_paypal') {
                $billyInvoiceEmailSubject = $billyParams->get('billy_invoice_email_subject_creditcard');
                $billyInvoiceEmailBody    = $billyParams->get('billy_invoice_email_body_creditcard', '');
            } else {
                $billyInvoiceEmailSubject = $billyParams->get('billy_invoice_email_subject_other');
                $billyInvoiceEmailBody 	 = $billyParams->get('billy_invoice_email_body_other', '');
            }
        }

        $bil['billy_invoice_email_subject'] = $billyInvoiceEmailSubject;			
        $bil['billy_invoice_email_body'] 	= $billyInvoiceEmailBody;
        $userBillingInfo 					= \RedshopEntityOrder::getInstance($orderId)->getBilling()->getItem();

        // get billy user Id from redhsop user
        $db     = \JFactory::getDbo();
        $selSQL = "SELECT billy_id from `#__redshop_billy_relation` WHERE redshop_id = '" . $userBillingInfo->users_info_id . "' AND relation_type='user'";
        $db->setQuery($selSQL);

        $billyId 				= $db->loadResult();
        $bil['billy_user_id'] 	= $billyId;
        $debtorHandle        	= \RedshopHelperUtility::getDispatcher()->trigger('debtorFindByNumber', array($bil));
        $bil['debtorHandle']  	= $debtorHandle[0]->id;			
        $bil['invoiceHandle'] 	= $orderDetail->billy_invoice_no;
        $bil['order_id']      	= $orderDetail->order_id;
        $bil['is_billy_booked'] = $orderDetail->is_billy_booked;

        return $resendInvoice = \RedshopHelperUtility::getDispatcher()->trigger('ReSendInvoice', array($bil));
    }

    /**
     * Method to Calculate overdue days
     * 
     * @param   array   $accountgroupId
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public static function calulateOverdueDays($invoiceNo)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        
        $overdueDays = \RedshopHelperUtility::getDispatcher()->trigger('calulateOverdueDays', array($invoiceNo));

        return $overdueDays[0];
    }

    /**
     * Method to Calculate overdue limits
     * 
     * @param   array   $accountgroupId
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public static function calulateOverdueLimits($invoiceNo, $reminder = false)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $overdueLimits = \RedshopHelperUtility::getDispatcher()->trigger('calulateOverdueLimits', array($invoiceNo,$reminder));

        return $overdueLimits[0];
    }

    /**
     * Method to Send reminder in Billy
     * 
     * @param   array   $accountgroupId
     *
     * @return  mixed
     *
     * @since   3.0.3
     */
    public static function sendReminder($orderId, $billyInvoiceNo)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $remindersent = \RedshopHelperUtility::getDispatcher()->trigger('sendReminder', array($orderId, $billyInvoiceNo));
        
        if ($remindersent[0]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method to Get invoice data from Billy
     * 
     * @param   array   $accountgroupId
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public static function getInvoiceData($billyInvoiceNo)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        if ($billyInvoiceNo) {
            $invoice = \RedshopHelperUtility::getDispatcher()->trigger('getInvoiceData', array($billyInvoiceNo));

            return $invoice[0];
        }

        return;
    }

    /**
     * Method to Get sent reminders from Billy
     * 
     * @param   array   $accountgroupId
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public static function getSentReminders($billyInvoiceNo)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        if ($billyInvoiceNo) {
            $reminders 			  = \RedshopHelperUtility::getDispatcher()->trigger('getSentReminders', array($billyInvoiceNo));
            $reminderDetailsFinal = array();
            
            if (is_array($reminders) && count($reminders) > 0) {
                $reminders = $reminders[0];
                
                foreach($reminders as $reminder)
                {
                    $reminderDetails = \RedshopHelperUtility::getDispatcher()->trigger('getReminderDetails', array($reminder->reminderId));
                    
                    if ($reminderDetails[0]->body->invoiceReminder) {
                        $reminderDetailsFinal[] = $reminderDetails[0]->body->invoiceReminder;
                    }
                }
                
                if (is_array($reminderDetailsFinal) && !empty($reminderDetailsFinal)) {
                    foreach($reminderDetailsFinal as $reminderDetailsF)
                    {
                        $finalRems[$reminderDetailsF->id] = $reminderDetailsF->createdTime;
                    }
        
                    arsort($finalRems);
                    foreach($finalRems as $reminderId => $finalRem)
                    {
                        $FreminderDetails        = \RedshopHelperUtility::getDispatcher()->trigger('getReminderDetails', array($reminderId));
                        $FreminderDetailsFinal[] = $FreminderDetails[0]->body->invoiceReminder;
                    }

                    return $FreminderDetailsFinal;
                }
            }
        }		
    }

    /**
     * Method to Get invoice Timeline from Billy
     * 
     * @param   array   $accountgroupId
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public static function getInvoiceTimelines($billyInvoiceNo)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();
        $message   = '';

        if ($billyInvoiceNo) {
            $timelines = \RedshopHelperUtility::getDispatcher()->trigger('getInvoiceTimeline', array($billyInvoiceNo));
            
            if (count($timelines[0]) > 0)	{
                foreach($timelines[0] as $timeline) {
                    if ($timeline->type == 'InvoiceEmailSent') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_EMAIL_SENT');
                        $timeline->icon = 'fa-regular fa-envelope';
                    }
                    if ($timeline->type == 'InvoiceEmailDelivered') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_EMAIL_DELIVERED');
                        $timeline->icon = 'fa-regular fa-square-check';
                    }
                    if ($timeline->type == 'InvoiceEmailOpened') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_EMAIL_OPEN');
                        $timeline->icon = 'fa-regular fa-envelope-open';
                    }
                    if ($timeline->type == 'InvoiceEanQueued') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_EAN_QUEUED');
                        $timeline->icon = 'fa-solid fa-file-circle-question';
                    }
                    if ($timeline->type == 'InvoiceEanProcessed') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_EAN_PROCESSED');
                        $timeline->icon = 'fa-regular fa-file-import';
                    }
                    if ($timeline->type == 'InvoiceEanSent') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_EAN_SENT');
                        $timeline->icon = 'fa-regular fa-envelope';
                    }
                    if ($timeline->type == 'InvoiceEanReceived') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_EAN_RECEIVED');
                        $timeline->icon = 'fa-regular fa-square-check';
                    }
                    if ($timeline->type == 'InvoiceEanFailed') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_EAN_FAILED');
                        $timeline->icon = 'fa-regular fa-file-excel';
                    }
                    if ($timeline->type == 'InvoiceReminderSent') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_SUCCESSFULLY_SENT_REMINDER_IN_BILLY');
                        $timeline->icon = 'fa-regular fa-bell';
                    }
                    if ($timeline->type == 'UserCommentedOnInvoice') {
                        $timeline->type = \JText::_('COM_REDSHOP_BILLY_TIMELINE_USERCOMMENT');
                        $timeline->icon = 'fa-solid fa-quote-left';
                    }

                    $message .= '<div class="timeline-entry" style="margin-bottom: 20px;position: relative;z-index: 2">';
                    $message .= '<div class="timeline-entry-default" style="display:flex">';
                    $message .= '<div class="timeline-entry-default-icon" style="display: flex;width: 36px;height: 36px;border-radius: 36px;background-color: #dde1e3;margin-right: 10px;padding: 8px;text-align: center;font-size: 16px;border: 2px solid #fff;align-items: center;justify-content: center">';
                    $message .= '<i class="' . $timeline->icon . '"></i>';
                    $message .= '</div>';
                    $message .= '<div class="timeline-entry-default-content" style="text-align: left;flex: 1;overflow: hidden;line-height: 20px;margin-top:5px">';
                    $message .= '<span id="" style="font-size:14px;font-weight:700">';
                    $message .= $timeline->type;
                    $message .= '</span>';
                    $message .= '<div class="timeline-entry-default-time" style="font-size:12px">';
                    $message .= date("d M Y - H:i", strtotime($timeline->timestamp));
                    $message .= '</div>';
                    if (isset($timeline->properties->subject)) {
                    $message .= '<div class="timeline-entry-default-time" style="font-size:12px;font-weight:700">';
                    $message .= $timeline->properties->subject;
                    $message .= '</div>';
                    }
                    if (isset($timeline->properties->message)) {
                    $message .= '<div class="timeline-entry-default-time" style="font-size:12px">';
                    $message .= $timeline->properties->message;
                    $message .= '</div>';
                    }
                    if (isset($timeline->properties->user->name)) {
                    $message .= '<div class="timeline-entry-default-time" style="font-size:12px">';
                    $message .= $timeline->properties->user->name;
                    $message .= '</div>';
                    }
                    if (isset($timeline->properties->comment)) {
                    $message .= '<div class="timeline-entry-default-time" style="font-size:12px">';
                    $message .= '<b>' . $timeline->properties->comment . '</b>';
                    $message .= '</div>';
                    }
                    $message .= '</div></div></div>';
                }
            }
                    
            return $message;
        }
        
        return $message;
    }

    /**
     * Method to Check all overdue invoices in Billy
     * 
     * @param   array   $accountgroupId
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public static function checkAnyOverdueOrder($billyUserId)
    {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $overdueDays = false;
        
        if ($billyUserId) {
            // get orders from Billy which are unPaid in billy
            $orders = \RedshopHelperUtility::getDispatcher()->trigger('getOrderUnpaidFromContact', array($billyUserId));
            
            foreach($orders[0] as $order) {
                $orderDetail = \RedshopEntityOrder::getInstance($order->invoiceNo)->getItem();

                if (is_array($orderDetail) || !empty($orderDetail)) {
                //	$billyInvoiceNo = $orderDetail->billy_invoice_no;

                    if ($orderDetail->order_payment_status == 'Unpaid') {
                        $overdueDays = self::calulateOverdueLimits($order->id);
                        
                        if ($overdueDays > 10) {
                    // Tweak by Ronni - This can replace the calculation, but requires cron job setup
                    //	if ($orderDetail->overdue_days > 10) {
                            $overdueDays = true;

                            return $overdueDays;
                        }
                    }
                }
            }	
        }
        
        return $overdueDays;
    }

    /**
     * Method to Get all accounts from Billy
     * 
     * @param   array   $accountgroupId
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public function getAllAccountsFromBilly($accountgroupId) {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $accounts = \RedshopHelperUtility::getDispatcher()->trigger('getAccounts', array($accountgroupId));

        return $accounts[0];
    }

    /**
     * Method to Get all invoice data from Billy
     * 
     *
     * @return  mixed
     *
     * @since   3.0.4
     */
    public function getAllInvoiceData() {
        // If using Dispatcher, must call plugin Billy first
        self::importBilly();

        $invoices = \RedshopHelperUtility::getDispatcher()->trigger('getAllInvoiceData', array());

            return $invoices[0];
    }
}