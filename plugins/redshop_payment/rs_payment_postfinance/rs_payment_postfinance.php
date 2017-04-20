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

/**
 * PlgRedshop_PaymentRs_Payment_PostFinance class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */

class PlgRedshop_PaymentRs_Payment_PostFinance extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.7.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * [onPrePayment Plugin method with the same name as the event will be called automatically.]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [type]            [description]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_postfinance')
		{
			return;
		}

		require_once JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * [onNotifyPaymentrs_payment_postfinance description]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $request  [request params]
	 *
	 * @return  [object]  $values
	 */
	function onNotifyPaymentrs_payment_postfinance($element, $request)
	{
		if ($element != 'rs_payment_postfinance')
		{
			return;
		}

		$db                  = JFactory::getDbo();
		$request             = JFactory::getApplication()->input;

		$orderId            = $request->get('orderID', 0, 'INT');
		$responseHash       = $request->get('SHASIGN', '');
		$currency           = $request->get('currency');
		$amount             = $request->get('amount', 0);
		$pm                 = $request->get('PM');
		$acceptance         = $request->get('ACCEPTANCE');
		$status             = $request->get('STATUS');
		$ncError            = $request->get('NCERROR');
		$tid                = $request->get('PAYID');
		$cardNumber         = $request->get('CARDNO');
		$brand              = $request->get('BRAND');

		// Get params from plugin
		$shaOutPassPhrase   = $this->params->get("sha_out_pass_phrase");
		$verifyStatus       = $this->params->get("verify_status");
		$invalidStatus      = $this->params->get("invalid_status");

		$secretWords = $orderId
						. $currency
						. $amount
						. $pm
						. $acceptance
						. $status
						. $cardNumber
						. $tid
						. $ncError
						. $brand
						. $shaOutPassPhrase;
		$hashToCheck = strtoupper(sha1($secretWords));

		if (($STATUS == 5 || $STATUS == 9) && $ncError == 0)
		{
			if ($responseHash === $hashToCheck)
			{
				// UPDATE THE ORDER STATUS to 'VALID'
				$values->order_status_code         = $verifyStatus;
				$values->order_payment_status_code = 'Paid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->transaction_id            = $tid;
				$values->order_id                  = $orderId;
			}
			else
			{
				$values->order_status_code         = $invalidStatus;
				$values->order_payment_status_code = 'Unpaid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
				$values->order_id                  = $orderId;
			}
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg                               = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
			$values->order_id                  = $orderId;
		}

		return $values;
	}
}
