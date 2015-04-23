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

class plgRedshop_PaymentKlarna extends JPlugin
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
	public function onPrePayment($element, $data)
	{
		if ($element != 'klarna')
		{
			return;
		}

		$app = JFactory::getApplication();

		define('JPATH_PLUGIN_KLARNA_LIBRARY', JPATH_SITE . '/plugins/redshop_payment/klarna/library/klarna/');

		require_once JPATH_PLUGIN_KLARNA_LIBRARY . 'Klarna.php';

		// Dependencies from http://phpxmlrpc.sourceforge.net/
		require_once JPATH_PLUGIN_KLARNA_LIBRARY . '/transport/xmlrpc-3.0.0.beta/lib/xmlrpc.inc';
		require_once JPATH_PLUGIN_KLARNA_LIBRARY . '/transport/xmlrpc-3.0.0.beta/lib/xmlrpc_wrappers.inc';

		$k = new Klarna();

		$k->config(
			$this->params->get('merchantId'),
			$this->params->get('sharedSecret'),
			KlarnaCountry::fromCode($this->params->get('purchaseCountry')),
			KlarnaLanguage::fromCode($this->params->get('purchaseLanguage')),
			KlarnaCurrency::fromCode(strtolower($this->params->get('purchaseCurrency'))),
			Klarna::BETA,         // Server
			'json',               // PClass storage
			'./pclasses.json'     // PClass storage URI path
		);

		$orderHelper = new order_functions;
		$orderItems = $orderHelper->getOrderItemDetail($data['order_id']);

		foreach ($orderItems as $orderItem)
		{
			$k->addArticle(
				$orderItem->product_quantity,                      // Quantity
				$orderItem->order_item_sku,             // Article number
				$orderItem->order_item_name,      // Article name/title
				$orderItem->product_final_price,                 // Price
				0,                     // 25% VAT @todo need to fix with dynamic vat
				0,                      // Discount
				KlarnaFlags::INC_VAT    // Price is including VAT.
			);
		}

		$k->addArticle(
			1,
			'',
			'Shipping fee',
			$data['order']->order_shipping,
			0,  // @todo  need to show vat
			0,
			KlarnaFlags::INC_VAT | KlarnaFlags::IS_SHIPMENT
		);

		$k->addArticle(
			1,
			'',
			'Discount Line',
			$data['order']->order_discount,
			0,  // @todo  need to show vat
			0,
			KlarnaFlags::INC_VAT
		);

		/*
		 @todo Not sure what is this for now.
		$k->addArticle(
			1,
			"",
			"Handling fee",
			11.5,
			25,
			0,
			KlarnaFlags::INC_VAT | KlarnaFlags::IS_HANDLING
		);*/

		$k->setAddress(
			KlarnaFlags::IS_BILLING,
			new KlarnaAddr(
				$data['billinginfo']->user_email,
				'',
				$data['billinginfo']->phone,
				$data['billinginfo']->firstname,
				$data['billinginfo']->lastname,
				'',
				$data['billinginfo']->address,
				$data['billinginfo']->zipcode,
				$data['billinginfo']->city,
				KlarnaCountry::fromCode($data['billinginfo']->country_2_code),
				null,                         // House number (AT/DE/NL only)
				null                          // House extension (NL only)
			)
		);

		$k->setAddress(
			KlarnaFlags::IS_SHIPPING,
			new KlarnaAddr(
				$data['shippinginfo']->user_email,
				'',
				$data['shippinginfo']->phone,
				$data['shippinginfo']->firstname,
				$data['shippinginfo']->lastname,
				'',
				$data['shippinginfo']->address,
				$data['shippinginfo']->zipcode,
				$data['shippinginfo']->city,
				KlarnaCountry::fromCode($data['shippinginfo']->country_2_code),
				null,                         // House number (AT/DE/NL only)
				null                          // House extension (NL only)
			)
		);

		try
		{
			$result = $k->reserveAmount(
				'0801363945', // PNO (Date of birth DD-MM-YYYY for AT/DE/NL)  @todo
				null, // KlarnaFlags::MALE, KlarnaFlags::FEMALE (AT/DE/NL only) @todo
				-1,   // Automatically calculate and reserve the cart total amount
				KlarnaFlags::NO_FLAG,
				KlarnaPClass::INVOICE
			);

			$reservation = $result[0];
			$status      = $result[1];

			$values                 = new stdClass;
			$values->order_id       = $data['order_id'];
			$values->transaction_id = $reservation;

			if ($status == KlarnaFlags::ACCEPTED)
			{
				$values->order_status_code         = $this->params->get('verify_status', '');
				$values->order_payment_status_code = 'Paid';

				$values->log = JText::_('PLG_KLARNA_ORDER_PLACED');
				$values->msg = JText::_('PLG_KLARNA_ORDER_PLACED');
			}
			else
			{
				$values->order_status_code         = $this->params->get('invalid_status', '');
				$values->order_payment_status_code = 'Unpaid';

				$values->log = JText::_('PLG_KLARNA_NOT_PLACED');
				$values->msg = JText::_('PLG_KLARNA_NOT_PLACED');
			}

			// Change order status based on Klarna status response
			$this->klarnaOrderReservationUpdate($values);

			/*// $status is KlarnaFlags::PENDING or KlarnaFlags::ACCEPTED.
			echo "OK: reservation {$reservation} - order status {$status}\n";
			die;*/
		}
		catch(Exception $e)
		{
			$values                            = new stdClass;
			$values->order_id                  = $data['order_id'];
			$values->transaction_id            = null;
			$values->order_status_code         = $this->params->get('invalid_status', '');
			$values->order_payment_status_code = 'Unpaid';

			$values->log                       = $e->getMessage() . ' #' . $e->getCode();
			$values->msg                       = $e->getMessage();

			// Change order status based on Klarna status response
			$this->klarnaOrderReservationUpdate($values);
		}
	}

	/**
	 * Notify payment function
	 *
	 * @return  void
	 */
	public function klarnaOrderReservationUpdate($values)
	{
		$app         = JFactory::getApplication();
		$orderHelper = new order_functions;
		$orderId     = $values->order_id;

		$orderHelper->changeorderstatus($values);

		RedshopModel::getInstance('order_detail', 'RedshopModel')->resetcart();

		$app->redirect(
			JRoute::_('index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=' . $app->input->getInt('Itemid') . '&oid=' . $orderId),
			$values->msg
		);
	}

	/**
	 * Handle Payment notification from Epay
	 *
	 * @param   string  $element  Plugin Name
	 * @param   array   $request  Request data sent from Epay
	 *
	 * @return  object  Status Object
	 */
	public function onNotifyPaymentKlarna($element, $request)
	{
		if ($element != 'klarna')
		{
			return false;
		}

		print_r($_SERVER);
		die;
	}
}
