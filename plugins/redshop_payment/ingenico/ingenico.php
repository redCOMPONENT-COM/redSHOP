<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
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

		$ncError             = $request['NCERROR'];
		$orderId             = $request['orderID'];
		$status              = $request['STATUS'];
		$responseHash       = $request['SHASIGN'];
		$tid                 = $request['PAYID'];

		// Get params from plugin
		$shaOutPassPhrase 	 = $this->params->get("sha_out_pass_phrase");
		$verifyStatus        = $this->params->get("verify_status");
		$invalidStatus       = $this->params->get("invalid_status");
		$secretWords         = "";

		$request = array_change_key_case($request, CASE_UPPER);
		ksort($request, SORT_STRING);

		foreach ($request as $key => $value)
		{
			if ($value != '' && $key != 'SHASIGN')
			{
				$secretWords .= $key . "=" . $value . $shaOutPassPhrase;
			}
		}

		$hashToCheck = strtoupper(sha1($secretWords));
		$values = new stdClass;

		if (($status == 5 || $status == 9) && $ncError == 0)
		{
			if ($responseHash === $hashToCheck)
			{
				$this->transactionStatus = true;
				$values->order_status_code = $verifyStatus;
				$values->order_payment_status_code = 'Paid';
				$values->transaction_id = $tid;
				$values->log = JText::_('PLG_REDSHOP_PAYMENT_INGENICO_ORDER_PLACED');
			}
			else
			{
				$this->transactionStatus = false;
				$values->order_status_code = $invalidStatus;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('PLG_REDSHOP_PAYMENT_INGENICO_ORDER_NOT_PLACED');
			}
		}
		else
		{
			$this->transactionStatus = false;
			$values->transaction_id = $tid;
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_REDSHOP_PAYMENT_INGENICO_ORDER_NOT_PLACED');
		}

		$values->msg = $values->log;
		$values->order_id = $orderId;

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
