<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

class order_functions
{
	public $_orderstatuslist = null;

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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::resetOrderId() instead
	 */
	public function resetOrderId()
	{
		return RedshopHelperOrder::resetOrderId();
	}

	/**
	 * Get order status title
	 *
	 * @param   string  $order_status_code  Order status code to get title
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getOrderStatusTitle() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::updateOrderStatus() instead
	 */
	public function updateOrderStatus($order_id, $newstatus)
	{
		return RedshopHelperOrder::updateOrderStatus($order_id, $newstatus);
	}

	/**
	 * Generate parcel
	 *
	 * @param   integer  $orderId  Order ID to generate
	 *
	 * @return  string   'success' or error message
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::generateParcel() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::changeOrderStatus() instead
	 */
	public function changeorderstatus($data)
	{
		return RedshopHelperOrder::changeOrderStatus($data);
	}

	/**
	 * Update Order Payment Status
	 *
	 * @param   integer  $order_id   Order ID
	 * @param   string   $newstatus  New status
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::updateOrderPaymentStatus() instead
	 */
	public function updateOrderPaymentStatus($order_id, $newstatus)
	{
		return RedshopHelperOrder::updateOrderPaymentStatus($order_id, $newstatus);
	}

	/**
	 * Update order comment
	 *
	 * @param   integer  $order_id  Order ID
	 * @param   string   $comment   New Comment
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::updateOrderComment() instead
	 */
	public function updateOrderComment($order_id, $comment = '')
	{
		return RedshopHelperOrder::updateOrderComment($order_id, $comment);
	}

