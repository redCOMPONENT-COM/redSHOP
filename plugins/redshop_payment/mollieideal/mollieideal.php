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
 *  PlgRedshop_PaymentMollieIdeal class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentMollieIdeal extends JPlugin
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

		require_once JPATH_SITE . '/plugins/redshop_payment/mollieideal/library/initialize.php';

		$data['order_id'] = $data['order_id'];

		$app = JFactory::getApplication();

		$step = $app->input->getInt('step', 1);

		if ($step == 1)
		{
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
		elseif ($step == 2)
		{
			// Determine the url parts to these example files.
			$protocol    = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
			$hostname    = $_SERVER['HTTP_HOST'];
			$path        = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

			$webhookUrl  = JUri::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=mollieideal&orderid=" . $data['order_id'];
			$redirectUrl = JUri::base() . "index.php?option=com_redshop&view=order_detail&layout=receipt&oid=" . $data['order_id'] . "&Itemid=" . $app->input->getInt('Itemid');

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
				[
					"amount"      => $data['order']->order_total,
					"method"      => Mollie_API_Object_Method::IDEAL,
					"description" => JText::_('PLG_REDSHOP_PAYMENT_MOLLIEIDEAL_PAYMENT_DESCRIPTION'),
					"webhookUrl"  => $webhookUrl,
					"redirectUrl" => $redirectUrl,
					"metadata"    => ["order_id" => $data['order_id']],
					"issuer" 	  => !empty($_POST["issuer"]) ? $_POST["issuer"] : null
				]
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
	 * @return  object  Payment status information
	 */
	public function onNotifyPaymentMollieideal($element, $request)
	{
		if ($element != 'mollieideal')
		{
			return;
		}

		/*
		 * Initialize the Mollie API library with your API key.
		 *
		 * @link  https://www.mollie.com/beheer/account/profielen/
		 */
		require_once __DIR__ . '/library/initialize.php';

		$transactioId = JFactory::getApplication()->input->getString('id');

		// Retrieve the payment's current state.
		$payment = $mollie->payments->get($transactioId);
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

		$values->transaction_id = $transactioId;
		$values->order_id       = $orderId;

		return $values;
	}
}
