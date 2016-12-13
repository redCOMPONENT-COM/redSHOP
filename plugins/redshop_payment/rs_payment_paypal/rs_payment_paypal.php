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
 * PlgRedshop_PaymentRs_Payment_Paypall class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_PaypalInstallerScript extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.7.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * [onPrePayment description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [void]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paypal')
		{
			return;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * [onNotifyPaymentrs_payment_paypal description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $request  [request data]
	 *
	 * @return  [object]  $values
	 */
	public function onNotifyPaymentrs_payment_paypal($element, $request)
	{
		if ($element != 'rs_payment_paypal')
		{
			return;
		}

		$request       = JFactory::getApplication()->input;
		$verifyStatus  = $this->params->get('verify_status', '');
		$invalidStatus = $this->params->get('invalid_status', '');
		$orderId       = $request["orderid"];
		$status        = $request['payment_status'];
		$tid           = $request['txn_id'];
		$pendingReason = $request['pending_reason'];
		$values         = new stdClass;
		$key = array($orderId, (int) $this->params->get("sandbox"), $this->params->get("merchant_email"));
		$key = md5(implode('|', $key));

		if (($status == 'Completed' || $pendingReason == 'authorization') && $request->get('key', '') == $key)
		{
			$values->order_status_code = $verifyStatus;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('PLG_RS_PAYMENT_PAYPAL_ORDER_PLACED');
			$values->msg = JText::_('PLG_RS_PAYMENT_PAYPAL_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_RS_PAYMENT_PAYPAL_NOT_PLACED');
			$values->msg = JText::_('PLG_RS_PAYMENT_PAYPAL_NOT_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $orderId;

		return $values;
	}
}
