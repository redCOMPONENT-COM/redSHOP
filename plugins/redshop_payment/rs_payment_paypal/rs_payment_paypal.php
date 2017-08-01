<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_paypal extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_rs_payment_paypal');
		parent::__construct($subject, $config);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paypal')
		{
			return;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_paypal($element, $request)
	{
		if ($element != 'rs_payment_paypal')
		{
			return;
		}

		$request        = JRequest::get('request');
		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$order_id       = $request["orderid"];
		$status         = $request['payment_status'];
		$tid            = $request['txn_id'];
		$pending_reason = $request['pending_reason'];
		$values         = new stdClass;
		$key = array($order_id, (int) $this->params->get("sandbox"), $this->params->get("merchant_email"));
		$key = md5(implode('|', $key));

		if (($status == 'Completed' || $pending_reason == 'authorization') && $request['key'] == $key)
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('PLG_RS_PAYMENT_PAYPAL_ORDER_PLACED');
			$values->msg = JText::_('PLG_RS_PAYMENT_PAYPAL_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('PLG_RS_PAYMENT_PAYPAL_NOT_PLACED');
			$values->msg = JText::_('PLG_RS_PAYMENT_PAYPAL_NOT_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}
}
