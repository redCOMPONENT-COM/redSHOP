<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.3  Use RedshopHelperOrder instead
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

/**
 * Order helper for backend
 *
 * @since       2.0.3
 *
 * @deprecated  2.0.3  Use RedshopHelperOrder instead
 */
class order_functions
{
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
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getMultiOrderDetails() instead
	 */
	public function getmultiOrderDetails($order_id)
	{
		return RedshopHelperOrder::getMultiOrderDetails($order_id);
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

		return RedshopHelperOrder::getOrderPaymentDetail($paymentOrderId);
	}

	/**
	 * Get Order Partial Payment
	 *
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderPartialPayment() instead
	 */
	public function getOrderPartialPayment($order_id)
	{
		return RedshopHelperOrder::getOrderPartialPayment($order_id);
	}

	/**
	 * Get Shipping Method Info
	 *
	 * @param   string  $shipping_class  Shipping class
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getShippingMethodInfo() instead
	 */
	public function getShippingMethodInfo($shipping_class = '')
	{
		return RedshopHelperOrder::getShippingMethodInfo($shipping_class);
	}

	/**
	 * Get payment method info
	 *
	 * @param   string  $payment_method_class  Payment method class
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getPaymentMethodInfo() instead
	 */
	public function getPaymentMethodInfo($payment_method_class = '')
	{
		return RedshopHelperOrder::getPaymentMethodInfo($payment_method_class);
	}

	/**
	 * Get billing address
	 *
	 * @param   integer  $user_id  User ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getBillingAddress() instead
	 */
	public function getBillingAddress($user_id = 0)
	{
		return RedshopHelperOrder::getBillingAddress($user_id);
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
	 * @param   integer  $order_id  Order Id
	 *
	 * @deprecated 1.6   Use RedshopHelperOrder::getOrderShippingUserInfo($orderId) instead
	 *
	 * @return  object   Order Shipping Information object
	 */
	public function getOrderShippingUserInfo($order_id)
	{
		return RedshopHelperOrder::getOrderShippingUserInfo($order_id);
	}

	/**
	 * Get User full name
	 *
	 * @param   integer  $user_id  User ID
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getUserFullName() instead
	 */
	public function getUserFullname($user_id)
	{
		return RedshopHelperOrder::getUserFullName($user_id);
	}

	/**
	 * Get order item accessory detail
	 *
	 * @param   integer  $order_item_id  Order Item ID
	 *
	 * @return  null/object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderItemAccessoryDetail() instead
	 */
	public function getOrderItemAccessoryDetail($order_item_id = 0)
	{
		return RedshopHelperOrder::getOrderItemAccessoryDetail($order_item_id);
	}

	/**
	 * Get order item attribute detail
	 *
	 * @param   integer  $order_item_id      Order Item ID
	 * @param   integer  $is_accessory       Is accessory
	 * @param   string   $section            Section text
	 * @param   integer  $parent_section_id  Parent section ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderItemAttributeDetail() instead
	 */
	public function getOrderItemAttributeDetail($order_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		return RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, $section, $parent_section_id);
	}

	/**
	 * Get Order User Field Data
	 *
	 * @param   integer  $order_item_id  Order Item ID
	 * @param   integer  $section        Section ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getOrderUserFieldData() instead
	 */
	public function getOrderUserfieldData($order_item_id = 0, $section = 0)
	{
		return RedshopHelperOrder::getOrderUserFieldData($order_item_id, $section);
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
	 * @param   string  $p_length  Length of string
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::randomGenerateEncryptKey() instead
	 */
	public function random_gen_enc_key($p_length = '30')
	{
		return RedshopHelperOrder::randomGenerateEncryptKey($p_length);
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
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::sendDownload() instead
	 */
	public function SendDownload($order_id = 0)
	{
		return RedshopHelperOrder::sendDownload($order_id);
	}

	/**
	 * Get download product
	 *
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getDownloadProduct() instead
	 */
	public function getDownloadProduct($order_id)
	{
		return RedshopHelperOrder::getDownloadProduct($order_id);
	}

	/**
	 * Get download product log
	 *
	 * @param   integer  $order_id  Order Id
	 * @param   string   $did       Download id
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::getDownloadProductLog() instead
	 */
	public function getDownloadProductLog($order_id, $did = '')
	{
		return RedshopHelperOrder::getDownloadProductLog($order_id, $did);
	}

	/**
	 * Get payment parameters
	 *
	 * @param   string  $payment  Payment type
	 *
	 * @return  object
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
	 * @param   integer  $order_id  Order ID
	 * @param   array    $post      Post array
	 *
	 * @return  boolean/mixed
	 *
	 * @deprecated  2.0.3  Use RedshopHelperOrder::orderStatusUpdate() instead
	 */
	public function orderStatusUpdate($order_id, $post = array())
	{
		return RedshopHelperOrder::orderStatusUpdate($order_id, $post);
	}
}
