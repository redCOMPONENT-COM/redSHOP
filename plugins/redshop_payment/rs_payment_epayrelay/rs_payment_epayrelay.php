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
class plgRedshop_paymentrs_payment_epayrelay extends JPlugin
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
	function plgRedshop_paymentrs_payment_epayrelay(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_epayrelay');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_epayrelay')
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
	function onNotifyPaymentrs_payment_epayrelay($element, $request)
	{

		if ($element != 'rs_payment_epayrelay')
		{
			break;
		}

		$db = jFactory::getDBO();
		$tid = $request["tid"];

		$order_id = $request["orderid"];

		$order_amount = $request["amount"];

		@$order_ekey = $request["eKey"];
		$order_currency = $request["cur"];

		JPlugin::loadLanguage('com_redshop');
		$amazon_parameters = $this->getparameters('rs_payment_epayrelay');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// get the class
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'epaysoap.php';
		include($paymentpath);

		//Access the webservice
		$epay = new EpaySoap;
		$merchantnumber = $paymentparams->get('merchant_id');
		$verify_status = $paymentparams->get('verify_status', '');
		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$auth_type = $paymentparams->get('auth_type', '');
		$debug_mode = $paymentparams->get('debug_mode', 0);
		$values = new stdClass;
		$epay_paymentkey = $paymentparams->get('epay_paymentkey', '');
		$epay_md5 = $paymentparams->get('epay_md5', '');

		$transaction = $epay->gettransaction($merchantnumber, $tid);

		//
		// Now validat on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled
		//
		if ((@$order_ekey == md5($order_amount . $order_id . $tid . $epay_paymentkey)) || $epay_md5 == 0)
		{

			$db = JFactory::getDBO();
			$qv = "SELECT order_id, order_number FROM " . $this->_table_prefix . "orders WHERE order_id='" . $order_id . "'";
			$db->SetQuery($qv);
			$orders = $db->LoadObjectList();

			foreach ($orders as $order_detail)
			{
				$d['order_id'] = $order_detail->order_id;
			}
			//
			// Switch on the order accept code
			// accept = 1 (standard redirect) accept = 2 (callback)

			if ($transaction['gettransactionResult'] == 'true')
			{

				if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
				{

					if ($debug_mode == 1)
					{
						$payment_messsge = $transaction['transactionInformation']['history']['TransactionHistoryInfo']['eventMsg'];
					}
					else
					{
						$payment_messsge = JText::_('COM_REDSHOP_ORDER_PLACED');
					}
					// UPDATE THE ORDER STATUS to 'VALID'
					$transaction_id = $tid;
					$values->order_status_code = $verify_status;
					$values->order_payment_status_code = 'Paid';
					$values->log = $payment_messsge;
					$values->msg = $payment_messsge;

				}

			}
			else
			{

				if ($debug_mode == 1)
				{
					$payment_messsge = $epay->getEpayError($merchantnumber, $transaction['epayresponse']);
				}
				else
				{
					$payment_messsge = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				}

				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = $payment_messsge;
				$values->msg = $payment_messsge;
				$msg = JText::_('COM_REDSHOP_EPAY_PAYMENT_ERROR');

			}

		}
		//

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function onCapture_Paymentrs_payment_epayrelay($element, $data)
	{

		$amazon_parameters = $this->getparameters('rs_payment_epayrelay');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// get the class
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'epaysoap.php';
		include($paymentpath);

		//Access the webservice
		$epay = new EpaySoap;
		$merchantnumber = $paymentparams->get('merchant_id');

		$order_id = $data['order_id'];
		$tid = $data['order_transactionid'];

		$order_amount = round($data['order_amount'] * 100, 2);

		$response = $epay->capture($merchantnumber, $tid, $order_amount);

		if ($response['captureResult'] == 'true')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('ORDER_CAPTURED');
		}
		else
		{
			$message = JText::_('ORDER_NOT_CAPTURED');
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;

		return;
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

}