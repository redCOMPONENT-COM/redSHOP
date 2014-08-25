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

JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_paymentrs_payment_ogone extends JPlugin
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
	public function plgRedshop_paymentrs_payment_ogone(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_ogone');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_ogone')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$paymentpath = JPATH_SITE . "/plugins/redshop_payment/$plugin/$plugin/extra_info.php";
		include $paymentpath;
	}

	function onNotifyPaymentrs_payment_ogone($element, $request)
	{
		if ($element != 'rs_payment_ogone')
		{
			return;
		}

		$db = JFactory::getDbo();
		$request = JRequest::get('request');

		$ACCEPTANCE = $request['ACCEPTANCE'];
		$amount = $request['amount'];
		$CARDNO = $request['CARDNO'];
		$CN = $request['CN'];
		$currency = $request['currency'];
		$ED = $request['ED'];
		$IP = $request['IP'];
		$NCERROR = $request['NCERROR'];
		$order_id = $request['orderID'];
		$PAYID = $request['PAYID'];
		$PM = $request['PM'];
		$STATUS = $request['STATUS'];
		$TRXDATE = $request['TRXDATE'];
		$response_hash = $request['SHASIGN'];

		$tid = $request['PAYID'];

		// Get params from plugin
		$sha_out_pass_phrase = $this->_params->get("sha_out_pass_phrase");
		$algo_used = $this->_params->get("algo_used");
		$hash_string = $this->_params->get("hash_string");
		$verify_status = $this->_params->get("verify_status");
		$invalid_status = $this->_params->get("invalid_status");
		$secret_words = "";

		$request = array_change_key_case($request, CASE_UPPER);
		ksort($request, SORT_STRING);

		foreach ($request as $key => $value)
		{
			if ($key == "ACCEPTANCE"
				|| $key == "AMOUNT"
				|| $key == "CARDNO"
				|| $key == "CN"
				|| $key == "BRAND"
				|| $key == "IP"
				|| $key == "ED"
				|| $key == "NCERROR"
				|| $key == "PM"
				|| $key == "PAYID"
				|| $key == "STATUS"
				|| $key == "TRXDATE"
				|| $key == "CURRENCY"
				|| $key == "ORDERID")
			{
				$secret_words .= $key . "=" . $value . $sha_out_pass_phrase;
			}
		}

		$hash_to_check = strtoupper(sha1($secret_words));

		if (($STATUS == 5 || $STATUS == 9) && $NCERROR == 0)
		{
			if ($response_hash === $hash_to_check)
			{
				// UPDATE THE ORDER STATUS to 'VALID'

				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JTEXT::_('ORDER_PLACED');
				$values->msg = JTEXT::_('ORDER_PLACED');

				$values->order_id = $order_id;
			}
			else
			{
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JTEXT::_('ORDER_NOT_PLACED');
				$values->msg = JTEXT::_('ORDER_NOT_PLACED');
				$msg = JText::_('PHPSHOP_PAYMENT_ERROR');
				$values->order_id = $order_id;
			}
		}
		else
		{
			$values->transaction_id = $tid;
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JTEXT::_('ORDER_NOT_PLACED');
			$values->msg = JTEXT::_('ORDER_NOT_PLACED');
			$msg = JText::_('PHPSHOP_PAYMENT_ERROR');
			$values->order_id = $order_id;
		}

		return $values;
	}
}
