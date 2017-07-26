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

class plgRedshop_paymentrs_payment_postfinance extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_postfinance')
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

	function onNotifyPaymentrs_payment_postfinance($element, $request)
	{
		if ($element != 'rs_payment_postfinance')
		{
			return;
		}

		$db                  = JFactory::getDbo();
		$request             = JRequest::get('request');

		$order_id            = $request['orderID'];
		$response_hash       = $request['SHASIGN'];
		$currency            = $request['currency'];
		$amount              = $request['amount'];
		$PM                  = $request['PM'];
		$ACCEPTANCE          = $request['ACCEPTANCE'];
		$STATUS              = $request['STATUS'];
		$NCERROR             = $request['NCERROR'];
		$tid                 = $request['PAYID'];

		// Get params from plugin
		$sha_out_pass_phrase = $this->params->get("sha_out_pass_phrase");
		$algo_used           = $this->params->get("algo_used");
		$hash_string         = $this->params->get("hash_string");
		$verify_status       = $this->params->get("verify_status");
		$invalid_status      = $this->params->get("invalid_status");

		$secret_words = $order_id
						. $request['currency']
						. $request['amount']
						. $request['PM']
						. $request['ACCEPTANCE']
						. $request['STATUS']
						. $request['CARDNO']
						. $request['PAYID']
						. $request['NCERROR']
						. $request['BRAND']
						. $sha_out_pass_phrase;
		$hash_to_check = strtoupper(sha1($secret_words));

		if (($STATUS == 5 || $STATUS == 9) && $NCERROR == 0)
		{
			if ($response_hash === $hash_to_check)
			{
				// UPDATE THE ORDER STATUS to 'VALID'
				$transaction_id                    = $tid;
				$values->order_status_code         = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->transaction_id            = $transaction_id;
				$values->order_id                  = $order_id;
			}
			else
			{
				$values->order_status_code         = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
				$values->order_id                  = $order_id;
			}
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
			$values->order_id                  = $order_id;
		}

		return $values;
	}
}
