<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_giropay extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_giropay')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/gsGiropay.php';

		$gsGiropay = new gsGiropay;

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $plugin . '/extra_info.php';
	}

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_giropay($element, $request)
	{
		if ($element != 'rs_payment_giropay')
		{
			return false;
		}

		$db            = JFactory::getDbo();
		$request       = JRequest::get('request');
		$transactionId = $request['order_id'];
		$gpCode        = $request['gpCode'];
		$gpHash        = $request['gpHash'];

		JPlugin::loadLanguage('com_redshop');

		$merchantId      = $this->params->get('merchant_id', '');
		$projectId       = $this->params->get('project_id', '');
		$verify_status   = $this->params->get('verify_status', '');
		$invalid_status  = $this->params->get('invalid_status', '');
		$auth_type       = $this->params->get('auth_type', '');
		$secret_password = $this->params->get("secret_password");
		$debug_mode      = $this->params->get("debug_mode");

		$values = new stdClass;

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/gsGiropay.php';

		$gsGiropay = new gsGiropay;
		$hash      = $gsGiropay->generateHash($merchantId . $projectId . $transactionId . $gpCode, $secret_password);
		$message   = $gsGiropay->getCodeDescription($gpCode);

		if ($gpHash != $hash)
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $message;
			$values->msg = $message;
		}

		// Neuen Bestellstatus ermitteln
		if ($gsGiropay->codeIsOK($gpCode))
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = $message;
			$values->msg = $message;
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $message;
			$values->msg = $message;
		}

		$values->transaction_id = $transactionId;
		$values->order_id       = $request['order_id'];

		return $values;
	}
}
