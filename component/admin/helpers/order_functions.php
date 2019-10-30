<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.3  Use RedshopHelperOrder instead
 */

defined('_JEXEC') or die;

/**
 * Order helper for backend
 *
 * @since       1.6.0
 *
 * @deprecated  2.0.3  Use RedshopHelperOrder instead
 */
class order_functions
{
	/**
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * Returns the order_functions object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  order_functions  The order_functions object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Truncate tables orders and relatives
	 *
	 * @return      void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::resetOrderId() instead
	 */
	public function resetOrderId()
	{
		RedshopHelperOrder::resetOrderId();
	}

	/**
	 * Get order status title
	 *
	 * @param   string  $order_status_code  Order status code to get title
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderStatusTitle() instead
	 */
	public function getOrderStatusTitle($order_status_code)
	{
		return RedshopHelperOrder::getOrderStatusTitle($order_status_code);
	}

	/**
	 * Update order status
	 *
	 * @param   integer  $order_id   Order ID to update
	 * @param   string   $newstatus  New status
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::updateOrderStatus() instead
	 */
	public function updateOrderStatus($order_id, $newstatus)
	{
		RedshopHelperOrder::updateOrderStatus($order_id, $newstatus);
	}

	/**
	 * Generate parcel
	 *
	 * @param   integer  $orderId  Order ID to generate
	 *
	 * @return  string   'success' or error message
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::generateParcel() instead
	 */
	public function generateParcel($orderId)
	{
		return RedshopHelperOrder::generateParcel($orderId);
	}

	/**
	 * Change Order status
	 *
	 * @param   object  $data  Data to change
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::changeOrderStatus() instead
	 */
	public function changeorderstatus($data)
	{
		RedshopHelperOrder::changeOrderStatus($data);
	}

	/**
	 * Update Order Payment Status
	 *
	 * @param   integer  $order_id   Order ID
	 * @param   string   $newstatus  New status
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::updateOrderPaymentStatus() instead
	 */
	public function updateOrderPaymentStatus($order_id, $newstatus)
	{
		RedshopHelperOrder::updateOrderPaymentStatus($order_id, $newstatus);
	}

	/**
	 * Update order comment
	 *
	 * @param   integer  $order_id  Order ID
	 * @param   string   $comment   New Comment
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::updateOrderComment() instead
	 */
	public function updateOrderComment($order_id, $comment = '')
	{
		RedshopHelperOrder::updateOrderComment($order_id, $comment);
	}

