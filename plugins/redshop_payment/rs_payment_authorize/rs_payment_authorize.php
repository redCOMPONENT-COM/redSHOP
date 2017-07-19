<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

class plgRedshop_paymentrs_payment_authorize extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_authorize($element, $data)
	{
		if ($element != 'rs_payment_authorize')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$db      = JFactory::getDbo();
		$session = JFactory::getSession();
		$ccdata  = $session->get('ccdata');
		$cart    = $session->get('cart');

		// For total amount
		$cal_no = 2;

		if (Redshop::getConfig()->get('PRICE_DECIMAL') != '')
		{
			$cal_no = Redshop::getConfig()->get('PRICE_DECIMAL');
		}

		$order_total = round($data['order_total'], $cal_no);

		$item_details = "";

		for ($p = 0; $p < $cart['idx']; $p++)
		{
			if (isset($cart[$p]['product_id']) && $cart[$p]['product_id'] != "")
			{
				$product_id   = $cart[$p]['product_id'];
				$query        = "SELECT product_name,product_s_desc FROM `#__redshop_product` WHERE `product_id` = '" . $cart[$p]['product_id'] . "'";
				$db->setQuery($query);
				$proinfo      = $db->loadObjectlist();
				$product_name = substr(str_replace("&", "and", $proinfo[0]->product_name), 0, 30);
			}

			if (isset($cart[$p]['giftcard_id']) && $cart[$p]['giftcard_id'] != "")
			{
				$product_id   = $cart[$p]['giftcard_id'];
				$query_gift   = "SELECT * FROM `#__redshop_giftcard` WHERE `giftcard_id` = '" . $cart[$p]['giftcard_id'] . "'";
				$db->setQuery($query_gift);
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

		// For Email Receipt
		if ($this->params->get("emailreceipt_to_customer") == 1)
		{
			$x_merchant_email = $data['billinginfo']->user_email;
		}
		else
		{
			$x_merchant_email = "";
		}

		$view_table_format = $this->params->get("view_table_format");

		// Authnet vars to send

		$formdata = array(
			'x_version'            => '3.1',
			'x_login'              => $this->params->get("access_id"),
			'x_tran_key'           => $this->params->get("transaction_id"),
			'x_test_request'       => $this->params->get("is_test"),

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
			'x_email_customer'     => $this->params->get("emailreceipt_to_customer"),
			'x_merchant_email'     => $x_merchant_email,

			// Invoice Information
			'x_invoice_num'        => substr($data['order_number'], 0, 20),
			'x_description'        => JText::_('COM_REDSHOP_AUTHORIZENET_ORDER_PRINT_PO_LBL'),

			// Item information

			'x_line_item'          => $item_str,

			// Transaction Data
			'x_amount'             => $order_total,
			'x_currency_code'      => Redshop::getConfig()->get('CURRENCY_CODE'),
			'x_method'             => 'CC',
			'x_type'               => $this->params->get("auth_type"),

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

		// Build the post string
		$poststring = '';

		foreach ($formdata AS $key => $val)
		{
			$poststring .= urlencode($key) . "=" . $val . "&";
		}

		// Strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);

		if ($this->params->get("is_test") == 'TRUE')
		{
			$host = 'test.authorize.net';
		}
		else
		{
			$host = 'secure2.authorize.net';
		}

		$url = "https://$host/gateway/transact.dll";

		$urlParts = parse_url($url);

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

	public function onCapture_Paymentrs_payment_authorize($element, $data)
	{
		if ($element != 'rs_payment_authorize')
		{
			return;
		}

		$order_id      = $data['order_id'];
		$tid           = $data['order_transactionid'];
		$db            = JFactory::getDbo();
		$billing_info  = $data['billinginfo'];
		$shipping_info = $data['shippinginfo'];

		// Fetch the Credit Card information from Order Id
		$sql = "SELECT op.*,o.order_total,o.user_id FROM `#__redshop_order_payment` AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $order_id . "' AND op.order_payment_trans_id='" . $tid . "' ";
		$db->setQuery($sql);
		$order_details = $db->loadObject();

		// For Email Receipt
		if ($this->params->get("emailreceipt_to_customer") == 1)
		{
			$x_merchant_email = $data['billinginfo']->user_email;
		}
		else
		{
			$x_merchant_email = "";
		}

		// For total amount
		$cal_no = 2;

		if (Redshop::getConfig()->get('PRICE_DECIMAL') != '')
		{
			$cal_no = Redshop::getConfig()->get('PRICE_DECIMAL');
		}

		$order_total = round($order_details->order_total, $cal_no);

		// Authnet vars to send
		$formdata = array(
			'x_version'            => '3.1',
			'x_login'              => $this->params->get("access_id"),
			'x_tran_key'           => $this->params->get("transaction_id"),
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
			'x_email_customer'     => $this->params->get("emailreceipt_to_customer"),
			'x_merchant_email'     => $x_merchant_email,
			// Invoice Information
			'x_invoice_num'        => substr($data['order_number'], 0, 20),
			'x_description'        => JText::_('COM_REDSHOP_AUTHORIZENET_ORDER_PRINT_PO_LBL'),
			// Transaction Data
			'x_amount'             => $order_total,
			'x_currency_code'      => Redshop::getConfig()->get('CURRENCY_CODE'),
			'x_method'             => 'CC',
			'x_type'               => "PRIOR_AUTH_CAPTURE",
			'x_card_num'           => base64_decode($order_details->order_payment_number),
			'x_card_code'          => base64_decode($order_details->order_payment_ccv),
			'x_exp_date'           => $order_details->order_payment_expire,
			'x_trans_id'           => $tid,
			// Level 2 data
			'x_po_num'             => substr($order_id, 0, 20),
		);

		// Build the post string
		$poststring = '';

		foreach ($formdata AS $key => $val)
		{
			$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
		}

		// Strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);

		if ($this->params->get("is_test") == 'TRUE')
		{
			$host = 'test.authorize.net';
		}
		else
		{
			$host = 'secure2.authorize.net';
		}

		$url = "https://$host/gateway/transact.dll";
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

	public function onAuthorizeStatus_Paymentrs_payment_authorize($element, $order_id)
	{
		if ($element != 'rs_payment_authorize')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		// Update authorize_status
		if ($this->params->get("auth_type") == "AUTH_ONLY")
		{
			$authorize_status = "Authorized";
		}
		else
		{
			$authorize_status = "Captured";
		}

		$db = JFactory::getDbo();
		$query = "UPDATE `#__redshop_order_payment` SET  authorize_status = '"
			. $authorize_status . "' where order_id = '" . $order_id . "'";
		$db->setQuery($query);
		$db->execute();
	}
}
