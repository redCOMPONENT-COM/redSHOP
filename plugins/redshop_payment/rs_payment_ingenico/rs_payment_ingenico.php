<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');

class PlgRedshop_Paymentrs_Payment_Ingenico extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_payment_rs_payment_ingenico', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_ingenico')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		include JPATH_SITE . "/plugins/redshop_payment/$plugin/$plugin/extra_info.php";
	}

	function onNotifyPaymentrs_payment_ingenico($element, $request)
	{
		if ($element != 'rs_payment_ingenico')
		{
			return;
		}

		$request             = JRequest::get('request');

		$ACCEPTANCE          = $request['ACCEPTANCE'];
		$amount              = $request['amount'];
		$CARDNO              = $request['CARDNO'];
		$CN                  = $request['CN'];
		$currency            = $request['currency'];
		$ED                  = $request['ED'];
		$IP                  = $request['IP'];
		$NCERROR             = $request['NCERROR'];
		$order_id            = $request['orderID'];
		$PAYID               = $request['PAYID'];
		$PM                  = $request['PM'];
		$STATUS              = $request['STATUS'];
		$TRXDATE             = $request['TRXDATE'];
		$response_hash       = $request['SHASIGN'];
		$tid                 = $request['PAYID'];

		// Get params from plugin
		$sha_out_pass_phrase = $this->params->get("sha_out_pass_phrase");
		$algo_used           = $this->params->get("algo_used");
		$hash_string         = $this->params->get("hash_string");
		$verify_status       = $this->params->get("verify_status");
		$invalid_status      = $this->params->get("invalid_status");
		$secret_words        = "";

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
		$values = new stdClass;

		if (($STATUS == 5 || $STATUS == 9) && $NCERROR == 0)
		{
			if ($response_hash === $hash_to_check)
			{
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('PLG_RS_PAYMENT_INGENICO_ORDER_PLACED');
			}
			else
			{
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('PLG_RS_PAYMENT_INGENICO_ORDER_NOT_PLACED');
			}
		}
		else
		{
			$values->transaction_id = $tid;
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_RS_PAYMENT_INGENICO_ORDER_NOT_PLACED');
		}

		$values->msg = $values->log;
		$values->order_id = $order_id;

		return $values;
	}
}
