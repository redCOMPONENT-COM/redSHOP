<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperHelper');

class PlgRedshop_PaymentDotpay extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'dotpay')
		{
			return;
		}

		$formInput = $this->preparePaymentInput($data);

		echo $this->preparePaymentForm($data, $formInput);
	}

	private function preparePaymentInput($data)
	{
		$inputs = array(
				'id'          => $this->params->get("customerId"),
				'amount'      => $data['carttotal'],
				'currency'    => CURRENCY_CODE,
				'description' => 'Payment for order ' . $data['order_id'],
				'lang'        => $this->getLang(),
				'type'        => 0,
				'firstname'   => $data['billinginfo']->firstname,
				'lastname'    => $data['billinginfo']->lastname,
				'email'       => $data['billinginfo']->user_email,
				'control'     => $data['order_id'],
				'url'         => $this->getReturnUrl($data['order_id']),
				'urlc'        => $this->getNotifyUrl($data['order_id']),
				'city'        => $data['billinginfo']->city,
				'postcode'    => $data['billinginfo']->zipcode,
				'phone'       => $data['billinginfo']->phone,
				'country'     => $data['billinginfo']->country_code,
				'street'      => $data['billinginfo']->address,
				'street_n1'   => '',
				'api_version' => 'dev'
			);

		return $inputs;
	}

	private function preparePaymentForm($data, $formInput)
	{
		return RedshopLayoutHelper::render(
				'form',
				array(
					'data' => $data,
					'formInput' => $formInput,
					'params'    => $this->params
				),
				__DIR__ . '/layouts'
			);
	}

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
		$orderId = $input->post->getInt('control');

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
	 * Get Payment Language
	 *
	 * @return  string  Language Code
	 */
	private function getLang()
	{
		return substr(JFactory::getLanguage()->getTag(), 0, 2);
	}

	private function getNotifyUrl($orderId)
	{
		return JURI::base() . 'index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=dotpay&orderid=' . $orderId;
	}

	private function getReturnUrl($orderId)
	{
		return JURI::base() . 'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $orderId;
	}

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

	private function setStatus($orderId, $transactionId, $status, $paymentStatus, $message, $log)
	{
		$values = new stdClass;
		$values->transaction_id            = $transactionId;
		$values->order_id                  = $orderId;
		$values->order_status_code         = $status;
		$values->order_payment_status_code = $paymentStatus;
		$values->log                       = $log;
		$values->msg                       = $message;

		return $values;
	}
}
