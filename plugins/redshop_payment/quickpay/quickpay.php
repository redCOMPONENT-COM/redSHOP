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
			'currency'        => Redshop::getConfig()->get('CURRENCY_CODE'),
			'continueurl'     => $this->getReturnUrl($orderInfo['order_id']),
			'cancelurl'       => $this->getNotifyUrl($orderInfo['order_id']),
			'callbackurl'     => $this->getNotifyUrl($orderInfo['order_id']),
			'language'        => $this->getLang(),
			'autocapture'     => $this->params->get("autoCapture")
		);

		$paymentMethods = array_merge(
			$this->params->get('paymentMethods', array()),
			$this->params->get('paymentMethodsExlude', array())
		);

		if (!empty($paymentMethods))
		{
			$params['payment_methods'] = 'creditcard, ' . implode(', ', $paymentMethods);
		}

		$params["checksum"] = $this->sign($params, $this->params->get("apiKey"));

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

		$orderId = $input->getInt('orderid');

		$requestBody = file_get_contents("php://input");

		$response = json_decode($requestBody);

		$merchantId    = $response->merchant_id;
		$transactionId = $response->id;

		// Invalid request
		if ($response->merchant_id != $this->params->get("merchantId"))
		{
			JLog::add('Invalid Request', JLog::ERROR, 'jerror');

			return false;
		}

		// Invalid Signature
		if (!$this->isSignatureValidated())
		{
			JLog::add('Invalid Signature', JLog::ERROR, 'jerror');

			return $this->setStatus(
				$orderId,
				$transactionId,
				$this->params->get('cancel_status', ''),
				'Unpaid',
				JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_INVALID'),
				JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_INVALID_LOG')
			);
		}

		$operations = $response->operations;
		$operation = $operations[count($operations) - 1];

		if (!$response->accepted)
		{
			return $this->setStatus(
				$orderId,
				$transactionId,
				$this->params->get('invalid_status', ''),
				'Unpaid',
				$operation->qp_status_msg . '<br />' . $operation->aq_status_msg,
				$operation->qp_status_msg . '<br />' . $operation->aq_status_msg
			);
		}

		if ($operation->pending
				|| $operation->qp_status_code != 20000)
		{
			return $this->setStatus(
				$orderId,
				$transactionId,
				$this->params->get('cancel_status', ''),
				'Unpaid',
				$operation->qp_status_msg . '<br />' . $operation->aq_status_msg,
				JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_REJECTED_LOG')
			);
		}

		// Set order status based QuickPay post notification.
		switch($operation->type)
		{
			case 'authorize':

				if ('pending' == $response->state || 'new' == $response->state)
				{
					return $this->setStatus(
						$orderId,
						$transactionId,
						$this->params->get('verify_status', ''),
						'Paid',
						$operation->qp_status_msg . '<br />' . $operation->aq_status_msg,
						JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_SUCCESS_LOG')
					);
				}

			break;
			case 'capture':
				return $this->setStatus(
					$orderId,
					$transactionId,
					$this->params->get('capture_status', ''),
					'Paid',
					$operation->qp_status_msg . '<br />' . $operation->aq_status_msg,
					JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_SUCCESS_LOG')
				);
			break;
		}

		return;
	}

	/**
	 * Send Information on QuickPay using CURL
	 *
	 * @param   string  $method  Method to send data
	 * @param   array   $data    Array of information or null
	 * @param   string  $type    API type to get information
	 *
	 * @return  object           Response in Object
	 */
	private function sendQuickpayRequest($method, $data, $type)
	{
		// Set up Curl Headers
		$headers = array(
			'Accept-Version' => 'v10',
			'Authorization' => 'Basic ' . base64_encode(':' . $this->params->get('apiUserKey'))
		);

		// Set Account Creation Url
		$url = JUri::getInstance('https://api.quickpay.net/' . $type);

		$curl     = new JHttpTransportCurl(new JRegistry);
		$response = $curl->request($method, $url, $data, $headers);

		return $response;
	}

	/**
	 * Method will be trigger on capturing payment.
	 *
	 * @param   string  $element  Element Name
	 * @param   array   $data     Order information in array
	 *
	 * @return  object            Return information Object
	 */
	public function onCapture_PaymentQuickpay($element, $data)
	{
		if ($element != 'quickpay')
		{
			return;
		}

		$sendData = array(
			'amount' => ($data['order_amount'] * 100)
		);

		$type     = 'payments/' . $data['order_transactionid'] . '/capture';
		$response = $this->sendQuickpayRequest('POST', $sendData, $type);

		$body = json_decode($response->body);

		if (202 == $response->code && $body->accepted)
		{
			$operations = $body->operations;
			$operation = $operations[count($operations) - 1];

			if ($operation->pending
				|| $operation->qp_status_code != 20000)
			{
				$message                = $operation->qp_status_msg . '<br />' . $operation->aq_status_msg;
				$values->responsestatus = 'Fail';
				JLog::add($message, JLog::ERROR, 'jerror');
			}
			else
			{
				$values->responsestatus = 'Success';
				$message                = $operation->qp_status_msg . '<br />' . $operation->aq_status_msg;
			}
		}
		else
		{
			$message                = $body->message . ' Error: Amount ' . implode('<br />', $body->errors->amount);
			$values->responsestatus = 'Fail';
			JLog::add($message, JLog::ERROR, 'jerror');
		}

		$values->message = $message;

		return $values;
	}

	/**
	 * Method will be trigger on cancelling order
	 *
	 * @param   string  $element  Element name
	 * @param   array   $data     Order info
	 *
	 * @return  object            Response to update order status
	 */
	public function onStatus_PaymentQuickpay($element, $data)
	{
		if ($element != 'quickpay')
		{
			return;
		}

		$type = 'payments/' . $data['order_transactionid'];

		$response  = $this->sendQuickpayRequest('GET', null, $type);

		$body = json_decode($response->body);

		if (200 != $response->code)
		{
			$values->message        = $body->message;
			$values->responsestatus = 'Fail';
			JLog::add($values->message, JLog::ERROR, 'jerror');

			return $values;
		}

		if ('processed' == $body->state)
		{
			return $this->doRefund($data);
		}

		return $this->doCancel($data);
	}

	/**
	 * Method to do refund
	 *
	 * @param   array  $data  Order Information
	 *
	 * @return  object         Response object to update order status
	 */
	protected function doRefund($data)
	{
		// Only allow refund if enabled.
		if (!(boolean) $this->params->get('refund'))
		{
			return;
		}

		$sendData = array(
			'amount' => ($data['order_amount'] * 100)
		);

		$type = 'payments/' . $data['order_transactionid'] . '/refund';

		$response  = $this->sendQuickpayRequest('POST', $sendData, $type);

		$body = json_decode($response->body);

		if (202 == $response->code && $body->accepted)
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_CAPTURED');
		}
		else
		{
			$message                = $body->message . ' Error: Amount ' . implode('<br />', $body->errors->amount);
			$values->responsestatus = 'Fail';
			JLog::add($message, JLog::ERROR, 'jerror');
		}

		$values->message = $message;

		return $values;
	}

	/**
	 * Cancel the payment
	 *
	 * @param   array  $data  Order Information
	 *
	 * @return  object         Return object to update order status log
	 */
	protected function doCancel($data)
	{
		$type = 'payments/' . $data['order_transactionid'] . '/cancel';

		$response  = $this->sendQuickpayRequest('POST', null, $type);

		$body = json_decode($response->body);

		if (202 == $response->code && $body->accepted)
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('PLG_REDSHOP_PAYMENT_QUICKPAY_PAYMENT_CAPTURED');
		}
		else
		{
			$message                = $body->message;
			$values->responsestatus = 'Fail';
			JLog::add($message, JLog::ERROR, 'jerror');
		}

		$values->message = $message;

		return $values;
	}

	/**
	 * Method to verify signature
	 *
	 * @param   object   $post  JInput object for post data
	 *
	 * @return  boolean         True on validate signature
	 */
	private function isSignatureValidated()
	{
		$requestBody = file_get_contents("php://input");
		$checksum    = hash_hmac("sha256", $requestBody, $this->params->get('privateKey'));

		if ($checksum == $_SERVER["HTTP_QUICKPAY_CHECKSUM_SHA256"])
		{
			return true;
		}

		return false;
	}

	/**
	 * Hash sign to create checksum to send quickpay
	 *
	 * @param   array   $params  Payment Information
	 * @param   string  $apiKey  Payment Window API Key given from quickpay
	 *
	 * @return  string  Prepared hash sign key
	 */
	protected function sign($params, $apiKey)
	{
		$flattenedParams = $this->flattenParams($params);
		ksort($flattenedParams);
		$base = implode(" ", $flattenedParams);

		return hash_hmac("sha256", $base, $apiKey);
	}

	/**
	 * Flatten the paramters of the array.
	 *
	 * @param   object  $obj     Base object to flatten
	 * @param   array   $result  Final result of array
	 * @param   array   $path    Path of array depth
	 *
	 * @return  array   Final flatten array
	 */
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
