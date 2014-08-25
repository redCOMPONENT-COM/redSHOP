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

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

class plgRedshop_paymentrs_payment_dibspaymentmethod extends JPlugin
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
	public function plgRedshop_paymentrs_payment_dibspaymentmethod(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_dibspaymentmethod');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_dibspaymentmethod')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
		include $paymentpath;
	}

	public function onNotifyPaymentrs_payment_dibspaymentmethod($element, $request)
	{
		$db = JFactory::getDbo();

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

		$db = JFactory::getDbo();
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
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_dibspaymentmethod($element, $data)
	{
		if ($element != 'rs_payment_dibspaymentmethod')
		{
			return;
		}

		JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
		JLoader::load('RedshopHelperAdminOrder');
		$objOrder = new order_functions;
		$db = JFactory::getDbo();
		$order_id = $data['order_id'];
		JPlugin::loadLanguage('com_redshop');
		$dibsurl = "https://payment.architrade.com/cgi-bin/capture.cgi?";
		$orderid = $data['order_id'];
		$key2 = $this->_params->get("dibs_md5key2");
		$key1 = $this->_params->get("dibs_md5key1");
		$merchantid = $this->_params->get("seller_id");

		$currencyClass = new CurrencyHelper;
		$formdata['amount'] = $currencyClass->convert($data['order_amount'], '', $this->_params->get("dibs_currency"));
		$formdata['amount'] = number_format($formdata['amount'], 2, '.', '') * 100;

		$md5key = md5(
			$key2 . md5(
				$key1
					. 'merchant=' . $merchantid
					. '&orderid=' . $order_id
					. '&transact=' . $data["order_transactionid"]
					. '&amount=' . $formdata['amount']
			)
		);

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
