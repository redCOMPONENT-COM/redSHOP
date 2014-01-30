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
//$app = JFactory::getApplication();
//$app->registerEvent( 'onPrePayment', 'plgRedshoprs_payment_bbs' );
class plgRedshop_paymentrs_payment_realex_redirect extends JPlugin
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
	public function plgRedshop_paymentrs_payment_realex_redirect(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_realex_redirect');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_realex_redirect($element, $data)
	{
		if ($element != 'rs_payment_realex_redirect')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$session =& JFactory::getSession();
		$ccdata = $session->get('ccdata');

		$merchantid = $this->_params->get("realex_merchant_id");
		$secret = $this->_params->get("realex_shared_secret");
		$account = $this->_params->get("realex_account_name");
		$ip_address = $_SERVER['REMOTE_ADDR'];
		require_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_realex_redirect/rs_payment_realex_redirect/class.realmpi.php';
		$timestamp = strftime("%Y%m%d%H%M%S");
		mt_srand((double) microtime() * 1000000);
		$orderid = $data['order_number'];
		$curr = CURRENCY_CODE;
		$amount = $data['carttotal'];
		$tmp = "$timestamp.$merchantid.$orderid.$amount.$curr";
		$md5hash = md5($tmp);
		$tmp = "$md5hash.$secret";
		$md5hash = md5($tmp);
		$cardnumber = $ccdata['order_payment_number'];
//		$cardtype	 = $ccdata['credit_card_code'];
		$cardtype = urlencode($ccdata['creditcard_code']);
		$exp_month = $ccdata['order_payment_expire_month'];
		$exp_year = $ccdata['order_payment_expire_year'];
		$exp_year = substr($exp_year, 2, 2);
		$expdate = $exp_month . $exp_year; /* $expdate="0211"; */
		$amount = round($data['carttotal']);

		$realex = new Realex;
		$total = $data['order_total'] * 100;
		$response = $realex->createRequest(array(
			"merchantid"     => $merchantid,
			"secret"         => $secret,
			"account"        => $account,
			"orderid"        => $data['order_number'],
			"amount"         => $total,
			"currency"       => CURRENCY_CODE,
			"cardnumber"     => $cardnumber,
			"cardname"       => "Owen O Byrne",
			"cardtype"       => $cardtype,
			"expdate"        => $expdate,
			"autosettleflag" => "1",
		));
	}

	function onNotifyPaymentrs_payment_realex_redirect($element, $request)
	{
		if ($element != 'rs_payment_realex_redirect')
		{
			return;
		}

		$db = JFactory::getDbo();
		$request = JRequest::get('request');
		JPlugin::loadLanguage('com_redshop');
		$amazon_parameters = $this->getparameters('rs_payment_realex_redirect');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$auth_type = $paymentparams->get('auth_type', '');

		$order_id = $request['orderid'];
		$status = $request['status'];

		$values = new stdClass;

		if ($request['status'] == 'PS' && $request['operation'] == 'pay')
		{
			$tid = $request['transactionId'];

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				$transaction_id = $tid;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'PAID';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'UNPAID';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	function onCapture_Paymentrs_payment_realex_redirect($element, $data)
	{
		return;
	}

}