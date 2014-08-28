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

class plgRedshop_paymentrs_payment_epayv2 extends JPlugin
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
	public function plgRedshop_paymentrs_payment_epayv2(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_epayv2');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_epayv2')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
		JLoader::load('RedshopHelperProduct');
		JLoader::load('RedshopHelperCurrency');

		$producthelper  = new producthelper;
		$CurrencyHelper = new CurrencyHelper;
		$uri            = JURI::getInstance();
		$url            = $uri->root();
		$user           = JFactory::getUser();

		$formdata = array(
			'merchantnumber'  => $this->_params->get("merchant_id"),
			'amount'          => number_format($data['carttotal'], 2, '.', '') * 100,
			'currency'        => $CurrencyHelper->get_iso_code(CURRENCY_CODE),
			'orderid'         => $data['order_id'],
			'group'           => $this->_params->get("payment_group"),
			'instantcapture'  => $this->_params->get("auth_type"),
			'instantcallback' => 1,
			'language'        => $this->_params->get("language"),
			'windowstate'     => $this->_params->get("epay_window_state"),
			'windowid'        => $this->_params->get("windowid"),
			'ownreceipt'      => $this->_params->get("ownreceipt"),
			'use3D'           => $this->_params->get("epay_3dsecure"),
			'subscription'    => $this->_params->get("epay_subscription")
		);

		if ((int) $this->_params->get('activate_callback', 0) == 1)
		{
			$formdata['cancelurl']   = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=0';
			$formdata['callbackurl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=1';
			$formdata['accepturl']   = JURI::base() . 'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $data['order_id'];
		}
		else
		{
			$formdata['cancelurl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=0';
			$formdata['accepturl'] = JURI::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_epayv2&accept=1';
		}

		// Create hash value to post
		$formdata['hash'] = md5(implode($formdata, "") . $this->_params->get("epay_paymentkey"));

		// New Code
		$json_pass_string = json_encode($formdata);

		$html = '';
		$html .= '<script charset="UTF-8" src="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js" type="text/javascript"></script>';
		$html .= '<div id="payment-div"></div>';
		$html .= '<script type="text/javascript">';
			$html .= 'paymentwindow = new PaymentWindow(' . $json_pass_string . ');';
			$html .= 'paymentwindow.append(\'payment-div\');';
			$html .= 'paymentwindow.open();';
		$html .= '</script>';
		$html .= '<input onclick="javascript: paymentwindow.open()" type="button" value="Go to payment">';
		echo $html;
	}

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_epayv2($element, $request)
	{
		if ($element != 'rs_payment_epayv2')
		{
			return false;
		}

		$db = JFactory::getDbo();
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

		$values = new stdClass;
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

					// Add history callback info
					if ($accept == "2")
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_CALLBACK');
					}

					// Payment fee
					if ($request["transfee"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_FEE');
					}

					// Payment date
					if ($request["date"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_DATE');
					}

					// Payment fraud control
					if (@$request["fraud"])
					{
						$msg = JText::_('COM_REDSHOP_EPAY_FRAUD');
					}

					// Card id
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

					// Creation information
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

	public function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_epayv2($element, $data)
	{
		$epay_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $epay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// Get the class
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/epaysoap.php';
		include $paymentpath;

		// Access the webservice
		$epay = new EpaySoap;
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

	public function onStatus_Paymentrs_payment_epayv2($element, $data)
	{
		$epay_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $epay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// Get the class
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/epaysoap.php';
		include $paymentpath;

		// Access the webservice
		$epay = new EpaySoap;
		$merchantnumber = $paymentparams->get('merchant_id');

		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];

		$order_amount = round($data['order_amount'] * 100, 2);

		$response = $epay->gettransactionInformation($merchantnumber, $tid);

		if ($response['status'] == "PAYMENT_NEW")
		{
			$data_refund = $this->onCancel_Paymentrs_payment_epayv2($element, $data);
		}
		elseif ($response['status'] == "PAYMENT_CAPTURED")
		{
			$data_refund = $this->onRefund_Paymentrs_payment_epayv2($element, $data);
		}

		return $data_refund;
	}

	public function onCancel_Paymentrs_payment_epayv2($element, $data)
	{
		$epay_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $epay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// Access the webservice
		$epay = new EpaySoap;
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

	public function onRefund_Paymentrs_payment_epayv2($element, $data)
	{
		$epay_parameters = $this->getparameters('rs_payment_epayv2');
		$paymentinfo = $epay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// Access the webservice
		$epay = new EpaySoap;
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
