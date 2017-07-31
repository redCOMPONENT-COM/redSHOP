<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use Redshop\Economic\Economic;

/**
 * Class Redshop Helper for Order
 *
 * @since  1.5
 */
class RedshopHelperOrder
{
	/**
	 * All the published status code
	 *
	 * @var  null
	 */
	protected static $allStatus = null;

	/**
	 * Order Billing user Info
	 *
	 * @var  array
	 */
	protected static $orderBillingInfo = array();

	/**
	 * Order Billing user Extra Field Info
	 *
	 * @var  array
	 */
	protected static $orderExtraFieldData = array();

	/**
	 * Order shipping user Info
	 *
	 * @var  array
	 */
	protected static $orderShippingInfo = array();

	/**
	 * Order status list
	 *
	 * @var    null
	 *
	 * @since  2.0.3
	 */
	public static $orderStatusList = null;

	/**
	 * Billing addresses
	 *
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected static $billingAddresses = array();

	/**
	 * Shipping addresses
	 *
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected static $shippingAddresses = array();

	/**
	 * Shipping methods
	 *
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected static $shippingMethods = array();

	/**
	 * Order items
	 *
	 * @var   array
	 *
	 * @since  2.0.6
	 */
	protected static $orderItems = array();

	/**
	 * Order Products Download Log
	 *
	 * @var   array
	 *
	 * @since  2.0.6
	 */
	protected static $orderProductsDownloadLog = array();

	/**
	 * Order Products Download
	 *
	 * @var   array
	 *
	 * @since  2.0.6
	 */
	protected static $orderProductsDownload = array();

	/**
	 * Get order information from order id.
	 *
	 * @param   integer  $orderId  Order Id
	 * @param   boolean  $force    Force to get order information from DB instead of cache.
	 *
	 * @return  object    Order Information Object
	 *
	 * @deprecated  2.0.6
	 */
	public static function getOrderDetail($orderId, $force = false)
	{
		if (!$orderId)
		{
			return null;
		}

		$order = RedshopEntityOrder::getInstance($orderId);

		if ($force)
		{
			$order->reset();
		}

		return $order->getItem();
	}

	/**
	 * Generate Invoice number in chronological order
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  mixed              Invoice number clean and formatted value
	 */
	public static function generateInvoiceNumber($orderId)
	{
		$db    = JFactory::getDbo();

		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('invoice_number, invoice_number_chrono, order_status, order_payment_status, order_total')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId);

		$db->setQuery($query);

		$orderInfo = $db->loadObject();

		// Don't generate invoice number for free orders if disabled from config
		if ($orderInfo->order_total <= 0 && ! (boolean) Redshop::getConfig()->get('INVOICE_NUMBER_FOR_FREE_ORDER'))
		{
			return false;
		}

		$number          = $orderInfo->invoice_number_chrono;
		$formattedNumber = $orderInfo->invoice_number;

		// Check if number is not set and order status is confirm or number is set and order status is refund.
		if (($number <= 0 && 'C' == $orderInfo->order_status && 'Paid' == $orderInfo->order_payment_status)
			|| ($number > 0 && ('R' == $orderInfo->order_status || 'X' == $orderInfo->order_status)))
		{
			$query = $db->getQuery(true)
				->select('MAX(invoice_number_chrono) as max_invoice_number')
				->from($db->qn('#__redshop_orders'));

			// Set the query and load the result.
			$db->setQuery($query);

			$maxInvoiceNo   = $db->loadResult();

			$firstInvoiceNo = (int) Redshop::getConfig()->get('FIRST_INVOICE_NUMBER');

			// It will apply only for the first number ideally!
			if ($maxInvoiceNo <= $firstInvoiceNo)
			{
				$maxInvoiceNo += $firstInvoiceNo;
			}

			$number = $maxInvoiceNo + 1;

			self::updateInvoiceNumber($number, $orderId);

			$formattedNumber = self::formatInvoiceNumber($number);
		}

		$invoiceNo        = new stdClass;
		$invoiceNo->clean = $number;
		$invoiceNo->value = $formattedNumber;

