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

// Load baokim library
require_once dirname(__DIR__) . '/baokim/library/constants.php';
require_once dirname(__DIR__) . '/baokim/library/baokim_payment_pro.php';
require_once dirname(__DIR__) . '/baokim/library/baokim_payment.php';

class plgRedshop_PaymentBaokim extends RedshopPayment
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
		if ($element != 'baokim')
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
				'action' 	  => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=baokim&orderid=" . $orderInfo['order_id'],
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
	public function onNotifyPaymentBaokim($element, $request)
	{
		if ($element != 'baokim')
		{
			return;
		}

		$app                 = JFactory::getApplication();
		$input               = $app->input;
		$orderHelper         = new order_functions;
		$orderId             = $input->getInt('orderid');
		$order               = $orderHelper->getOrderDetails($orderId);
		$price               = $order->order_total;
		$bankPaymentMethodId = $input->post->get('bank_payment_method_id');

		if (!empty($bankPaymentMethodId))
		{
			$data                           = array();
			$data['order_id']               = $orderId;
			$data['total_amount']           = $price;
			$data['bank_payment_method_id'] = $input->post->get('bank_payment_method_id');
			$data['payer_name']             = $input->post->get('payer_name');
			$data['payer_email']            = $input->post->get('payer_email');
			$data['payer_phone_no']         = $input->post->get('payer_phone_no');
			$data['address']                = $input->post->get('address');
			$returnUrl                      = $this->getReturnUrl($orderId);
			$cancelUrl                      = urlencode($this->getNotifyUrl($orderId));
			$data['return']                 = $returnUrl;
			$data['cancel']                 = $cancelUrl;
			$feeshipping                    = $order->order_shipping;
			$taxAmount                      = $order->order_tax;
			$data['shipping_fee']           = $feeshipping;
			$data['tax_fee']                = $taxAmount;
			$baokim                         = new BaoKimPaymentPro;
			$result                         = $baokim->pay_by_card($data);

			if (!empty($result['error']))
			{
				$app->enqueueMessage($result['error'], 'error');

				return false;
			}

			$baokimUrl = $result['redirect_url'] ? $result['redirect_url'] : $result['guide_url'];
		}
		else
		{
			$data      = $_POST;
			$baokim    = new BaoKimPayment;
			$baokimUrl = $baokim->createRequestUrl($data);
		}

		return $app->redirect((string) $baokimUrl);
	}

	/**
	 * Redirecting after payment notify
	 *
	 * @param   string   $name     Name of plugin
	 * @param   integer  $orderId  Order Information Id
	 *
	 * @return  void
	 */
	public function onAfterNotifyPaymentBaokim($name, $orderId)
	{
		$app    = JFactory::getApplication();
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
	public function onStatus_PaymentBaokim($element, $data)
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

		$app = JFactory::getApplication();
		$app->enqueueMessage($return->message, $return->type);

		return $return;
	}
}
