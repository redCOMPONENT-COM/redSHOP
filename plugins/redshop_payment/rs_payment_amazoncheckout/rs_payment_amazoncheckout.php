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
 *  PlgRedshop_PaymentRs_Payment_AmazonCheckout installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_AmazonCheckout extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 * 
	 * @param   string  $element  name of plugin
	 * @param   array   $data     data array
	 * 
	 * @return  void
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_amazoncheckout')
		{
			return;
		}

		include_once JPATH_SITE . '/plugins/redshop_payment/' . $element . '/libraries/extra_info.php';
	}

	/**
	 * onNotifyPaymentrs_payment_amazoncheckout
	 * 
	 * @param   string  $element  Plugin name
	 * @param   string  $request  Request string
	 * 
	 * @return  object  $values
	 */
	public function onNotifyPaymentrs_payment_amazoncheckout($element, $request)
	{
		if ($element != 'rs_payment_amazoncheckout')
		{
			return;
		}

		JPlugin::loadLanguage('com_redshop');

		$request          = JRequest::get('request');
		$verifyStatus     = $this->params->get('verify_status', '');
		$invalidStatus    = $this->params->get('invalid_status', '');
		$orderId          = $request['orderid'];

		$values = new stdClass;

		if ($request['status'] == 'PS' && $request['operation'] == 'pay')
		{
			$tranId = $request['transactionId'];

			if ($this->orderPaymentNotYetUpdated($orderId, $tranId))
			{
				$values->transaction_id = $tranId;
				$values->order_status_code = $verifyStatus;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
			else
			{
				$values->transaction_id = $tranId;
				$values->order_status_code = $invalidStatus;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->order_id = $orderId;

		return $values;
	}

	/**
	 * orderPaymentNotYetUpdated
	 * 
	 * @param   int  $orderId  Order ID
	 * @param   int  $tranId   Transaction ID
	 * 
	 * @return  bool
	 */
	public function orderPaymentNotYetUpdated($orderId, $tranId)
	{
		$db    	= JFactory::getDbo();
		$result = true;

		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('payment_order_id') . ') AS ' . $db->qn('qty'))
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . $db->q($orderId))
			->where($db->qn('order_payment_trans_id') . ' = ' . $db->q($tranId));
		$db->setQuery($query);

		$orderPayment = $db->loadResult();

		if ($orderPayment == 0)
		{
			$result = false;
		}

		return $result;
	}

	/**
	 * onCapture_Paymentrs_payment_amazoncheckout
	 * 
	 * @param   string  $element  plugin name
	 * @param   array   $data     data params
	 * 
	 * @return  void
	 */
	public function onCapture_Paymentrs_payment_amazoncheckout($element, $data)
	{
		// @TODO: Not sure why is that function simply return void. Need complete the codes.

		return;
	}
}