		return $invoiceNo;
	}

	/**
	 * Format the given invoice number
	 *
	 * @param   integer  $invoiceNo  Order Invoice Number
	 *
	 * @return  string   Formatted invoice number
	 */
	public static function formatInvoiceNumber($invoiceNo)
	{
		if ($invoiceNo == 0)
		{
			return '';
		}

		if (!Redshop::getConfig()->get('REAL_INVOICE_NUMBER_TEMPLATE'))
		{
			return $invoiceNo;
		}

		return self::parseNumberTemplate(
			Redshop::getConfig()->get('REAL_INVOICE_NUMBER_TEMPLATE'),
			$invoiceNo
		);
	}

	/**
	 * Parse Invoice or Order Number template.
	 *
	 * @param   string  $template  Number Template
	 * @param   float   $number    Source number to be replaced
	 *
	 * @return  string  Formatted Invoice Number
	 */
	public static function parseNumberTemplate($template, $number)
	{
		$format = sprintf("%06d", $number);
		$formattedInvoiceNo = str_replace("XXXXXX", $format, $template);
		$formattedInvoiceNo = str_replace("xxxxxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("######", $format, $formattedInvoiceNo);

		$format = sprintf("%05d", $number);
		$formattedInvoiceNo = str_replace("XXXXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxxxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("#####", $format, $formattedInvoiceNo);

		$format = sprintf("%04d", $number);
		$formattedInvoiceNo = str_replace("XXXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("####", $format, $formattedInvoiceNo);

		$format = sprintf("%03d", $number);
		$formattedInvoiceNo = str_replace("XXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("###", $format, $formattedInvoiceNo);

		$format = sprintf("%02d", $number);
		$formattedInvoiceNo = str_replace("XX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("##", $format, $formattedInvoiceNo);

		return $formattedInvoiceNo;
	}

	/**
	 * Update invoice number in database
	 *
	 * @param   integer  $number   Order Invoice Number
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.6
	 */
	public static function updateInvoiceNumber($number, $orderId)
	{
		$order = RedshopEntityOrder::getInstance($orderId);

		if ($order->isValid())
		{
			$order->set('invoice_number_chrono', (int) $number)
				->set('invoice_number', self::formatInvoiceNumber($number))
				->save();
		}
	}

	/**
	 * Get all the order status code information list
	 *
	 * @return  array  Order Status info
	 */
	public static function getOrderStatusList()
	{
		if (!empty(self::$allStatus))
		{
			return self::$allStatus;
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select(
						array(
							$db->qn('order_status_code', 'value'),
							$db->qn('order_status_name', 'text')
						)
					)
					->from($db->qn('#__redshop_order_status'))
					->where($db->qn('published') . ' = ' . $db->q('1'));

		// Set the query and load the result.
		$db->setQuery($query);
		self::$allStatus = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		return self::$allStatus;
	}

	/**
	 * Get Order Payment Information
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  object   Payment Information for orders
	 *
	 * @deprecated  2.0.6
	 */
	public static function getPaymentInfo($orderId)
	{
		$payment = RedshopEntityOrder::getInstance($orderId)->getPayment();

		if (null === $payment)
		{
			return null;
		}

		return $payment->getItem();
	}

	/**
	 * Prepare Order Query
	 *
	 * @param   integer  $orderId  Order Information Id
	 *
	 * @return  object             Query Object
	 *
	 * @deprecated  2.0.6
	 */
	public static function getOrderUserQuery($orderId)
	{
		$db = JFactory::getDbo();

		return $db->getQuery(true)
			->select('*, `user_email` as email')
			->from($db->qn('#__redshop_order_users_info'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId);
	}

	/**
	 * Get Order billing user information
	 *
	 * @param   integer  $orderId  Order Id
	 * @param   boolean  $force    Force get information
	 *
	 * @return  object   Order Billing information object
	 *
	 * @deprecated  2.0.6
	 */
	public static function getOrderBillingUserInfo($orderId, $force = false)
	{
		if (!$orderId)
		{
			return null;
		}

		/** @var RedshopEntityOrder_User $userBilling */
		$userBilling = RedshopEntityOrder::getInstance($orderId)->getBilling();

		if ($force)
		{
			$userBilling->reset()->loadExtraFields();
		}

		return $userBilling->getItem();
	}

	/**
	 * Get Order shipping user information
	 *
	 * @param   integer  $orderId  Order Id
	 * @param   boolean  $force    Order Id
	 *
	 * @return  object   Order Shipping information object
	 *
	 * @deprecated  2.0.6
	 */
	public static function getOrderShippingUserInfo($orderId, $force = false)
	{
		if (!$orderId)
		{
			return null;
		}

		/** @var RedshopEntityOrder_User $userBilling */
		$userBilling = RedshopEntityOrder::getInstance($orderId)->getShipping();

		if ($force)
		{
			$userBilling->reset()->loadExtraFields();
		}

		return $userBilling->getItem();
	}

	/**
	 * Get order Billing extra field information in array
	 *
	 * @param   integer  $orderUserInfoId  Order Info id
	 * @param   string   $section          Section to get information
	 * @param   boolean  $force            Force to get information
	 *
	 * @return  array                      Extra Field name as a key of an array
	 *
	 * @deprecated  2.0.6
	 */
	public static function getOrderExtraFieldsData($orderUserInfoId, $section = 'billing', $force = false)
	{
		$key = $section . '.' . $orderUserInfoId;

		if (array_key_exists($key, self::$orderExtraFieldData) && !$force)
		{
			return self::$orderExtraFieldData[$key];
		}

		$privateSection = extraField::SECTION_PRIVATE_BILLING_ADDRESS;
		$companySection = extraField::SECTION_COMPANY_BILLING_ADDRESS;

		if ('shipping' == $section)
		{
			$privateSection = extraField::SECTION_PRIVATE_SHIPPING_ADDRESS;
			$companySection = extraField::SECTION_COMPANY_SHIPPING_ADDRESS;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('f.name') . ',' . $db->qn('fd.data_txt'))
			->from($db->qn('#__redshop_fields_data', 'fd'))
			->where(
				'('
					. $db->qn('fd.section') . ' = ' . $privateSection
					. ' OR '
					. $db->qn('fd.section') . ' = ' . $companySection
				. ')'
			)
			->where($db->qn('fd.itemid') . ' = ' . (int) $orderUserInfoId);

		$query->leftJoin(
			$db->qn('#__redshop_fields', 'f')
			. ' ON ' . $db->qn('f.id') . '=' . $db->qn('fd.fieldid')
		);

		// Set the query and load the result.
		$fields = $db->setQuery($query)->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		$fieldsData = array();

		if (!empty($fields))
		{
			foreach ($fields as $field)
			{
				$fieldsData[$field->name] = $field->data_txt;
			}
		}

		self::$orderExtraFieldData[$key] = $fieldsData;

		return $fieldsData;
	}

	/**
	 * Get all the items from order
	 *
	 * @param   integer  $orderId  Valid Integer order Id
	 *
	 * @return  array              Order Items
	 *
	 * @deprecated  2.0.6 Use RedshopEntityOrder::getOrderItems instead
	 */
	public static function getItems($orderId)
	{
		if (!$orderId)
		{
			return null;
		}

		return RedshopEntityOrder::getInstance($orderId)->getOrderItems()->toObjects();
	}

	/**
	 * Get all gift card items from order items array
	 *
	 * @param   integer  $orderId  A valid integer Order Id
	 *
	 * @return  array    Contains gift card item.
	 */
	public static function giftCardItems($orderId)
	{
		$orderItems = RedshopEntityOrder::getInstance($orderId)->getOrderItems();

		if (!$orderItems->count())
		{
			return array();
		}

		return array_filter(
			$orderItems->toObjects(), function ($item) {
				return $item->is_giftcard;
			}
		);
	}

	/**
	 * Truncate tables orders and relatives
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function resetOrderId()
	{
		$db = JFactory::getDbo();

		$query = 'TRUNCATE TABLE `#__redshop_orders`';
		$db->setQuery($query);
		$db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_order_item`';
		$db->setQuery($query);
		$db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_order_users_info`';
		$db->setQuery($query);
		$db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_order_status_log`';
		$db->setQuery($query);
		$db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_order_acc_item`';
		$db->setQuery($query);
		$db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_order_attribute_item`';
		$db->setQuery($query);
		$db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_order_payment`';
		$db->setQuery($query);
		$db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_product_download`';
		$db->setQuery($query);
		$db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_product_download_log`';
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Get order status title
	 *
	 * @param   string  $orderStatusCode  Order status code to get title
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function getOrderStatusTitle($orderStatusCode)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('order_status_name'))
			->from($db->qn('#__redshop_order_status'))
			->where($db->qn('order_status_code') . ' = ' . $db->quote($orderStatusCode));
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Update order status
	 *
	 * @param   integer  $orderId    Order ID to update
	 * @param   string   $newStatus  New status
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function updateOrderStatus($orderId, $newStatus)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_orders'))
			->set($db->qn('order_status') . ' = ' . $db->quote($newStatus))
			->set($db->qn('mdate') . ' = ' . (int) time())
			->where($db->qn('order_id') . ' = ' . (int) $orderId);
		$db->setQuery($query);
		$db->execute();

		self::generateInvoiceNumber($orderId);

		$query = $db->getQuery(true)
			->select(
				$db->qn(
					array(
					'e.element', 'op.order_transfee', 'op.order_payment_trans_id', 'op.order_payment_amount', 'op.authorize_status'
					)
				)
			)
			->from($db->qn('#__extensions', 'e'))
			->leftJoin($db->qn('#__redshop_order_payment', 'op') . ' ON ' . $db->qn('op.payment_method_class') . ' = ' . $db->qn('e.element'))
			->where($db->qn('op.order_id') . ' = ' . (int) $orderId)
			->where($db->qn('e.folder') . ' = ' . $db->quote('redshop_payment'));
		$result = $db->setQuery($query, 0, 1)->loadObject();

		$authorizeStatus = $result->authorize_status;

		$paymentMethod   = self::getPaymentMethodInfo($result->element);
		$paymentMethod   = $paymentMethod[0];

		// Getting the order details
		$orderDetail        = self::getOrderDetails($orderId);
		$paymentParams      = new Registry($paymentMethod->params);
		$orderStatusCapture = $paymentParams->get('capture_status', '');
		$orderStatusCode    = $orderStatusCapture;

		if ($orderStatusCapture == $newStatus
			&& ($authorizeStatus == "Authorized" || $authorizeStatus == ""))
		{
			$values["order_number"]        = $orderDetail->order_number;
			$values["order_id"]            = $orderId;
			$values["order_transactionid"] = $result->order_payment_trans_id;
			$values["order_amount"]        = $orderDetail->order_total + $result->order_transfee;
			$values['shippinginfo']        = self::getOrderShippingUserInfo($orderId);
			$values['billinginfo']         = self::getOrderBillingUserInfo($orderId);
			$values["order_userid"]        = $values['billinginfo']->user_id;

			JPluginHelper::importPlugin('redshop_payment');
			$data = RedshopHelperUtility::getDispatcher()->trigger('onCapture_Payment' . $result->element, array($result->element, $values));
			$results = $data[0];

			if (!empty($data))
			{
				$message = $results->message;

				$orderStatusLog = JTable::getInstance('order_status_log', 'Table');
				$orderStatusLog->order_id = $orderId;
				$orderStatusLog->order_status = $orderStatusCode;
				$orderStatusLog->date_changed = time();
				$orderStatusLog->customer_note = $message;
				$orderStatusLog->store();
			}
		}

		if (($newStatus == "X" || $newStatus == "R")
			&& $paymentParams->get('refund', 0) == 1)
		{
			$values["order_number"]        = $orderDetail->order_number;
			$values["order_id"]            = $orderId;
			$values["order_transactionid"] = $result->order_payment_trans_id;
			$values["order_amount"]        = $orderDetail->order_total + $result->order_transfee;
			$values["order_userid"]        = $values['billinginfo']->user_id;

			JPluginHelper::importPlugin('redshop_payment');

			// Get status and refund if capture/cancel if authorize (for quickpay only)
			$data = RedshopHelperUtility::getDispatcher()->trigger('onStatus_Payment' . $result->element, array($result->element, $values));
			$results = $data[0];

			if (!empty($data))
			{
				$message = $results->message;
				$orderStatusLog = JTable::getInstance('order_status_log', 'Table');
				$orderStatusLog->order_id = $orderId;
				$orderStatusLog->order_status = $newStatus;
				$orderStatusLog->date_changed = time();
				$orderStatusLog->customer_note = $message;
				$orderStatusLog->store();
			}
		}
	}

	/**
	 * Generate parcel
	 *
	 * @param   integer  $orderId  Order ID to generate
	 *
	 * @return  string   'success' or error message
	 *
	 * @since   2.0.3
	 */
	public static function generateParcel($orderId)
	{
		$db                        = JFactory::getDbo();
		$orderDetail               = self::getOrderDetails($orderId);
		$productHelper             = productHelper::getInstance();
		$orderProducts             = self::getOrderItemDetail($orderId);
		$billingInfo               = self::getOrderBillingUserInfo($orderId);
		$shippingInfo              = self::getOrderShippingUserInfo($orderId);
		$shippingRateDecryptDetail = RedshopShippingRate::decrypt($orderDetail->ship_method_id);

		// Get Shipping Delivery Type
		$shippingDeliveryType = 1;

		if (isset($shippingRateDecryptDetail[8]) === true)
		{
			$shippingDeliveryType = (int) $shippingRateDecryptDetail[8];
		}

		$query = $db->getQuery(true)
					->select($db->qn('country_2_code'))
					->from($db->qn('#__redshop_country'))
					->where($db->qn('country_3_code') . ' = ' . $db->quote(Redshop::getConfig()->get('SHOP_COUNTRY')));
		$db->setQuery($query);
		$billingInfo->country_code = $db->loadResult();

		$query = $db->getQuery(true)
					->select($db->qn('country_2_code'))
					->from($db->qn('#__redshop_country'))
					->where($db->qn('country_3_code') . ' = ' . $db->quote($shippingInfo->country_code));
		$db->setQuery($query);
		$shippingInfo->country_code = $db->loadResult();

		// For product content
		$totalWeight     = 0;
		$contentProducts = array();
		$qty             = 0;

		for ($c = 0, $cn = count($orderProducts); $c < $cn; $c++)
		{
			$qty += $orderProducts [$c]->product_quantity;
			$contentProducts[] = $orderProducts[$c]->order_item_name;

			// Product Weight
			$query = $db->getQuery(true)
						->select($db->qn('weight'))
						->from($db->qn('#__redshop_product'))
						->where($db->qn('product_id') . ' = ' . (int) $orderProducts [$c]->product_id);
			$db->setQuery($query);
			$weight = $db->loadResult();

			// Accessory Weight
			$orderAccItemData = self::getOrderItemAccessoryDetail($orderProducts[$c]->order_item_id);
			$accWeight = 0;

			if (count($orderAccItemData) > 0)
			{
				for ($a = 0, $an = count($orderAccItemData); $a < $an; $a++)
				{
					$accessoryQuantity = $orderAccItemData[$a]->product_quantity;
					$query = $db->getQuery(true)
								->select($db->qn('weight'))
								->from($db->qn('#__redshop_product'))
								->where($db->qn('product_id') . ' = ' . (int) $orderAccItemData[$a]->product_id);
					$db->setQuery($query);
					$accessoryWeight = $db->loadResult();
					$accWeight += ($accessoryWeight * $accessoryQuantity);
				}
			}

			// Total weight
			$totalWeight += (($weight * $orderProducts [$c]->product_quantity) + $accWeight);
		}

		$unitRatio = $productHelper->getUnitConversation('kg', Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'));

		if ($unitRatio != 0)
		{
			// Converting weight in pounds
			$totalWeight = $totalWeight * $unitRatio;
		}

		if (Redshop::getConfig()->get('SHOW_PRODUCT_DETAIL'))
		{
			$contentProducts       = array_unique($contentProducts);
			$contentProducts       = implode(",", $contentProducts);
			$contentProducts       = mb_convert_encoding($contentProducts, "ISO-8859-1", "UTF-8");
			$contentProductsRemark = substr(mb_convert_encoding($contentProducts, "ISO-8859-1", "UTF-8"), 0, 29);
		}
		else
		{
			$contentProducts       = " ";
			$contentProductsRemark = " ";
		}

		$filter    = JFilterInput::getInstance();

		// Filter name to remove special characters
		// We are using $billingInfo instead $shippingInfo because $shippingInfo stored information of service point not buyer
		$firstName = $filter->clean(
			mb_convert_encoding($billingInfo->firstname, "ISO-8859-1", "UTF-8"),
			'username'
		);
		$lastName  = $filter->clean(
			mb_convert_encoding($billingInfo->lastname, "ISO-8859-1", "UTF-8"),
			'username'
		);
		$fullName  = $firstName . " " . $lastName;

		$address   = mb_convert_encoding($billingInfo->address, "ISO-8859-1", "UTF-8");
		$city      = mb_convert_encoding($billingInfo->city, "ISO-8859-1", "UTF-8");

		if ($billingInfo->is_company)
		{
			$companyName   = mb_convert_encoding($shippingInfo->company_name, "ISO-8859-1", "UTF-8");
			$fProductCode  = "PDKEP";
			$addon         = "<addon adnid='POD'></addon>";
			$finalAddress1 = $companyName;
			$finalAddress2 = $address;
		}
		else
		{
			// Post Danmark MyPack Home
			$fProductCode  = "PDK17";
			$addon         = "<addon adnid='DLV'></addon>";
			$finalAddress1 = $address;
			$finalAddress2 = "";
		}

		// When shipping delivery set to post office don't need to send DLV or POD addon.
		if ($shippingDeliveryType == 0)
		{
			// Post Danmark MyPack Collect
			$fProductCode = "P19DK";
			$addon        = "";
		}

		if (Redshop::getConfig()->get('WEBPACK_ENABLE_EMAIL_TRACK'))
		{
			$addon .= '<addon adnid="NOTEMAIL"></addon>';
		}

		if (Redshop::getConfig()->get('WEBPACK_ENABLE_SMS'))
		{
			$addon .= '<addon adnid="NOTSMS"></addon>';
		}

		// No pickup agent by default
		$agentEle = '';

		// Only when we have store to send parcel - i.e Pickup Location
		if ('' != trim($orderDetail->shop_id))
		{
			// Get shop location stored using postdanmark plugin or other similar plugin.
			$shopLocation = explode('|', $orderDetail->shop_id);

			// Sending shop location id as an agent code.
			$agentEle     = '<val n="agentto">' . $shopLocation[0] . '</val>';

			// PUPOPT is stands for "Optional Service Point".
			$addon        .= '<addon adnid="PUPOPT"></addon>';
		}

		$xmlnew = '<?xml version="1.0" encoding="ISO-8859-1"?>
				<unifaunonline>
				<meta>
				<val n="doorcode">"' . date('Y-m-d H:i') . '"</val>
				</meta>
				<receiver rcvid="' . $shippingInfo->users_info_id . '">
				<val n="name"><![CDATA[' . $fullName . ']]></val>
				<val n="address1"><![CDATA[' . $finalAddress1 . ']]></val>
				<val n="address2"><![CDATA[' . $finalAddress2 . ']]></val>
				<val n="zipcode">' . $billingInfo->zipcode . '</val>
				<val n="city">' . $city . '</val>
				<val n="country">' . $billingInfo->country_code . '</val>
				<val n="contact"><![CDATA[' . $firstName . ']]></val>
				<val n="phone">' . $shippingInfo->phone . '</val>
				<val n="doorcode"/>
				<val n="email">' . $shippingInfo->user_email . '</val>
				<val n="sms">' . $shippingInfo->phone . '</val>
				</receiver>
				<shipment orderno="' . $shippingInfo->order_id . '">
				<val n="from">1</val>
				<val n="to">' . $shippingInfo->users_info_id . '</val>
				<val n="reference">' . $orderDetail->order_number . '</val>
				' . $agentEle . '
				<service srvid="' . $fProductCode . '">
				' . $addon . '
				</service>
				<container type="parcel">
				<val n="copies">1</val>
				<val n="weight">' . $totalWeight . '</val>
				<val n="contents">' . $contentProducts . '</val>
				<val n="packagecode">PC</val>
				</container>
				</shipment>
				</unifaunonline>';

		$postURL = "https://www.pacsoftonline.com/ufoweb/order?session=po_DK"
				. "&user=" . Redshop::getConfig()->get('POSTDK_CUSTOMER_NO')
				. "&pin=" . Redshop::getConfig()->get('POSTDK_CUSTOMER_PASSWORD')
				. "&developerid=000000075"
				. "&type=xml";

		try
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $postURL);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlnew);
			$response = curl_exec($ch);
			curl_close($ch);

			$xmlResponse = JFactory::getXML($response, false);

			if (empty($xmlResponse) || !empty($error))
			{
				return JText::_('LIB_REDSHOP_PACSOFT_ERROR_NO_RESPONSE');
			}

			$xmlResponse = $xmlResponse->val;

			if ('201' === (string) $xmlResponse[1] && 'Created' === (string) $xmlResponse[2])
			{
				// Update current order success entry.
				$query = $db->getQuery(true)
							->update($db->qn('#__redshop_orders'))
							->set($db->qn('order_label_create') . ' = 1')
							->where($db->qn('order_id') . ' = ' . (int) $orderId);

				// Set the query and execute the update.
				$db->setQuery($query);
				$db->execute();

				return "success";
			}
			else
			{
				return (string) $xmlResponse[1] . "-" . (string) $xmlResponse[2] . "-" . (string) $xmlResponse[0];
			}
		}
		catch (Exception $e)
		{
			return $e->getMessage();
		}
	}

	/**
	 * Change Order status
	 *
	 * @param   object  $data  Data to change
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function changeOrderStatus($data)
	{
		$db      = JFactory::getDbo();
		$orderId = $data->order_id;
		$pos     = strpos(JURI::base(), 'plugins');

		if ($pos !== false)
		{
			$explode = explode("plugins", JURI::base());
		}

		$data->order_status_code         = trim($data->order_status_code);
		$data->order_payment_status_code = trim($data->order_payment_status_code);
		$checkUpdateOrders               = self::checkUpdateOrders($data);

		if ($checkUpdateOrders == 0 && $data->order_status_code != "" && $data->order_payment_status_code != "")
		{
			// Order status valid and change the status
			$query = $db->getQuery(true)
						->update($db->qn('#__redshop_orders'))
						->set($db->qn('order_status') . ' = ' . $db->quote($data->order_status_code))
						->set($db->qn('order_payment_status') . ' = ' . $db->quote($data->order_payment_status_code))
						->where($db->qn('order_id') . ' = ' . (int) $orderId);
			$db->setQuery($query);
			$db->execute();

			// Generate Invoice Number
			if ("C" == $data->order_status_code
				&& "Paid" == $data->order_payment_status_code)
			{
				self::sendDownload($orderId);
				self::generateInvoiceNumber($orderId);
			}

			if (!isset($data->transfee))
			{
				$data->transfee = null;
			}

			$query = $db->getQuery(true)
						->update($db->qn('#__redshop_order_payment'))
						->set($db->qn('order_transfee') . ' = ' . $db->quote($data->transfee))
						->set($db->qn('order_payment_trans_id') . ' = ' . $db->quote($data->transaction_id))
						->where($db->qn('order_id') . ' = ' . (int) $orderId);
			$db->setQuery($query);
			$db->execute();

			$query = $db->getQuery(true)
						->insert($db->qn('#__redshop_order_status_log'))
						->columns($db->qn(array('order_status', 'order_payment_status', 'date_changed', 'order_id', 'customer_note')))
						->values(
							implode(',',
								array(
									$db->quote($data->order_status_code),
									$db->quote($data->order_payment_status_code),
									(int) time(),
									(int) $orderId,
									$db->quote($data->log)
								)
							)
						);
			$db->setQuery($query);
			$db->execute();

			// Send status change email only if config is set to Before order mail or Order is not confirmed.
			if (!Redshop::getConfig()->get('ORDER_MAIL_AFTER')
				|| (Redshop::getConfig()->get('ORDER_MAIL_AFTER') && $data->order_status_code != "C"))
			{
				self::changeOrderStatusMail($orderId, $data->order_status_code);
			}

			if ($data->order_payment_status_code == "Paid")
			{
				JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_redshop/models');
				$checkoutModelCheckout = JModelLegacy::getInstance('Checkout', 'RedshopModel');
				$checkoutModelCheckout->sendGiftCard($orderId);

				// Send the Order mail
				$redshopMail = redshopMail::getInstance();

				// Send Order Mail After Payment
				if (Redshop::getConfig()->get('ORDER_MAIL_AFTER') && $data->order_status_code == "C")
				{
					$redshopMail->sendOrderMail($orderId);
				}

				// Send Invoice mail only if order mail is set to before payment.
				elseif (Redshop::getConfig()->get('INVOICE_MAIL_ENABLE'))
				{
					$redshopMail->sendInvoiceMail($orderId);
				}
			}

			// Trigger function on Order Status change
			JPluginHelper::importPlugin('redshop_order');
			RedshopHelperUtility::getDispatcher()->trigger(
				'onAfterOrderStatusUpdate',
				array(
					self::getOrderDetails($orderId),
					$data->order_status_code
				)
			);

			// For Webpack Postdk Label Generation
			self::createWebPackLabel($orderId, $data->order_status_code, $data->order_payment_status_code);
			self::createBookInvoice($orderId, $data->order_status_code);
		}
	}

	/**
	 * Update Order Payment Status
	 *
	 * @param   integer  $orderId    Order ID
	 * @param   string   $newStatus  New status
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 *
	 * @deprecated  2.0.6
	 */
	public static function updateOrderPaymentStatus($orderId, $newStatus)
	{
		$order = RedshopEntityOrder::getInstance($orderId);

		if ($order->isValid())
		{
			$order->set('order_payment_status', $newStatus)
				->set('mdate', time())
				->save();
		}
	}

	/**
	 * Update order comment
	 *
	 * @param   integer  $orderId  Order ID
	 * @param   string   $comment  New Comment
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 *
	 * @deprecated  2.0.6
	 */
	public static function updateOrderComment($orderId, $comment = '')
	{
		$order = RedshopEntityOrder::getInstance($orderId);

		if ($order->isValid())
		{
			$order->set('customer_note', $comment)
				->save();
		}
	}

	/**
	 * Update Order Requisition Number
	 *
	 * @param   integer  $orderId            Order ID
	 * @param   string   $requisitionNumber  Number required
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function updateOrderRequisitionNumber($orderId, $requisitionNumber = '')
	{
		$order = RedshopEntityOrder::getInstance($orderId);

		if (!$order->isValid())
		{
			return;
		}

		$order->set('requisition_number', $requisitionNumber);

		if ($order->save())
		{
			// Economic Integration start for invoice generate and book current invoice
			if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
			{
				Economic::renewInvoiceInEconomic($order->getItem());
			}
		}
	}

	/**
	 * Update Order Item Status
	 *
	 * @param   integer  $orderId      Order id
	 * @param   integer  $productId    Product id
	 * @param   string   $newStatus    New status
	 * @param   string   $comment      Comment
	 * @param   integer  $orderItemId  Order item id
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function updateOrderItemStatus($orderId = 0, $productId = 0, $newStatus = '', $comment = '', $orderItemId = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_order_item'))
			->set($db->qn('order_status') . ' = ' . $db->q($newStatus))
			->where($db->qn('order_id') . ' = ' . (int) $orderId);

		if ($productId != 0)
		{
			$query->set($db->qn('customer_note') . ' = ' . $db->q($comment))
				->where($db->qn('product_id') . ' = ' . (int) $productId);
		}

		if ($orderItemId != 0)
		{
			$query->where($db->qn('order_item_id') . ' = ' . (int) $orderItemId);
		}

		$db->setQuery($query);

		if (!$db->execute())
		{
			JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'error');
		}
	}

	/**
	 * Get status list
	 *
	 * @param   string  $name        Name of status list
	 * @param   string  $selected    Selet status name
	 * @param   string  $attributes  Attributes of html
	 *
	 * @return  string  HTML of status list
	 *
	 * @since   2.0.3
	 */
	public static function getStatusList($name = 'statuslist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		if (!self::$orderStatusList)
		{
			self::$orderStatusList = self::getOrderStatusList();
		}

		$types[]   = JHtml::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_STATUS_LBL') . ' -');
		$types     = array_merge($types, self::$orderStatusList);
		$totStatus = explode(",", $selected);

		return JHtml::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $totStatus);
	}

	/**
	 * Get filter by list
	 *
	 * @param   string  $name        Name of filter by list
	 * @param   string  $selected    Select filter list
	 * @param   string  $attributes  Attributes of HTML
	 *
	 * @return  string  HTML of filter list
	 *
	 * @since   2.0.3
	 */
	public static function getFilterByList($name = 'filterbylist', $selected = 'all', $attributes = ' class="inputbox" size="1" ')
	{
		$filterByList = array(
			'orderid'     => JText::_('COM_REDSHOP_ORDERID'),
			'ordernumber' => JText::_('COM_REDSHOP_ORDERNUMBER'),
			'fullname'    => JText::_('COM_REDSHOP_FULLNAME'),
			'useremail'   => JText::_('COM_REDSHOP_USEREMAIL')
		);

		$types[]   = JHtml::_('select.option', '', 'All');
		$types     = array_merge($types, $filterByList);
		$totStatus = explode(",", $selected);

		return JHtml::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $totStatus);
	}

	/**
	 * Get payment status list
	 *
	 * @param   string  $name        Name of payment status list
	 * @param   string  $selected    Select option
	 * @param   string  $attributes  Attributes of HTML
	 *
	 * @return  string  HTML of payment status list
	 *
	 * @since   2.0.3
	 */
	public static function getPaymentStatusList($name = 'paymentstatuslist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		$types[] = JHtml::_('select.option', '', JText::_('COM_REDSHOP_SELECT_PAYMENT_STATUS'));
		$types[] = JHtml::_('select.option', 'Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PAID'));
		$types[] = JHtml::_('select.option', 'Unpaid', JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID'));
		$types[] = JHtml::_('select.option', 'Partial Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID'));

		return JHtml::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $selected);
	}

	/**
	 * Update order status and trigger emails based on status.
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 *
	 * @throws  Exception
	 */
	public static function updateStatus()
	{
		$app             = JFactory::getApplication();
		$productHelper   = productHelper::getInstance();

		$newStatus       = $app->input->getCmd('status');
		$paymentStatus   = $app->input->getString('order_paymentstatus');
		$return          = $app->input->getCmd('return');

		$customerNote    = $app->input->get('customer_note', array(), 'array');
		$customerNote    = stripslashes($customerNote[0]);

		$oid             = $app->input->get('order_id', array(), 'method', 'array');
		$orderId         = (int) $oid[0];

		$isProduct       = $app->input->getInt('isproduct', 0);
		$productId       = $app->input->getInt('product_id', 0);
		$orderItemId     = $app->input->getInt('order_item_id', 0);

		// Get order detail before processing
		$orderDetail = RedshopEntityOrder::getInstance($orderId);
		$prevOrderStatus = $orderDetail->getItem()->order_status;

		if (isset($paymentStatus))
		{
			self::updateOrderPaymentStatus($orderId, $paymentStatus);
		}

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');
		$orderLog = JTable::getInstance('order_status_log', 'Table');

		if (!$isProduct)
		{
			$data['order_id']             = $orderId;
			$data['order_status']         = $newStatus;
			$data['order_payment_status'] = $paymentStatus;
			$data['date_changed']         = time();
			$data['customer_note']        = $customerNote;

			if (!$orderLog->bind($data))
			{
				JFactory::getApplication()->enqueueMessage($orderLog->getError(), 'error');

				return;
			}

			if (!$orderLog->store())
			{
				throw new Exception($orderLog->getError());
			}

			self::updateOrderComment($orderId, $customerNote);

			$requisitionNumber = $app->input->getString('requisition_number', '');

			if ('' != $requisitionNumber)
			{
				self::updateOrderRequisitionNumber($orderId, $requisitionNumber);
			}

			// Changing the status of the order
			self::updateOrderStatus($orderId, $newStatus);

			// Trigger function on Order Status change
			JPluginHelper::importPlugin('redshop_order');

			RedshopHelperUtility::getDispatcher()->trigger(
				'onAfterOrderStatusUpdate',
				array(
					RedshopEntityOrder::getInstance($orderId)->getItem(),
					$newStatus
				)
			);

			if ($paymentStatus == "Paid")
			{
				JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_redshop/models');
				$checkoutModel = JModelLegacy::getInstance('Checkout', 'RedshopModel');
				$checkoutModel->sendGiftCard($orderId);

				// Send the Order mail
				if (Redshop::getConfig()->get('ORDER_MAIL_AFTER') && $newStatus == 'C')
				{
					RedshopHelperMail::sendOrderMail($orderId);
				}

				elseif (Redshop::getConfig()->get('INVOICE_MAIL_ENABLE'))
				{
					RedshopHelperMail::sendInvoiceMail($orderId);
				}
			}

			self::createWebPackLabel($orderId, $newStatus, $paymentStatus);
		}

		self::updateOrderItemStatus($orderId, $productId, $newStatus, $customerNote, $orderItemId);
		RedshopHelperClickatell::clickatellSMS($orderId);

		switch ($newStatus)
		{
			// Cancel & return
			case 'X';
			case 'R':

				$orderProducts = self::getOrderItemDetail($orderId);

				for ($i = 0, $in = count($orderProducts); $i < $in; $i++)
				{
					$prodid = $orderProducts[$i]->product_id;
					$prodqty = $orderProducts[$i]->stockroom_quantity;

					// Do not process update stock if this order already "returned" before
					if ($prevOrderStatus != 'RT')
					{
						// When the order is set to "cancelled",product will return to stock
						RedshopHelperStockroom::manageStockAmount($prodid, $prodqty, $orderProducts[$i]->stockroom_id);
					}

					$productHelper->makeAttributeOrder($orderProducts[$i]->order_item_id, 0, $prodid, 1);
				}
				break;

			// Returned
			case "RT":

				if ($isProduct)
				{
					// Changing the status of the order item to Returned
					self::updateOrderItemStatus($orderId, $productId, "RT", $customerNote, $orderItemId);

					// Changing the status of the order to Partially Returned
					self::updateOrderStatus($orderId, "PRT");
				}

				break;

			case "RC":

				if ($isProduct)
				{
					// Changing the status of the order item to Reclamation
					self::updateOrderItemStatus($orderId, $productId, "RC", $customerNote, $orderItemId);

					// Changing the status of the order to Partially Reclamation
					self::updateOrderStatus($orderId, "PRC");
				}

				break;

			// Shipped
			case "S":

				if ($isProduct)
				{
					// Changing the status of the order item to Reclamation
					self::updateOrderItemStatus($orderId, $productId, "S", $customerNote, $orderItemId);

					// Changing the status of the order to Partially Reclamation
					self::updateOrderStatus($orderId, "PS");
				}

				break;

			// Completed
			case "C":

				// SensDownload Products
				if ($paymentStatus == "Paid")
				{
					self::sendDownload($orderId);
				}

				break;
		}

		if ($app->input->getCmd('order_sendordermail') == 'true')
		{
			self::changeOrderStatusMail($orderId, $newStatus, $customerNote);
		}

		self::createBookInvoice($orderId, $newStatus);

		$msg       = JText::_('COM_REDSHOP_ORDER_STATUS_SUCCESSFULLY_SAVED_FOR_ORDER_ID') . " " . $orderId;

		$isArchive = ($app->input->getInt('isarchive')) ? '&isarchive=1' : '';

		if ($return == 'order')
		{
			$app->redirect('index.php?option=com_redshop&view=' . $return . '' . $isArchive . '', $msg);
		}
		else
		{
			$tmpl = $app->input->getCmd('tmpl');

			if ('' != $tmpl)
			{
				$app->redirect('index.php?option=com_redshop&view=' . $return . '&cid[]=' . $orderId . '&tmpl=' . $tmpl . '' . $isArchive . '', $msg);
			}
			else
			{
				$app->redirect('index.php?option=com_redshop&view=' . $return . '&cid[]=' . $orderId . '' . $isArchive . '', $msg);
			}
		}
	}

	/**
	 * Get order details
	 *
	 * @param   integer  $orderId  Order ID
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 *
	 * @deprecated  2.0.6
	 */
	public static function getOrderDetails($orderId)
	{
		return self::getOrderDetail($orderId);
	}

	/**
	 * Get list order details
	 *
	 * @param   integer  $orderId  Order ID
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getMultiOrderDetails($orderId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_orders'))
					->where($db->qn('order_id') . ' = ' . (int) $orderId);
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get User Order Details
	 *
	 * @param   integer  $userId  User ID
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getUserOrderDetails($userId = 0)
	{
		$db   = JFactory::getDbo();
		$user = JFactory::getUser();

		if ($userId == 0)
		{
			$userId = $user->id;
		}

		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_orders'))
					->where($db->qn('user_id') . ' = ' . (int) $userId)
					->order($db->qn('order_id'));
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	/**
	 * Get list item of an specific order.
	 *
	 * @param   mixed    $orderId      Order ID
	 * @param   integer  $productId    Product ID
	 * @param   integer  $orderItemId  Order Item ID
	 *
	 * @return  mixed
	 *
	 * @since   2.0.3
	 */
	public static function getOrderItemDetail($orderId = 0, $productId = 0, $orderItemId = 0)
	{
		// Make sure at least one options has been pass.
		if (empty($orderId) && !$productId && !$orderItemId)
		{
			return false;
		}

		$key = $orderId . '_' . $productId . '_' . $orderItemId;

		if (!array_key_exists($key, self::$orderItems))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_order_item'));

			if (!empty($orderId))
			{
				$orderId = explode(',', $orderId);
				$orderId = ArrayHelper::toInteger($orderId);
				$orderId = implode(',', $orderId);

				$query->where($db->qn('order_id') . ' IN (' . $orderId . ')');
			}

			if ($productId != 0)
			{
				$query->where($db->qn('product_id') . ' = ' . (int) $productId);
			}

			if ($orderItemId != 0)
			{
				$query->where($db->qn('order_item_id') . ' = ' . (int) $orderItemId);
			}

			self::$orderItems[$key] = $db->setQuery($query)->loadObjectList();
		}

		return self::$orderItems[$key];
	}

	/**
	 * Get Order Partial Payment
	 *
	 * @param   integer  $orderId  Order ID
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getOrderPartialPayment($orderId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
					->select($db->qn('order_payment_amount'))
					->from($db->qn('#__redshop_order_payment'))
					->where($db->qn('order_id') . ' = ' . (int) $orderId);
		$db->setQuery($query);
		$list = $db->loadObjectlist();

		$spiltPaymentAmount = 0;

		for ($i = 0, $in = count($list); $i < $in; $i++)
		{
			if ($list[$i]->order_payment_amount > 0)
			{
				$spiltPaymentAmount = $list[$i]->order_payment_amount;
			}
		}

		return $spiltPaymentAmount;
	}

	/**
	 * Get Shipping Method Info
	 *
	 * @param   string  $shippingClass  Shipping class
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getShippingMethodInfo($shippingClass = '')
	{
		$key = (!empty($shippingClass)) ? $shippingClass : '0';

		if (!array_key_exists($key, static::$shippingMethods))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__extensions'))
				->where($db->qn('enabled') . ' = ' . $db->quote('1'))
				->where('LOWER(' . $db->qn('folder') . ') = ' . $db->quote('redshop_shipping'))
				->order($db->qn('ordering') . ' ASC');

			if (!empty($shippingClass))
			{
				$query->where($db->qn('element') . ' = ' . $db->quote($shippingClass));
			}

			static::$shippingMethods = $db->setQuery($query)->loadObjectList();
		}

		return static::$shippingMethods;
	}

	/**
	 * Get payment method info
	 *
	 * @param   string   $paymentMethodClass  Payment method class
	 * @param   boolean  $includeDiscover     Include all plugins even not discover install yet
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getPaymentMethodInfo($paymentMethodClass = '', $includeDiscover = true)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__extensions'))
			->where($db->qn('enabled') . ' = ' . $db->quote('1'))
			->where('LOWER(' . $db->qn('folder') . ') = ' . $db->quote('redshop_payment'))
			->order($db->qn('ordering') . ' ASC');

		if ($paymentMethodClass != '')
		{
			$query->where($db->qn('element') . ' = ' . $db->quote($paymentMethodClass));
		}

		if (!$includeDiscover)
		{
			$query->where($db->qn('state') . ' >= 0');
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get billing address
	 *
	 * @param   integer  $userId  User ID
	 *
	 * @return  object            Object data if success. False otherwise.
	 *
	 * @since   2.0.3
	 */
	public static function getBillingAddress($userId = 0)
	{
		if ($userId == 0)
		{
			$user = JFactory::getUser();
			$userId = $user->id;
		}

		if (!$userId)
		{
			return false;
		}

		if (!array_key_exists($userId, static::$billingAddresses))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('*')
				->select('CONCAT(' . $db->qn('firstname') . '," ",' . $db->qn('lastname') . ') AS text')
				->from($db->qn('#__redshop_users_info'))
				->where($db->qn('address_type') . ' = ' . $db->quote('BT'))
				->where($db->qn('user_id') . ' = ' . (int) $userId);

			static::$billingAddresses[$userId] = $db->setQuery($query)->loadObject();
		}

		return static::$billingAddresses[$userId];
	}

	/**
	 * Get Shipping address
	 *
	 * @param   integer  $userId  User Id
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getShippingAddress($userId = 0)
	{
		if ($userId == 0)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		if (!$userId)
		{
			return false;
		}

		if (!array_key_exists($userId, static::$shippingAddresses))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('*')
				->select('CONCAT(' . $db->qn('firstname') . '," ",' . $db->qn('lastname') . ') AS text')
				->from($db->qn('#__redshop_users_info'))
				->where($db->qn('address_type') . ' = ' . $db->quote('ST'))
				->where($db->qn('user_id') . ' = ' . (int) $userId);

			static::$shippingAddresses[$userId] = $db->setQuery($query)->loadObjectList();
		}

		return static::$shippingAddresses[$userId];
	}

	/**
	 * Get User full name
	 *
	 * @param   integer  $userId  User ID
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function getUserFullName($userId)
	{
		$fullName = "";
		$user     = JFactory::getUser();
		$db       = JFactory::getDbo();

		if ($userId == 0)
		{
			$userId = $user->id;
		}

		$query = $db->getQuery(true)
					->select($db->qn(array('firstname', 'lastname')))
					->from($db->qn('#__redshop_users_info'))
					->where($db->qn('address_type') . ' LIKE ' . $db->quote('BT'))
					->where($db->qn('user_id') . ' = ' . (int) $userId);
		$db->setQuery($query);
		$list = $db->loadObject();

		if ($list)
		{
			$fullName = $list->firstname . " " . $list->lastname;
		}
		else
		{
			$query = $db->getQuery(true)
						->select($db->qn('name'))
						->from($db->qn('#__users'))
						->where($db->qn('id') . ' = ' . (int) $userId);
			$db->setQuery($query);
			$list = $db->loadObject();

			if ($list)
			{
				$fullName = $list->name;
			}
		}

		return $fullName;
	}

	/**
	 * Get order item accessory detail
	 *
	 * @param   integer  $orderItemId  Order Item ID
	 *
	 * @return  null/array
	 *
	 * @since   2.0.3
	 */
	public static function getOrderItemAccessoryDetail($orderItemId = 0)
	{
		if (!$orderItemId)
		{
			return null;
		}

		return RedshopEntityOrder_Item::getInstance($orderItemId)->getAccessoryItems()->toObjects();
	}

	/**
	 * Get order item attribute detail
	 *
	 * @param   integer  $orderItemId      Order Item ID
	 * @param   integer  $isAccessory      Is accessory
	 * @param   string   $section          Section text
	 * @param   integer  $parentSectionId  Parent section ID
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getOrderItemAttributeDetail($orderItemId = 0, $isAccessory = 0, $section = "attribute", $parentSectionId = 0)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_order_attribute_item'))
					->where($db->qn('is_accessory_att') . ' = ' . (int) $isAccessory)
					->where($db->qn('section') . ' = ' . $db->quote($section));

		if ($orderItemId != 0)
		{
			$query->where($db->qn('order_item_id') . ' = ' . (int) $orderItemId);
		}

		if ($parentSectionId != 0)
		{
			$query->where($db->qn('parent_section_id') . ' = ' . (int) $parentSectionId);
		}

		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	/**
	 * Get Order User Field Data
	 *
	 * @param   integer  $orderItemId  Order Item ID
	 * @param   integer  $section      Section ID
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getOrderUserFieldData($orderItemId = 0, $section = 0)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('fd.*')
			->select($db->qn(array('f.title', 'f.type', 'f.name')))
			->from($db->qn('#__redshop_fields_data', 'fd'))
			->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('f.id') . ' = ' . $db->qn('fd.fieldid'))
			->where($db->qn('fd.itemid') . ' = ' . (int) $orderItemId)
			->where($db->qn('fd.section') . ' = ' . $db->quote($section));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Generate Order Number
	 *
	 * @return  integer
	 *
	 * @since   2.0.3
	 */
	public static function generateOrderNumber()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
					->select('MAX(' . $db->qn('order_id') . ')')
					->from($db->qn('#__redshop_orders'));
		$db->setQuery($query);
		$maxId = $db->loadResult();

		/*
		 * if Economic Integration is on !!!
		 * We are not using Order Invoice Number Template
		 * Economic Order Number Only Support (int) value.
		 * Invoice Number May be varchar or int.
		 */
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') && JPluginHelper::isEnabled('economic'))
		{
			$query = $db->getQuery(true)
				->select($db->qn('order_number'))
				->from($db->qn('#__redshop_orders'))
				->where($db->qn('order_id') . ' = ' . (int) $maxId);
			$db->setQuery($query);

			$maxOrderNumber = $db->loadResult();
			$maxInvoice     = Economic::getMaxOrderNumberInEconomic();
			$maxId          = max((int) $maxOrderNumber, $maxInvoice);
		}
		elseif (Redshop::getConfig()->get('INVOICE_NUMBER_TEMPLATE'))
		{
			$maxId = ($maxId + Redshop::getConfig()->get('FIRST_INVOICE_NUMBER') + 1);

			return self::parseNumberTemplate(
							Redshop::getConfig()->get('INVOICE_NUMBER_TEMPLATE'),
							$maxId
						);
		}

		return $maxId + 1;
	}

	/**
	 * Random Generate Encrypt Key
	 *
	 * @param   string  $pLength  Length of string
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function randomGenerateEncryptKey($pLength = '30')
	{
		/* Generated a unique order number */
		$charList = "abcdefghijklmnopqrstuvwxyz";
		$charList .= "1234567890123456789012345678901234567890123456789012345678901234567890";

		$random = "";
		srand((double) microtime() * 1000000);

		for ($i = 0; $i < $pLength; $i++)
		{
			$random .= substr($charList, (rand() % (strlen($charList))), 1);
		}

		return $random;
	}

	/**
	 * Get Country name by 3 characters of country code
	 *
	 * @param   string  $cnt3  Country code
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function getCountryName($cnt3 = '')
	{
		if (empty($cnt3))
		{
			return '';
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('country_3_code', 'value'))
			->select($db->qn('country_name', 'text'))
			->select($db->qn('country_jtext'))
			->from($db->qn('#__redshop_country'));

		if ($cnt3 != '')
		{
			$query->where($db->qn('country_3_code') . ' = ' . $db->quote($cnt3));
		}

		$countries = $db->setQuery($query)->loadObjectList();

		$countries = RedshopHelperUtility::convertLanguageString($countries);

		if (count($countries) > 0)
		{
			return $countries[0]->text;
		}

		return '';
	}

	/**
	 * Get state name
	 *
	 * @param   string  $st3   State code
	 * @param   string  $cnt3  Country code
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function getStateName($st3 = "", $cnt3 = "")
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('s.state_name'))
			->from($db->qn('#__redshop_state', 's'))
			->leftJoin($db->qn('#__redshop_country', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('s.country_id'));

		if ($st3 != "")
		{
			$query->where($db->qn('s.state_2_code') . ' = ' . $db->quote($st3));
		}

		if ($cnt3 != "")
		{
			$query->where($db->qn('c.country_3_code') . ' = ' . $db->quote($cnt3));
		}

		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Send download by email
	 *
	 * @param   integer  $orderId  Order ID
	 *
	 * @return  boolean
	 *
	 * @since   2.0.3
	 */
	public static function sendDownload($orderId = 0)
	{
		$config = Redconfiguration::getInstance();
		$app    = JFactory::getApplication();

		// Getting the order status changed template from mail center end
		$mailFrom = $app->get('mailfrom');
		$fromName = $app->get('fromname');

		$mailData    = "";
		$mailSubject = "";
		$mailBcc     = null;
		$mailInfo    = RedshopHelperMail::getMailTemplate(0, "downloadable_product_mail");

		if (count($mailInfo) > 0)
		{
			$mailData = $mailInfo[0]->mail_body;
			$mailSubject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		// Get Downloadable Product
		$rows = self::getDownloadProduct($orderId);

		// There is no downloadable product
		if ($rows === null || count($rows) == 0)
		{
			return false;
		}

		// Getting the order details
		$orderDetail = self::getOrderDetails($orderId);
		$userDetail  = self::getOrderBillingUserInfo($orderId);

		$userFullname = $userDetail->firstname . " " . $userDetail->lastname;
		$userEmail    = $userDetail->email;

		$mailData = str_replace("{fullname}", $userFullname, $mailData);
		$mailData = str_replace("{order_id}", $orderDetail->order_id, $mailData);
		$mailData = str_replace("{order_number}", $orderDetail->order_number, $mailData);
		$mailData = str_replace("{order_date}", $config->convertDateFormat($orderDetail->cdate), $mailData);

		$productStart  = "";
		$productEnd    = "";
		$productMiddle = "";
		$pMiddle       = "";
		$mailFirst     = explode("{product_serial_loop_start}", $mailData);

		if (count($mailFirst) > 1)
		{
			$productStart = $mailFirst[0];
			$mailSec = explode("{product_serial_loop_end}", $mailFirst[1]);

			if (count($mailSec) > 1)
			{
				$productMiddle = $mailSec[0];
				$productEnd    = $mailSec[1];
			}
		}

		foreach ($rows as $row)
		{
			$dataMessage      = $productMiddle;
			$downloadFilename = substr(basename($row->file_name), 11);

			$mailToken = "<a href='" . JUri::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid="
				. $row->download_id . "'>" . $downloadFilename . "</a>";

			$dataMessage = str_replace("{product_serial_number}", $row->product_serial_number, $dataMessage);
			$dataMessage = str_replace("{product_name}", $row->product_name, $dataMessage);
			$dataMessage = str_replace("{token}", $mailToken, $dataMessage);

			$pMiddle .= $dataMessage;
		}

		$mailData = $productStart . $pMiddle . $productEnd;
		$mailBody = $mailData;
		$mailBody = RedshopHelperMail::imgInMail($mailBody);
		$mailSubject = str_replace("{order_number}", $orderDetail->order_number, $mailSubject);

		if ($mailBody && $userEmail != "")
		{
			if (!JFactory::getMailer()->sendMail($mailFrom, $fromName, $userEmail, $mailSubject, $mailBody, 1, null, $mailBcc))
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DOWNLOAD_MAIL_FAIL'), 'error');
			}
		}

		return true;
	}

	/**
	 * Get download product
	 *
	 * @param   integer  $orderId  Order ID
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getDownloadProduct($orderId)
	{
		if (!array_key_exists($orderId, self::$orderProductsDownload))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('pd.*')
				->select($db->qn('product_name'))
				->from($db->qn('#__redshop_product_download', 'pd'))
				->leftJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('pd.product_id') . ' = ' . $db->qn('p.product_id'))
				->where($db->qn('order_id') . ' = ' . (int) $orderId);


			self::$orderProductsDownload[$orderId] = $db->setQuery($query)->loadObjectList();
		}

		return self::$orderProductsDownload[$orderId];
	}

	/**
	 * Get download product log
	 *
	 * @param   integer  $orderId  Order Id
	 * @param   string   $did      Download id
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getDownloadProductLog($orderId, $did = '')
	{
		$key = $orderId . '_' . $did;

		if (!array_key_exists($key, self::$orderProductsDownloadLog))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('pdl.*')
				->select($db->qn(array('pd.order_id', 'pd.product_id', 'pd.file_name')))
				->from($db->qn('#__redshop_product_download_log', 'pdl'))
				->leftJoin($db->qn('#__redshop_product_download', 'pd') . ' ON ' . $db->qn('pd.download_id') . ' = ' . $db->qn('pdl.download_id'))
				->where($db->qn('pd.order_id') . ' = ' . (int) $orderId);

			if ($did != '')
			{
				$query->where($db->qn('pdl.download_id') . ' = ' . $db->quote($did));
			}

			self::$orderProductsDownloadLog[$key] = $db->setQuery($query)->loadObjectList();
		}

		return self::$orderProductsDownloadLog[$key];
	}

	/**
	 * Get payment parameters
	 *
	 * @param   string  $payment  Payment type
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getParameters($payment)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__extensions'))
					->where($db->qn('element') . ' = ' . $db->quote($payment));
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get payment information
	 *
	 * @param   object  $row   Payment info row
	 * @param   array   $post  payment method class
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function getPaymentInformation($row, $post)
	{
		$app       = JFactory::getApplication();
		$redconfig = Redconfiguration::getInstance();

		$pluginParameters = self::getParameters($post['payment_method_class']);
		$paymentInfo      = $pluginParameters[0];
		$paymentParams    = new Registry($paymentInfo->params);

		$isCreditcard = $paymentParams->get('is_creditcard', '');

		$order = self::getOrderDetails($row->order_id);

		if ($userBillingInfo = self::getOrderBillingUserInfo($row->order_id))
		{
			$userBillingInfo->country_2_code = $redconfig->getCountryCode2($userBillingInfo->country_code);
			$userBillingInfo->state_2_code   = $redconfig->getCountryCode2($userBillingInfo->state_code);
		}

		$task = $app->input->getCmd('task');

		if ($shippingAddress = self::getOrderShippingUserInfo($row->order_id))
		{
			$shippingAddress->country_2_code = $redconfig->getCountryCode2($shippingAddress->country_code);
			$shippingAddress->state_2_code   = $redconfig->getCountryCode2($shippingAddress->state_code);
		}

		$values                   = array();
		$values['shippinginfo']   = $shippingAddress;
		$values['billinginfo']    = $userBillingInfo;
		$values['carttotal']      = $order->order_total;
		$values['order_subtotal'] = $order->order_subtotal;
		$values["order_id"]       = $row->order_id;
		$values['payment_plugin'] = $post['payment_method_class'];
		$values['task']           = $task;
		$values['order']          = $order;

		if ($isCreditcard == 0)
		{
			// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
			$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($values['payment_plugin']);

			if ($isBankTransferPaymentType)
			{
				$app->redirect(
					JUri::base() . "index.php?option=com_redshop&view=order_detail&layout=creditcardpayment&plugin="
					. $values['payment_plugin'] . "&order_id=" . $row->order_id
				);
			}

			JPluginHelper::importPlugin('redshop_payment');
			RedshopHelperUtility::getDispatcher()->trigger('onPrePayment', array($values['payment_plugin'], $values));

			$app->redirect(
				JUri::base() . "index.php?option=com_redshop&view=order_detail&task=edit&cid[]=" . $row->order_id
			);
		}
		else
		{
			$app->redirect(
				JUri::base() . "index.php?option=com_redshop&view=order_detail&layout=creditcardpayment&plugin="
				. $values['payment_plugin'] . "&order_id=" . $row->order_id
			);
		}
	}

	/**
	 * Get shipping location information
	 *
	 * @param   string  $shippingName  Shipping name
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getShippingLocationInfo($shippingName)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('shipping_location_info'))
			->from($db->qn('#__redshop_shipping_rate'))
			->where($db->qn('shipping_rate_name') . ' = ' . $db->quote($shippingName));

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Check update Orders
	 *
	 * @param   object  $data  Data to check
	 *
	 * @return  integer
	 *
	 * @since   2.0.3
	 */
	public static function checkUpdateOrders($data)
	{
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' = ' . $db->quote($data->order_status_code))
			->where($db->qn('order_payment_status') . ' = ' . $db->quote($data->order_payment_status_code))
			->where($db->qn('order_id') . ' = ' . (int) $data->order_id);
		$db->setQuery($query);

		if (count($db->loadObjectList()) == 0)
		{
			return 0;
		}

		return 1;
	}

	/**
	 * Change order status mail
	 *
	 * @param   integer  $orderId       Order ID
	 * @param   string   $newStatus     New status
	 * @param   string   $orderComment  Order Comment
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function changeOrderStatusMail($orderId, $newStatus, $orderComment = '')
	{
		$app = JFactory::getApplication();

		$config          = Redconfiguration::getInstance();
		$cartHelper      = rsCarthelper::getInstance();
		$redshopMail     = redshopMail::getInstance();

		// Changes to parse all tags same as order mail end
		$userDetail = self::getOrderBillingUserInfo($orderId);

		$mailFrom     = $app->get('mailfrom');
		$fromName     = $app->get('fromname');
		$mailBcc      = null;
		$mailTemplate = RedshopHelperMail::getMailTemplate(
			0, '', '`mail_section` LIKE "order_status" AND `mail_order_status` LIKE "' . $newStatus . '"'
		);

		if (count($mailTemplate) > 0)
		{
			$mailData    = $mailTemplate[0]->mail_body;
			$mailSubject = $mailTemplate[0]->mail_subject;

			$fieldArray = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_ORDER, 0);

			if (count($fieldArray) > 0)
			{
				for ($i = 0, $in = count($fieldArray); $i < $in; $i++)
				{
					$fieldValueArray = RedshopHelperExtrafields::getSectionFieldDataList(
						$fieldArray[$i]->id, RedshopHelperExtrafields::SECTION_ORDER, $orderId, $userDetail->user_email
					);

					if ($fieldValueArray->data_txt != "")
					{
						$mailData = str_replace('{' . $fieldArray[$i]->name . '}', $fieldValueArray->data_txt, $mailData);
						$mailData = str_replace('{' . $fieldArray[$i]->name . '_lbl}', $fieldArray[$i]->title, $mailData);
					}
					else
					{
						$mailData = str_replace('{' . $fieldArray[$i]->name . '}', "", $mailData);
						$mailData = str_replace('{' . $fieldArray[$i]->name . '_lbl}', "", $mailData);
					}
				}
			}

			if (trim($mailTemplate[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailTemplate[0]->mail_bcc);
			}

			// Changes to parse all tags same as order mail start
			$orderDetail      = self::getOrderDetails($orderId);
			$mailData = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $mailData);
			$mailData = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $mailData);

			$mailData = $cartHelper->replaceOrderTemplate($orderDetail, $mailData, true);

			$arrDiscount     = explode('@', $orderDetail->discount_type);
			$discountType    = '';

			for ($d = 0, $dn = count($arrDiscount); $d < $dn; $d++)
			{
				if ($arrDiscount [$d])
				{
					$arrDiscountType = explode(':', $arrDiscount [$d]);

					if ($arrDiscountType [0] == 'c')
					{
						$discountType .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $arrDiscountType [1] . '<br>';
					}

					if ($arrDiscountType [0] == 'v')
					{
						$discountType .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arrDiscountType [1] . '<br>';
					}
				}
			}

			if (!$discountType)
			{
				$discountType = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
			}

			$search []  = "{discount_type}";
			$replace [] = $discountType;

			// Getting the order status changed template from mail center end
			$mailData = $cartHelper->replaceBillingAddress($mailData, $userDetail);

			// Get ShippingAddress From order Users info
			$shippingAddresses = self::getOrderShippingUserInfo($orderId);

			if (count($shippingAddresses) <= 0)
			{
				$shippingAddresses = $userDetail;
			}

			$mailData = $cartHelper->replaceShippingAddress($mailData, $shippingAddresses);

			$search[]  = "{shopname}";
			$replace[] = Redshop::getConfig()->get('SHOP_NAME');

			$search[]  = "{fullname}";
			$replace[] = $userDetail->firstname . " " . $userDetail->lastname;

			$search[]  = "{email}";
			$replace[] = $userDetail->user_email;

			$search[]  = "{customer_id}";
			$replace[] = $userDetail->users_info_id;

			$search[]  = "{order_id}";
			$replace[] = $orderId;

			$search[]  = "{order_number}";
			$replace[] = $orderDetail->order_number;

			$search[]  = "{order_date}";
			$replace[] = $config->convertDateFormat($orderDetail->cdate);

			$search[]  = "{customer_note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_COMMENT');

			$search[]  = "{customer_note}";
			$replace[] = $orderComment;

			$search[]  = "{order_detail_link_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LBL');

			$orderDetailurl = JUri::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $orderId . '&encr=' . $orderDetail->encr_key;
			$search[]       = "{order_detail_link}";
			$replace[]      = "<a href='" . $orderDetailurl . "'>" . JText::_("COM_REDSHOP_ORDER_DETAIL_LINK_LBL") . "</a>";

			// Todo: Move to the shipping plugin to return track no and track url
			$details = RedshopShippingRate::decrypt($orderDetail->ship_method_id);

			if (count($details) <= 1)
			{
				$details = explode("|", $orderDetail->ship_method_id);
			}

			if ($details[0] == 'plgredshop_shippingdefault_shipping_gls')
			{
				$arrLocationDetails = explode('|', $orderDetail->shop_id);
				$orderDetail->track_no = $arrLocationDetails[0];
			}

			if (strpos($mailData, "{if track_no}") !== false && strpos($mailData, "{track_no end if}") !== false)
			{
				if (empty($orderDetail->track_no))
				{
					$template_pd_sdata = explode('{if track_no}', $mailData);
					$template_pd_edata = explode('{track_no end if}', $template_pd_sdata [1]);
					$mailData          = $template_pd_sdata[0] . $template_pd_edata[1];
				}

				$mailData = str_replace("{if track_no}", '', $mailData);
				$mailData = str_replace("{track_no end if}", '', $mailData);
			}

			$search[] = "{order_track_no}";
			$replace[] = trim($orderDetail->track_no);

			$order_trackURL = 'http://www.pacsoftonline.com/ext.po.dk.dk.track?key=' . Redshop::getConfig()->get('POSTDK_CUSTOMER_NO') . '&order=' . $orderId;
			$search[] = "{order_track_url}";
			$replace[] = "<a href='" . $order_trackURL . "'>" . JText::_("COM_REDSHOP_TRACK_LINK_LBL") . "</a>";

			$mailBody = str_replace($search, $replace, $mailData);
			$mailBody = $redshopMail->imginmail($mailBody);
			$mailSubject = str_replace($search, $replace, $mailSubject);

			if ('' != $userDetail->thirdparty_email && $mailBody)
			{
				JFactory::getMailer()->sendMail(
					$mailFrom,
					$fromName,
					$userDetail->thirdparty_email,
					$mailSubject,
					$mailBody,
					1,
					null
				);
			}

			if ('' != $userDetail->user_email && $mailBody)
			{
				JFactory::getMailer()->sendMail(
					$mailFrom,
					$fromName,
					$userDetail->user_email,
					$mailSubject,
					$mailBody,
					1,
					null,
					$mailBcc
				);
			}
		}
	}

	/**
	 * Create book invoice
	 *
	 * @param   integer  $orderId      Order ID
	 * @param   string   $orderStatus  Order status
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function createBookInvoice($orderId, $orderStatus)
	{
		// Economic Integration start for invoice generate and book current invoice
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') != 1)
		{
			if (Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2 && $orderStatus == Redshop::getConfig()->get('BOOKING_ORDER_STATUS'))
			{
				$paymentInfo  = self::getPaymentInfo($orderId);
				$economicData = array();

				if (!empty($paymentInfo))
				{
					$paymentName = $paymentInfo->payment_method_class;
					$paymentArr  = explode("rs_payment_", $paymentInfo->payment_method_class);

					if (count($paymentArr) > 0)
					{
						$paymentName = $paymentArr[1];
					}

					$economicData['economic_payment_method'] = $paymentName;
					$paymentMethod = self::getPaymentMethodInfo($paymentInfo->payment_method_class);

					if (count($paymentMethod) > 0)
					{
						$paymentParams = new Registry($paymentMethod[0]->params);
						$economicData['economic_payment_terms_id'] = $paymentParams->get('economic_payment_terms_id');
						$economicData['economic_design_layout']    = $paymentParams->get('economic_design_layout');
						$economicData['economic_is_creditcard']    = $paymentParams->get('is_creditcard');
					}
				}

				Economic::createInvoiceInEconomic($orderId, $economicData);
			}

			$bookInvoicePdf = Economic::bookInvoiceInEconomic($orderId, Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT'));

			if (JFile::exists($bookInvoicePdf))
			{
				RedshopHelperMail::sendEconomicBookInvoiceMail($orderId, $bookInvoicePdf);
			}
		}
	}

	/**
	 * Create Multi Print Invoice PDF
	 *
	 * @param   array  $orderIds  Order ID
	 *
	 * @return  string            File name of generated pdf.
	 *
	 * @since   2.0.3
	 */
	public static function createMultiPrintInvoicePdf($orderIds)
	{
		return RedshopHelperMail::createMultiprintInvoicePdf($orderIds);
	}

	/**
	 * Method for generate Invoice PDF of specific Order
	 *
	 * @param   int      $orderId  ID of order.
	 * @param   string   $code     Code when generate PDF.
	 * @param   boolean  $isEmail  Is generate for use in Email?
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function generateInvoicePdf($orderId, $code = 'F', $isEmail = false)
	{
		if (!$orderId)
		{
			return;
		}

		$plugins = JPluginHelper::getPlugin('redshop_pdf');

		if (empty($plugins))
		{
			return;
		}

		$cartHelper    = rsCarthelper::getInstance();
		$orderDetail   = self::getOrderDetails($orderId);
		$orderTemplate = RedshopHelperTemplate::getTemplate('order_print');

		if (count($orderTemplate) > 0 && $orderTemplate[0]->template_desc != "")
		{
			$message = $orderTemplate[0]->template_desc;
		}
		else
		{
			$message = '<table style="width: 100%;" border="0" cellpadding="5" cellspacing="0">
				<tbody><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody>
				<tr style="background-color: #cccccc;"><th align="left">{order_information_lbl}{print}</th></tr><tr></tr
				><tr><td>{order_id_lbl} : {order_id}</td></tr><tr><td>{order_number_lbl} : {order_number}</td></tr><tr>
				<td>{order_date_lbl} : {order_date}</td></tr><tr><td>{order_status_lbl} : {order_status}</td></tr><tr>
				<td>{shipping_method_lbl} : {shipping_method} : {shipping_rate_name}</td></tr><tr><td>{payment_lbl} : {payment_method}</td>
				</tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
				<tbody><tr style="background-color: #cccccc;"><th align="left">{billing_address_information_lbl}</th>
				</tr><tr></tr><tr><td>{billing_address}</td></tr></tbody></table></td></tr><tr><td colspan="2">
				<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody><tr style="background-color: #cccccc;">
				<th align="left">{shipping_address_info_lbl}</th></tr><tr></tr><tr><td>{shipping_address}</td></tr></tbody>
				</table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
				<tbody><tr style="background-color: #cccccc;"><th align="left">{order_detail_lbl}</th></tr><tr></tr><tr><td>
				<table style="width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody><tr><td>{product_name_lbl}</td><td>{note_lbl}</td>
				<td>{price_lbl}</td><td>{quantity_lbl}</td><td align="right">Total Price</td></tr>{product_loop_start}<tr>
				<td><p>{product_name}<br />{product_attribute}{product_accessory}{product_userfields}</p></td>
				<td>{product_wrapper}{product_thumb_image}</td><td>{product_price}</td><td>{product_quantity}</td>
				<td align="right">{product_total_price}</td></tr>{product_loop_end}</tbody></table></td></tr><tr>
				<td></td></tr><tr><td><table style="width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
				<tr align="left"><td align="left"><strong>{order_subtotal_lbl} : </strong></td><td align="right">{order_subtotal}</td>
				</tr>{if vat}<tr align="left"><td align="left"><strong>{vat_lbl} : </strong></td><td align="right">{order_tax}</td>
				</tr>{vat end if}{if discount}<tr align="left"><td align="left"><strong>{discount_lbl} : </strong></td>
				<td align="right">{order_discount}</td></tr>{discount end if}<tr align="left"><td align="left">
				<strong>{shipping_lbl} : </strong></td><td align="right">{order_shipping}</td></tr><tr align="left">
				<td colspan="2" align="left"><hr /></td></tr><tr align="left"><td align="left"><strong>{total_lbl} :</strong>
				</td><td align="right">{order_total}</td></tr><tr align="left"><td colspan="2" align="left"><hr /><br />
				 <hr /></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>';
		}

		$print_tag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT') . "'>"
			. "<img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT') . "' title='"
			. JText::_('COM_REDSHOP_PRINT') . "' /></a>";

		$message = str_replace("{print}", $print_tag, $message);
		$message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
		$message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);

		$message = $cartHelper->replaceOrderTemplate($orderDetail, $message, true);

		JPluginHelper::importPlugin('redshop_pdf');
		RedshopHelperUtility::getDispatcher()->trigger('onRedshopOrderCreateInvoicePdf', array($orderId, $message, $code, $isEmail));
	}

	/**
	 * Create PacSoft Label from Order Status Change functions
	 *
	 * @param   integer  $orderId        Order Information ID
	 * @param   string   $orderStatus    Order Status Code
	 * @param   string   $paymentStatus  Order Payment Status Code
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function createWebPackLabel($orderId, $orderStatus, $paymentStatus)
	{
		// If PacSoft is not enable then return
		if (!Redshop::getConfig()->get('POSTDK_INTEGRATION'))
		{
			return;
		}

		// If auto generation is disable then return
		if (!Redshop::getConfig()->get('AUTO_GENERATE_LABEL'))
		{
			return;
		}

		// Only Execute this function for selected status match
		if ($orderStatus == Redshop::getConfig()->get('GENERATE_LABEL_ON_STATUS') && $paymentStatus == "Paid")
		{
			$orderDetails   = self::getOrderDetails($orderId);
			$details        = RedshopShippingRate::decrypt($orderDetails->ship_method_id);

			$shippingParams = new Registry(
								JPluginHelper::getPlugin(
									'redshop_shipping',
									str_replace(
										'plgredshop_shipping',
										'',
										strtolower($details[0])
									)
								)->params
							);

			// Checking 'plgredshop_shippingdefault_shipping' to support backward compatibility
			$allowPacsoftLabel = ($details[0] === 'plgredshop_shippingdefault_shipping' || (boolean) $shippingParams->get('allowPacsoftLabel'));

			if ($allowPacsoftLabel && !$orderDetails->order_label_create)
			{
				$generateLabel = self::generateParcel($orderId);

				if ($generateLabel != "success")
				{
					JFactory::getApplication()->enqueueMessage($generateLabel, 'error');
				}
			}
		}
	}

	/**
	 * Order status update
	 *
	 * @param   integer  $orderId  Order ID
	 * @param   array    $post     Post array
	 *
	 * @return  boolean/mixed
	 *
	 * @since   2.0.3
	 */
	public static function orderStatusUpdate($orderId, $post = array())
	{
		$productHelper = productHelper::getInstance();
		$newStatus     = $post['mass_change_order_status'];
		$customerNote  = $post['customer_note' . $orderId];
		$isProduct     = (isset($post['isproduct'])) ? $post['isproduct'] : 0;
		$productId     = (isset($post['product_id'])) ? $post['product_id'] : 0;
		$paymentStatus = $post['mass_change_payment_status'];

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		// Add status log...
		$orderLog                = JTable::getInstance('order_status_log', 'Table');
		$orderLog->order_id      = $customerNote;
		$orderLog->customer_note = $customerNote;
		$orderLog->order_status  = $newStatus;
		$orderLog->date_changed  = time();

		if (!$orderLog->store())
		{
			return JError::raiseWarning('', $orderLog->getError());
		}

		// Changing the status of the order
		self::updateOrderStatus($orderId, $newStatus);

		// Changing the status of the order
		if (isset($paymentStatus))
		{
			self::updateOrderPaymentStatus($orderId, $paymentStatus);
		}

		if ($post['isPacsoft'])
		{
			// For Webpack Postdk Label Generation
			self::createWebPackLabel($orderId, $newStatus, $paymentStatus);
		}

		if (Redshop::getConfig()->get('CLICKATELL_ENABLE'))
		{
			// Changing the status of the order end
			RedshopHelperClickatell::clickatellSMS($orderId);
		}

		// If changing the status of the order then there item status need to change
		if ($isProduct != 1)
		{
			self::updateOrderItemStatus($orderId, 0, $newStatus);
		}

		// If order is cancelled then
		if ($newStatus == 'X')
		{
			$orderProducts = self::getOrderItemDetail($orderId);

			for ($j = 0, $jn = count($orderProducts); $j < $jn; $j++)
			{
				$prodid  = $orderProducts[$j]->product_id;
				$prodqty = $orderProducts[$j]->stockroom_quantity;

				// When the order is set to "cancelled",product will return to stock
				RedshopHelperStockroom::manageStockAmount($prodid, $prodqty, $orderProducts[$j]->stockroom_id);
				$productHelper->makeAttributeOrder($orderProducts[$j]->order_item_id, 0, $prodid, 1);
			}
		}
		elseif ($newStatus == 'RT')
		{
			// If any of the item from the order is returuned back then,
			// change the status of whole order and also put back to stock.
			if ($isProduct)
			{
				$orderProductDetail = self::getOrderItemDetail($orderId, $productId);
				$prodid             = $orderProductDetail[0]->product_id;

				// Changing the status of the order item to Returned
				self::updateOrderItemStatus($orderId, $prodid, "RT");

				// Changing the status of the order to Partially Returned
				self::updateOrderStatus($orderId, "PRT");
			}
		}
		elseif ($newStatus == 'RC')
		{
			// If any of the item from the order is reclamation back then,
			// change the status of whole order and also put back to stock.
			if ($isProduct)
			{
				// Changing the status of the order item to Reclamation
				self::updateOrderItemStatus($orderId, $productId, "RC");

				// Changing the status of the order to Partially Reclamation
				self::updateOrderStatus($orderId, "PRC");
			}
		}
		elseif ($newStatus == 'S')
		{
			if ($isProduct)
			{
				// Changing the status of the order item to Reclamation
				self::updateOrderItemStatus($orderId, $productId, "S");

				// Changing the status of the order to Partially Reclamation
				self::updateOrderStatus($orderId, "PS");
			}
		}

		// Mail to customer of order status change
		if ($post['mass_mail_sending'] == 1)
		{
			self::changeOrderStatusMail($orderId, $newStatus, $customerNote);
		}

		self::createBookInvoice($orderId, $newStatus);

		// GENERATE PDF CODE WRITE
		return true;
	}

	/**
	 * Get Order Payment Detail
	 *
	 * @param   int  $orderPaymentId  Payment order id
	 *
	 * @return  object                Order payment info
	 *
	 * @since   2.0.3
	 *
	 * @deprecated  2.0.6
	 */
	public static function getOrderPaymentDetail($orderPaymentId = 0)
	{
		return RedshopEntityOrder_Payment::getInstance($orderPaymentId)->getItem();
	}
}
