<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_moneybooker extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_moneybooker')
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

	public function onNotifyPaymentrs_payment_moneybooker($element, $request)
	{
		if ($element != 'rs_payment_moneybooker')
		{
			return;
		}

		JPlugin::loadLanguage('com_redshop');

		$db             = JFactory::getDbo();
		$request        = JRequest::get('request');
		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');

		if ($request['status'] == "2")
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

		$values->transaction_id = $request['mb_transaction_id'];
		$values->order_id       = $request['transaction_id'];
		$values->order_id_temp  = $request['orderid'];

		return $values;
	}

	public function onCapture_Paymentrs_payment_moneybooker($element, $data)
	{
		return;
	}
}
