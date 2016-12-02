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
 * PlgRedshop_PaymentRs_Payment_DibsPaymentMethod installer class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentRs_Payment_DibsPaymentMethod extends JPlugin
{
	/**
	 * [onPrePayment Plugin method with the same name as the event will be called automatically.]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [voice]
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_dibspaymentmethod')
		{
			return;
		}

		$app = JFactory::getApplication();

		require_once JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * [onNotifyPaymentrs_payment_dibspaymentmethod]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $request  [data array]
	 *
	 * @return  [object]  $array_values(input)
	 */
	public function onNotifyPaymentrs_payment_dibspaymentmethod($element, $request)
	{
		$db = JFactory::getDbo();

		if ($element != 'rs_payment_dibspaymentmethod')
		{
			return;
		}

		$key2           = $this->params->get("dibs_md5key2");
		$key1           = $this->params->get("dibs_md5key1");
		$orderId        = $request['orderid'];
		$status         = $request['status'];
		$currency       = $this->params->get("dibs_currency");
		$verifyStatus   = $this->params->get('verify_status', '');
		$invalidStatus  = $this->params->get('invalid_status', '');

		$db = JFactory::getDbo();

		JPlugin::loadLanguage('com_redshop');

		$values = new stdClass;

		if (isset($request['transact']))
		{
			$tranId = $request['transact'];

			if ($this->orderPaymentNotYetUpdated($db, $orderId, $tranId))
			{
				$values->order_status_code = $verifyStatus;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalidStatus;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $request['transact'];
		$values->order_id       = $orderId;

		return $values;
	}

	/**
	 * [orderPaymentNotYetUpdated description]
	 *
	 * @param   [obj]  $db       [db connection object]
	 * @param   [int]  $orderId  [ID of order]
	 * @param   [int]  $tranId   [ID of transaction]
	 *
	 * @return  [bool]
	 */
	public function orderPaymentNotYetUpdated($db, $orderId, $tranId)
	{
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}

		$result = false;
		$query  = $db->getQuery(true);

		$query->select('COUNT(' . $db->qn('payment_order_id') . ')')
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . $db->getEscaped($orderId))
			->where($db->qn('order_payment_trans_id') . ' = ' . $db->getEscaped($tranId));

		$db->setQuery($query);
		$orderPayment = $db->loadResult();

		if ($orderPayment == 0)
		{
			$result = true;
		}

		return $result;
	}

	/**
	 * [onCapture_Paymentrs_payment_dibspaymentmethod]
	 *
	 * @param   [string]  $element  [plugin name]
	 * @param   [array]   $data     [data params]
	 *
	 * @return  [object]  $values
	 */
	public function onCapture_Paymentrs_payment_dibspaymentmethod($element, $data)
	{
		if ($element != 'rs_payment_dibspaymentmethod')
		{
			return;
		}

		$objOrder   = order_functions::getInstance();
		$db         = JFactory::getDbo();
		$orderId    = $data['order_id'];

		JPlugin::loadLanguage('com_redshop');

		$dibsUrl    = "https://payment.architrade.com/cgi-bin/capture.cgi?";
		$orderId    = $data['order_id'];
		$key2       = $this->params->get("dibs_md5key2");
		$key1       = $this->params->get("dibs_md5key1");
		$merchantId = $this->params->get("seller_id");

		$currencyClass      = CurrencyHelper::getInstance();
		$formData['amount'] = $currencyClass->convert($data['order_amount'], '', $this->params->get("dibs_currency"));
		$formData['amount'] = number_format($formData['amount'], 2, '.', '') * 100;

		$md5key = md5(
			$key2 . md5(
				$key1
					. 'merchant=' . $merchantId
					. '&orderid=' . $orderId
					. '&transact=' . $data["order_transactionid"]
					. '&amount=' . $formData['amount']
			)
		);

		$dibsUrl .= "merchant=" . urlencode($this->params->get("seller_id")) . "&amount=" . urlencode($formData['amount']) . "&transact=" . $data["order_transactionid"] . "&orderid=" . $orderId . "&force=yes&textreply=yes&md5key=" . $md5key;

		$data = $dibsUrl;
		$ch   = curl_init($data);

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data          = curl_exec($ch);
		$data          = explode('&', $data);
		$captureStatus = explode('=', $data[0]);

		if ($captureStatus[1] == 'ACCEPTED')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = JText::_('COM_REDSHOP_TRANSACTION_DECLINE');
		}

		$values->message = $message;

		return $values;
	}
}
