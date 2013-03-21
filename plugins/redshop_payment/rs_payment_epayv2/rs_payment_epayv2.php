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

class plgRedshop_paymentrs_payment_epayv2 extends JPlugin
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
	function plgRedshop_paymentrs_payment_epayv2(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_epayv2');
		$this->_params = new JRegistry($this->_plugin->params);


	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_epayv2')
		{
			return;
		}
		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $plugin . DS . $plugin . DS . 'extra_info.php';
		include($paymentpath);
	}


	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	function onNotifyPaymentrs_payment_epayv2($element, $request)
	{

		if ($element != 'rs_payment_epayv2')
		{
			break;
		}

		$db = jFactory::getDBO();
		$request = JRequest::get('request');

		$accept = $request["accept"];
		$tid = $request["txnid"];
		$order_id = $request["orderid"];
		$Itemid = $request["Itemid"];
		$order_amount = $request["amount"];
		@$order_ekey = $request["hash"];
		@$error = $request["error"];
		$order_currency = $request["currency"];
		$transfee = $request["txnfee"];
		$transfee = $transfee / 100;


		JPlugin::loadLanguage('com_redshop');
		$amazon_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$auth_type = $paymentparams->get('auth_type', '');

		$values = new stdClass();
		$epay_paymentkey = $paymentparams->get('epay_paymentkey', '');
		$epay_md5 = $paymentparams->get('epay_md5', '');


		$var = "";

		foreach ($request as $key => $value)
		{
			if ($key != "hash")
			{
				$var .= $value;
			}
		}
		// Generated Hash
		$genstamp = md5($var . $epay_paymentkey);

		// Now validat on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled

		if ((@$order_ekey == $genstamp) || $epay_md5 == 0)
		{


			// Switch on the order accept code
			// accept = 1 (standard redirect) accept = 2 (callback)

			if (empty($request['errorcode']) && ($accept == "1" || $accept == "2"))
			{


				// Only update the order information once

				if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
				{


					// UPDATE THE ORDER STATUS to 'VALID'
					$transaction_id = $tid;
					$values->order_status_code = $verify_status;
					$values->order_payment_status_code = 'Paid';
					$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
					$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');

					// add history callback info
					if ($accept == "2")
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_CALLBACK');
					}

					// payment fee
					if ($request["transfee"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_FEE');
					}

					// payment date
					if ($request["date"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_DATE');
					}

					// payment fraud control
					if (@$request["fraud"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_FRAUD');
					}

					// card id
					if ($request["cardid"])
					{
						$cardname = "Unknown";
						$cardimage = "c" . $_REQUEST["cardid"] . ".gif";
						switch ($_REQUEST["cardid"])
						{
							case 1:
								$cardname = 'Dankort (DK)';
								break;
							case 2:
								$cardname = 'Visa/Dankort (DK)';
								break;
							case 3:
								$cardname = 'Visa Electron (Udenlandsk)';
								break;
							case 4:
								$cardname = 'Mastercard (DK)';
								break;
							case 5:
								$cardname = 'Mastercard (Udenlandsk)';
								break;
							case 6:
								$cardname = 'Visa Electron (DK)';
								break;
							case 7:
								$cardname = 'JCB (Udenlandsk)';
								break;
							case 8:
								$cardname = 'Diners (DK)';
								break;
							case 9:
								$cardname = 'Maestro (DK)';
								break;
							case 10:
								$cardname = 'American Express (DK)';
								break;
							case 11:
								$cardname = 'Ukendt';
								break;
							case 12:
								$cardname = 'eDankort (DK)';
								break;
							case 13:
								$cardname = 'Diners (Udenlandsk)';
								break;
							case 14:
								$cardname = 'American Express (Udenlandsk)';
								break;
							case 15:
								$cardname = 'Maestro (Udenlandsk)';
								break;
							case 16:
								$cardname = 'Forbrugsforeningen (DK)';
								break;
							case 17:
								$cardname = 'eWire';
								break;
							case 18:
								$cardname = 'VISA';
								break;
							case 19:
								$cardname = 'IKANO';
								break;
							case 20:
								$cardname = 'Andre';
								break;
							case 21:
								$cardname = 'Nordea';
								break;
							case 22:
								$cardname = 'Danske Bank';
								break;
							case 23:
								$cardname = 'Danske Bank';
								break;
						}
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_CARDTYPE');

					}

					// creation information
					$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_LOG_TID');
					$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_TRANSACTION_SUCCESS');
				}
			}
			elseif ($accept == "0")
			{
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_ERROR');
			}
			else
			{
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
		}


		$values->transaction_id = $tid;
		$values->order_id = $order_id;
		$values->transfee = $transfee;

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


	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{

		$db = JFactory::getDBO();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->SetQuery($query);
		$order_payment = $db->loadResult();
		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	function onCapture_Paymentrs_payment_epayv2($element, $data)
	{

		$epay_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $epay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// get the class
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'epaysoap.php';
		include($paymentpath);

		//Access the webservice
		$epay = new EpaySoap();
		$merchantnumber = $paymentparams->get('merchant_id');


		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];

		$order_amount = round($data['order_amount'] * 100, 2);


		$response = $epay->capture($merchantnumber, $tid, $order_amount);


		if ($response['captureResult'] == 'true')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_ORDER_CAPTURED');
		}
		else
		{
			$message = JText::_('COM_REDSHOP_ORDER_NOT_CAPTURED');
			$values->responsestatus = 'Fail';
		}
		$values->message = $message;

		return $values;

	}


	function onStatus_Paymentrs_payment_epayv2($element, $data)
	{

		$epay_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $epay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// get the class
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'epaysoap.php';
		include($paymentpath);

		//Access the webservice
		$epay = new EpaySoap();
		$merchantnumber = $paymentparams->get('merchant_id');


		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];

		$order_amount = round($data['order_amount'] * 100, 2);

		$response = $epay->gettransactionInformation($merchantnumber, $tid);

		if ($response['status'] == "PAYMENT_NEW")
		{
			$data_refund = $this->onCancel_Paymentrs_payment_epayv2($element, $data);
		}
		else if ($response['status'] == "PAYMENT_CAPTURED")
		{
			$data_refund = $this->onRefund_Paymentrs_payment_epayv2($element, $data);
		}

		return $data_refund;


	}

	function onCancel_Paymentrs_payment_epayv2($element, $data)
	{

		$epay_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $epay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);


		//Access the webservice
		$epay = new EpaySoap();
		$merchantnumber = $paymentparams->get('merchant_id');


		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];

		$order_amount = round($data['order_amount'] * 100, 2);

		$response = $epay->delete($merchantnumber, $tid);
		if ($response['deleteResult'] == 1)
		{

			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_ORDER_REFUND');
		}
		else
		{
			$message = JText::_('COM_REDSHOP_ORDER_NOT_REFUND');
			$values->responsestatus = 'Fail';
		}
		$values->message = $message;

		return $values;


	}

	function onRefund_Paymentrs_payment_epayv2($element, $data)
	{

		$epay_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $epay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);


		//Access the webservice
		$epay = new EpaySoap();
		$merchantnumber = $paymentparams->get('merchant_id');


		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];

		$order_amount = round($data['order_amount'] * 100, 2);

		$response = $epay->credit($merchantnumber, $tid, $order_amount);

		if ($response['creditResult'] == 1)
		{

			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_ORDER_REFUND');
		}
		else
		{
			$message = JText::_('COM_REDSHOP_ORDER_NOT_REFUND');
			$values->responsestatus = 'Fail';
		}
		$values->message = $message;

		return $values;

	}


}
