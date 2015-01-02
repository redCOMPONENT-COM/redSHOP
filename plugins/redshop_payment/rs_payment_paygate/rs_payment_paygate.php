<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_paygate extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paygate')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_paygate($element, $request)
	{
		if ($element != 'rs_payment_paygate')
		{
			return;
		}

		$db             = JFactory::getDbo();
		$request        = JRequest::get('request');
		$order_id       = $request['orderid'];
		$Itemid         = $request['Itemid'];

		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');

		$order_id       = $request['REFERENCE'];
		$user           = JFactory::getUser();

		$status         = $request['TRANSACTION_STATUS'];
		$tid            = $request['TRANSACTION_ID'];
		$result_code    = $request['RESULT_CODE'];
		$auth_code      = $request['AUTH_CODE'];
		$uri            = JURI::getInstance();
		$url            = JURI::base();
		$uid            = $user->id;
		$db             = JFactory::getDbo();

		if ($status == 1 && $result_code == 990017)
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $txn_id;
		$values->order_id = $order_id;

		return $values;
	}

	public function onCapture_Paymentrs_payment_paygate($element, $data)
	{
		return;
	}
}
