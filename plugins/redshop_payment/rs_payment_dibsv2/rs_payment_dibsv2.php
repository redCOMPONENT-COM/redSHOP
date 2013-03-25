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
class plgRedshop_paymentrs_payment_dibsv2 extends JPlugin
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
	function plgRedshop_paymentrs_payment_dibsv2(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_dibsv2');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_dibsv2')
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

	function onNotifyPaymentrs_payment_dibsv2($element, $request)
	{
		if ($element != 'rs_payment_dibsv2')
		{
			return;
		}

		$api_path = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'dibs_hmac.php';
		include($api_path);
		$dibs_hmac = new dibs_hmac;

		JPlugin::loadLanguage('com_redshop');
		$db = jFactory::getDBO();

		$verify_status = $this->_params->get('verify_status', '');
		$invalid_status = $this->_params->get('invalid_status', '');

		$request = JRequest::get('request');
		$order_id = $request['orderid'];
		$Itemid = $request['Itemid'];
		// Put your HMAC key below.
		$HmacKey = $this->_params->get('hmac_key');
		$values = new stdClass;
		// Calculate the MAC for the form key-values posted from DIBS.
		if (sizeof($request) > 0)
		{
			$MAC = $dibs_hmac->calculateMac($request, $HmacKey);

			if ($request['MAC'] == $MAC && $request['status'] == "ACCEPTED")
			{
				$tid = $request['transaction'];

				if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
				{
					$transaction_id = $tid;
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
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDBO();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM `" . $this->_table_prefix . "order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->SetQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	function onCapture_Paymentrs_payment_dibsv2($element, $data)
	{
		if ($element != 'rs_payment_dibsv2')
		{
			return;
		}

		$db = JFactory::getDBO();
		JPlugin::loadLanguage('com_redshop');
		$order_id = $data['order_id'];
		$dibsurl = "https://payment.architrade.com/cgi-bin/capture.cgi?";
		$orderid = $data['order_id'];
		$hmac_key = $this->_params->get("hmac_key");

		$api_path = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'dibs_hmac.php';
		include($api_path);
		$dibs_hmac = new dibs_hmac;

		$formdata = array(
			'merchant' => $this->_params->get("seller_id"),
			'amount'   => $data['order_amount'],
			'transact' => $data["order_transactionid"],
			'orderid'  => $data['order_id']
		);

		$mac_key = $dibs_hmac->calculateMac($formdata, $hmac_key);
		$dibsurl .= "merchant=" . urlencode($this->_params->get("seller_id")) . "&amount=" . urlencode($data['order_amount']) . "&transact=" . $data["order_transactionid"] . "&orderid=" . $data['order_id'] . "&force=yes&textreply=yes&mac=" . $mac_key;
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
