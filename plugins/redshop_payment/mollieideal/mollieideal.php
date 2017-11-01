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
 * Mollie payment class
 *
 * @package  Redshop.Plugin
 *
 * @since    2.0.0
 */
class PlgRedshop_PaymentMollieideal extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Initialize payment procedure
	 *
	 * @param   string  $element  Payment plugin name
	 * @param   array   $data     Cart Information
	 *
	 * @return  void
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'mollieideal')
		{
			return;
		}

		$mollie = $this->getFramework();

		$app = JFactory::getApplication();

		$step = $app->input->getInt('step', 1);

		if (1 == $step)
		{
			// $this->showBanks($mollie);
			echo RedshopLayoutHelper::render(
				'form',
				array(
					'mollie' => $mollie,
					'data'   => $data,
					'params' => $this->params
				),
				__DIR__ . '/layouts'
			);
		}
		elseif (2 == $step)
		{
			// Determine the url parts to these example files.
			$webHookUrl = JUri::base() . 'index.php?tmpl=component&option=com_redshop&view=order_detail&task=notify_payment'
				. '&payment_plugin=mollieideal&orderid=' . $data['order_id'];

			$redirectUrl = JUri::base() . "index.php?option=com_redshop&view=order_detail&layout=receipt&oid="
				. $data['order_id'] . "&Itemid=" . $app->input->getInt('Itemid');

			/*
			 * Payment parameters:
			 *   amount        Amount in EUROs. This example creates a € 27.50 payment.
			 *   method        Payment method "ideal".
			 *   description   Description of the payment.
			 *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
			 *   metadata      Custom metadata that is stored with the payment.
			 *   issuer        The customer's bank. If empty the customer can select it later.
			 */
			$payment = $mollie->payments->create(
				array(
					"amount"      => $data['order']->order_total,
					"method"      => Mollie_API_Object_Method::IDEAL,
					"description" => JText::_('PLG_REDSHOP_PAYMENT_MOLLIEIDEAL_PAYMENT_DESCRIPTION'),
					"webhookUrl"  => $webHookUrl,
					"redirectUrl" => $redirectUrl,
					"metadata"    => array(
						"order_id" => $data['order_id'],
					),
					"issuer"      => $app->input->post->get('issuer', null)
				)
			);

			// Send the customer off to complete the payment.
			$app->redirect($payment->getPaymentUrl());
		}
	}

	/**
	 * Get payment information from the gateway - payment hook
	 *
	 * @param   string  $element  Payment Method Name
	 * @param   array   $request  Request parameter
	 *
	 * @return  object            Payment status information
	 */
	public function onNotifyPaymentMollieideal($element, $request)
	{
		if ($element != 'mollieideal')
		{
			return;
		}

		$mollie = $this->getFramework();

		$transactionId = JFactory::getApplication()->input->getString('id');

		// Retrieve the payment's current state.
		$payment = $mollie->payments->get($transactionId);
		$orderId = $payment->metadata->order_id;

		$values = new stdClass;

		if ($payment->isPaid() == true)
		{
			$values->order_status_code         = $this->params->get('verify_status', '');
			$values->order_payment_status_code = 'Paid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
		}
		elseif ($payment->isOpen() == false)
		{
			$values->order_status_code         = $this->params->get('invalid_status', '');
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $transactionId;
		$values->order_id       = $orderId;

		return $values;
	}

	/**
	 * Method for load mollie framework
	 *
	 * @return  Mollie_API_Client  Mollie class
	 *
	 * @since  2.0.0
	 */
	protected function getFramework()
	{
		require_once __DIR__ . '/library/vendor/autoload.php';

		/*
		 * Initialize the Mollie API library with your API key.
		 *
		 * See: https://www.mollie.com/beheer/account/profielen/
		 */
		$mollie = new Mollie_API_Client;
		$mollie->setApiKey($this->params->get('apiKey'));

		return $mollie;
	}
}
