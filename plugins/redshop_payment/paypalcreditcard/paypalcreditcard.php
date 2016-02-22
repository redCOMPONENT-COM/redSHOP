<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

require_once __DIR__ . '/library/paypal.php';

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;

class plgRedshop_PaymentPaypalCreditcard extends RedshopPaypalPayment
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
	 * Example of return parameters:
	 * $return->responsestatus = 'Success' or 'Fail';
	 * $return->message        = 'Success or Fail messafe';
	 * $return->transaction_id = 'Transaction Id from gateway';
	 *
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
	 *
	 * @return  object  Authorize or Charge success or failed message and transaction id
	 */
	public function onPrePayment_PaypalCreditcard($element, $data)
	{
		if ($element != 'paypalcreditcard')
		{
			return;
		}

		$apiContext  = $this->loadFramework();
		$app         = JFactory::getApplication();
		$session     = JFactory::getSession();
		$cart        = $session->get('cart');
		$ccdata      = $session->get('ccdata');
		$billingInfo = $data['billinginfo'];

		$return = new stdClass;

		$expyear    = $ccdata['order_payment_expire_year'];
		$expmonth   = $ccdata['order_payment_expire_month'];
		$address    = $data['billinginfo']->address;
		$postalcode = $data['billinginfo']->zipcode;
		$cvv        = $ccdata['credit_card_code'];

		$cardType = strtolower($ccdata['creditcard_code']);

		if ('mc' == $cardType)
		{
			$cardType = 'mastercard';
		}

		// CreditCard
		// A resource representing a credit card that can be
		// used to fund a payment.
		$card = new CreditCard();
		$card->setType($cardType)
			->setNumber($ccdata['order_payment_number'])
			->setExpireMonth($ccdata['order_payment_expire_month'])
			->setExpireYear($ccdata['order_payment_expire_year'])
			->setCvv2($ccdata['credit_card_code'])
			->setFirstName($billingInfo->firstname)
			->setLastName($billingInfo->lastname);

		// FundingInstrument
		// A resource representing a Payer's funding instrument.
		// For direct credit card payments, set the CreditCard
		// field on this object.
		$fi = new FundingInstrument();
		$fi->setCreditCard($card);

		// Payer
		// A resource representing a Payer that funds a payment
		// For direct credit card payments, set payment method
		// to 'credit_card' and add an array of funding instruments.
		$payer = new Payer();
		$payer->setPaymentMethod("credit_card")
			->setFundingInstruments(array($fi));

		// Itemized information
		// (Optional) Lets you specify item wise
		// information
		$cartItems = array();

		for ($i = 0; $i < $cart['idx']; $i++)
		{
			$cartItem    = $cart[$i];
			$product     = RedshopHelperProduct::getProductById($cartItem['product_id']);
			$item        = new Item();
			$cartItems[] =  $item->setName($product->product_name)
								->setDescription($product->product_s_desc)
								->setCurrency(CURRENCY_CODE)
								->setQuantity($cartItem['quantity'])
								->setTax($cartItem['product_vat'])
								->setPrice($cartItem['product_price']);
		}

		$itemList = new ItemList();
		$itemList->setItems($cartItems);

		// Additional payment details
		// Use this optional field to set additional
		// payment information such as tax, shipping
		// charges etc.
		$details = new Details();
		$details->setShipping($cart['shipping'])
				->setTax($cart['tax'])
				->setSubtotal($cart['subtotal']);

		// Amount
		// Lets you specify a payment amount.
		// You can also specify additional details
		// such as shipping, tax.
		$amount = new Amount();
		$amount->setCurrency("USD")
			->setTotal($cart['total'])
			->setDetails($details);

		// Transaction
		// A transaction defines the contract of a
		// payment - what is the payment for and who
		// is fulfilling it.
		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($itemList)
			->setDescription(SHOP_NAME . ' Order No ' . $data['order_number'])
			->setInvoiceNumber(uniqid());


		// Payment
		// A Payment Resource; create one using
		// the above types and intent set to sale 'sale'
		$payment = new Payment();
		$payment->setIntent($this->params->get('paymentIntent'))
			->setPayer($payer)
			->setTransactions(array($transaction));

		// Create Payment
		// Create a payment by calling the payment->create() method
		// with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
		// The return object contains the state.
		try
		{
			$payment->create($apiContext);

			$return->transaction_id = $payment->getId();

			if ('created' == $payment->getState()
				|| 'approved' == $payment->getState())
			{
				$return->responsestatus = 'Success';
				$return->message = JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_AUTHORIZE_SUCCESS');

				if ('sale' == $payment->getIntent())
				{
					$return->message = JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_CAPTURE_SUCCESS');
				}

				$app->enqueueMessage($return->message, 'message');
			}
			else
			{
				$return->responsestatus = 'Fail';
				$return->message        = JText::sprintf('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_PAYMENT_FAIL', '');
			}
		}
		catch (Exception $ex)
		{
			$return->responsestatus = 'Fail';
			$return->message        = JText::sprintf(
				'PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_PAYMENT_FAIL',
				implode('<br />', $this->parsePaypalException($ex))
			);
		}

		return $return;
	}

	public function onAuthorizeStatus_PaypalCreditcard($element, $orderId)
	{
		if ($element != 'paypalcreditcard')
		{
			return;
		}

		$authorizeStatus = 'Authorized';

		// If directly captured then set status to 'Captured'
		if ('sale' == $this->params->get('paymentIntent'))
		{
			$authorizeStatus = 'Captured';
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
				->update($db->qn('#__redshop_order_payment'))
				->set($db->qn('authorize_status') . ' = ' . $db->q($authorizeStatus))
				->where($db->qn('order_id') . ' = ' . $db->q($orderId));

		// Set the query and execute the update.
		$db->setQuery($query)->execute();
	}

	/**
	 * This method will be trigger on order status change to capture order amount.
	 *
	 * @param   string  $element  Name of plugin
	 * @param   array   $data     Order Information array
	 *
	 * @return  object  Success or failed message
	 */
	public function onCapture_PaymentPaypalCreditcard($element, $data)
	{
		$transactionId = $data['order_transactionid'];

		if ('' == $transactionId)
		{
			return;
		}

		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();

		$apiContext       = $this->loadFramework();
		$payment          = Payment::get($transactionId, $apiContext);

		if ('sale' == $payment->getIntent())
		{
			$app->enqueueMessage(JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_PAYMENT_IS_SALE'), 'warning');

			return;
		}

		$transactions     = $payment->getTransactions();
		$relatedResources = $transactions[0]->getRelatedResources();
		$authorization    = $relatedResources[0]->getAuthorization();
		$authorization    = Authorization::get($authorization->getId(), $apiContext);

		$return = new stdClass;

		try
		{
			$amount = new Amount();
			$amount->setCurrency(CURRENCY_CODE)
				->setTotal($data['order_amount']);

			// Capture
			$capture = new Capture();
			$capture->setAmount($amount);

			// Perform a capture
			$getCapture    = $authorization->capture($capture, $apiContext);
			$transactionId = $getCapture->getId();

			if ('approved' == $getCapture->getState()
				|| 'completed' == $getCapture->getState())
			{
				// Update transaction string
				$query = $db->getQuery(true)
						->update($db->qn('#__redshop_order_payment'))
						->set($db->qn('order_payment_trans_id') . ' = ' . $db->q($transactionId))
						->where($db->qn('order_id') . ' = ' . $db->q($data['order_id']));

				// Set the query and execute the update.
				$db->setQuery($query)->execute();

				$return->responsestatus = 'Success';
				$return->message        = JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_CAPTURE_SUCCESS');

				$app->enqueueMessage($return->message, 'message');
			}
		}
		catch (Exception $ex)
		{
			$return->responsestatus = 'Fail';
			$return->message        = JText::sprintf(
				'PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_PAYMENT_FAIL',
				implode('<br />', $this->parsePaypalException($ex))
			);

			$app->enqueueMessage($return->message, 'error');
		}

		return $return;
	}

	private function parsePaypalException($ex)
	{
		$data = json_decode($ex->getData());

		$errorMessage = array();
		$errorMessage[] = $data->name;

		if (isset($data->details))
		{
			foreach ($data->details as $detail)
			{
				$errorMessage[] = $detail->field . ': ' . $detail->issue;
			}
		}

		$errorMessage[] = $data->message . ' ' . $data->information_link;

		return $errorMessage;
	}
}
