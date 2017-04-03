<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JLoader::import('redshop.library');

require_once 'library/paysonapi.php';

class plgRedshop_PaymentPayson extends JPlugin
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
		if ($element != 'payson')
		{
			return;
		}

		$app           = JFactory::getApplication();
		$currencyClass = CurrencyHelper::getInstance();
		$orderHelper   = order_functions::getInstance();

		$agentID         = $this->params->get('agentID');
		$md5Key          = $this->params->get('md5Key');

		// URLs used by payson for redirection after a completed/canceled purchase.
		$returnURL       = JURI::base() . 'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $data['order_id'];
		$cancelURL       = JURI::base() . 'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $data['order_id'];

		// Please note that only IP/URLS accessible from the internet will work
		$ipnURL    = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=payson&orderid=" . $data['order_id'];

		$returnURL = $ipnURL;
		$cancelURL = $ipnURL;

		// Account details of the receiver of money
		$receiverEmail   = $this->params->get('receiverEmail');

		// Amount to send to receiver

		$amountToReceive = $currencyClass->convert($data['order']->order_total, '', $this->params->get('currencyCode'));

		// Information about the sender of money
		$senderEmail     = $data['billinginfo']->user_email;
		$senderFirstname = $data['billinginfo']->firstname;
		$senderLastname  = $data['billinginfo']->lastname;

		/* Every interaction with Payson goes through the PaysonApi object which you set up as follows.
		 * For the use of our test or live environment use one following parameters:
		 * TRUE: Use test environment, FALSE: use live environment */
		$credentials = new PaysonCredentials($agentID, $md5Key);
		$api         = new PaysonApi(
						$credentials,
						(boolean) $this->params->get('paymentMode')
					);

		// Details about the receiver
		$receivers = array(
			new Receiver(
				$receiverEmail,
				$amountToReceive
			)
		);

		// Details about the user that is the sender of the money
		$sender = new Sender($senderEmail, $senderFirstname, $senderLastname);

		$payData = new PayData(
			$returnURL,
			$cancelURL,
			$ipnURL,
			$data['order_id'],
			$sender,
			$receivers
		);


		$items = $orderHelper->getOrderItemDetail($data['order_id']);

		//Set the list of products. For direct payment this is optional
		$orderItems = array();

		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$vat = ($items[$i]->product_item_price - $items[$i]->product_item_price_excl_vat) / $items[$i]->product_item_price;

			$orderItems[] = new OrderItem(
				$items[$i]->order_item_name . strip_tags($items[$i]->product_attribute),
				$currencyClass->convert($items[$i]->product_item_price_excl_vat, '', $this->params->get('currencyCode')),
				$items[$i]->product_quantity,
				$vat,
				$items[$i]->order_item_sku
			);
		}

		$shippingInfo = RedshopShippingRate::decrypt($data['order']->ship_method_id);

		$orderItems[] = new OrderItem(
			$shippingInfo[2],
			$currencyClass->convert($shippingInfo[3], '', $this->params->get('currencyCode')),
			1,
			0,
			'shipping-rate'
		);

		//$payData->setOrderItems($orderItems);

		// Set the payment method
		$constraints = array(FundingConstraint::BANK, FundingConstraint::CREDITCARD);
		$payData->setFundingConstraints($constraints);

		// Set the payer of Payson fees
		$payData->setFeesPayer(FeesPayer::SENDER);

		// Set currency code
		$payData->setCurrencyCode($this->params->get('currencyCode'));

		// Set locale code
		$payData->setLocaleCode($this->params->get('localeCode'));

		// Set guarantee options
		$payData->setGuaranteeOffered($this->params->get('guaranteeOffered'));

		// Step 2 initiate payment
		$payResponse = $api->pay($payData);

		// Step 3: verify that it suceeded
		if ($payResponse->getResponseEnvelope()->wasSuccessful())
		{
			// Step 4: forward user
			$app->redirect($api->getForwardPayUrl($payResponse));
		}
		else
		{
			$errors = $payResponse->getResponseEnvelope()->getErrors();

			$message = '';

			for ($i = 0, $n = count($errors); $i < $n; $i++)
			{
				$message .= $errors[$i]->getMessage();
			}

			$app->enqueueMessage($message, 'Error');

			$app->redirect(JRoute::_('index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $data['order_id'], false));
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
	public function onNotifyPaymentPayson($element, $request)
	{
		if ($element != 'payson')
		{
			return;
		}

		$app         = JFactory::getApplication();
		$orderId     = $app->input->getInt('orderid');
		$values      = new stdClass;

		// Initialize response
		$values->order_id                  = $orderId;
		$values->order_status_code         = $this->params->get('invalid_status', '');
		$values->order_payment_status_code = 'Unpaid';
		$values->log                       = JText::_('PLG_REDSHOP_PAYMENT_PAYSON_ORDER_NOT_PLACED');
		$values->msg                       = JText::_('PLG_REDSHOP_PAYMENT_PAYSON_ORDER_NOT_PLACED');

		// Your agent ID and md5 key
		$agentID = $this->params->get('agentID');
		$md5Key  = $this->params->get('md5Key');

		// Set up API
		$credentials = new PaysonCredentials($agentID, $md5Key);
		$api         = new PaysonApi(
			$credentials,
			(boolean) $this->params->get('paymentMode')
		);

		// Get the POST data
		$postData = file_get_contents("php://input");

		// Validate the request
		$response = $api->validate($postData);

		if ($response->isVerified())
		{
			// IPN request is verified with Payson
			// Check details to find out what happened with the payment
			$details = $response->getPaymentDetails();

			// Payment Trasaction Id
			$values->transaction_id = 'zzzzz';

			// After we have checked that the response validated we have to check the actual status
			// of the transfer
			if ($details->getType() == "TRANSFER" && $details->getStatus() == "COMPLETED")
			{
				$values->order_status_code         = $this->params->get('verify_status', '');
				$values->order_payment_status_code = 'Paid';

				$values->log = JText::_('PLG_REDSHOP_PAYMENT_PAYSON_ORDER_PLACED');
				$values->msg = JText::_('PLG_REDSHOP_PAYMENT_PAYSON_ORDER_PLACED');
			}
			else if ($details->getStatus() == "ERROR")
			{
				// Handle errors here
			}

			/*
			  //More info
			  $response->getPaymentDetails()->getCustom();
			  $response->getPaymentDetails()->getShippingAddressName();
			  $response->getPaymentDetails()->getShippingAddressStreetAddress();
			  $response->getPaymentDetails()->getShippingAddressPostalCode();
			  $response->getPaymentDetails()->getShippingAddressCity();
			  $response->getPaymentDetails()->getShippingAddressCountry();
			  $response->getPaymentDetails()->getToken();
			  $response->getPaymentDetails()->getType();
			  $response->getPaymentDetails()->getStatus();
			  $response->getPaymentDetails()->getCurrencyCode();
			  $response->getPaymentDetails()->getTrackingId();
			  $response->getPaymentDetails()->getCorrelationId();
			  $response->getPaymentDetails()->getPurchaseId();
			  $response->getPaymentDetails()->getSenderEmail();
			  $response->getPaymentDetails()->getInvoiceStatus();
			  $response->getPaymentDetails()->getGuaranteeStatus();
			  $details->getReceiverFee();
			 */
		}

		return $values;
	}
}
