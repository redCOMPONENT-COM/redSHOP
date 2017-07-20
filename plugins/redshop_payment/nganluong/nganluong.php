<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

// Load nganluong library
require_once dirname(__DIR__) . '/nganluong/library/init.php';

class plgRedshop_PaymentNganluong extends RedshopPayment
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method will be triggered on before placing order to authorize or charge credit card
	 *
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
	 *
	 * @return  object  Authorize or Charge success or failed message and transaction id
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'nganluong')
		{
			return;
		}

		echo $this->renderPaymentForm($data);
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
				'action' 	  => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=process_payment&payment_method_id=nganluong&order_id=" . $orderInfo['order_id'],
				'firstname'   => $orderInfo['billinginfo']->firstname,
				'lastname'    => $orderInfo['billinginfo']->lastname,
				'email'       => $orderInfo['billinginfo']->user_email,
				'url'         => $this->getReturnUrl($orderInfo['order_id']),
				'urlc'        => $this->getNotifyUrl($orderInfo['order_id']),
				'phone'       => $orderInfo['billinginfo']->phone,
				'street'      => $orderInfo['billinginfo']->address,
			);

		return $inputs;
	}

	/**
	 * Notify payment
	 *
	 * @param   string  $element  Name of plugin
	 * @param   array   $request  HTTP request data
	 *
	 * @return  object  Contains the information of order success of falier in object
	 */
	public function onPrePayment_Nganluong($element, $request)
	{
		if ($element != 'nganluong')
		{
			return;
		}

		$app         = JFactory::getApplication();
		$input       = $app->input;
		$orderHelper = order_functions::getInstance();
		$orderId     = $input->getInt('order_id');
		$order       = $orderHelper->getOrderDetails($orderId);
		$price       = $order->order_total;

		$merchantId = $this->params->get('nganluong_merchant_id');
		$merchantPass = $this->params->get('nganluong_merchant_password');
		$email = $this->params->get('nganluong_email');
		$url = $this->params->get('nganluong_url_api');

		$nlCheckout = new NL_CheckOutV3($merchantId, $merchantPass, $email, $url);
		$totalAmount = $price;
		$items = array();
		$paymentMethod = $input->post->get('option_payment');
		$bankCode = $input->post->get('bankcode');
		$orderCode = $orderId;
		$paymentType = '';
		$discountAmount = $order->order_discount;
		$orderDescription = '';
		$taxAmount = $order->order_tax;
		$feeshipping = $order->order_shipping;
		$returnUrl = urlencode($this->getNotifyUrl($orderId));
		$cancelUrl = urlencode($this->getReturnUrl($orderId));

		$buyerFullname = $input->post->get('fullname');
		$buyerEmail = $input->post->get('email');
		$buyerMobile = $input->post->get('phone');
		$buyerAddress = $input->post->get('address');

		if (!empty($paymentMethod) && !empty($buyerEmail) && !empty($buyerMobile) && !empty($buyerFullname))
		{
			if ($paymentMethod == "VISA")
			{
				$nlResult = $nlCheckout->VisaCheckout($orderCode, $totalAmount, $paymentType, $orderDescription, $taxAmount, $feeshipping, $discountAmount, $returnUrl, $cancelUrl, $buyerFullname, $buyerEmail, $buyerMobile, $buyerAddress, $items, $bankCode);
			}
			elseif ($paymentMethod == "NL")
			{
				$nlResult = $nlCheckout->NLCheckout($orderCode, $totalAmount, $paymentType, $orderDescription, $taxAmount, $feeshipping, $discountAmount, $returnUrl, $cancelUrl, $buyerFullname, $buyerEmail, $buyerMobile, $buyerAddress, $items);
			}
			elseif ($paymentMethod == "ATM_ONLINE" && !empty($bankCode))
			{
				$nlResult = $nlCheckout->BankCheckout($orderCode, $totalAmount, $bankCode, $paymentType, $orderDescription, $taxAmount, $feeshipping, $discountAmount, $returnUrl, $cancelUrl, $buyerFullname, $buyerEmail, $buyerMobile, $buyerAddress, $items);
			}
			elseif ($paymentMethod == "NH_OFFLINE")
			{
				$nlResult = $nlCheckout->officeBankCheckout($orderCode, $totalAmount, $bankCode, $paymentType, $orderDescription, $taxAmount, $feeshipping, $discountAmount, $returnUrl, $cancelUrl, $buyerFullname, $buyerEmail, $buyerMobile, $buyerAddress, $items);
			}
			elseif ($paymentMethod == "ATM_OFFLINE")
			{
				$nlResult = $nlCheckout->BankOfflineCheckout($orderCode, $totalAmount, $bankCode, $paymentType, $orderDescription, $taxAmount, $feeshipping, $discountAmount, $returnUrl, $cancelUrl, $buyerFullname, $buyerEmail, $buyerMobile, $buyerAddress, $items);
			}
			elseif ($paymentMethod == "IB_ONLINE")
			{
				$nlResult = $nlCheckout->IBCheckout($orderCode, $totalAmount, $bankCode, $paymentType, $orderDescription, $taxAmount, $feeshipping, $discountAmount, $returnUrl, $cancelUrl, $buyerFullname, $buyerEmail, $buyerMobile, $buyerAddress, $items);
			}
		}

		$redirect   = (string) $nlResult->checkout_url;
		$app->redirect($redirect);
	}

	/**
	 * Notify payment
	 *
	 * @param   string  $element  Name of plugin
	 * @param   array   $request  HTTP request data
	 *
	 * @return  object  Contains the information of order success of falier in object
	 */
	public function onNotifyPaymentNganluong($element, $request)
	{
		if ($element != 'nganluong')
		{
			return;
		}

		$app   = JFactory::getApplication();
		$input = $app->input;
		$token = $input->getString('token');

		$merchantId = $this->params->get('nganluong_merchant_id');
		$merchantPass = $this->params->get('nganluong_merchant_password');
		$email = $this->params->get('nganluong_email');
		$url = $this->params->get('nganluong_url_api');

		$nlCheckout       = new NL_CheckOutV3($merchantId, $merchantPass, $email, $url);
		$nlResult         = $nlCheckout->GetTransactionDetail($token);
		$nlErrorCode      = (string) $nlResult->error_code;
		$values           = new stdClass;
		$values->order_id = (int) $nlResult->order_code;

		if ($nlErrorCode == '00')
		{
			$values->order_status_code         = $this->params->get('verify_status', '');
			$values->order_payment_status_code = 'Paid';
			$values->log                       = JText::_('PLG_REDSHOP_PAYMENT_NGANLUONG_ORDER_PLACED');
			$values->msg                       = JText::_('PLG_REDSHOP_PAYMENT_NGANLUONG_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code         = $this->params->get('invalid_status', '');
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('PLG_REDSHOP_PAYMENT_NGANLUONG_ORDER_NOT_PLACED');
			$values->msg                       = $nlResult->error_message;
		}

		return $values;
	}
}
