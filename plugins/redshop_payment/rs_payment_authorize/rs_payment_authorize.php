<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
//$mainframe =& JFactory::getApplication();
//$mainframe->registerEvent( 'onPrePayment', 'plgRedshoprs_payment_bbs' );
require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
class plgRedshop_paymentrs_payment_authorize extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgRedshop_paymentrs_payment_authorize(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_authorize');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment_rs_payment_authorize($element, $data)
	{
		if ($element != 'rs_payment_authorize')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$db = JFactory::getDBO();
		$user = JFActory::getUser();
		$session =& JFactory::getSession();
		$ccdata = $session->get('ccdata');
		$cart = $session->get('cart');

		// for total amount
		$cal_no = 2;

		if (defined('PRICE_DECIMAL'))
		{
			$cal_no = PRICE_DECIMAL;
		}

		$order_total = round($data['order_total'], $cal_no);

		$item_details = "";

		for ($p = 0; $p < $cart['idx']; $p++)
		{
			if (isset($cart[$p]['product_id']) && $cart[$p]['product_id'] != "")
			{
				$product_id = $cart[$p]['product_id'];
				$query = "SELECT product_name,product_s_desc FROM `#__redshop_product` WHERE `product_id` = '" . $cart[$p]['product_id'] . "'";
				$db->SetQuery($query);
				$proinfo = $db->loadObjectlist();
				$product_name = substr(str_replace("&", "and", $proinfo[0]->product_name), 0, 30);
			}

			if (isset($cart[$p]['giftcard_id']) && $cart[$p]['giftcard_id'] != "")
			{
				$product_id = $cart[$p]['giftcard_id'];
				$query_gift = "SELECT * FROM `#__redshop_giftcard` WHERE `giftcard_id` = '" . $cart[$p]['giftcard_id'] . "'";
				$db->SetQuery($query_gift);
				$giftinfoinfo = $db->loadObjectlist();
				$product_name = substr(str_replace("&", "and", $giftinfoinfo[0]->giftcard_name), 0, 30);
			}

			if ($cart[$p]['product_price_excl_vat'] == $cart[$p]['product_price'])
			{
				$taxable = "N";
			}
			else
			{
				$taxable = "Y";
			}

			$product_price = round($cart[$p]['product_price'], $cal_no);

			$item_details[] = $product_id . "<|>" . $product_name . "<|><|>" . $cart[$p]['quantity'] . "<|>" . $product_price . "<|>" . $taxable;
		}

		$item_str = implode("&x_line_item=", $item_details);


		// for Email Receipt
		if ($this->_params->get("emailreceipt_to_customer") == 1)
		{
			$x_merchant_email = $data['billinginfo']->user_email;
		}
		else
		{
			$x_merchant_email = "";
		}

		$view_table_format = $this->_params->get("view_table_format");
		//Authnet vars to send

		$formdata = array(
			'x_version'            => '3.1',
			'x_login'              => $this->_params->get("access_id"),
			'x_tran_key'           => $this->_params->get("transaction_id"),
			'x_test_request'       => $this->_params->get("is_test"),

			// Gateway Response Configuration
			'x_delim_data'         => 'TRUE',
			'x_delim_char'         => '|',
			'x_relay_response'     => 'FALSE',

			// Customer Name and Billing Address
			'x_first_name'         => substr($data['billinginfo']->firstname, 0, 50),
			'x_last_name'          => substr($data['billinginfo']->lastname, 0, 50),
			'x_company'            => substr(@$data['billinginfo']->company, 0, 50),
			'x_address'            => substr($data['billinginfo']->address, 0, 60),
			'x_city'               => substr($data['billinginfo']->city, 0, 40),
			'x_state'              => substr($data['billinginfo']->state_code, 0, 40),
			'x_zip'                => substr($data['billinginfo']->zipcode, 0, 20),
			'x_country'            => substr($data['billinginfo']->country_code, 0, 60),
			'x_phone'              => substr($data['billinginfo']->phone, 0, 25),
			'x_fax'                => substr(@$data['billinginfo']->fax, 0, 25),

			// Customer Shipping Address
			'x_ship_to_first_name' => substr($data['shippinginfo']->firstname, 0, 50),
			'x_ship_to_last_name'  => substr($data['shippinginfo']->lastname, 0, 50),
			'x_ship_to_company'    => substr(@$data['shippinginfo']->company, 0, 50),
			'x_ship_to_address'    => substr($data['shippinginfo']->address, 0, 60),
			'x_ship_to_city'       => substr($data['shippinginfo']->city, 0, 40),
			'x_ship_to_state'      => substr($data['shippinginfo']->state_code, 0, 40),
			'x_ship_to_zip'        => substr($data['shippinginfo']->zipcode, 0, 20),
			'x_ship_to_country'    => substr($data['shippinginfo']->country_code, 0, 60),

			// Additional Customer Data
			'x_cust_id'            => $data['billinginfo']->user_id,
			'x_customer_ip'        => $_SERVER["REMOTE_ADDR"],

			// Email Settings
			'x_email'              => $data['billinginfo']->user_email,
			'x_email_customer'     => $this->_params->get("emailreceipt_to_customer"),
			'x_merchant_email'     => $x_merchant_email,

			// Invoice Information
			'x_invoice_num'        => substr($data['order_number'], 0, 20),
			'x_description'        => JText::_('COM_REDSHOP_AUTHORIZENET_ORDER_PRINT_PO_LBL'),

			// item information

			'x_line_item'          => $item_str,


			// Transaction Data
			'x_amount'             => $order_total,
			'x_currency_code'      => CURRENCY_CODE,
			'x_method'             => 'CC',
			'x_type'               => $this->_params->get("auth_type"),

			'x_card_num'           => $ccdata['order_payment_number'],
			'x_card_code'          => $ccdata['credit_card_code'],
			'x_exp_date'           => ($ccdata['order_payment_expire_month']) . ($ccdata['order_payment_expire_year']),

			// Level 2 data
			'x_po_num'             => substr($data['order_number'], 0, 20),
			'x_tax'                => substr($data['order_tax'], 0, 15),
			'x_tax_exempt'         => "FALSE",
			'x_freight'            => $data['order_shipping'],
			'x_duty'               => 0

		);

		if ($view_table_format == 0)
		{
			unset($formdata['x_line_item']);
		}
		//build the post string
		$poststring = '';
		foreach ($formdata AS $key => $val)
		{
			$poststring .= urlencode($key) . "=" . $val . "&";
		}

		// strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);


		if ($this->_params->get("is_test") == 'TRUE')
		{
			$host = 'test.authorize.net';
		}
		else
		{
			$host = 'secure.authorize.net';
		}

		$url = "https://$host:443/gateway/transact.dll";

		$urlParts = parse_url($url);

		//$poststring = substr($poststring, 0, -1);

		$CR = curl_init();

		curl_setopt($CR, CURLOPT_URL, $url);

		curl_setopt($CR, CURLOPT_TIMEOUT, 30);

		curl_setopt($CR, CURLOPT_FAILONERROR, true);

		if ($poststring)
		{

			curl_setopt($CR, CURLOPT_POSTFIELDS, $poststring);

			curl_setopt($CR, CURLOPT_POST, 1);

		}

		curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);

		if ($urlParts['scheme'] == 'https')
		{

			curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);

		}

		$result = curl_exec($CR);

		$error = curl_error($CR);

		curl_close($CR);

		if (!$result || !empty($error))
		{
			return false;
		}

		$response_vars = explode('|', $result);

		$response_vars[0] = str_replace('"', '', $response_vars[0]);
		$transaction_id = $response_vars[6];

		if ($response_vars[0] == '1' || $response_vars[0] == 1)
		{
			$values->responsestatus = 'Success';
			$message = $response_vars[3];
		}
		else
		{
			// Catch Transaction ID
			$message = "ERROR RESPONCE CODE : " . $response_vars[0] . "<br>" . $response_vars[3];
			$values->responsestatus = 'Fail';
		}

		$values->transaction_id = $transaction_id;
		$values->message = $message;

		return $values;
	}

	function onCapture_Paymentrs_payment_authorize($element, $data)
	{
		$objOrder = new order_functions();
		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];
		$db =& JFactory::getDBO();
		$billing_info = $data['billinginfo'];
		$shipping_info = $data['shippinginfo'];
		//Fetch the Credit Card information from Order Id

		$sql = "SELECT op.*,o.order_total,o.user_id FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $order_id . "' AND op.order_payment_trans_id='" . $tid . "' ";
		$db->setQuery($sql);
		$order_details = $db->loadObject();

		// for Email Receipt
		if ($this->_params->get("emailreceipt_to_customer") == 1)
		{
			$x_merchant_email = $data['billinginfo']->user_email;
		}
		else
		{
			$x_merchant_email = "";
		}

		// for total amount
		$cal_no = 2;

		if (defined('PRICE_DECIMAL'))
		{
			$cal_no = PRICE_DECIMAL;
		}

		$order_total = round($order_details->order_total, $cal_no);
		//Authnet vars to send
		$formdata = array(
			'x_version'            => '3.1',
			'x_login'              => $this->_params->get("access_id"),
			'x_tran_key'           => $this->_params->get("transaction_id"),
			//  'x_test_request' => $this->_params->get("is_test"),
			// Gateway Response Configuration
			'x_delim_data'         => 'TRUE',
			'x_delim_char'         => '|',
			'x_relay_response'     => 'FALSE',
			// Customer Name and Billing Address
			'x_first_name'         => substr($billing_info->firstname, 0, 50),
			'x_last_name'          => substr($billing_info->lastname, 0, 50),
			'x_company'            => substr(@$billing_info->company, 0, 50),
			'x_address'            => substr($billing_info->address, 0, 60),
			'x_city'               => substr($billing_info->city, 0, 40),
			'x_state'              => substr($billing_info->state_code, 0, 40),
			'x_zip'                => substr($billing_info->zipcode, 0, 20),
			'x_country'            => substr($billing_info->country_code, 0, 60),
			'x_phone'              => substr($billing_info->phone, 0, 25),
			'x_fax'                => substr(@$billing_info->fax, 0, 25),
			// Customer Shipping Address
			'x_ship_to_first_name' => substr($shipping_info->firstname, 0, 50),
			'x_ship_to_last_name'  => substr($shipping_info->lastname, 0, 50),
			'x_ship_to_company'    => substr(@$shipping_info->company, 0, 50),
			'x_ship_to_address'    => substr($shipping_info->address, 0, 60),
			'x_ship_to_city'       => substr($shipping_info->city, 0, 40),
			'x_ship_to_state'      => substr($shipping_info->state_code, 0, 40),
			'x_ship_to_zip'        => substr($shipping_info->zipcode, 0, 20),
			'x_ship_to_country'    => substr($shipping_info->country_code, 0, 60),
			// Additional Customer Data
			'x_cust_id'            => $billing_info->user_id,
			'x_customer_ip'        => $_SERVER["REMOTE_ADDR"],
			// Email Settings
			'x_email'              => $billing_info->user_email,
			'x_email_customer'     => $this->_params->get("emailreceipt_to_customer"),
			'x_merchant_email'     => $x_merchant_email,
			// Invoice Information
			'x_invoice_num'        => substr($data['order_number'], 0, 20),
			'x_description'        => JText::_('COM_REDSHOP_AUTHORIZENET_ORDER_PRINT_PO_LBL'),
			// Transaction Data
			'x_amount'             => $order_total,
			'x_currency_code'      => CURRENCY_CODE,
			'x_method'             => 'CC',
			'x_type'               => "PRIOR_AUTH_CAPTURE",
			'x_card_num'           => base64_decode($order_details->order_payment_number),
			'x_card_code'          => base64_decode($order_details->order_payment_ccv),
			'x_exp_date'           => $order_details->order_payment_expire,
			'x_trans_id'           => $tid,
			// Level 2 data
			'x_po_num'             => substr($order_id, 0, 20),
		);

		//build the post string
		$poststring = '';
		foreach ($formdata AS $key => $val)
		{
			$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
		}
		// strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);

		if ($this->_params->get("is_test") == 'TRUE')
		{
			$host = 'test.authorize.net';
		}
		else
		{
			$host = 'secure.authorize.net';
		}

		$url = "https://$host:443/gateway/transact.dll";
		$urlParts = parse_url($url);
		$poststring = substr($poststring, 0, -1);
		$CR = curl_init();
		curl_setopt($CR, CURLOPT_URL, $url);
		curl_setopt($CR, CURLOPT_TIMEOUT, 30);
		curl_setopt($CR, CURLOPT_FAILONERROR, true);

		if ($poststring)
		{
			curl_setopt($CR, CURLOPT_POSTFIELDS, $poststring);
			curl_setopt($CR, CURLOPT_POST, 1);
		}
		curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);

		if ($urlParts['scheme'] == 'https')
		{
			curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
		}

		$result = curl_exec($CR);
		$error = curl_error($CR);
		curl_close($CR);
		$response_vars = explode('|', $result);
		$x_response_code = $response_vars[0];

		if ($x_response_code == '1')
		{
			$values->responsestatus = 'Success';
			$message = $response_vars[3];
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = "ERROR RESPONCE CODE : " . $response_vars[0] . "<br>" . $response_vars[3];
		}

		$values->message = $message;

		return $values;
	}

	function onAuthorizeStatus_Paymentrs_payment_authorize($element, $order_id)
	{
		if ($element != 'rs_payment_authorize')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$db = JFactory::getDBO();
		// update authorize_status
		if ($this->_params->get("auth_type") == "AUTH_ONLY")
		{
			$authorize_status = "Authorized";
		}
		else
		{
			$authorize_status = "Captured";
		}

		$query = "UPDATE " . $this->_table_prefix . "order_payment SET  authorize_status = '" . $authorize_status . "' where order_id = '" . $order_id . "'";
		$db->SetQuery($query);
		$db->Query();

		//return $authorize_status;

	}
}