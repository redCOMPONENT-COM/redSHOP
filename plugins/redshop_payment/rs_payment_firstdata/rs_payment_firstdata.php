<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/order.php';

class plgRedshop_paymentrs_payment_firstdata extends JPlugin
{
	public $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgRedshop_paymentrs_payment_firstdata(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_firstdata');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_firstdata($element, $data)
	{
		if ($element != 'rs_payment_firstdata')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$user = JFActory::getUser();
		$session = JFactory::getSession();
		$ccdata = $session->get('ccdata');
		$cart = $session->get('cart');

		// For total amount
		$cal_no = 2;

		if (defined('PRICE_DECIMAL'))
		{
			$cal_no = PRICE_DECIMAL;
		}

		$order_total = round($data['order_total'], $cal_no);
		$order_shipping = round($data['order_shipping'], $cal_no);
		$order_tax = round($data['order_tax'], $cal_no);
		$order_subtotal = round(($data['order_subtotal'] - $data['order_tax'] - $data['odiscount']), $cal_no);

		// Get Params from Plugin
		$is_test = $this->_params->get("is_test");
		$store_id = $this->_params->get("store_id");
		$auth_uname = $this->_params->get("auth_uname");
		$auth_password = $this->_params->get("auth_password");
		$key_password = $this->_params->get("key_password");
		$pem_file_path = $this->_params->get("pem_file_path");
		$key_file_path = $this->_params->get("key_file_path");
		$auth_type = $this->_params->get("auth_type");

		/*
		 * Storing the SOAP message in a variable – note that the plain XML code
		 * is passed here as string for reasons of simplicity, however, it is
		 * certainly a good practice to build the XML e.g. with DOM – furthermore,
		 * when using special characters, you should make sure that the XML string
		 * gets UTF-8 encoded (which is not done here):
		 */

		$body = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header/>

				<SOAP-ENV:Body>
				<fdggwsapi:FDGGWSApiOrderRequest xmlns:v1="http://secure.linkpt.net/fdggwsapi/schemas_us/v1" xmlns:v3="http://secure.linkpt.net/fdggwsapi/schemas_us/a1" xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi">

				<v1:Transaction>
				<v1:CreditCardTxType>
				<v1:Type>' . $auth_type . '</v1:Type>
				</v1:CreditCardTxType>

				<v1:CreditCardData>
				<v1:CardNumber>' . $ccdata['order_payment_number'] . '</v1:CardNumber>
				<v1:ExpMonth>' . $ccdata['order_payment_expire_month'] . '</v1:ExpMonth>
				<v1:ExpYear>' . substr($ccdata['order_payment_expire_year'], -2) . '</v1:ExpYear>
				</v1:CreditCardData>

				<v1:Payment>
					<v1:ChargeTotal>' . $order_total . '</v1:ChargeTotal>
					<v1:SubTotal>' . $order_subtotal . '</v1:SubTotal>
					<v1:VATTax>' . $order_tax . '</v1:VATTax>
					<v1:Shipping>' . $order_shipping . '</v1:Shipping>
				</v1:Payment>

				 <v1:TransactionDetails>
					<v1:UserID>' . $user->id . '</v1:UserID>
               				<v1:OrderId>' . $data['order_number'] . '</v1:OrderId>
            			</v1:TransactionDetails>

				<v1:Billing>
					 <v1:CustomerID>' . $user->id . '</v1:CustomerID>
				   	 <v1:Name>' . $data['billinginfo']->firstname . ' ' . $data['billinginfo']->lastname . '</v1:Name>
				   	 <v1:Address1>' . $data['billinginfo']->address . '</v1:Address1>
					 <v1:City>' . $data['billinginfo']->city . '</v1:City>
					 <v1:State>' . $data['billinginfo']->state_code . '</v1:State>
					 <v1:Zip>' . $data['billinginfo']->zipcode . '</v1:Zip>
					 <v1:Country>' . $data['billinginfo']->country_code . '</v1:Country>
					 <v1:Email>' . $data['billinginfo']->user_email . '</v1:Email>
			     	</v1:Billing>
				<v1:Shipping>
					 <v1:Name>' . $data['shippinginfo']->firstname . ' ' . $data['shippinginfo']->lastname . '</v1:Name>
				   	 <v1:Address1>' . $data['shippinginfo']->address . '</v1:Address1>
					 <v1:City>' . $data['shippinginfo']->city . '</v1:City>
					 <v1:State>' . $data['shippinginfo']->state_code . '</v1:State>
					 <v1:Zip>' . $data['shippinginfo']->zipcode . '</v1:Zip>
					 <v1:Country>' . $data['shippinginfo']->country_code . '</v1:Country>
				</v1:Shipping>
				</v1:Transaction>

				</fdggwsapi:FDGGWSApiOrderRequest>

				</SOAP-ENV:Body>
				</SOAP-ENV:Envelope>';

		// Initializing cURL with the IPG API URL (OLD URL):
		if ($is_test == 1)
		{
			$ch = curl_init("https://ws.merchanttest.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");
		}
		else
		{
			$ch = curl_init("https://ws.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");
		}

		// Setting the request type to POST:
		curl_setopt($ch, CURLOPT_POST, 1);

		// Setting the content type:
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

		// Setting the authorization method to BASIC:
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		// Supplying your credentials:
		curl_setopt($ch, CURLOPT_USERPWD, "" . $auth_uname . "._.1:" . $auth_password . "");

		// Filling the request body with your SOAP message:
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

		// Telling cURL to verify the server certificate:
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		// Setting the path where cURL can find the certificate to verify the Info directly from the API Manual Below:
		curl_setopt($ch, CURLOPT_SSLCERT, JPATH_SITE . '/plugins/redshop_payment/' . $plugin . DS . $plugin . '/certificates/WS' . $store_id . '._.1.pem');
		curl_setopt($ch, CURLOPT_SSLKEY, JPATH_SITE . '/plugins/redshop_payment/' . $plugin . DS . $plugin . '/certificates/WS' . $store_id . '._.1.key');
		curl_setopt($ch, CURLOPT_SSLKEYPASSWD, '' . $key_password . '');

		// Telling cURL to return the HTTP response body as operation result value when calling curl_exec:
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// If the curl executed successfully then it will return the <saop> XML response with getTransactionResult
		$result = curl_exec($ch);
		curl_close($ch);

		if ($this->getTransactionResult($result) == 'FAILED')
		{
			$message = $this->getErrorMessage($result);
			$values->responsestatus = 'Fail';
		}
		else
		{
			$values->responsestatus = 'Success';
			$message = JText::_('ORDER_PLACED');
			$values->transaction_id = $this->getFirstDataTransactionID($result);
		}

		$values->message = $message;

		return $values;
	}

	public function getErrorMessage($result)
	{
		$varPos = strpos($result, '<fdggwsapi:ErrorMessage>');
		$varPos2 = strpos($result, '</fdggwsapi:ErrorMessage>');

		if ($varPos !== false)
		{
			$varLen = $varPos2 - $varPos;

			return substr($result, $varPos, $varLen);
		}

	}

	public function getTransactionResult($result)
	{
		$varPos = strpos($result, '<fdggwsapi:TransactionResult>');
		$varPos2 = strpos($result, '</fdggwsapi:TransactionResult>');

		if ($varPos !== false)
		{
			$varPos = $varPos + 29;
			$varLen = $varPos2 - $varPos;

			return substr($result, $varPos, $varLen);
		}
		else
		{
			return 'FAILED';
		}
	}

	public function getFirstDataTransactionID($result)
	{
		$varPos = strpos($result, '<fdggwsapi:TransactionID>');
		$varPos2 = strpos($result, '</fdggwsapi:TransactionID>');
		$varPos = $varPos + 25;
		$varLen = $varPos2 - $varPos;

		return substr($result, $varPos, $varLen);
	}

	public function onCapture_Paymentrs_payment_firstdata($element, $data)
	{
		if ($element != 'rs_payment_firstdata')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$objOrder = new order_functions;
		$order_id = $data['order_id'];
		$order_number = $data['order_number'];
		$tid = $data['order_transactionid'];

		// For total amount
		$cal_no = 2;

		if (defined('PRICE_DECIMAL'))
		{
			$cal_no = PRICE_DECIMAL;
		}

		$order_amount = round($data['order_amount'], $cal_no);

		// Get Plugin parameters
		$is_test = $this->_params->get("is_test");
		$store_id = $this->_params->get("store_id");
		$auth_uname = $this->_params->get("auth_uname");
		$auth_password = $this->_params->get("auth_password");
		$key_password = $this->_params->get("key_password");
		$pem_file_path = $this->_params->get("pem_file_path");
		$key_file_path = $this->_params->get("key_file_path");
		$auth_type = $this->_params->get("auth_type");

		// Authnet vars to send
		$body1 = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header/>

				<SOAP-ENV:Body>
				<fdggwsapi:FDGGWSApiOrderRequest xmlns:v1="http://secure.linkpt.net/fdggwsapi/schemas_us/v1"  xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi">

				<v1:Transaction>
				<v1:CreditCardTxType>
					<v1:Type>postAuth</v1:Type>
				</v1:CreditCardTxType>
				<v1:Payment>
					<v1:ChargeTotal>' . $order_amount . '</v1:ChargeTotal>
				</v1:Payment>
				<v1:TransactionDetails>
				<v1:OrderId>
					' . $order_number . '
				</v1:OrderId>
				</v1:TransactionDetails>
				</v1:Transaction>
				</fdggwsapi:FDGGWSApiOrderRequest>

				</SOAP-ENV:Body>
				</SOAP-ENV:Envelope>';

		// Initializing cURL with the IPG API URL (OLD URL):
		if ($is_test == 1)
		{
			$ch = curl_init("https://ws.merchanttest.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");
		}
		else
		{
			$ch = curl_init("https://ws.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");
		}

		// Setting the request type to POST:
		curl_setopt($ch, CURLOPT_POST, 1);

		// Setting the content type:
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

		// Setting the authorization method to BASIC:
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		// Supplying your credentials:
		curl_setopt($ch, CURLOPT_USERPWD, "" . $auth_uname . "._.1:" . $auth_password . "");

		// Filling the request body with your SOAP message:
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body1);

		// Telling cURL to verify the server certificate:
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		// Setting the path where cURL can find the certificate to verify the Info directly from the API Manual Below:
		curl_setopt($ch, CURLOPT_SSLCERT, JPATH_SITE . '/plugins/redshop_payment/' . $plugin . DS . $plugin . '/certificates/WS' . $store_id . '._.1.pem');
		curl_setopt($ch, CURLOPT_SSLKEY, JPATH_SITE . '/plugins/redshop_payment/' . $plugin . DS . $plugin . '/certificates/WS' . $store_id . '._.1.key');
		curl_setopt($ch, CURLOPT_SSLKEYPASSWD, '' . $key_password . '');

		// Telling cURL to return the HTTP response body as operation result value when calling curl_exec:
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// If the curl executed successfully then it will return the <saop> XML response with getTransactionResult
		$result = curl_exec($ch);

		if ($this->getTransactionResult($result) == 'FAILED')
		{
			$message = JText::_('ORDER_NOT_CAPTURED');
			$values->responsestatus = 'Fail';
		}
		else
		{
			$values->responsestatus = 'Success';
			$message = JText::_('ORDER_CAPTURED');
		}

		$values->message = $message;

		return $values;
	}
}
