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

/**
 *  PlgRedshop_PaymentRs_Payment_Authorize class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Authorize extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var  boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * onPrePayment_rs_payment_authorize Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data array]
	 *
	 * @return  [$values]           [array]
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
		$calNo = 2;

		if (Redshop::getConfig()->get('PRICE_DECIMAL') != '')
		{
			$calNo = Redshop::getConfig()->get('PRICE_DECIMAL');
		}

		$orderTotal = round($data['order_total'], $calNo);

		$itemDetails = "";

		for ($p = 0; $p < $cart['idx']; $p++)
		{
			if (isset($cart[$p]['product_id']) && $cart[$p]['product_id'] != "")
			{
				$productId   = $cart[$p]['product_id'];

				$query = $db->getQuery(true);
				$query->select($db->qn(['product_name', 'product_s_desc']))
					->from($db->qn('#__redshop_product'))
					->where($db->qn('product_id') . ' = ' . $db->q($cart[$p]['product_id']));

				$db->setQuery($query);
				$productInfo = $db->loadObjectlist();
				$productName = substr(str_replace("&", "and", $productInfo[0]->product_name), 0, 30);
			}

			if (isset($cart[$p]['giftcard_id']) && $cart[$p]['giftcard_id'] != "")
			{
				$productId   = $cart[$p]['giftcard_id'];

				$query = $db->getQuery(true);
				$query->select(
						$db->qn(
							[
								'giftcard_id', 'giftcard_name', 'giftcard_price',
								'giftcard_value', 'giftcard_validity', 'giftcard_date',
								'giftcard_bgimage', 'giftcard_image', 'published',
								'giftcard_desc', 'customer_amount', 'accountgroup_id',
								'free_shipping'
							]
						)
					)
					->from($db->qn('#__redshop_giftcard'))
					->where($db->qn('giftcard_id') . ' = ' . $db->q($cart[$p]['giftcard_id']));

				$db->setQuery($query_gift);

				$giftCardInfo = $db->loadObjectlist();
				$productName = substr(str_replace("&", "and", $giftCardInfo[0]->giftcard_name), 0, 30);
			}

			if ($cart[$p]['product_price_excl_vat'] == $cart[$p]['product_price'])
			{
				$taxable = "N";
			}
			else
			{
				$taxable = "Y";
			}

			$productPrice = round($cart[$p]['product_price'], $calNo);

			$itemDetails[] = $productId . "<|>" . $productName . "<|><|>" . $cart[$p]['quantity'] . "<|>" . $productPrice . "<|>" . $taxable;
		}

		$itemString = implode("&x_line_item=", $itemDetails);

		// For Email Receipt
		$merchantEmail = ($this->params->get("emailreceipt_to_customer") == 1)? $data['billinginfo']->user_email: '';

		$viewTableFormat = $this->params->get("view_table_format");

		// Authnet vars to send

		$formData = array(
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
			'x_merchant_email'     => $merchantEmail,

			// Invoice Information
			'x_invoice_num'        => substr($data['order_number'], 0, 20),
			'x_description'        => JText::_('COM_REDSHOP_AUTHORIZENET_ORDER_PRINT_PO_LBL'),

			// Item information

			'x_line_item'          => $itemString,

			// Transaction Data
			'x_amount'             => $orderTotal,
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

		if ($viewTableFormat == 0)
		{
			unset($formData['x_line_item']);
		}

		// Build the post string
		$postString = '';

		foreach ($formData AS $key => $val)
		{
			$postString .= urlencode($key) . "=" . $val . "&";
		}

		// Strip off trailing ampersand
		$postString = substr($postString, 0, -1);

		$host = ($this->params->get("is_test") == 'TRUE')? 'test.authorize.net': 'secure2.authorize.net';

		$url = "https://$host/gateway/transact.dll";

		$urlParts = parse_url($url);

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);

		curl_setopt($curl, CURLOPT_TIMEOUT, 30);

		curl_setopt($curl, CURLOPT_FAILONERROR, true);

		if ($postString)
		{
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postString);

			curl_setopt($curl, CURLOPT_POST, 1);
		}

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		if ($urlParts['scheme'] == 'https')
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		}

		$result = curl_exec($curl);

		$error = curl_error($curl);

		curl_close($curl);

		if (!$result || !empty($error))
		{
			return false;
		}

		$responseVars = explode('|', $result);

		$responseVars[0] = str_replace('"', '', $responseVars[0]);
		$transactionId = $responseVars[6];

		if ($responseVars[0] == '1' || $responseVars[0] == 1)
		{
			$values->responsestatus = 'Success';
			$message = $responseVars[3];
		}
		else
		{
			// Catch Transaction ID
			$message = "ERROR RESPONCE CODE : " . $responseVars[0] . "<br>" . $responseVars[3];
			$values->responsestatus = 'Fail';
		}

		$values->transaction_id = $transactionId;
		$values->message = $message;

		return $values;
	}

	/**
	 * onCapture_Paymentrs_payment_authorize
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data array]
	 *
	 * @return  [$values]           [array]
	 */
	public function onCapture_Paymentrs_payment_authorize($element, $data)
	{
		if ($element != 'rs_payment_authorize')
		{
			return;
		}

		$db           = JFactory::getDbo();
		$orderId      = $data['order_id'];
		$tranId          = $data['order_transactionid'];
		$billingInfo  = $data['billinginfo'];
		$shippingInfo = $data['shippinginfo'];

		// Fetch the Credit Card information from Order Id
		$query = $db->getQuery(true);
		$query->select(
				$db->qn(
					[
						'o.order_total', 'o.user_id',
						'op.payment_order_id', 'op.order_id', 'op.payment_method_id',
						'op.order_payment_code', 'op.order_payment_cardname', 'op.order_payment_number',
						'op.order_payment_ccv', 'op.order_payment_amount', 'op.order_payment_expire',
						'op.order_payment_name', 'op.payment_method_class', 'op.order_payment_trans_id',
						'op.authorize_status', 'op.order_transfee'
					]
				)
			)
			->from($db->qn('#__redshop_order_payment', 'op'))
			->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('op.order_id') . ' = ' . $db->qn('o.order_id'))
			->where($db->qn('o.order_id') . ' = ' . $db->q('orderId'))
			->where($db->qn('op.order_payment_trans_id') . ' = ' . $db->q($tranId));

		$db->setQuery($query);
		$orderDetails = $db->loadObject();

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
		$calNo = 2;

		if (Redshop::getConfig()->get('PRICE_DECIMAL') != '')
		{
			$calNo = Redshop::getConfig()->get('PRICE_DECIMAL');
		}

		$orderTotal = round($orderDetails->order_total, $calNo);

		// Authnet vars to send
		$formData = array(
			'x_version'            => '3.1',
			'x_login'              => $this->params->get("access_id"),
			'x_tran_key'           => $this->params->get("transaction_id"),
			// Gateway Response Configuration
			'x_delim_data'         => 'TRUE',
			'x_delim_char'         => '|',
			'x_relay_response'     => 'FALSE',
			// Customer Name and Billing Address
			'x_first_name'         => substr($billingInfo->firstname, 0, 50),
			'x_last_name'          => substr($billingInfo->lastname, 0, 50),
			'x_company'            => substr(@$billingInfo->company, 0, 50),
			'x_address'            => substr($billingInfo->address, 0, 60),
			'x_city'               => substr($billingInfo->city, 0, 40),
			'x_state'              => substr($billingInfo->state_code, 0, 40),
			'x_zip'                => substr($billingInfo->zipcode, 0, 20),
			'x_country'            => substr($billingInfo->country_code, 0, 60),
			'x_phone'              => substr($billingInfo->phone, 0, 25),
			'x_fax'                => substr(@$billingInfo->fax, 0, 25),
			// Customer Shipping Address
			'x_ship_to_first_name' => substr($shippingInfo->firstname, 0, 50),
			'x_ship_to_last_name'  => substr($shippingInfo->lastname, 0, 50),
			'x_ship_to_company'    => substr(@$shippingInfo->company, 0, 50),
			'x_ship_to_address'    => substr($shippingInfo->address, 0, 60),
			'x_ship_to_city'       => substr($shippingInfo->city, 0, 40),
			'x_ship_to_state'      => substr($shippingInfo->state_code, 0, 40),
			'x_ship_to_zip'        => substr($shippingInfo->zipcode, 0, 20),
			'x_ship_to_country'    => substr($shippingInfo->country_code, 0, 60),
			// Additional Customer Data
			'x_cust_id'            => $billingInfo->user_id,
			'x_customer_ip'        => $_SERVER["REMOTE_ADDR"],
			// Email Settings
			'x_email'              => $billingInfo->user_email,
			'x_email_customer'     => $this->params->get("emailreceipt_to_customer"),
			'x_merchant_email'     => $x_merchant_email,
			// Invoice Information
			'x_invoice_num'        => substr($data['order_number'], 0, 20),
			'x_description'        => JText::_('COM_REDSHOP_AUTHORIZENET_ORDER_PRINT_PO_LBL'),
			// Transaction Data
			'x_amount'             => $orderTotal,
			'x_currency_code'      => Redshop::getConfig()->get('CURRENCY_CODE'),
			'x_method'             => 'CC',
			'x_type'               => "PRIOR_AUTH_CAPTURE",
			'x_card_num'           => base64_decode($orderDetails->order_payment_number),
			'x_card_code'          => base64_decode($orderDetails->order_payment_ccv),
			'x_exp_date'           => $orderDetails->order_payment_expire,
			'x_trans_id'           => $tranId,
			// Level 2 data
			'x_po_num'             => substr($orderId, 0, 20),
		);

		// Build the post string
		$postString = '';

		foreach ($formData AS $key => $val)
		{
			$postString .= urlencode($key) . "=" . urlencode($val) . "&";
		}

		// Strip off trailing ampersand
		$postString = substr($postString, 0, -1);

		$host = ($this->params->get("is_test") == 'TRUE')? 'test.authorize.net': 'secure2.authorize.net';

		$url = "https://$host/gateway/transact.dll";
		$urlParts = parse_url($url);
		$postString = substr($postString, 0, -1);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);

		if ($postString)
		{
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postString);
			curl_setopt($curl, CURLOPT_POST, 1);
		}

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		if ($urlParts['scheme'] == 'https')
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		}

		$result = curl_exec($curl);
		curl_close($curl);
		$responseVars = explode('|', $result);
		$responseCode = $responseVars[0];

		if ($responseCode == '1')
		{
			$values->responseStatus = 'Success';
			$message = $responseVars[3];
		}
		else
		{
			$values->responseStatus = 'Fail';
			$message = "ERROR RESPONCE CODE : " . $responseVars[0] . "<br>" . $responseVars[3];
		}

		$values->message = $message;

		return $values;
	}

	/**
	 * onCapture_Paymentrs_payment_authorize
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [int]     $orderId  [data array]
	 *
	 * @return  [void]
	 */
	public function onAuthorizeStatus_Paymentrs_payment_authorize($element, $orderId)
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
		$authorizeStatus = ($this->params->get("auth_type") == "AUTH_ONLY")? "Authorized": "Captured";

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$fields = [
			$db->qn('authorize_status') . ' = ' . $db->q($authorizeStatus),
		];

		$query->update($db->qn('#__redshop_order_payment'))
			->set($fields)
			->where($db->qn('order_id') . ' = ' . $db->q($orderId));

		$db->setQuery($query);
		$db->execute();
	}
}
