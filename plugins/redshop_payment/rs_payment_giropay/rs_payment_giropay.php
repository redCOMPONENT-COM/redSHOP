<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgRedshop_PaymentRs_Payment_Giropay class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_Giropay extends JPlugin
{
	/**
	 * [onPrePayment Plugin method with the same name as the event will be called automatically.]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_giropay')
		{
			return;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/gsGiropay.php';

		$gsGiropay = new gsGiropay;

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * [Plugin onNotifyPayment method with the same name as the event will be called automatically.]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $request  [request array]
	 *
	 * @return  [obj]     $values
	 */
	public function onNotifyPaymentrs_payment_giropay($element, $request)
	{
		if ($element != 'rs_payment_giropay')
		{
			return false;
		}

		$db            = JFactory::getDbo();
		$request       = JFactory::getApplication()->input;
		$transactionId = $request->get('order_id');
		$gpCode        = $request->get('gpCode');
		$gpHash        = $request->get('gpHash');

		JPlugin::loadLanguage('com_redshop');

		$merchantId     = $this->params->get('merchant_id', '');
		$projectId      = $this->params->get('project_id', '');
		$verifyStatus   = $this->params->get('verify_status', '');
		$invalidStatus  = $this->params->get('invalid_status', '');
		$authType       = $this->params->get('auth_type', '');
		$secretPassword = $this->params->get("secret_password");
		$debugMode      = $this->params->get("debug_mode");

		$values = new stdClass;

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/gsGiropay.php';

		$gsGiropay = new gsGiropay;
		$hash      = $gsGiropay->generateHash($merchantId . $projectId . $transactionId . $gpCode, $secretPassword);
		$message   = $gsGiropay->getCodeDescription($gpCode);

		if ($gpHash != $hash)
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $message;
			$values->msg = $message;
		}

		// Neuen Bestellstatus ermitteln
		if ($gsGiropay->codeIsOK($gpCode))
		{
			$values->order_status_code = $verifyStatus;
			$values->order_payment_status_code = 'Paid';
			$values->log = $message;
			$values->msg = $message;
		}
		else
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $message;
			$values->msg = $message;
		}

		$values->transaction_id = $transactionId;
		$values->order_id       = $request->get('order_id', 0, 'int');

		return $values;
	}
}
