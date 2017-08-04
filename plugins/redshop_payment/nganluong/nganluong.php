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

class plgRedshop_PaymentNganluong extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Get notify url for payment status update.
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  string             Notify url
	 */
	protected function getNotifyUrl($orderId)
	{
		return JUri::base()
				. 'index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=' . $this->_name
				. '&orderid=' . $orderId
				. '&Itemid=' . JFactory::getApplication()->input->getInt('Itemid');
	}

	/**
	 * Get return url of for the payment.
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  string   Return Url
	 */
	protected function getReturnUrl($orderId)
	{
		return JUri::base()
				. 'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $orderId
				. '&Itemid=' . JFactory::getApplication()->input->getInt('Itemid');
	}

	/**
	 * This method will be triggered on before placing order to authorize or charge credit card
	 *
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
	 *
	 * @return  mixed
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'nganluong')
		{
			return;
		}

		$app = JFactory::getApplication();
		$url = 'https://www.nganluong.vn/checkout.php';

		if ($this->params->get('sandbox') == 1)
		{
			$url = 'http://sandbox.nganluong.vn:8088/nl30/checkout.php';
		}

		$merchantId = $this->params->get('nganluong_merchant_id');
		$merchantPass = $this->params->get('nganluong_merchant_password');
		$email = $this->params->get('nganluong_email');

		$nlCheckout                   = new NL_CheckOut;
		$nlCheckout->nganluongUrl     = $url;
		$nlCheckout->merchantSiteCode = $merchantId;
		$nlCheckout->securePass       = $merchantPass;

		$orderId          = $data['order']->order_id;
		$totalAmount      = $data['order']->order_total;
		$items            = array();
		$orderCode        = $orderId;
		$orderQuantity    = $data['order_quantity'];
		$paymentType      = '';
		$discountAmount   = $data['order']->order_discount;
		$orderDescription = $data['order']->customer_message;
		$taxAmount        = $data['order']->order_tax;
		$feeShipping      = $data['order']->order_shipping;
		$returnUrl        = urlencode($this->getNotifyUrl($orderId));
		$cancelUrl        = urlencode($this->getReturnUrl($orderId));
		$buyerFullname    = $data['billinginfo']->firstname . ' ' . $data['billinginfo']->lastname;
		$buyerEmail       = $data['billinginfo']->email;
		$buyerMobile      = $data['billinginfo']->phone;
		$buyerInfo        = $buyerFullname . "*|*" . $buyerEmail . "*|*" . $buyerMobile;

		$nlResult = $nlCheckout->buildCheckoutUrlExpand(
			$returnUrl,
			$email,
			'',
			$orderCode,
			$totalAmount,
			Redshop::getConfig()->get('CURRENCY_CODE'),
			$orderQuantity,
			$taxAmount,
			$discountAmount,
			0,
			$feeShipping,
			$orderDescription,
			$buyerInfo,
			''
		);

		if (!empty($orderId))
		{
			$nlResult .= '&cancel_url=' . $cancelUrl;
			$app->redirect($nlResult);
		}
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
