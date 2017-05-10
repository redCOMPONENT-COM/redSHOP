<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Currency\Currency;

/**
 * Ingenico payment gateway
 *
 * @package     Redshop.Plugins
 * @subpackage  Ingenico
 * @since       1.6.1
 */
class PlgRedshop_PaymentIngenico extends RedshopPayment
{
	/**
	 * Set transaction status
	 *
	 * @var  boolean
	 */
	protected $transactionStatus = false;

	/**
	 * Method to setup the payment form and send to gateway
	 *
	 * @param   string  $element    Plugin Name
	 * @param   array   $orderInfo  Order Information
	 *
	 * @return  void
	 */
	public function onPrePayment($element, $orderInfo)
	{
		if ($element != 'ingenico')
		{
			return;
		}

		echo $this->renderPaymentForm($orderInfo);
	}

	/**
	 * Prepare Payment Input
	 *
	 * @param   array  $orderInfo  Order Information
	 *
	 * @return  array  Payment Gateway for parameters
	 */
	protected function preparePaymentInput($orderInfo)
	{
		$currency      = $this->params->get("currency");
		$orderSubtotal = Currency::getInstance()->convert($orderInfo['carttotal'], '', $currency);
		$orderSubtotal = round($orderSubtotal, 2) * 100;

		$params = array(
			"PSPID"        => $this->params->get('ingenico_pspid'),
			"ORDERID"      => $orderInfo['order_id'],
			"AMOUNT"       => $orderSubtotal,
			"CURRENCY"     => $currency,
			"LANGUAGE"     => $this->params->get('language', $this->getLang()),
			"ACCEPTURL"    => $this->getNotifyUrl($orderInfo['order_id']),
			"DECLINEURL"   => $this->getNotifyUrl($orderInfo['order_id']),
			"CANCELURL"    => $this->getNotifyUrl($orderInfo['order_id']),
			"OPERATION"    => $this->params->get("opreation_mode")
		);

		if ($ingenicoUserid = $this->params->get('ingenico_userid'))
		{
			$params['USERID'] = $ingenicoUserid;
		}

		if ($ownerCity = $orderInfo['billinginfo']->city)
		{
			$params['OWNERCTY'] = $ownerCity;
		}

		if ($ownerAddress = $orderInfo['billinginfo']->address)
		{
			$params['OWNERADDRESS'] = $ownerAddress;
		}

		if ($buyerEmail = $orderInfo['billinginfo']->user_email)
		{
			$params['EMAIL'] = $buyerEmail;
		}

		if ($ownerZIP = $orderInfo['billinginfo']->zipcode)
		{
			$params['OWNERZIP'] = $ownerZIP;
		}

		if ($buyerFirstName = $orderInfo['billinginfo']->firstname)
		{
			$params['CN'] = $buyerFirstName;
		}

		ksort($params);

		$str = '';

		foreach ($params as $key => $variable)
		{
			$str .= $key . '=' . $variable . $this->params->get('sha_in_pass_phrase');
		}

		$params['SHASIGN'] = sha1($str);

		return $params;
	}

	/**
	 * Handle payment status notification
	 *
	 * @param   string  $element  Payment Name
	 * @param   array   $request  Reqest Array
	 *
	 * @return  object  Order Status information object
	 */
	public function onNotifyPaymentIngenico($element, $request)
	{
		if ($element != 'ingenico')
		{
			return;
		}

		$request = JRequest::get('request');
		unset(
			$request['option'],
			$request['view'],
			$request['controller'],
			$request['task'],
			$request['payment_plugin'],
			$request['Itemid'],
			$request['tmpl']
		);

		$NCERROR             = $request['NCERROR'];
		$order_id            = $request['orderID'];
		$STATUS              = $request['STATUS'];
		$response_hash       = $request['SHASIGN'];
		$tid                 = $request['PAYID'];

		// Get params from plugin
		$sha_out_pass_phrase = $this->params->get("sha_out_pass_phrase");
		$verify_status       = $this->params->get("verify_status");
		$invalid_status      = $this->params->get("invalid_status");
		$secret_words        = "";

		$request = array_change_key_case($request, CASE_UPPER);
		ksort($request, SORT_STRING);

		foreach ($request as $key => $value)
		{
			if ($value != '' && $key != 'SHASIGN')
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
				$this->transactionStatus = true;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->transaction_id = $tid;
				$values->log = JText::_('PLG_REDSHOP_PAYMENT_INGENICO_ORDER_PLACED');
			}
			else
			{
				$this->transactionStatus = false;
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('PLG_REDSHOP_PAYMENT_INGENICO_ORDER_NOT_PLACED');
			}
		}
		else
		{
			$this->transactionStatus = false;
			$values->transaction_id = $tid;
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_REDSHOP_PAYMENT_INGENICO_ORDER_NOT_PLACED');
		}

		$values->msg = $values->log;
		$values->order_id = $order_id;

		return $values;
	}

	/**
	 * Set HTTP Status message based on transaction status
	 *
	 * @param   string   $element  Payment Gateway name
	 * @param   integer  $orderId  Order Information ID
	 *
	 * @return  void
	 */
	public function onAfterNotifyPaymentIngenico($element, $orderId)
	{
		if ($element != 'ingenico')
		{
			return;
		}

		if ($this->transactionStatus)
		{
			header("HTTP/1.1 200 Ok");
		}
		else
		{
			header("HTTP/1.1 401 Unauthorized");
		}
	}
}
