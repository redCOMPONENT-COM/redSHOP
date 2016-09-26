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

// Load stripe library
require_once dirname(__DIR__) . '/stripe/library/init.php';

class plgRedshop_PaymentStripe extends JPlugin
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
		if ($element != 'stripe')
		{
			return;
		}

		echo RedshopLayoutHelper::render(
			'form',
			array(
				'action' => JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=stripe&orderid=" . $data['order_id'],
				'data'   => $data,
				'params' => $this->params
			),
			dirname(__DIR__) . '/stripe/layouts'
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
	public function onNotifyPaymentStripe($element, $request)
	{
		if ($element != 'stripe')
		{
			return;
		}

		$app         = JFactory::getApplication();
		$orderHelper = order_functions::getInstance();
		$orderId     = $app->input->getInt('orderid');
		$order       = $orderHelper->getOrderDetails($orderId);
		$price       = $order->order_total;
		$values      = new stdClass;

		// Initialize response
		$values->order_id                  = $orderId;
		$values->order_status_code         = $this->params->get('invalid_status', '');
		$values->order_payment_status_code = 'Unpaid';
		$values->log                       = JText::_('PLG_REDSHOP_PAYMENT_STRIPE_ORDER_NOT_PLACED');
		$values->msg                       = JText::_('PLG_REDSHOP_PAYMENT_STRIPE_ORDER_NOT_PLACED');

		// Set Stripe API Key
		\Stripe\Stripe::setApiKey($this->params->get('secretKey'));

		try
		{
			// Change amount
			$charge = \Stripe\Charge::create(
				array(
					"amount"      => round($price * 100),
					"currency"    => Redshop::getConfig()->get('CURRENCY_CODE'),
					"source"      => $app->input->get('stripeToken'),
					"description" => $orderId
				)
			);

			$values->transaction_id = $charge->id;

			// When Transaction Success
			if ($charge->captured)
			{
				$values->order_status_code         = $this->params->get('verify_status', '');
				$values->order_payment_status_code = 'Paid';

				$values->log = JText::_('PLG_REDSHOP_PAYMENT_STRIPE_ORDER_PLACED');
				$values->msg = JText::_('PLG_REDSHOP_PAYMENT_STRIPE_ORDER_PLACED');
			}
		}
		catch (\Stripe\Error\Card $e)
		{
			$app->enqueueMessage($e->getMessage(), 'error');
		}

		return $values;
	}

	/**
	 * Redirecting after payment notify
	 *
	 * @param   string   $name     Name of plugin
	 * @param   integer  $orderId  Order Information Id
	 *
	 * @return  void
	 */
	public function onAfterNotifyPaymentStripe($name, $orderId)
	{
		$app = JFactory::getApplication();
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
	public function onStatus_PaymentStripe($element, $data)
	{
		if ($element != 'stripe')
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

		// Set Stripe API Key
		\Stripe\Stripe::setApiKey($this->params->get('secretKey'));

		$return = new stdClass;

		try
		{
			$ch     = \Stripe\Charge::retrieve($transactionId);
			$refund = $ch->refunds->create();

			// Update transaction string
			$query = $db->getQuery(true)
					->update($db->qn('#__redshop_order_payment'))
					->set($db->qn('order_payment_trans_id') . ' = ' . $db->q($refund->id))
					->where($db->qn('order_id') . ' = ' . $db->q($data['order_id']));

			// Set the query and execute the update.
			$db->setQuery($query)->execute();

			$return->responsestatus = 'Success';
			$return->type           = 'message';
			$return->message = JText::_('PLG_REDSHOP_PAYMENT_STRIPE_REFUND_SUCCESS');
		}
		catch(Exception $e)
		{
			$return->responsestatus = 'Fail';
			$return->message        = $e->getMessage() . ' #' . $e->getCode();
			$return->type           = 'error';
		}

		$app->enqueueMessage($return->message, $return->type);

		return $return;
	}
}
