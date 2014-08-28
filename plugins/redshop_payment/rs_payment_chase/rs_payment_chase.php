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

class plgRedshop_paymentrs_payment_chase extends JPlugin
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
	public function plgRedshop_paymentrs_payment_chase(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_chase');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_chase($element, $data)
	{
		$config = new Redconfiguration;
		$currencyClass = new CurrencyHelper;

		// Get user billing information
		$user = JFActory::getUser();

		if ($element != 'rs_payment_chase')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		// Get params from plugin
		$chase_parameters = $this->getparameters('rs_payment_chase');
		$paymentinfo = $chase_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$chase_terminal_id = $paymentparams->get('chase_terminal_id', '');
		$chase_merchant_id = $paymentparams->get('chase_merchant_id', '');
		$chase_conn_username = $paymentparams->get('chase_conn_username', '');
		$chase_conn_password = $paymentparams->get('chase_conn_password', '');
		$chase_test_status = $paymentparams->get('chase_test_status', '');
		$chase_transaction_type = $paymentparams->get('chase_transaction_type', '');
		$debug_mode = $paymentparams->get('debug_mode', 0);

		$session = JFactory::getSession();
		$ccdata = $session->get('ccdata');

		// Additional Customer Data
		$user_id = $data['billinginfo']->user_id;
		$remote_add = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$user_email = $data['billinginfo']->user_email;

		// Get Credit card Information
		$order_payment_name = substr($ccdata['order_payment_name'], 0, 50);
		$creditcard_code = ucfirst(strtolower($ccdata['creditcard_code']));
		$order_payment_number = substr($ccdata['order_payment_number'], 0, 20);
		$credit_card_code = substr($ccdata['credit_card_code'], 0, 4);
		$order_payment_expire_month = substr($ccdata['order_payment_expire_month'], 0, 2);
		$order_payment_expire_year = substr($ccdata['order_payment_expire_year'], -2);

		$order_number = substr($data['order_number'], 0, 16);
		$tax_exempt = false;

		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/rs_payment_chase/rs_payment_chase/class.Chase.php';
		include $paymentpath;

		// Create object for chase
		$obj_chase = new Chase($currency);

		if ($chase_test_status == 1)
		{
			$obj_chase->chase_gateway_url = "https://orbitalvar1.paymentech.net/authorize";
		}
		else
		{
			$obj_chase->chase_gateway_url = "https://orbital.paymentech.net/authorize";
		}

		// Assign merchant info
		$obj_chase->OrbitalConnectionUsername = $chase_conn_username;
		$obj_chase->OrbitalConnectionPassword = $chase_conn_password;
		$obj_chase->IndustryType = 'EC';
		$obj_chase->MessageType = $chase_transaction_type;
		$obj_chase->BIN = '000002';
		$obj_chase->MerchantID = $chase_merchant_id;
		$obj_chase->TerminalID = $chase_terminal_id;
		$obj_chase->OrderID = $data['order_number'];

		// Assign Credit Card Information
		$obj_chase->AccountNum = $order_payment_number;
		$card_expire_str = str_pad($order_payment_expire_month, 2, "0", STR_PAD_LEFT) . $order_payment_expire_year;
		$obj_chase->Exp = $card_expire_str;
		$obj_chase->CardSecVal = $credit_card_code;
		$obj_chase->CCtype = $creditcard_code;

		// Assign AVS Information
		$obj_chase->AVSname = $order_payment_name;
		$obj_chase->AVSzip = $data['billinginfo']->zipcode;
		$obj_chase->AVSaddress1 = $data['billinginfo']->address;
		$obj_chase->AVSaddress2 = "";
		$obj_chase->AVScity = $data['billinginfo']->city;
		$obj_chase->AVSstate = $data['billinginfo']->state_code;
		$obj_chase->AVSphoneNum = $data['billinginfo']->phone;

		// Assign Other information
		$obj_chase->Email = $uname;
		$obj_chase->Phone = $phone;
		$obj_chase->Comments = 'Email - ' . $uname . ' | Phone - ' . $phone;

		// Assign Amount
		$tot_amount = $order_total = $data['order_total'];
		$amount = $currencyClass->convert($tot_amount, '', 'USD');
		$amount = number_format($amount, 2, '.', '') * 100;
		$obj_chase->Amount = $amount;

		$response = $obj_chase->post_an_order();

		// Call function to post an order
		if ($response['transaction_sts'] == "success")
		{
			if ($debug_mode == 1)
			{
				$message = $response['message'];
			}
			else
			{
				$message = JText::_('COM_REDSHOP_ORDER_PLACED');
			}

			$values->responsestatus = 'Success';
			$values->transaction_id = $response['TxRefNum'];
		}
		else
		{
			if ($debug_mode == 1)
			{
				$message = $response['message'];
			}
			else
			{
				$message = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			}

			$tid = 0;
			$values->transaction_id = 0;
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}

	public function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function onCapture_Paymentrs_payment_chase($element, $data)
	{
		$db = JFactory::getDbo();
		JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
		JLoader::load('RedshopHelperAdminOrder');
		$objOrder = new order_functions;

		// Get params from plugin
		$chase_parameters = $this->getparameters('rs_payment_chase');
		$paymentinfo = $chase_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$chase_terminal_id = $paymentparams->get('chase_terminal_id', '');
		$chase_merchant_id = $paymentparams->get('chase_merchant_id', '');
		$chase_conn_username = $paymentparams->get('chase_conn_username', '');
		$chase_conn_password = $paymentparams->get('chase_conn_password', '');
		$chase_test_status = $paymentparams->get('chase_test_status', '');
		$chase_transaction_type = $paymentparams->get('chase_transaction_type', '');

		// Add request-specific fields to the request string.

		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/rs_payment_chase/class.Chase.php';
		include $paymentpath;

		// Create object for chase
		$obj_chase = new Chase($currency);

		if ($chase_test_status == 1)
		{
			$obj_chase->chase_gateway_url = "https://orbitalvar1.paymentech.net/authorize";
		}
		else
		{
			$obj_chase->chase_gateway_url = "https://orbital.paymentech.net/authorize";
		}

		$amount = number_format($data['order_amount'], 2, '.', '') * 100;

		// Assign merchant info
		$obj_chase->OrbitalConnectionUsername = $chase_conn_username;
		$obj_chase->OrbitalConnectionPassword = $chase_conn_password;
		$obj_chase->IndustryType = 'EC';
		$obj_chase->MessageType = $chase_transaction_type;
		$obj_chase->BIN = '000002';
		$obj_chase->MerchantID = $chase_merchant_id;
		$obj_chase->TerminalID = $chase_terminal_id;
		$obj_chase->OrderID = $data['order_number'];
		$obj_chase->Amount = $amount;
		$obj_chase->TxRefNum = $data['order_transactionid'];

		// Call function to post an order ------

		$response = $obj_chase->capture_an_order();

		if ($response['ProcStatus'] == 1)
		{
			$message = $response->StatusMsg;
			$values->responsestatus = 'Success';
		}
		else
		{
			$message = $response->StatusMsg;
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}
}
