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
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   11.1
	 */
	public function __construct(&$subject, $config = array())
	{
		define('JPATH_PLUGIN_KLARNA_LIBRARY', JPATH_SITE . '/plugins/redshop_payment/klarna/library/klarna/');

		require_once JPATH_PLUGIN_KLARNA_LIBRARY . 'Klarna.php';

		// Dependencies from http://phpxmlrpc.sourceforge.net/
		require_once JPATH_PLUGIN_KLARNA_LIBRARY . '/transport/xmlrpc-3.0.0.beta/lib/xmlrpc.inc';
		require_once JPATH_PLUGIN_KLARNA_LIBRARY . '/transport/xmlrpc-3.0.0.beta/lib/xmlrpc_wrappers.inc';

		parent::__construct($subject, $config);
	}

	/**
	 * This method will be triggered on before placing order to reserve amount in klarna.
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

		$orderHelper = order_functions::getInstance();
		$extraField  = extraField::getInstance();
		$k           = new Klarna;

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

		$orderItems = $orderHelper->getOrderItemDetail($data['order_id']);

		foreach ($orderItems as $orderItem)
		{
			$vatInPercentage = 100 * (($orderItem->product_item_price / $orderItem->product_item_price_excl_vat) - 1);

			$productName = strip_tags($orderItem->order_item_name . " " . $orderItem->product_attribute . " " . $orderItem->product_accessory);

			$k->addArticle(
				$orderItem->product_quantity,
				$orderItem->order_item_sku,
				$productName,
				$orderItem->product_item_price,
				$vatInPercentage,
				0, // Discount will be added as a new line in order
				KlarnaFlags::INC_VAT
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

		if ($data['order']->order_discount > 0)
		{
			$k->addArticle(
				1,
				'',
				'Discount Line',
				$data['order']->order_discount,
				0,  // @todo  need to show vat
				0,
				KlarnaFlags::INC_VAT
			);
		}

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
				mb_convert_encoding($data['billinginfo']->address, 'iso-8859-1'),
				$data['billinginfo']->zipcode,
				$data['billinginfo']->city,
				KlarnaCountry::fromCode($data['billinginfo']->country_2_code),
				null,  // House number (AT/DE/NL only)
				null   // House extension (NL only)
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
				mb_convert_encoding($data['shippinginfo']->address, 'iso-8859-1'),
				$data['shippinginfo']->zipcode,
				$data['shippinginfo']->city,
				KlarnaCountry::fromCode($data['shippinginfo']->country_2_code),
				null, // House number (AT/DE/NL only)
				null  // House extension (NL only)
			)
		);

		// Set redSHOP Order Identifier. We can always search using klarna reference number too.
		$k->setEstoreInfo(
			$data['order_id'],
			$data['order']->order_number
		);

		try
		{
			// Collect PNO Information
			$pnoInfo = $extraField->getSectionFieldDataList(
				$this->params->get('privatePNO'),
				extraField::SECTION_PRIVATE_BILLING_ADDRESS,
				$data['billinginfo']->users_info_id
			);

			$dateOfBirth = $extraField->getSectionFieldDataList(
				$this->params->get('privateDOB'),
				extraField::SECTION_PRIVATE_BILLING_ADDRESS,
				$data['billinginfo']->users_info_id
			);

			$gender = $extraField->getSectionFieldDataList(
				$this->params->get('privateGender'),
				extraField::SECTION_PRIVATE_BILLING_ADDRESS,
				$data['billinginfo']->users_info_id
			);

			if ((int) $data['billinginfo']->is_company)
			{
				$pnoInfo = $extraField->getSectionFieldDataList(
					$this->params->get('companyPNO'),
					extraField::SECTION_COMPANY_BILLING_ADDRESS,
					$data['billinginfo']->users_info_id
				);

				$dateOfBirth = $extraField->getSectionFieldDataList(
					$this->params->get('companyDOB'),
					extraField::SECTION_COMPANY_BILLING_ADDRESS,
					$data['billinginfo']->users_info_id
				);

				$gender = $extraField->getSectionFieldDataList(
					$this->params->get('companyGender'),
					extraField::SECTION_COMPANY_BILLING_ADDRESS,
					$data['billinginfo']->users_info_id
				);
			}

			$data['billinginfo']->pno    = (count($pnoInfo) > 0) ? $pnoInfo->data_txt : '';
			$data['billinginfo']->gender = '';

			if (in_array($data['billinginfo']->country_2_code, array('AT', 'DE', 'NL')))
			{
				$data['billinginfo']->pno    = (count($dateOfBirth) > 0) ? str_replace('-', '', $dateOfBirth->data_txt) : '';

				if (count($gender) > 0)
				{
					$data['billinginfo']->gender = KlarnaFlags::FEMALE;

					if ('m' == $dateOfBirth->data_txt)
					{
						$data['billinginfo']->gender = KlarnaFlags::MALE;
					}
				}
			}

			$paymentInfo = $orderHelper->getOrderPaymentDetail($data['order_id']);

			// Reserve amount only for new orders.
			if ('' == trim($paymentInfo[0]->order_payment_trans_id))
			{
				$result = $k->reserveAmount(
					$data['billinginfo']->pno,
					$data['billinginfo']->gender,
					-1,   // Automatically calculate and reserve the cart total amount
					KlarnaFlags::NO_FLAG,
					KlarnaPClass::INVOICE
				);

				$reservation = $result[0];
				$status      = $result[1];

				$values                 = new stdClass;
				$values->order_id       = $data['order_id'];
				$values->transaction_id = $reservation;

				if ($status == KlarnaFlags::ACCEPTED || $status == KlarnaFlags::PENDING)
				{
					$values->order_status_code         = $this->params->get('verify_status', '');
					$values->order_payment_status_code = 'Paid';
					$values->log                       = JText::_('PLG_KLARNA_ORDER_PLACED');
					$values->msg                       = JText::_('PLG_KLARNA_ORDER_PLACED');

					if ($status == KlarnaFlags::PENDING)
					{
						$values->order_payment_status_code = 'Unpaid';
						$values->log                       = JText::_('PLG_KLARNA_ORDER_PENDING_STATUS_APPROVED');
						$values->msg                       = JText::_('PLG_KLARNA_ORDER_PENDING_STATUS_APPROVED');
					}
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
			}

			// Update order if transaction placed already.
			else
			{
				$k->update($paymentInfo[0]->order_payment_trans_id);

				$app = JFactory::getApplication();
				$app->redirect(
					JRoute::_('index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=' . $app->input->getInt('Itemid') . '&oid=' . $data['order_id'])
				);
			}
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
		$orderHelper = order_functions::getInstance();
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
	 * This evet is not needed to verify the status, we are doing in different way for Klarna.
	 * This method is only to ignore.
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
	}

	/**
	 * This method will be trigger on order status change to capture order ammount.
	 *
	 * @param   string  $element  Name of plugin
	 * @param   array   $data     Order Information array
	 *
	 * @return  object  Success or failed message
	 */
	public function onCapture_PaymentKlarna($element, $data)
	{
		$transactionId = $data['order_transactionid'];

		if ('' == $transactionId)
		{
			return;
		}

		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();
		$k   = new Klarna;

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

		$return = new stdClass;

		try
		{
			$result = $k->activate(
				$transactionId,
				null,    // OCR Number
				KlarnaFlags::RSRV_SEND_BY_EMAIL
			);

			// For optional arguments, flags, partial activations and so on, refer to the documentation.
			// See Klarna::setActivateInfo

			// "ok" or "no_risk"
			$risk = $result[0];
			$invNo = $result[1];

			if ('ok' == $risk || 'no_risk' == $risk)
			{
				// Update transaction string
				$query = $db->getQuery(true)
						->update($db->qn('#__redshop_order_payment'))
						->set($db->qn('order_payment_trans_id') . ' = ' . $db->q($invNo))
						->where($db->qn('order_id') . ' = ' . $db->q($data['order_id']));

				// Set the query and execute the update.
				$db->setQuery($query)->execute();

				$return->responsestatus = 'Success';
				$return->message        = JText::_('PLG_REDSHOP_PAYMENT_KLARNA_PAYMENT_CAPTURE_SUCCESS');
				$return->type           = 'message';
			}
			else
			{
				$return->responsestatus = 'Fail';
				$return->message        = JText::_('PLG_REDSHOP_PAYMENT_KLARNA_PAYMENT_CAPTURE_FAIL');
				$return->type           = 'error';
			}
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

	/**
	 * Refund amount on cancel order
	 *
	 * @param   string  $element  Plugin Name
	 * @param   array   $data     Order Transaction information
	 *
	 * @return  object  Return status information
	 */
	public function onStatus_PaymentKlarna($element, $data)
	{
		if ($element != 'klarna')
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
		$k   = new Klarna;

		$k->config(
			$this->params->get('merchantId'),
			$this->params->get('sharedSecret'),
			KlarnaCountry::fromCode($this->params->get('purchaseCountry')),
			KlarnaLanguage::fromCode($this->params->get('purchaseLanguage')),
			KlarnaCurrency::fromCode(strtolower($this->params->get('purchaseCurrency'))),
			Klarna::BETA, // Server
			'json',
			'./pclasses.json'
		);

		$return = new stdClass;

		try
		{
			$k->creditInvoice($transactionId);

			$return->responsestatus = 'Success';
			$return->type           = 'message';
			$return->message = JText::_('PLG_REDSHOP_PAYMENT_KLARNA_PAYMENT_REFUND_SUCCESS');
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