	/**
	 * Update Order Requisition Number
	 *
	 * @param   integer  $order_id            Order ID
	 * @param   string   $requisition_number  Number required
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::updateOrderRequisitionNumber() instead
	 */
	public function updateOrderRequisitionNumber($order_id, $requisition_number = '')
	{
		RedshopHelperOrder::updateOrderRequisitionNumber($order_id, $requisition_number);
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
	 * @deprecated  2.0.3  Use RedshopHelperOrder::updateOrderItemStatus() instead
	 */
	public function updateOrderItemStatus($orderId = 0, $productId = 0, $newStatus = '', $comment = '', $orderItemId = 0)
	{
		RedshopHelperOrder::updateOrderItemStatus($orderId, $productId, $newStatus, $comment, $orderItemId);
	}

	/**
	 * Get order status list
	 *
	 * @deprecated  1.6  Use RedshopHelperOrder::getOrderStatusList() instead
	 *
	 * @return  array  Order status list
	 */
	public function getOrderStatus()
	{
		return RedshopHelperOrder::getOrderStatusList();
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
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getStatusList() instead
	 */
	public function getstatuslist($name = 'statuslist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		return RedshopHelperOrder::getStatusList($name, $selected, $attributes);
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
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getFilterByList() instead
	 */
	public function getFilterbyList($name = 'filterbylist', $selected = 'all', $attributes = ' class="inputbox" size="1" ')
	{
		return RedshopHelperOrder::getFilterByList($name, $selected, $attributes);
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
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getPaymentStatusList() instead
	 */
	public function getpaymentstatuslist($name = 'paymentstatuslist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		return RedshopHelperOrder::getPaymentStatusList($name, $selected, $attributes);
	}

	/**
	 * Update order status and trigger emails based on status.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::updateStatus() instead
	 */
	public function update_status()
	{
		RedshopHelperOrder::updateStatus();
	}

	/**
	 * Get order details
	 *
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderDetails() instead
	 */
	public function getOrderDetails($order_id)
	{
		return RedshopHelperOrder::getOrderDetails($order_id);
	}

	/**
	 * Get list order details
	 *
	 * @param   integer  $orderId  Order ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopEntityOrder::getInstance($orderId)->getItem() instead
	 */
	public function getmultiOrderDetails($orderId)
	{
		return RedshopEntityOrder::getInstance($orderId)->getItem();
	}

	/**
	 * Get User Order Details
	 *
	 * @param   integer  $user_id  User ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getUserOrderDetails() instead
	 */
	public function getUserOrderDetails($user_id = 0)
	{
		return RedshopHelperOrder::getUserOrderDetails($user_id);
	}

	/**
	 * Get order item detail
	 *
	 * @param   integer  $order_id       Order ID
	 * @param   integer  $product_id     Product ID
	 * @param   integer  $order_item_id  Order Item ID
	 *
	 * @return  boolean/object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderItemDetail() instead
	 */
	public function getOrderItemDetail($order_id = 0, $product_id = 0, $order_item_id = 0)
	{
		return RedshopHelperOrder::getOrderItemDetail($order_id, $product_id, $order_item_id);
	}

	/**
	 * Get Order Payment Detail
	 *
	 * @param   integer  $orderId         Order Id
	 * @param   integer  $paymentOrderId  Payment order id
	 *
	 * @deprecated 1.5   Use RedshopHelperOrder::getPaymentInfo or RedshopHelperOrder::getOrderPaymentDetail instead
	 *
	 * @return  array    order payment info
	 */
	public function getOrderPaymentDetail($orderId, $paymentOrderId = 0)
	{
		if (!$paymentOrderId)
		{
			return array(RedshopHelperOrder::getPaymentInfo($orderId));
		}

		return array(RedshopHelperOrder::getOrderPaymentDetail($paymentOrderId));
	}

	/**
	 * Get Order Partial Payment
	 *
	 * @param   integer $orderId Order ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderPartialPayment() instead
	 */
	public function getOrderPartialPayment($orderId)
	{
		return RedshopHelperOrder::getOrderPartialPayment($orderId);
	}

	/**
	 * Get Shipping Method Info
	 *
	 * @param   string $shippingClass Shipping class
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getShippingMethodInfo() instead
	 */
	public function getShippingMethodInfo($shippingClass = '')
	{
		return RedshopHelperOrder::getShippingMethodInfo($shippingClass);
	}

	/**
	 * Get payment method info
	 *
	 * @param   string $paymentMethodClass Payment method class
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getPaymentMethodInfo() instead
	 */
	public function getPaymentMethodInfo($paymentMethodClass = '')
	{
		return RedshopHelperOrder::getPaymentMethodInfo($paymentMethodClass);
	}

	/**
	 * Get billing address
	 *
	 * @param   integer $userId User ID
	 *
	 * @return  mixed
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getBillingAddress() instead
	 */
	public function getBillingAddress($userId = 0)
	{
		return RedshopHelperOrder::getBillingAddress($userId);
	}

	/**
	 * Order Billing User info
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @deprecated 1.6   Use RedshopHelperOrder::getOrderBillingUserInfo($orderId) instead
	 *
	 * @return  object   Order Billing Information object
	 */
	public function getOrderBillingUserInfo($orderId)
	{
		return RedshopHelperOrder::getOrderBillingUserInfo($orderId);
	}

	/**
	 * Get Shipping address
	 *
	 * @param   integer  $userId  User Id
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getShippingAddress() instead
	 */
	public function getShippingAddress($userId = 0)
	{
		return RedshopHelperOrder::getShippingAddress($userId);
	}

	/**
	 * Order Shipping User info
	 *
	 * @param   integer $orderId Order Id
	 *
	 * @deprecated 1.6   Use RedshopHelperOrder::getOrderShippingUserInfo($orderId) instead
	 *
	 * @return  object   Order Shipping Information object
	 */
	public function getOrderShippingUserInfo($orderId)
	{
		return RedshopHelperOrder::getOrderShippingUserInfo($orderId);
	}

	/**
	 * Get User full name
	 *
	 * @param   integer $userId User ID
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getUserFullName() instead
	 */
	public function getUserFullname($userId)
	{
		return RedshopHelperOrder::getUserFullName($userId);
	}

	/**
	 * Get order item accessory detail
	 *
	 * @param   integer $orderItemId Order Item ID
	 *
	 * @return  null/object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderItemAccessoryDetail() instead
	 */
	public function getOrderItemAccessoryDetail($orderItemId = 0)
	{
		return RedshopHelperOrder::getOrderItemAccessoryDetail($orderItemId);
	}

	/**
	 * Get order item attribute detail
	 *
	 * @param   integer $orderItemId     Order Item ID
	 * @param   integer $isAccessory     Is accessory
	 * @param   string  $section         Section text
	 * @param   integer $parentSectionId Parent section ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderItemAttributeDetail() instead
	 */
	public function getOrderItemAttributeDetail($orderItemId = 0, $isAccessory = 0, $section = "attribute", $parentSectionId = 0)
	{
		return RedshopHelperOrder::getOrderItemAttributeDetail($orderItemId, $isAccessory, $section, $parentSectionId);
	}

	/**
	 * Get Order User Field Data
	 *
	 * @param   integer $orderItemId Order Item ID
	 * @param   integer $section     Section ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderUserFieldData() instead
	 */
	public function getOrderUserfieldData($orderItemId = 0, $section = 0)
	{
		return RedshopHelperOrder::getOrderUserFieldData($orderItemId, $section);
	}

	/**
	 * Generate Order Number
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::generateOrderNumber() instead
	 */
	public function generateOrderNumber()
	{
		return RedshopHelperOrder::generateOrderNumber();
	}

	/**
	 * Random Generate Encrypt Key
	 *
	 * @param   string $length Length of string
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use \Redshop\Crypto\Helper\Encrypt::generateCustomRandomEncryptKey() instead
	 */
	public function random_gen_enc_key($length = '30')
	{
		return \Redshop\Crypto\Helper\Encrypt::generateCustomRandomEncryptKey((int) $length);
	}

	/**
	 * Get Country name by 3 characters of country code
	 *
	 * @param   string  $cnt3  Country code
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getCountryName() instead
	 */
	public function getCountryName($cnt3 = "")
	{
		return RedshopHelperOrder::getCountryName($cnt3);
	}

	/**
	 * Get state name
	 *
	 * @param   string  $st3   State code
	 * @param   string  $cnt3  Country code
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getStateName() instead
	 */
	public function getStateName($st3 = "", $cnt3 = "")
	{
		return RedshopHelperOrder::getStateName($st3, $cnt3);
	}

	/**
	 * Send download by email
	 *
	 * @param   integer $orderId Order ID
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::sendDownload() instead
	 */
	public function SendDownload($orderId = 0)
	{
		return RedshopHelperOrder::sendDownload($orderId);
	}

	/**
	 * Get download product
	 *
	 * @param   integer $orderId Order ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getDownloadProduct() instead
	 */
	public function getDownloadProduct($orderId)
	{
		return RedshopHelperOrder::getDownloadProduct($orderId);
	}

	/**
	 * Get download product log
	 *
	 * @param   integer $orderId Order Id
	 * @param   string  $did     Download id
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getDownloadProductLog() instead
	 */
	public function getDownloadProductLog($orderId, $did = '')
	{
		return RedshopHelperOrder::getDownloadProductLog($orderId, $did);
	}

	/**
	 * Get payment parameters
	 *
	 * @param   string  $payment  Payment type
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getParameters() instead
	 */
	public function getparameters($payment)
	{
		return RedshopHelperOrder::getParameters($payment);
	}

	/**
	 * Get payment information
	 *
	 * @param   object  $row   Payment info row
	 * @param   array   $post  payment method class
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getPaymentInformation() instead
	 */
	public function getpaymentinformation($row, $post)
	{
		RedshopHelperOrder::getPaymentInformation($row, $post);
	}

	/**
	 * Get shipping location information
	 *
	 * @param   string  $shippingname  Shipping name
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getShippingLocationInfo() instead
	 */
	public function getshippinglocationinfo($shippingname)
	{
		return RedshopHelperOrder::getShippingLocationInfo($shippingname);
	}

	/**
	 * Generate barcode
	 *
	 * @param   integer  $length      Length
	 * @param   integer  $barcodekey  Key
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3
	 */
	public function barcode_randon_number($length = 12, $barcodekey = 0)
	{
		return null;
	}

	/**
	 * Generate barcode
	 *
	 * @param   integer  $oid      Length
	 * @param   integer  $barcode  Key
	 *
	 * @return  null
	 *
	 * @deprecated  2.0.3
	 */
	public function updatebarcode($oid, $barcode)
	{
		return null;
	}

	/**
	 * Check update Orders
	 *
	 * @param   object  $data  Data to check
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::checkUpdateOrders() instead
	 */
	public function checkupdateordersts($data)
	{
		return RedshopHelperOrder::checkUpdateOrders($data);
	}

	/**
	 * Change order status mail
	 *
	 * @param   integer  $order_id       Order ID
	 * @param   string   $newstatus      New status
	 * @param   string   $order_comment  Order Comment
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::changeOrderStatusMail() instead
	 */
	public function changeOrderStatusMail($order_id, $newstatus, $order_comment = '')
	{
		RedshopHelperOrder::changeOrderStatusMail($order_id, $newstatus, $order_comment);
	}

	/**
	 * Create book invoice
	 *
	 * @param   integer  $order_id      Order ID
	 * @param   string   $order_status  Order status
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::createBookInvoice() instead
	 */
	public function createBookInvoice($order_id, $order_status)
	{
		RedshopHelperOrder::createBookInvoice($order_id, $order_status);
	}

	/**
	 * Create Multi Print Invoice PDF
	 *
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::createMultiPrintInvoicePdf() instead
	 */
	public function createMultiprintInvoicePdf($order_id)
	{
		return RedshopHelperOrder::createMultiPrintInvoicePdf($order_id);
	}

	/**
	 * Method for generate Invoice PDF of specific Order
	 *
	 * @param   int  $orderId  ID of order.
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::generateInvoicePdf() instead
	 */
	public static function generateInvoicePDF($orderId)
	{
		RedshopHelperOrder::generateInvoicePdf($orderId);
	}

	/**
	 * Create PacSoft Label from Order Status Change functions
	 *
	 * @param   integer  $order_id       Order Information ID
	 * @param   string   $order_status   Order Status Code
	 * @param   string   $paymentStatus  Order Payment Status Code
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::createWebPackLabel() instead
	 */
	public function createWebPacklabel($order_id, $order_status, $paymentStatus)
	{
		RedshopHelperOrder::createWebPackLabel($order_id, $order_status, $paymentStatus);
	}

	/**
	 * Order status update
	 *
	 * @param   integer $orderId Order ID
	 * @param   array   $post    Post array
	 *
	 * @return  boolean/mixed
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::orderStatusUpdate() instead
	 */
	public function orderStatusUpdate($orderId, $post = array())
	{
		return RedshopHelperOrder::orderStatusUpdate($orderId, $post);
	}
}
