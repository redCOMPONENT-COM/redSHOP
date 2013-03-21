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
class plgRedshop_paymentrs_payment_chase extends JPlugin
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
	function plgRedshop_paymentrs_payment_chase(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_chase');
		$this->_params = new JRegistry($this->_plugin->params);


	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment_rs_payment_chase($element, $data)
	{

		$config = new Redconfiguration;
		$currencyClass = new convertPrice;

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


		// get params from plugin
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


		$session =& JFactory::getSession();
		$ccdata = $session->get('ccdata');


		// Additional Customer Data
		$user_id = $data['billinginfo']->user_id;
		$remote_add = $_SERVER["REMOTE_ADDR"];


		// Email Settings
		$user_email = $data['billinginfo']->user_email;


		// get Credit card Information
		$order_payment_name = substr($ccdata['order_payment_name'], 0, 50);
		$creditcard_code = ucfirst(strtolower($ccdata['creditcard_code']));
		$order_payment_number = substr($ccdata['order_payment_number'], 0, 20);
		$credit_card_code = substr($ccdata['credit_card_code'], 0, 4);
		$order_payment_expire_month = substr($ccdata['order_payment_expire_month'], 0, 2);
		$order_payment_expire_year = substr($ccdata['order_payment_expire_year'], -2);
		//die();
		$order_number = substr($data['order_number'], 0, 16);
		$tax_exempt = false;

//echo $creditcard_code;

		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . 'rs_payment_chase' . DS . 'rs_payment_chase' . DS . 'class.Chase.php';
		include($paymentpath);

		// create object for chase ------------------------------------
		$obj_chase = new Chase($currency);

		if ($chase_test_status == 1)
		{
			$obj_chase->chase_gateway_url = "https://orbitalvar1.paymentech.net/authorize";
		}
		else
		{

			$obj_chase->chase_gateway_url = "https://orbital.paymentech.net/authorize";
		}

		//Assign merchant info
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


		//Assign Amount
		$tot_amount = $order_total = $data['order_total'];
		$amount = $currencyClass->convert($tot_amount, '', 'USD');
		$amount = number_format($amount, 2, '.', '') * 100;
		$obj_chase->Amount = $amount; //die();


		$response = $obj_chase->post_an_order();


		// call function to post an order ------
		if ($response['transaction_sts'] == "success")
		{
			//echo "order Success -----";

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
			//echo "order Fail -----";
			/*
							$errors_in_processing = $obj_chase->error;

							if(is_array($errors_in_processing))
							{
								$total_errors = count($errors_in_processing);
								for($i=0; $i<$total_errors; $i++)
								{
									$str_error.= "<br />".$errors_in_processing[$i];
								}
							}
			*/
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

	function getparameters($payment)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	function onCapture_Paymentrs_payment_chase($element, $data)
	{
		$db = JFactory::getDBO();
		require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
		$objOrder = new order_functions;

		// get params from plugin
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

		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . 'rs_payment_chase' . DS . 'class.Chase.php';
		include($paymentpath);

		// create object for chase ------------------------------------
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
		//die();

		//Assign merchant info
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

		// call function to post an order ------

		$response = $obj_chase->capture_an_order();

		if ($response['ProcStatus'] == 1)
		{
			//echo "transaction Success -----";
			$message = $response->StatusMsg;
			$values->responsestatus = 'Success';

		}
		else
		{
			//echo "transaction Fail -----";
			$message = $response->StatusMsg;
			$values->responsestatus = 'Fail';

		}

		$values->message = $message;

		return $values;

	}


}