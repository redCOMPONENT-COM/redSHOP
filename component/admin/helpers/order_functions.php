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

	public function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element` = " . $db->quote($payment);
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function getpaymentinformation($row, $post)
	{
		$app = JFactory::getApplication();
		$redconfig = Redconfiguration::getInstance();

		$plugin_parameters = $this->getparameters($post['payment_method_class']);
		$paymentinfo = $plugin_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$is_creditcard = $paymentparams->get('is_creditcard', '');

		$order = $this->getOrderDetails($row->order_id);

		if ($userbillinginfo = RedshopHelperOrder::getOrderBillingUserInfo($row->order_id))
		{
			$userbillinginfo->country_2_code = $redconfig->getCountryCode2($userbillinginfo->country_code);
			$userbillinginfo->state_2_code = $redconfig->getCountryCode2($userbillinginfo->state_code);
		}

		$task = JRequest::getVar('task');

		if ($shippingaddress = RedshopHelperOrder::getOrderShippingUserInfo($row->order_id))
		{
			$shippingaddress->country_2_code = $redconfig->getCountryCode2($shippingaddress->country_code);
			$shippingaddress->state_2_code = $redconfig->getCountryCode2($shippingaddress->state_code);
		}

		$values = array();
		$values['shippinginfo'] = $shippingaddress;
		$values['billinginfo'] = $userbillinginfo;
		$values['carttotal'] = $order->order_total;
		$values['order_subtotal'] = $order->order_subtotal;
		$values["order_id"] = $row->order_id;
		$values['payment_plugin'] = $post['payment_method_class'];
		$values['task'] = $task;
		$values['order'] = $order;

		if ($is_creditcard == 0)
		{
			// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
			$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($values['payment_plugin']);

			if ($isBankTransferPaymentType)
			{
				$app->redirect(JURI::base() . "index.php?option=com_redshop&view=order_detail&layout=creditcardpayment&plugin=" . $values['payment_plugin'] . "&order_id=" . $row->order_id);
			}

			JPluginHelper::importPlugin('redshop_payment');
			JDispatcher::getInstance()->trigger('onPrePayment', array($values['payment_plugin'], $values));

			$app->redirect(
				JURI::base()
				. "index.php?option=com_redshop&view=order_detail&task=edit&cid[]="
				. $row->order_id
			);
		}
		else
		{
			$app->redirect(JURI::base() . "index.php?option=com_redshop&view=order_detail&layout=creditcardpayment&plugin=" . $values['payment_plugin'] . "&order_id=" . $row->order_id);

		}
	}

	function getshippinglocationinfo($shippingname)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT shipping_location_info FROM #__redshop_shipping_rate WHERE shipping_rate_name = " . $db->quote($shippingname);
		$db->setQuery($sql);
		$shippingloc = $db->loadObjectList();

		return $shippingloc;
	}

	public function barcode_randon_number($lenth = 12, $barcodekey = 0)
	{
		$mainhelper = redshopMail::getInstance();
		$redTemplate = Redtemplate::getInstance();

		$ordermail = $mainhelper->getMailtemplate(0, "order");
		$ordermailbody = $ordermail[0]->mail_body;

		$invoicemail = $mainhelper->getMailtemplate(0, "invoice_mail");
		$invoicemailbody = $invoicemail[0]->mail_body;

		$receipttemp = $redTemplate->getTemplate('order_receipt');
		$receipttempbody = $receipttemp[0]->template_desc;

		$rand_barcode = "";
		return $rand_barcode;
	}

	public function updatebarcode($oid, $barcode)
	{
		$db = JFactory::getDbo();
		$barcodequery = 'UPDATE #__redshop_orders SET barcode = ' . $db->quote($barcode) . ' WHERE order_id = ' . (int) $oid;
		$db->setQuery($barcodequery);
		$db->execute();
	}

	public function checkupdateordersts($data)
	{
		$res = 1;
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__redshop_orders " . "WHERE order_status = " . $db->quote($data->order_status_code)
			. " AND order_payment_status = " . $db->quote($data->order_payment_status_code) . " AND order_id = " . (int) $data->order_id;
		$db->setQuery($query);
		$order_payment = $db->loadObjectList();

		if (count($order_payment) == 0)
		{
			$res = 0;
		}

		return $res;
	}

	public function changeOrderStatusMail($order_id, $newstatus, $order_comment = '')
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$config          = Redconfiguration::getInstance();
		$carthelper      = rsCarthelper::getInstance();
		$order_functions = order_functions::getInstance();
		$redshopMail     = redshopMail::getInstance();

		$MailFrom = $app->getCfg('mailfrom');
		$FromName = $app->getCfg('fromname');
		$mailbcc = null;
		$mailtemplate = $redshopMail->getMailtemplate(0, '', 'mail_section LIKE "order_status" AND mail_order_status LIKE ' . $db->quote($newstatus) . ' ');

		if (count($mailtemplate) > 0)
		{
			$maildata = $mailtemplate[0]->mail_body;
			$mailsubject = $mailtemplate[0]->mail_subject;

			if (trim($mailtemplate[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailtemplate[0]->mail_bcc);
			}

			// Getting the order details
			$orderdetail = $this->getOrderDetails($order_id);
			$barcode_code = $orderdetail->barcode;

			// Changes to parse all tags same as order mail start
			$row = $order_functions->getOrderDetails($order_id);
			$maildata = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $maildata);
			$maildata = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $maildata);

			$maildata = $carthelper->replaceOrderTemplate($row, $maildata);

			$arr_discount_type = array();
			$arr_discount = explode('@', $row->discount_type);
			$discount_type = '';

			for ($d = 0, $dn = count($arr_discount); $d < $dn; $d++)
			{
				if ($arr_discount [$d])
				{
					$arr_discount_type = explode(':', $arr_discount [$d]);

					if ($arr_discount_type [0] == 'c')
					{
						$discount_type .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $arr_discount_type [1] . '<br>';
					}

					if ($arr_discount_type [0] == 'v')
					{
						$discount_type .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arr_discount_type [1] . '<br>';
					}
				}
			}

			if (!$discount_type)
			{
				$discount_type = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
			}

			$search [] = "{discount_type}";
			$replace [] = $discount_type;

			// Changes to parse all tags same as order mail end
			$userdetail = RedshopHelperOrder::getOrderBillingUserInfo($order_id);

			// Getting the order status changed template from mail center end
			$maildata = $carthelper->replaceBillingAddress($maildata, $userdetail);

			// Get ShippingAddress From order Users info
			$shippingaddresses = RedshopHelperOrder::getOrderShippingUserInfo($order_id);

			if (count($shippingaddresses) <= 0)
			{
				$shippingaddresses = $userdetail;
			}

			$maildata = $carthelper->replaceShippingAddress($maildata, $shippingaddresses);

			$search[] = "{shopname}";
			$replace[] = Redshop::getConfig()->get('SHOP_NAME');

			$search[] = "{fullname}";
			$replace[] = $userdetail->firstname . " " . $userdetail->lastname;

			$search[] = "{customer_id}";
			$replace[] = $userdetail->users_info_id;

			$search[] = "{order_id}";
			$replace[] = $order_id;

			$search[] = "{order_number}";
			$replace[] = $orderdetail->order_number;

			$search[] = "{order_date}";
			$replace[] = $config->convertDateFormat($orderdetail->cdate);

			$search[] = "{customer_note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_COMMENT');

			$search[] = "{customer_note}";
			$replace[] = $order_comment;

			$search[] = "{order_detail_link_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LBL');

			$orderdetailurl = JURI::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $order_id . '&encr=' . $orderdetail->encr_key;
			$search[] = "{order_detail_link}";
			$replace[] = "<a href='" . $orderdetailurl . "'>" . JText::_("COM_REDSHOP_ORDER_DETAIL_LINK_LBL") . "</a>";

			$details = RedshopShippingRate::decrypt($orderdetail->ship_method_id);

			if (count($details) <= 1)
			{
				$details = explode("|", $orderdetail->ship_method_id);
			}

			$shopLocation = $orderdetail->shop_id;

			if ($details[0] != 'plgredshop_shippingdefault_shipping_gls')
			{
				$shopLocation = '';
			}

			$arrLocationDetails = explode('|', $shopLocation);
			$orderdetail->track_no = $arrLocationDetails[0];

			$search[] = "{order_track_no}";
			$replace[] = trim($orderdetail->track_no);

			$order_trackURL = 'http://www.pacsoftonline.com/ext.po.dk.dk.track?key=' . Redshop::getConfig()->get('POSTDK_CUSTOMER_NO') . '&order=' . $order_id;
			$search[] = "{order_track_url}";
			$replace[] = "<a href='" . $order_trackURL . "'>" . JText::_("COM_REDSHOP_TRACK_LINK_LBL") . "</a>";

			$mailbody = str_replace($search, $replace, $maildata);
			$mailbody = $redshopMail->imginmail($mailbody);
			$mailsubject = str_replace($search, $replace, $mailsubject);

			if ('' != $userdetail->thirdparty_email && $mailbody)
			{
				JFactory::getMailer()->sendMail(
					$MailFrom,
					$FromName,
					$userdetail->thirdparty_email,
					$mailsubject,
					$mailbody,
					1,
					null
				);
			}

			if ('' != $userdetail->user_email && $mailbody)
			{
				JFactory::getMailer()->sendMail(
					$MailFrom,
					$FromName,
					$userdetail->user_email,
					$mailsubject,
					$mailbody,
					1,
					null,
					$mailbcc
				);
			}
		}
	}

	public function createBookInvoice($order_id, $order_status)
	{
		// Economic Integration start for invoice generate and book current invoice
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') != 1)
		{
			$economic = economic::getInstance();

			if (Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2 && $order_status == Redshop::getConfig()->get('BOOKING_ORDER_STATUS'))
			{
				$paymentInfo = $this->getOrderPaymentDetail($order_id);

				if (count($paymentInfo) > 0)
				{
					$payment_name = $paymentInfo[0]->payment_method_class;
					$paymentArr = explode("rs_payment_", $paymentInfo[0]->payment_method_class);

					if (count($paymentArr) > 0)
					{
						$payment_name = $paymentArr[1];
					}

					$economicdata['economic_payment_method'] = $payment_name;
					$paymentmethod = $this->getPaymentMethodInfo($paymentInfo[0]->payment_method_class);

					if (count($paymentmethod) > 0)
					{
						$paymentparams = new JRegistry($paymentmethod[0]->params);
						$economicdata['economic_payment_terms_id'] = $paymentparams->get('economic_payment_terms_id');
						$economicdata['economic_design_layout'] = $paymentparams->get('economic_design_layout');
						$economicdata['economic_is_creditcard'] = $paymentparams->get('is_creditcard');
					}
				}

				$economic->createInvoiceInEconomic($order_id, $economicdata);
			}

			$bookinvoicepdf = $economic->bookInvoiceInEconomic($order_id, Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT'));

			if (is_file($bookinvoicepdf))
			{
				$redshopMail = redshopMail::getInstance();
				$redshopMail->sendEconomicBookInvoiceMail($order_id, $bookinvoicepdf);
			}
		}
	}

	public function createMultiprintInvoicePdf($order_id)
	{
		$invoice = "";
		$redshopMail = redshopMail::getInstance();

		$invoice = $redshopMail->createMultiprintInvoicePdf($order_id);

		return $invoice;
	}

	/**
	 * Method for generate Invoice PDF of specific Order
	 *
	 * @param   int  $orderId  ID of order.
	 *
	 * @return  void
	 */
	public static function generateInvoicePDF($orderId)
	{
		if (!$orderId)
		{
			return;
		}

		$redTemplate = Redtemplate::getInstance();
		$pdfObj      = RedshopHelperPdf::getInstance();
		$cartHelper  = rsCarthelper::getInstance();
		$pdfObj->SetTitle('Invoice ' . $orderId);

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		// Changed font to support Unicode Characters - Specially Polish Characters
		$font = 'times';
		$pdfObj->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdfObj->setHeaderFont(array($font, '', 8));

		// Set font
		$pdfObj->SetFont($font, "", 6);

		$orderDetail   = self::getOrderDetails($orderId);
		$orderTemplate = $redTemplate->getTemplate("order_print");

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

		$pdfObj->AddPage();
		$pdfObj->writeHTML($message);

		$invoicePdf = 'invoice-' . round(microtime(true) * 1000);
		$invoiceFolder = JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $orderId;

		// Delete currently order invoice
		if (JFolder::exists($invoiceFolder))
		{
			JFolder::delete($invoiceFolder);
		}

		JFolder::create($invoiceFolder);

		ob_end_clean();
		$pdfObj->Output($invoiceFolder . '/' . $invoicePdf . ".pdf", "FI");
	}

	/**
	 * Create PacSoft Label from Order Status Change functions
	 *
	 * @param   integer  $order_id           Order Information ID
	 * @param   string   $order_status       Order Status Code
	 * @param   string   $paymentstatus      Order Payment Status Code
	 *
	 * @return  void
	 */
	public function createWebPacklabel($order_id, $order_status, $paymentstatus)
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
		if ($order_status == Redshop::getConfig()->get('GENERATE_LABEL_ON_STATUS') && $paymentstatus == "Paid")
		{
			$order_details  = $this->getOrderDetails($order_id);
			$details        = RedshopShippingRate::decrypt($order_details->ship_method_id);

			$shippingParams = new JRegistry(
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

			if ($allowPacsoftLabel && !$order_details->order_label_create)
			{
				$generate_label = $this->generateParcel($order_id);

				if ($generate_label != "success")
				{
					JFactory::getApplication()->enqueueMessage($generate_label, 'error');
				}
			}
		}
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
