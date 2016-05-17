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
JLoader::load('RedshopHelperAdminOrder');

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

		$orderId = $data['order_id'];
		$itemId = JFactory::getApplication()->input->getInt('Itemid');

		$returnUrl = JURI::root() . "index.php?option=com_redshop&view=order_detail&layout=receipt&oid=" . $orderId . "&Itemid=" . $itemId;
		$cancelUrl = JURI::root() . "index.php?option=com_redshop&view=order_detail&layout=checkout_final&oid=" . $orderId . "&Itemid=" . $itemId;

		echo RedshopLayoutHelper::render(
			'form',
			array(
				'action' => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=nganluong&orderid=" . $orderId,
				'data'   => $data,
				'params' => $this->params,
				'return' => $returnUrl,
				'cancel_return' => $cancelUrl
			),
			dirname(__DIR__) . '/nganluong/layouts'
		);
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

		$app         = JFactory::getApplication();
		$input       = $app->input;
		$orderHelper = new order_functions;
		$orderId     = $input->getInt('orderid');
		$order       = $orderHelper->getOrderDetails($orderId);
		$price       = $order->order_total;

		$merchantId = $this->params->get('nganluong_merchant_id');
		$merchantPass = $this->params->get('nganluong_merchant_password');
		$email = $this->params->get('nganluong_email');
		$url = $this->params->get('nganluong_url_api');
echo '<pre>';
print_r($order);
echo '</prE>';die;
		$nlCheckout = new NL_CheckOutV3($merchantId, $merchantPass, $email, $url);
		$totalAmount = $price;
		$items = array();
		$paymentMethod = $input->post->get('option_payment');
		$bankCode = @$input->post->get('bankcode');
		$orderCode = $orderId;
		$paymentType = '';
		$discountAmount = $order->order_discount;
		$orderDescription = '';
		$taxAmount = $order->order_tax;
		$feeshipping = $order->order_shipping;
		$returnUrl = $input->post->get('return_url');
		$cancelUrl = urlencode($input->post->get('cancel_url'));

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

		if ($nlResult->error_code == '00')
		{
			$return = $app->redirect((string) $nlResult->checkout_url);
		}
		else
		{
			$return = $app->enqueueMessage($nlResult->error_message, 'error');
		}

		return $return;
	}

	/**
	 * Redirecting after payment notify
	 *
	 * @param   string   $name     Name of plugin
	 * @param   integer  $orderId  Order Information Id
	 *
	 * @return  void
	 */
	public function onAfterNotifyPaymentNganluong($name, $orderId)
	{
		$app    = JFactory::getApplication();
		$values = new stdClass;

		// Initialize response
		$values->order_id                  = $orderId;
		$values->order_status_code         = $this->params->get('invalid_status', '');
		$values->order_payment_status_code = 'Unpaid';
		$values->log                       = JText::_('PLG_REDSHOP_PAYMENT_NGANLUONG_ORDER_NOT_PLACED');
		$values->msg                       = JText::_('PLG_REDSHOP_PAYMENT_NGANLUONG_ORDER_NOT_PLACED');

		$app->redirect(
			JRoute::_(
				'index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=' . $app->input->getInt('Itemid') . '&oid=' . $orderId,
				false
			)
		);
	}

	/**
	 * Refund amount on cancel order
	 *
	 * @param   string  $element  Plugin Name
	 * @param   array   $data     Order Transaction information
	 *
	 * @return  object  Return status information
	 */
	public function onStatus_PaymentNganluong($element, $data)
	{
		if ($element != 'nganluong')
		{
			return;
		}

		$transactionId = $data['order_transactionid'];

		if ('' == $transactionId)
		{
			return;
		}

		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$app->enqueueMessage($return->message, $return->type);

		return $return;
	}
}
