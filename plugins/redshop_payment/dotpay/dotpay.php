<?php
/**
 * @package     RedSHOP.Plugins
 * @subpackage  DotPay
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * DotPay payment gateway
 *
 * @package     Redshop.Plugins
 * @subpackage  DotPay
 * @since       1.5
 */
class PlgRedshop_PaymentDotpay extends RedshopPayment
{
	/**
	 * Method to setup the payment form and send to gateway
	 *
	 * @param   string  $element    Plugin Name
	 * @param   array   $orderInfo  Order Information
	 *
	 * @return  void
	 */
	public function onPrePayment($element, $orderInfo)
	{
		if ($element != 'dotpay')
		{
			return;
		}

		echo $this->renderPaymentForm($orderInfo);
	}

	/**
	 * Prepare Payment Input
	 *
	 * @param   array  $orderInfo  Order Information
	 *
	 * @return  array  Payment Gateway for parameters
	 */
	protected function preparePaymentInput($orderInfo)
	{
		$inputs = array(
				'id'          => $this->params->get("customerId"),
				'amount'      => $orderInfo['carttotal'],
				'currency'    => Redshop::getConfig()->get('CURRENCY_CODE'),
				'description' => 'Payment for order ' . $orderInfo['order_id'],
				'lang'        => $this->getLang(),
				'type'        => 0,
				'firstname'   => $orderInfo['billinginfo']->firstname,
				'lastname'    => $orderInfo['billinginfo']->lastname,
				'email'       => $orderInfo['billinginfo']->user_email,
				'control'     => $orderInfo['order_id'],
				'url'         => $this->getReturnUrl($orderInfo['order_id']),
				'urlc'        => $this->getNotifyUrl($orderInfo['order_id']),
				'city'        => $orderInfo['billinginfo']->city,
				'postcode'    => $orderInfo['billinginfo']->zipcode,
				'phone'       => $orderInfo['billinginfo']->phone,
				'country'     => $orderInfo['billinginfo']->country_code,
				'street'      => $orderInfo['billinginfo']->address,
				'street_n1'   => '',
				'api_version' => 'dev'
			);

		return $inputs;
	}

	/**
	 * Handle payment status notification
	 *
	 * @param   string  $element  Payment Name
	 * @param   array   $request  Reqest Array
	 *
	 * @return  object  Order Status information object
	 */
	public function onNotifyPaymentDotpay($element, $request)
	{
		if ($element != 'dotpay')
		{
			return false;
		}

		$input = JFactory::getApplication()->input;

		$accountId = $input->post->get('id');

		// Invalid request
		if ($accountId !== $this->params->get("customerId"))
		{
			JLog::add('Invalid Request', JLog::ERROR, 'jerror');

			return false;
		}

		// Invalid Signature
		if (!$this->isSignatureValidated($input->post))
		{
			JLog::add('Invalid Signature', JLog::ERROR, 'jerror');

			return $this->setStatus(
				$orderId,
				$transactionId,
				$this->params->get('invalid_status', ''),
				'Unpaid',
				JText::_('PLG_REDSHOP_PAYMENT_DOTPAY_PAYMENT_INVALID'),
				JText::_('PLG_REDSHOP_PAYMENT_DOTPAY_PAYMENT_INVALID_LOG')
			);
		}

		$transactionId = $input->post->getString('operation_number');
		$orderId       = $input->post->getInt('control');

		// Set order status based DotPay post notification.
		switch($input->post->get('operation_status'))
		{
			case 'completed':
				return $this->setStatus(
					$orderId,
					$transactionId,
					$this->params->get('verify_status', ''),
					'Paid',
					JText::_('PLG_REDSHOP_PAYMENT_DOTPAY_PAYMENT_SUCCESS'),
					JText::_('PLG_REDSHOP_PAYMENT_DOTPAY_PAYMENT_SUCCESS_LOG')
				);
			break;
			case 'rejected':
				return $this->setStatus(
					$orderId,
					$transactionId,
					$this->params->get('cancel_status', ''),
					'Unpaid',
					JText::_('PLG_REDSHOP_PAYMENT_DOTPAY_PAYMENT_REJECTED'),
					JText::_('PLG_REDSHOP_PAYMENT_DOTPAY_PAYMENT_REJECTED_LOG')
				);
			break;
		}

		return;
	}

	/**
	 * Method to verify signature
	 *
	 * @param   object   $post  JInput object for post data
	 *
	 * @return  boolean         True on validate signature
	 */
	private function isSignatureValidated($post)
	{
		$string = $this->params->get('pin') .
			$post->get('id', '', 'STRING') .
			$post->get('operation_number', '', 'STRING') .
			$post->get('operation_type', '', 'STRING') .
			$post->get('operation_status', '', 'STRING') .
			$post->get('operation_amount', '', 'STRING') .
			$post->get('operation_currency', '', 'STRING') .
			$post->get('operation_withdrawal_amount', '', 'STRING') .
			$post->get('operation_commission_amount', '', 'STRING') .
			$post->get('operation_original_amount', '', 'STRING') .
			$post->get('operation_original_currency', '', 'STRING') .
			$post->get('operation_datetime', '','STRING') .
			$post->get('operation_related_number', '', 'STRING') .
			$post->get('control', '', 'STRING') .
			$post->get('description', '','STRING') .
			$post->get('email', '', 'STRING') .
			$post->get('p_info', '', 'STRING') .
			$post->get('p_email', '', 'STRING') .
			$post->get('channel', '', 'STRING') .
			$post->get('channel_country', '', 'STRING') .
			$post->get('geoip_country','', 'STRING');

		if ($post->get('signature') == hash('sha256', $string))
		{
			return true;
		}
	}
}
