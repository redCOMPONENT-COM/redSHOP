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
 * Class PlgRedshop_PaymentQuickbook
 *
 * @since  1.5
 */
class PlgRedshop_PaymentQuickbook extends JPlugin
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
	public function onPrePayment_Quickbook($element, $data)
	{
		if ($element != 'quickbook')
		{
			return;
		}

		$app = JFactory::getApplication();

		// Include Quickbook Library
		require_once __DIR__ . '/library/vendor/autoload.php';

		$return = new stdClass;

		/*
		* If you want to log requests/responses to a database,
		* you can provide a database DSN-style connection string here
		* Example: $dsn = 'mysql://root:@localhost/quickbooks_merchantservice';
		* No, we don't want. We will do it by our own in redSHOP.
		*/
		$dsn = null;

		// As we are using HOSTED model so it is required.
		$pathToCertificate = $this->params->get('certifiedPemFile', null);

		// This is your login ID that Intuit assignes you during the application
		$appLogin = $this->params->get('appLogin', null);
		$connectionTicket = $this->params->get('connectionTicket');

		// Create an instance of the MerchantService object
		$qbms = new QuickBooks_MerchantService(
			$dsn,
			$pathToCertificate,
			$appLogin,
			$connectionTicket
		);

		// Set Test environment based on backend setting
		$qbms->useTestEnvironment((boolean) $this->params->get('isTest', 1));

		// If you want to see the full XML input/output, you can turn on debug mode
		$qbms->useDebugMode((boolean) $this->params->get('isDebug', 1));

		// Now, let's create a credit card object, and authorize an amount agains the card
		$session = JFactory::getSession();
		$ccdata  = $session->get('ccdata');

		$name       = $ccdata['order_payment_name'];
		$number     = $ccdata['order_payment_number'];
		$expyear    = $ccdata['order_payment_expire_year'];
		$expmonth   = $ccdata['order_payment_expire_month'];
		$address    = $data['billinginfo']->address;
		$postalcode = $data['billinginfo']->zipcode;
		$cvv        = $ccdata['credit_card_code'];

		// Create the CreditCard object
		$Card = new QuickBooks_MerchantService_CreditCard(
			$name,
			$number,
			$expyear,
			$expmonth,
			$address,
			$postalcode,
			$cvv
		);

		// We're going to authorize order total
		$amount = $data['order_total'];

		if ($Transaction = $qbms->authorize($Card, $amount))
		{
			$return->message = JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_PAYMENT_AUTHORIZE_SUCCESS');

			if ((boolean) $this->params->get('directCapture', 0))
			{
				if ($Transaction = $qbms->capture($Transaction, $amount))
				{
					$return->message = JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_PAYMENT_CAPTURE_SUCCESS');
				}
				else
				{
					$return->responsestatus = 'Fail';
					$return->message        = $qbms->errorNumber() . ': ' . $qbms->errorMessage();

					$app->enqueueMessage($return->message, 'fail');

					return $return;
				}
			}

			// Get the transaction as a string which can later be turned back into a transaction object
			$return->transaction_id = $Transaction->serialize();
			$return->responsestatus = 'Success';

			$app->enqueueMessage($return->message, 'success');
		}
		else
		{
			$return->responsestatus = 'Fail';
			$return->message        = $qbms->errorNumber() . ': ' . $qbms->errorMessage();

			$app->enqueueMessage($return->message, 'fail');
		}

		return $return;
	}

	public function onAuthorizeStatus_Quickbook($element, $orderId)
	{
		if ($element != 'quickbook')
		{
			return;
		}

		$authorizeStatus = 'Authorized';

		// If directly captured then set status to 'Captured'
		if ((boolean) $this->params->get('directCapture', 0))
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
	 * This method will be trigger on order status change to capture order ammount.
	 *
	 * @param   string  $element  Name of plugin
	 * @param   array   $data     Order Information array
	 *
	 * @return  object  Success or failed message
	 */
	public function onCapture_PaymentQuickbook($element, $data)
	{
		$transactionId = $data['order_transactionid'];

		if ('' == $transactionId)
		{
			return;
		}

		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();

		// Include Quickbook Library
		require_once __DIR__ . '/library/vendor/autoload.php';

		$return = new stdClass;

		/*
		* If you want to log requests/responses to a database,
		* you can provide a database DSN-style connection string here
		* Example: $dsn = 'mysql://root:@localhost/quickbooks_merchantservice';
		* No, we don't want. We will do it by our own in redSHOP.
		*/
		$dsn = null;

		// As we are using HOSTED model so it is required.
		$pathToCertificate = $this->params->get('certifiedPemFile', null);

		// This is your login ID that Intuit assignes you during the application
		$appLogin         = $this->params->get('appLogin', null);
		$connectionTicket = $this->params->get('connectionTicket');

		// Create an instance of the MerchantService object
		$qbms = new QuickBooks_MerchantService(
			$dsn,
			$pathToCertificate,
			$appLogin,
			$connectionTicket
		);

		// Set Test environment based on backend setting
		$qbms->useTestEnvironment((boolean) $this->params->get('isTest', 1));

		// If you want to see the full XML input/output, you can turn on debug mode
		$qbms->useDebugMode((boolean) $this->params->get('isDebug', 1));

		$Transaction = QuickBooks_MerchantService_Transaction::unserialize($transactionId);

		if ($Transaction = $qbms->capture($Transaction, $data['order_amount']))
		{
			$transactionId = $Transaction->serialize();

			// Update transaction string
			$query = $db->getQuery(true)
					->update($db->qn('#__redshop_order_payment'))
					->set($db->qn('order_payment_trans_id') . ' = ' . $db->q($transactionId))
					->where($db->qn('order_id') . ' = ' . $db->q($data['order_id']));

			// Set the query and execute the update.
			$db->setQuery($query)->execute();

			$return->responsestatus = 'Success';
			$return->message        = JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_PAYMENT_CAPTURE_SUCCESS');

			$app->enqueueMessage($return->message, 'success');
		}
		else
		{
			$return->responsestatus = 'Fail';
			$return->message        = $qbms->errorNumber() . ': ' . $qbms->errorMessage();

			$app->enqueueMessage($return->message, 'error');
		}

		return $return;
	}
}
