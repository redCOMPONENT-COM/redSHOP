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
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php';

class plgRedshop_paymentrs_payment_dibspaymentmethod extends JPlugin
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
	function plgRedshop_paymentrs_payment_dibspaymentmethod(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_dibspaymentmethod');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{

		if ($element != 'rs_payment_dibspaymentmethod')
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

	function onNotifyPaymentrs_payment_dibspaymentmethod($element, $request)
	{
		$db = JFactory::getDBO();

		if ($element != 'rs_payment_dibspaymentmethod')
		{
			return;
		}

		$key2 = $this->_params->get("dibs_md5key2");
		$key1 = $this->_params->get("dibs_md5key1");
		$seller_id = $this->_params->get("seller_id");
		$order_id = $request['orderid'];
		$transact = $request['transact'];
		$amount = $request['amount'];
		$currency = $this->_params->get("dibs_currency");
		$verify_status = $this->_params->get('verify_status', '');
		$invalid_status = $this->_params->get('invalid_status', '');

		$db = jFactory::getDBO();
		$request = JRequest::get('request');
		JPlugin::loadLanguage('com_redshop');
		$order_id = $request['orderid'];
		$status = $request['status'];

		$values = new stdClass;

		if (isset($request['transact']))
		{

			$tid = $request['transact'];

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{

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

		$values->transaction_id = $request['transact'];
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
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->SetQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	function onCapture_Paymentrs_payment_dibspaymentmethod($element, $data)
	{

		if ($element != 'rs_payment_dibspaymentmethod')
		{
			return;
		}
		require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
		$objOrder = new order_functions;
		$db = JFactory::getDBO();
		$order_id = $data['order_id'];
		JPlugin::loadLanguage('com_redshop');
		$dibsurl = "https://payment.architrade.com/cgi-bin/capture.cgi?";
		$orderid = $data['order_id'];
		$key2 = $this->_params->get("dibs_md5key2");
		$key1 = $this->_params->get("dibs_md5key1");
		$merchantid = $this->_params->get("seller_id");

		$currencyClass = new convertPrice;
		$formdata['amount'] = $currencyClass->convert($data['order_amount'], '', $this->_params->get("dibs_currency"));
		$formdata['amount'] = number_format($formdata['amount'], 2, '.', '') * 100;

		$md5key = md5($key2 . md5($key1 . 'merchant=' . $merchantid . '&orderid=' . $order_id . '&transact=' . $data["order_transactionid"] . '&amount=' . $formdata['amount']));
		//$md5key=md5($key2.md5($key1.'merchant='.$merchantid.'&orderid='.$data['order_id'].'&currency='.$dibs_currency.'&amount='.$data['order_amount']));

		$dibsurl .= "merchant=" . urlencode($this->_params->get("seller_id")) . "&amount=" . urlencode($formdata['amount']) . "&transact=" . $data["order_transactionid"] . "&orderid=" . $order_id . "&force=yes&textreply=yes&md5key=" . $md5key;

		$data = $dibsurl;
		$ch = curl_init($data);
		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		$data = explode('&', $data);
		$capture_status = explode('=', $data[0]);

		if ($capture_status[1] == 'ACCEPTED')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = JText::_('COM_REDSHOP_TRANSACTION_DECLINE');
		}

		$values->message = $message;

		return $values;

	}

}