<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

require_once __DIR__ . '/library/paypal.php';

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\Patch;

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
		$enableVault = $this->params->get('enableVault', 0);

		$return = new stdClass;

		// CreditCard
		// A resource representing a credit card that can be
		// used to fund a payment.
		$card = $this->creditCard($data);

		// If vault is enabled only save card - payment will be done from backend.
		if ($enableVault)
		{
			try
			{
				$card->create($apiContext);

				$return->transaction_id = $card->getId();
				$return->status         = 'P';
				$return->paymentStatus  = 'Unpaid';
				$return->responsestatus = 'Success';
				$return->message        = JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_CREATE_SUCCESS');
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

	protected function creditCard($data)
	{
		$billingInfo = $data['billinginfo'];
		$ccdata      = JFactory::getSession()->get('ccdata');
		$cardType    = strtolower($ccdata['creditcard_code']);

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

		$card->setMerchantId('redSHOPPaypalCreditCard');
		$card->setExternalCardId($billingInfo->users_info_id . uniqid());
		$card->setExternalCustomerId($billingInfo->users_info_id);

		return $card;
	}

	public function onListCreditCards()
	{
		$app = JFactory::getApplication();
		$plugin = $app->input->getCmd('plugin');

		if ($this->isAjaxRequest() && 'paypalcreditcard' == $plugin)
		{
			$this->handleAjaxRequest();

			$app->close();
		}

		$enableVault = $this->params->get('enableVault', 0);

		if (!$enableVault)
		{
			return;
		}

		$user = RedshopHelperUser::getUserInformation(JFactory::getUser()->id);

		$apiContext  = $this->loadFramework();

		$html =  RedshopLayoutHelper::render(
				'cards',
				array(
					'apiContext' => $apiContext,
					'params'    => $this->params,
					'plugin' => $this,
					'merchantId' => 'redSHOPPaypalCreditCard',
					//'externalCardId' => $user->users_info_id,
					'externalCustomerId' => $user->users_info_id,
					'creditCardTypes' => $this->creditCardTypes()
				),
				JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name . '/layouts'
			);

		echo $html;

		return $html;
	}

	protected function handleAjaxRequest()
	{
		$app = JFactory::getApplication();

		$task = $app->input->getCmd('task');

		$ajaxMethod = 'onCreditCard' . ucfirst($task);

		$data = array(
			'cardId'          => $app->input->getString('cardId'),
			'cardName'        => $app->input->getString('cardName'),
			'cardType'        => $app->input->getString('cardType'),
			'cardNumber'      => $app->input->getNumber('cardNumber'),
			'cardExpireMonth' => $app->input->getInt('cardExpireMonth'),
			'cardExpireYear'  => $app->input->getInt('cardExpireYear'),
			'cardCvv'         => $app->input->getInt('cardCvv'),
			'users_info_id'   => RedshopHelperUser::getUserInformation(JFactory::getUser()->id)->users_info_id
		);

		$this->$ajaxMethod($data);
	}

	protected function prepareCreditCard($data)
	{
		list($firstName, $lastName) = explode(" ", $data['cardName']);

		if ($data['cardId'] != '0')
		{
			$apiContext  = $this->loadFramework();
			$card = CreditCard::get($data['cardId'], $apiContext);
		}
		else
		{
			$card = new CreditCard();
		}

		$card->setType($data['cardType'])
			->setNumber($data['cardNumber'])
			->setExpireMonth($data['cardExpireMonth'])
			->setExpireYear($data['cardExpireYear'])
			->setFirstName($firstName)
			->setLastName($lastName);

		$card->setMerchantId('redSHOPPaypalCreditCard');
		$card->setExternalCardId($data['users_info_id'] . uniqid());
		$card->setExternalCustomerId($data['users_info_id']);

		return $card;
	}

	public function onCreditCardNew($data)
	{
		$app        = JFactory::getApplication();
		$apiContext = $this->loadFramework();
		$card       = $this->prepareCreditCard($data);
		$cardId     = 0;
		$html       = '';

		try
		{
			$card->setCvv2($data['cardCvv']);
			$card->create($apiContext);

			$cardId = $card->getId();

			$html = RedshopLayoutHelper::render(
							'card',
							array(
								'card'            => $card,
								'creditCardTypes' => $this->creditCardTypes()
							),
							JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name . '/layouts'
						);

			$app->enqueueMessage(JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_SAVED'), 'success');
		}
		catch (Exception $ex)
		{
			$app->enqueueMessage($ex->getMessage() . '<br />' . implode('<br />', $this->parsePaypalException($ex)), 'error');
		}

		$return             = $this->getSystemMessages();
		$return['cardId']   = $cardId;
		$return['response'] = $html;

		ob_clean();
		echo json_encode($return);
	}

	public function onCreditCardUpdate($data)
	{
		$apiContext  = $this->loadFramework();

		list($firstName, $lastName) = explode(" ", $data['cardName']);

		$card = $this->prepareCreditCard($data);

		$pathRequest = new \PayPal\Api\PatchRequest();

		$name = new Patch();
		$name->setOp("replace")
			->setPath('/first_name')
			->setValue($firstName);

		$pathRequest->addPatch($name);

		$lastnamePatch = new Patch();
		$lastnamePatch->setOp("replace")
			->setPath('/last_name')
			->setValue($lastName);

		$pathRequest->addPatch($lastnamePatch);

		$month = new Patch();
		$month->setOp("replace")
			->setPath('/expire_month')
			->setValue((int) $data['cardExpireMonth']);

		$pathRequest->addPatch($month);

		$year = new Patch();
		$year->setOp("replace")
			->setPath('/expire_year')
			->setValue((int) $data['cardExpireYear']);

		$pathRequest->addPatch($year);

		$app = JFactory::getApplication();

		try
		{
			$card->update($pathRequest, $apiContext);

			$app->enqueueMessage(JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_SAVED'), 'success');
		}
		catch (Exception $e)
		{
			$app->enqueueMessage($e->getMessage(), 'error');
		}

		$return             = $this->getSystemMessages();
		$return['cardId']   = $card->getId();
		$return['response'] = '';

		ob_clean();
		echo json_encode($return);
	}

	public function onCreditCardDelete($data)
	{
		$app        = JFactory::getApplication();
		$apiContext = $this->loadFramework();
		$card       = $this->prepareCreditCard($data);

		try
		{
			$card->delete($apiContext);

			$app->enqueueMessage(JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_DELETED'), 'success');
		}
		catch (Exception $ex)
		{
			$app->enqueueMessage($ex->getMessage() . '<br />' . implode('<br />', $this->parsePaypalException($ex)), 'error');
		}

		$return             = $this->getSystemMessages();
		$return['cardId']   = 0;
		$return['response'] = '';

		ob_clean();
		echo json_encode($return);
	}

	protected function getSystemMessages()
	{
		$messages = JFactory::getApplication()->getMessageQueue();
		$return['messages'] = array();

		if (is_array($messages))
		{
			foreach ($messages as $msg)
			{
				$msgList = array(
					'msgList' => array(
						$msg['type'] => array($msg['message'])
					)
				);
				$msg['message'] = RedshopLayoutHelper::render('system.message', $msgList);

				switch ($msg['type'])
				{
					case 'message':
						$typeMessage = 'success';
						break;
					case 'notice':
						$typeMessage = 'info';
						break;
					case 'error':
						$typeMessage = 'important';
						break;
					case 'warning':
						$typeMessage = 'warning';
						break;
					default:
						$typeMessage = $msg['type'];
				}

				$return['messages'][] = array('message' => $msg['message'], 'type_message' => $typeMessage);
			}
		}

		return $return;
	}

	protected function isAjaxRequest()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
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

	protected function parsePaypalException($ex)
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

	protected function creditCardTypes()
	{
		$creditCardTypes = array(
			JHtml::_('select.option', 'visa', 'Visa', 'value', 'text'),
			JHtml::_('select.option', 'mastercard', 'Mastercard', 'value', 'text'),
			JHtml::_('select.option', 'amex', 'American Express', 'value', 'text'),
			JHtml::_('select.option', 'maestro', 'Maestro', 'value', 'text'),
			JHtml::_('select.option', 'jcb', 'JCB', 'value', 'text'),
			JHtml::_('select.option', 'diners', 'Diners Club', 'value', 'text'),
			JHtml::_('select.option', 'discover', 'Discover', 'value', 'text')
		);

		return $creditCardTypes;
	}

	public function onPaymentBackend($orderId)
	{
		$app = JFactory::getApplication();

		$orderInfo = RedshopHelperOrder::getOrderDetail($orderId);

		// Only pay when order status is set to pending and unpaid.
		if ('P' != $orderInfo->order_status && 'Unpaid' != $orderInfo->order_payment_status)
		{
			$app->enqueueMessage(
				JText::sprintf(
					'PLG_REDSHOP_PAYMENT_PAYPAL_NOT_PAY_ORDER',
					$orderInfo->order_status,
					$orderInfo->order_payment_status
				),
				'error'
			);

			return false;
		}

		$paymentInfo = RedshopHelperOrder::getPaymentInfo($orderId);
		$cardId = $paymentInfo->order_payment_trans_id;

		$apiContext  = $this->loadFramework();
		$session     = JFactory::getSession();
		$cart        = $session->get('cart');

		//$card = CreditCard::get($cardId, $apiContext);

		$creditCardToken = new CreditCardToken;
		$creditCardToken->setCreditCardId($cardId);

		// FundingInstrument
		$fi = new FundingInstrument;
		$fi->setCreditCardToken($creditCardToken);

		// Payer
		$payer = new Payer;
		$payer->setPaymentMethod("credit_card")
			->setFundingInstruments(array($fi));

		// Itemized information
		$cartItems = array();
		$orderItems = order_functions::getInstance()->getOrderItemDetail($orderId);

		for ($i = 0, $n = count($orderItems); $i < $n; $i++)
		{
			$orderItem   = $orderItems[$i];
			$item        = new Item;
			$cartItems[] =  $item->setName($orderItem->order_item_name)
								->setDescription('')
								->setCurrency(CURRENCY_CODE)
								->setQuantity($orderItem->product_quantity)
								->setTax($orderItem->product_item_price - $orderItem->product_item_price_excl_vat)
								->setPrice($orderItem->product_item_price);
		}

		$itemList = new ItemList;
		$itemList->setItems($cartItems);

		// Additional payment details
		$details = new Details;
		$details->setShipping($orderInfo->order_shipping)
				->setTax($orderInfo->order_tax)
				->setSubtotal($orderInfo->order_subtotal);

		// Amount
		$amount = new Amount;
		$amount->setCurrency(CURRENCY_CODE)
			->setTotal($orderInfo->order_total)
			->setDetails($details);

		// Transaction
		$transaction = new Transaction;
		$transaction->setAmount($amount)
			->setItemList($itemList)
			->setDescription(SHOP_NAME . ' Order No ' . $orderInfo->order_number)
			->setInvoiceNumber(uniqid());


		// Payment
		$payment = new Payment;
		$payment->setIntent($this->params->get('paymentIntent'))
			->setPayer($payer)
			->setTransactions(array($transaction));

		// Create Payment
		try
		{
			$payment->create($apiContext);

			$return                 = new stdClass;
			$return->order_id       = $orderId;
			$return->transaction_id = $payment->getId();

			if ('created' == $payment->getState()
				|| 'approved' == $payment->getState())
			{
				$return->order_status_code         = $this->params->get('verify_status');
				$return->order_payment_status_code = 'Paid';

				$return->log = JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_AUTHORIZE_SUCCESS');
				$return->msg = JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_AUTHORIZE_SUCCESS');

				if ('sale' == $payment->getIntent())
				{
					$return->order_status_code = $this->params->get('capture_status');

					$return->msg = JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_CAPTURE_SUCCESS');
					$return->log = JText::_('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_CAPTURE_SUCCESS');
				}

				$app->enqueueMessage($return->msg, 'message');
			}
			else
			{
				$return->order_status_code         = $this->params->get('invalid_status');
				$return->order_payment_status_code = 'Unpaid';

				$return->log = JText::sprintf('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_PAYMENT_FAIL', '');
				$return->msg = JText::sprintf('PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_PAYMENT_FAIL', '');

				$app->enqueueMessage($return->msg, 'error');
			}

			// Update order status.
			order_functions::getInstance()->changeorderstatus($return);

			return true;
		}
		catch (Exception $ex)
		{
			$app->enqueueMessage(
				JText::sprintf(
					'PLG_REDSHOP_PAYMENT_PAYPAL_CREDITCARD_PAYMENT_FAIL',
					implode('<br />', $this->parsePaypalException($ex))
				),
				'error'
			);

			return false;
		}
	}
}
