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
class plgRedshop_paymentrs_payment_quickpay extends JPlugin
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
	function plgRedshop_paymentrs_payment_quickpay(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_quickpay');
		$this->_params = new JRegistry($this->_plugin->params);


	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{

		if ($element != 'rs_payment_quickpay')
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

	function onNotifyPaymentrs_payment_quickpay($element, $request)
	{

		if ($element != 'rs_payment_quickpay')
		{
			return;
		}

		$db = JFactory::getDBO();
		$request = JRequest::get('request');

		$order_id = $request["orderid"];
		$order_amount = $request["amount"];
		$order_currency = $request["currency"];
		$order_currency = $request["time"];
		$order_ekey = $request["state"];
		$qpstat = $request["qpstat"];
		$chstat = $request["chstat"];
		$transaction = $request["transaction"];
		$tid = $request["transaction"];
		$cardtype = $request["cardtype"];
		$cardnumber = $request["cardnumber"];
		$md5check = $request["md5check"];

		$md5word = $request["md5word"];
		$ok_page = $request["callback"];

		$quickpay_parameters = $this->getparameters('rs_payment_quickpay');
		$paymentinfo = $quickpay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');

		if ($qpstat == "000")
		{
			// Find the corresponding order in the database
			$db = JFactory::getDBO();
			$qv = "SELECT order_id, order_number FROM #__redshop_orders WHERE order_id='" . $order_id . "'";
			$db->SetQuery($qv);
			$orders = $db->LoadObjectList();

			if ($orders)
			{
				foreach ($orders as $order_detail)
				{
					$d['order_id'] = $order_detail->order_id;
				}
				// Switch on the order accept code
				// accept = 000 (callback)
				//
				// Only update the order information once
				//

				// UPDATE THE ORDER STATUS to 'VALID'
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $transaction;
		$values->order_id = $order_id;


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
		$query = "SELECT COUNT(*), `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->SetQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	function onCapture_Paymentrs_payment_quickpay($element, $data)
	{

		if ($element != 'rs_payment_quickpay')
		{
			return;
		}
		require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');

		$objOrder = new order_functions();
		$db = JFactory::getDBO();

		$protocol = '3';
		$msgtype = 'capture';
		$finalize = 1;
		$merchant_id = $this->_params->get("quickpay_customer_id");
		$order_amount = ($data['order_amount'] * 100);
		$transaction = $data['order_transactionid'];
		$md5word = $this->_params->get("quickpay_paymentkey");
		$md5check = md5($protocol . $msgtype . $merchant_id . $order_amount . $finalize . $transaction . $md5word);


		$message = array('protocol' => $protocol, 'msgtype' => $msgtype, 'merchant' => $merchant_id, 'amount' => $order_amount, 'finalize' => $finalize, 'transaction' => $transaction, 'md5check' => $md5check);

		$context = stream_context_create(
			array(
				'http' => array(
					'method'  => 'POST',
					'content' => http_build_query($message, false, '&'),
				),
			)
		);

		if (!$fp = @fopen('https://secure.quickpay.dk/api', 'r', false, $context))
		{
			throw new Exception('Could not connect to gateway');
		}

		if (($response = @stream_get_contents($fp)) === false)
		{
			throw new Exception('Could not read data from gateway');
		}

		$response = new SimpleXMLElement($response);
		$qpstat = $response->qpstat;
		$qpstatmsg = addslashes($response->qpstatmsg);

		if ($qpstat == '000')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_ORDER_CAPTURED');
		}
		else
		{
			$message = $qpstatmsg ? $qpstatmsg : JText::_('COM_REDSHOP_ORDER_NOT_CAPTURED');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}


	function onRefund_Paymentrs_payment_quickpay($element, $data)
	{

		if ($element != 'rs_payment_quickpay')
		{
			return;
		}
		require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');

		$objOrder = new order_functions();
		$db = JFactory::getDBO();

		$protocol = '3';
		$msgtype = 'refund';
		$merchant_id = $this->_params->get("quickpay_customer_id");
		$order_amount = ($data['order_amount'] * 100);
		$transaction = $data['order_transactionid'];
		$md5word = $this->_params->get("quickpay_paymentkey");
		$md5check = md5($protocol . $msgtype . $merchant_id . $order_amount . $transaction . $md5word);


		$message = array('protocol' => $protocol, 'msgtype' => $msgtype, 'merchant' => $merchant_id, 'amount' => $order_amount, 'transaction' => $transaction, 'md5check' => $md5check);

		$context = stream_context_create(
			array(
				'http' => array(
					'method'  => 'POST',
					'content' => http_build_query($message, false, '&'),
				),
			)
		);

		if (!$fp = @fopen('https://secure.quickpay.dk/api', 'r', false, $context))
		{
			throw new Exception('Could not connect to gateway');
		}

		if (($response = @stream_get_contents($fp)) === false)
		{
			throw new Exception('Could not read data from gateway');
		}

		$response = new SimpleXMLElement($response);
		$qpstat = $response->qpstat;
		$qpstatmsg = addslashes($response->qpstatmsg);

		if ($qpstat == '000')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('QUICKPAY_ORDER_REFUND');
		}
		else
		{
			$message = $qpstatmsg ? $qpstatmsg : JText::_('QUICKPAY_ORDER_NOT_REFUND');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}


	function onStatus_Paymentrs_payment_quickpay($element, $data)
	{

		if ($element != 'rs_payment_quickpay')
		{
			return;
		}
		require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');

		$objOrder = new order_functions();
		$db = JFactory::getDBO();

		$protocol = '3';
		$msgtype = 'status';
		$merchant_id = $this->_params->get("quickpay_customer_id");
		$order_amount = ($data['order_amount'] * 100);
		$transaction = $data['order_transactionid'];
		$md5word = $this->_params->get("quickpay_paymentkey");
		$md5check = md5($protocol . $msgtype . $merchant_id . $order_amount . $transaction . $md5word);


		$message = array('protocol' => $protocol, 'msgtype' => $msgtype, 'merchant' => $merchant_id, 'amount' => $order_amount, 'transaction' => $transaction, 'md5check' => $md5check);

		$context = stream_context_create(
			array(
				'http' => array(
					'method'  => 'POST',
					'content' => http_build_query($message, false, '&'),
				),
			)
		);

		if (!$fp = @fopen('https://secure.quickpay.dk/api', 'r', false, $context))
		{
			throw new Exception('Could not connect to gateway');
		}

		if (($response = @stream_get_contents($fp)) === false)
		{
			throw new Exception('Could not read data from gateway');
		}

		$response = new SimpleXMLElement($response);
		$status_count = count($response->history) - 1;
		$quickpay_status = $response->history[$status_count]->msgtype;

		if ($quickpay_status == "authorize")
		{
			$data_refund = $this->onCancel_Paymentrs_payment_quickpay($element, $data);
		}
		else if ($quickpay_status == "capture")
		{
			$data_refund = $this->onRefund_Paymentrs_payment_quickpay($element, $data);
		}

		return $data_refund;
	}

	function onCancel_Paymentrs_payment_quickpay($element, $data)
	{

		if ($element != 'rs_payment_quickpay')
		{
			return;
		}
		require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');

		$objOrder = new order_functions();
		$db = JFactory::getDBO();

		$protocol = '3';
		$msgtype = 'cancel';
		$merchant_id = $this->_params->get("quickpay_customer_id");
		$order_amount = ($data['order_amount'] * 100);
		$transaction = $data['order_transactionid'];
		$md5word = $this->_params->get("quickpay_paymentkey");
		$md5check = md5($protocol . $msgtype . $merchant_id . $order_amount . $transaction . $md5word);


		$message = array('protocol' => $protocol, 'msgtype' => $msgtype, 'merchant' => $merchant_id, 'amount' => $order_amount, 'transaction' => $transaction, 'md5check' => $md5check);

		$context = stream_context_create(
			array(
				'http' => array(
					'method'  => 'POST',
					'content' => http_build_query($message, false, '&'),
				),
			)
		);

		if (!$fp = @fopen('https://secure.quickpay.dk/api', 'r', false, $context))
		{
			throw new Exception('Could not connect to gateway');
		}

		if (($response = @stream_get_contents($fp)) === false)
		{
			throw new Exception('Could not read data from gateway');
		}

		$response = new SimpleXMLElement($response);
		$qpstat = $response->qpstat;
		$qpstatmsg = addslashes($response->qpstatmsg);

		if ($qpstat == '000')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('ORDER_CANCEL');
		}
		else
		{
			$message = $qpstatmsg ? $qpstatmsg : JText::_('ORDER_NOT_CANCEL');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}


}