	/**
	 * Update Order Requisition Number
	 *
	 * @param   integer  $order_id            Order ID
	 * @param   string   $requisition_number  Number required
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::updateOrderRequisitionNumber() instead
	 */
	public function updateOrderRequisitionNumber($order_id, $requisition_number = '')
	{
		return RedshopHelperOrder::updateOrderRequisitionNumber($order_id, $requisition_number);
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::updateOrderItemStatus() instead
	 */
	public function updateOrderItemStatus($orderId = 0, $productId = 0, $newStatus = '', $comment = '', $orderItemId = 0)
	{
		return RedshopHelperOrder::updateOrderItemStatus($orderId, $productId, $newStatus, $comment, $orderItemId);
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getStatusList() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getFilterByList() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getPaymentStatusList() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::updateStatus() instead
	 */
	public function update_status()
	{
		return RedshopHelperOrder::updateStatus();
	}

	/**
	 * Get order details
	 *
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getOrderDetails() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getMultiOrderDetails() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getUserOrderDetails() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getOrderItemDetail() instead
	 */
	public function getOrderItemDetail($order_id = 0, $product_id = 0, $order_item_id = 0)
	{
		return RedshopHelperOrder::getOrderItemDetail($order_id, $product_id, $order_item_id);
	}

	/**
	 * Get Order Payment Detail
	 *
	 * @param   integer  $order_id          Order Id
	 * @param   integer  $payment_order_id  Payment order id
	 *
	 * @deprecated 1.5   Use RedshopHelperOrder::getPaymentInfo instead
	 *
	 * @return  array    order payment info
	 */
	public function getOrderPaymentDetail($order_id, $payment_order_id = 0)
	{
		if (!$payment_order_id)
		{
			return array(RedshopHelperOrder::getPaymentInfo($order_id));
		}
		else
		{
			$db = JFactory::getDbo();
			$query = 'SELECT * FROM #__redshop_order_payment WHERE payment_order_id = ' . (int) $payment_order_id;
			$db->setQuery($query);

			return $db->loadObjectlist();
		}
	}

	/**
	 * Get Order Partial Payment
	 *
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getOrderPartialPayment() instead
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
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getShippingMethodInfo() instead
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
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getPaymentMethodInfo() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getBillingAddress() instead
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
	 * @param   integer  $user_id  User Id
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getShippingAddress() instead
	 */
	public function getShippingAddress($user_id = 0)
	{
		return RedshopHelperOrder::getShippingAddress($user_id);
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getUserFullname() instead
	 */
	public function getUserFullname($user_id)
	{
		return RedshopHelperOrder::getUserFullname($user_id);
	}

	/**
	 * Get order item accessory detail
	 *
	 * @param   integer  $order_item_id  Order Item ID
	 *
	 * @return  null/object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getOrderItemAccessoryDetail() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getOrderItemAttributeDetail() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getOrderUserFieldData() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::generateOrderNumber() instead
	 */
	function generateOrderNumber()
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::randomGenerateEncryptKey() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getCountryName() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getStateName() instead
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
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::sendDownload() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getDownloadProduct() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getDownloadProductLog() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getParameters() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getPaymentInformation() instead
	 */
	public function getpaymentinformation($row, $post)
	{
		return RedshopHelperOrder::getPaymentInformation($row, $post);
	}

	/**
	 * Get shipping location information
	 *
	 * @param   string  $shippingname  Shipping name
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::getShippingLocationInfo() instead
	 */
	function getshippinglocationinfo($shippingname)
	{
		return RedshopHelperOrder::getShippingLocationInfo($shippingname);
	}

	/**
	 * Generate barcode
	 *
	 * @param   integer  $lenth       Length
	 * @param   integer  $barcodekey  Key
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function barcode_randon_number($lenth = 12, $barcodekey = 0)
	{
		return "";
	}

	/**
	 * Generate barcode
	 *
	 * @param   integer  $oid      Length
	 * @param   integer  $barcode  Key
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function updatebarcode($oid, $barcode)
	{
		return "";
	}

	/**
	 * Check update Orders
	 *
	 * @param   object  $data  Data to check
	 *
	 * @return  integer
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::checkUpdateOrders() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::changeOrderStatusMail() instead
	 */
	public function changeOrderStatusMail($order_id, $newstatus, $order_comment = '')
	{
		return RedshopHelperOrder::changeOrderStatusMail($order_id, $newstatus, $order_comment);
	}

	/**
	 * Create book invoice
	 *
	 * @param   integer  $order_id      Order ID
	 * @param   string   $order_status  Order status
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::createBookInvoice() instead
	 */
	public function createBookInvoice($order_id, $order_status)
	{
		return RedshopHelperOrder::createBookInvoice($order_id, $order_status);
	}

	/**
	 * Create Multi Print Invoice PDF
	 *
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::createMultiPrintInvoicePdf() instead
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::generateInvoicePdf() instead
	 */
	public static function generateInvoicePDF($orderId)
	{
		return RedshopHelperOrder::generateInvoicePdf($orderId);
	}

	/**
	 * Create PacSoft Label from Order Status Change functions
	 *
	 * @param   integer  $order_id       Order Information ID
	 * @param   string   $order_status   Order Status Code
	 * @param   string   $paymentstatus  Order Payment Status Code
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperOrder::createWebPackLabel() instead
	 */
	public function createWebPacklabel($order_id, $order_status, $paymentstatus)
	{
		return RedshopHelperOrder::createWebPackLabel($order_id, $order_status, $paymentstatus);
	}

	public function orderStatusUpdate($order_id, $post = array())
	{
		$helper = redhelper::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();
		$producthelper = productHelper::getInstance();
		$newstatus = $post['order_status_all'];
		$customer_note = $post['customer_note' . $order_id];
		$isproduct = (isset($post['isproduct'])) ? $post['isproduct'] : 0;
		$product_id = (isset($post['product_id'])) ? $post['product_id'] : 0;
		$paymentstatus = $post['order_paymentstatus' . $order_id];

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		// Add status log...
		$order_log = JTable::getInstance('order_status_log', 'Table');
		$order_log->order_id = $customer_note;
		$order_log->customer_note = $customer_note;
		$order_log->order_status = $newstatus;
		$order_log->date_changed = time();

		if (!$order_log->store())
		{
			return JError::raiseWarning('', $order_log->getError());
		}

		// Changing the status of the order
		$this->updateOrderStatus($order_id, $newstatus);

		// Changing the status of the order
		if (isset($paymentstatus))
		{
			$this->updateOrderPaymentStatus($order_id, $paymentstatus);
		}

		if ($post['isPacsoft'])
		{
			// For Webpack Postdk Label Generation
			$this->createWebPacklabel($order_id, $newstatus, $paymentstatus);
		}

		if (Redshop::getConfig()->get('CLICKATELL_ENABLE'))
		{
			// Changing the status of the order end
			$helper->clickatellSMS($order_id);
		}

		// If changing the status of the order then there item status need to change
		if ($isproduct != 1)
		{
			$this->updateOrderItemStatus($order_id, 0, $newstatus);
		}

		// If order is cancelled then
		if ($newstatus == 'X')
		{
			$orderproducts = $this->getOrderItemDetail($order_id);

			for ($j = 0, $jn = count($orderproducts); $j < $jn; $j++)
			{
				$prodid = $orderproducts[$j]->product_id;
				$prodqty = $orderproducts[$j]->stockroom_quantity;

				// When the order is set to "cancelled",product will return to stock
				$stockroomhelper->manageStockAmount($prodid, $prodqty, $orderproducts[$j]->stockroom_id);
				$producthelper->makeAttributeOrder($orderproducts[$j]->order_item_id, 0, $prodid, 1);
			}
		}
		elseif ($newstatus == 'RT')
		{
			// If any of the item from the order is returuned back then,
			// change the status of whole order and also put back to stock.
			if ($isproduct)
			{
				$orderproductdetail = $this->getOrderItemDetail($order_id, $product_id);
				$prodid             = $orderproductdetail[0]->product_id;

				// Changing the status of the order item to Returned
				$this->updateOrderItemStatus($order_id, $prodid, "RT");

				// Changing the status of the order to Partially Returned
				$this->updateOrderStatus($order_id, "PRT");
			}
		}
		elseif ($newstatus == 'RC')
		{
			// If any of the item from the order is reclamation back then,
			// change the status of whole order and also put back to stock.
			if ($isproduct)
			{
				// Changing the status of the order item to Reclamation
				$this->updateOrderItemStatus($order_id, $product_id, "RC");

				// Changing the status of the order to Partially Reclamation
				$this->updateOrderStatus($order_id, "PRC");
			}
		}
		elseif ($newstatus == 'S')
		{
			if ($isproduct)
			{
				// Changing the status of the order item to Reclamation
				$this->updateOrderItemStatus($order_id, $product_id, "S");

				// Changing the status of the order to Partially Reclamation
				$this->updateOrderStatus($order_id, "PS");
			}
		}

		// Mail to customer of order status change
		$this->changeOrderStatusMail($order_id, $newstatus, $customer_note);
		$this->createBookInvoice($order_id, $newstatus);

		// GENERATE PDF CODE WRITE
		return true;
	}
}
