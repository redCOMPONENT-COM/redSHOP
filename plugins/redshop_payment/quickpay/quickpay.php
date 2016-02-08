<?php
/**
 * @package     RedSHOP.Plugins
 * @subpackage  QuickPay
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * QuickPay payment gateway
 *
 * @package     Redshop.Plugins
 * @subpackage  QuickPay
 * @since       1.5
 */
class PlgRedshop_PaymentQuickpay extends RedshopPayment
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
		if ($element != 'quickpay')
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
		$orderId = $orderInfo['order']->order_number;

		if ((boolean) $this->params->get('useOrderId'))
		{
			$orderId = $orderInfo['order_id'];
		}



		$params = array(
			'version'         => "v10",
			'merchant_id'     => $this->params->get("merchantId"),
			'agreement_id'    => $this->params->get("agreementId"),
			'order_id'        => $orderId,
			'amount'          => ($orderInfo['carttotal'] * 100),
			'currency'        => CURRENCY_CODE,
			'continueurl'     => $this->getReturnUrl($orderInfo['order_id']),
			'cancelurl'       => $this->getNotifyUrl($orderInfo['order_id']),
			'callbackurl'     => $this->getNotifyUrl($orderInfo['order_id']),
			'language'        => $this->getLang(),
			'autocapture'     => $this->params->get("autoCapture"),
			/*'variables'       => array(
				"abc" => $orderInfo['order_id']
			)*/
		);

		$paymentMethods = $this->params->get("paymentMethods");

		if (!empty($paymentMethods))
		{
			$params['payment_methods'] = 'creditcard, ' . implode(', ', $paymentMethods);
		}

		$params["checksum"] = $this->sign($params, $this->params->get("apiKey"));

		/*$params['invoice_address'] = array(
			'name'            => $orderInfo['billinginfo']->firstname . ' ' . $orderInfo['billinginfo']->lastname,
			'att'             => '',
			'street'          => $orderInfo['billinginfo']->address,
			'house_number'    => '',
			'house_extension' => '',
			'zip_code'        => $orderInfo['billinginfo']->zipcode,
			'city'            => $orderInfo['billinginfo']->city,
			'region'          => '',
			'country_code'    => $orderInfo['billinginfo']->country_code,
			'vat_no'          => '',
			'phone_number'    => $orderInfo['billinginfo']->phone,
			'mobile_number'   => '',
			'email'           => $orderInfo['billinginfo']->user_email
		);

		$params['shipping_address'] = array(
			'name'            => $orderInfo['shippinginfo']->firstname . ' ' . $orderInfo['shippinginfo']->lastname,
			'att'             => '',
			'street'          => $orderInfo['shippinginfo']->address,
			'house_number'    => '',
			'house_extension' => '',
			'zip_code'        => $orderInfo['shippinginfo']->zipcode,
			'city'            => $orderInfo['shippinginfo']->city,
			'region'          => '',
			'country_code'    => $orderInfo['shippinginfo']->country_code,
			'vat_no'          => '',
			'phone_number'    => $orderInfo['shippinginfo']->phone,
			'mobile_number'   => '',
			'email'           => $orderInfo['shippinginfo']->user_email
		);*/

		return $params;
	}

	/**
	 * Handle payment status notification
	 *
	 * @param   string  $element  Payment Name
	 * @param   array   $request  Reqest Array
	 *
	 * @return  object  Order Status information object
	 */
	public function onNotifyPaymentQuickpay($element, $request)
	{
		if ($element != 'quickpay')
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
				JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_INVALID'),
				JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_INVALID_LOG')
			);
		}

		$transactionId = $input->post->getString('operation_number');
		$orderId       = $input->post->getInt('control');

		// Set order status based QuickPay post notification.
		switch($input->post->get('operation_status'))
		{
			case 'completed':
				return $this->setStatus(
					$orderId,
					$transactionId,
					$this->params->get('verify_status', ''),
					'Paid',
					JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_SUCCESS'),
					JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_SUCCESS_LOG')
				);
			break;
			case 'rejected':
				return $this->setStatus(
					$orderId,
					$transactionId,
					$this->params->get('cancel_status', ''),
					'Unpaid',
					JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_REJECTED'),
					JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_REJECTED_LOG')
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

	protected function sign($params, $apiKey)
	{
		$flattenedParams = $this->flattenParams($params);
		ksort($flattenedParams);
		$base = implode(" ", $flattenedParams);

		return hash_hmac("sha256", $base, $apiKey);
	}

	protected function flattenParams($obj, $result = array(), $path = array())
	{
		if (is_array($obj))
		{
			foreach ($obj as $k => $v)
			{
				$result = array_merge($result, $this->flattenParams($v, $result, array_merge($path, array($k))));
			}
		}
		else
		{
			$result[implode("", array_map(function($p) { return "[{$p}]"; }, $path))] = $obj;
		}

		return $result;
	}
}
