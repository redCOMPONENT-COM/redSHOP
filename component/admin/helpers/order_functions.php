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

	public function getFilterbyList($name = 'filterbylist', $selected = 'all', $attributes = ' class="inputbox" size="1" ')
	{
		$filterbylist = array('orderid' => JText::_('COM_REDSHOP_ORDERID'),
								'ordernumber' => JText::_('COM_REDSHOP_ORDERNUMBER'),
								'fullname' => JText::_('COM_REDSHOP_FULLNAME'),
								'useremail' => JText::_('COM_REDSHOP_USEREMAIL')
							);

		$types[] = JHTML::_('select.option', '', 'All');
		$types = array_merge($types, $filterbylist);

		$tot_status = @explode(",", $selected);
		$mylist['filterbylist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $tot_status);

		return $mylist['filterbylist'];
	}

	public function getpaymentstatuslist($name = 'paymentstatuslist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		$types[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT_PAYMENT_STATUS'));
		$types[] = JHTML::_('select.option', 'Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PAID'));
		$types[] = JHTML::_('select.option', 'Unpaid', JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID'));
		$types[] = JHTML::_('select.option', 'Partial Paid', JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID'));
		$mylist['paymentstatuslist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $selected);

		return $mylist['paymentstatuslist'];
	}

	/**
	 * Update order status and trigger emails based on status.
	 *
	 * @return  void
	 */
	public function update_status()
	{
		$app             = JFactory::getApplication();
		$helper          = redhelper::getInstance();
		$producthelper   = productHelper::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();

		$newStatus       = $app->input->getCmd('status');
		$paymentStatus   = $app->input->getString('order_paymentstatus');
		$return          = $app->input->getCmd('return');

		$customerNote    = $app->input->get('customer_note', array(), 'array');
		$customerNote    = stripslashes($customerNote[0]);

		$oid             = $app->input->get('order_id', array(), 'method', 'array');
		$orderId         = $oid[0];

		$isProduct       = $app->input->getInt('isproduct', 0);
		$productId       = $app->input->getInt('product_id', 0);
		$orderItemId     = $app->input->getInt('order_item_id', 0);

		if (isset($paymentStatus))
		{
			$this->updateOrderPaymentStatus($orderId, $paymentStatus);
		}

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');
		$order_log = JTable::getInstance('order_status_log', 'Table');

		if (!$isProduct)
		{
			$data['order_id'] = $orderId;
			$data['order_status'] = $newStatus;
			$data['order_payment_status'] = $paymentStatus;
			$data['date_changed'] = time();
			$data['customer_note'] = $customerNote;

			if (!$order_log->bind($data))
			{
				return JFactory::getApplication()->enqueueMessage($order_log->getError(), 'error');
			}

			if (!$order_log->store())
			{
				throw new Exception($order_log->getError());
			}

			$this->updateOrderComment($orderId, $customerNote);

			$requisitionNumber = $app->input->getString('requisition_number', '');

			if ('' != $requisitionNumber)
			{
				$this->updateOrderRequisitionNumber($orderId, $requisitionNumber);
			}

			// Changing the status of the order
			$this->updateOrderStatus($orderId, $newStatus, $order_log->order_status_log_id);

			// Trigger function on Order Status change
			JPluginHelper::importPlugin('order');
			JDispatcher::getInstance()->trigger(
				'onAfterOrderStatusUpdate',
				array(RedshopHelperOrder::getOrderDetail($orderId))
			);

			if ($paymentStatus == "Paid")
			{
				JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_redshop/models');
				$checkoutModelcheckout = JModelLegacy::getInstance('Checkout', 'RedshopModel');
				$checkoutModelcheckout->sendGiftCard($orderId);

				// Send the Order mail
				$redshopMail = redshopMail::getInstance();

				if (Redshop::getConfig()->get('ORDER_MAIL_AFTER') && $newStatus == 'C')
				{
					$redshopMail->sendOrderMail($orderId);
				}

				elseif (Redshop::getConfig()->get('INVOICE_MAIL_ENABLE'))
				{
					$redshopMail->sendInvoiceMail($orderId);
				}
			}

			$this->createWebPacklabel($orderId, $newStatus, $paymentStatus);
		}

		$this->updateOrderItemStatus($orderId, $productId, $newStatus, $customerNote, $orderItemId);
		$helper->clickatellSMS($orderId);

		switch ($newStatus)
		{
			case "X";

				$orderproducts = $this->getOrderItemDetail($orderId);

				for ($i = 0, $in = count($orderproducts); $i < $in; $i++)
				{
					$prodid = $orderproducts[$i]->product_id;
					$prodqty = $orderproducts[$i]->stockroom_quantity;

					// When the order is set to "cancelled",product will return to stock
					$stockroomhelper->manageStockAmount($prodid, $prodqty, $orderproducts[$i]->stockroom_id);
					$producthelper->makeAttributeOrder($orderproducts[$i]->order_item_id, 0, $prodid, 1);
				}
				break;

			case "RT":

				if ($isProduct)
				{
					// Changing the status of the order item to Returned
					$this->updateOrderItemStatus($orderId, $productId, "RT", $customerNote, $orderItemId);

					// Changing the status of the order to Partially Returned
					$this->updateOrderStatus($orderId, "PRT");
				}

				break;

			case "RC":

				if ($isProduct)
				{
					// Changing the status of the order item to Reclamation
					$this->updateOrderItemStatus($orderId, $productId, "RC", $customerNote, $orderItemId);

					// Changing the status of the order to Partially Reclamation
					$this->updateOrderStatus($orderId, "PRC");
				}

				break;

			case "S":

				if ($isProduct)
				{
					// Changing the status of the order item to Reclamation
					$this->updateOrderItemStatus($orderId, $productId, "S", $customerNote, $orderItemId);

					// Changing the status of the order to Partially Reclamation
					$this->updateOrderStatus($orderId, "PS");
				}

				break;

			case "C":

				// SensDownload Products
				if ($paymentStatus == "Paid")
				{
					$this->SendDownload($orderId);
				}

				break;
		}

		if ($app->input->getCmd('order_sendordermail') == 'true')
		{
			$this->changeOrderStatusMail($orderId, $newStatus, $customerNote);
		}

		$this->createBookInvoice($orderId, $newStatus);

		$msg       = JText::_('COM_REDSHOP_ORDER_STATUS_SUCCESSFULLY_SAVED_FOR_ORDER_ID') . " " . $orderId;

		$isarchive = ($app->input->getInt('isarchive')) ? '&isarchive=1' : '';

		if ($return == 'order')
		{
			$app->redirect('index.php?option=com_redshop&view=' . $return . '' . $isarchive . '', $msg);
		}
		else
		{
			$tmpl = $app->input->getCmd('tmpl');

			if ('' != $tmpl)
			{
				$app->redirect('index.php?option=com_redshop&view=' . $return . '&cid[]=' . $orderId . '&tmpl=' . $tmpl . '' . $isarchive . '', $msg);
			}
			else
			{
				$app->redirect('index.php?option=com_redshop&view=' . $return . '&cid[]=' . $orderId . '' . $isarchive . '', $msg);
			}
		}
	}

	public function getOrderDetails($order_id)
	{
		$db = JFactory::getDbo();

		$query = "SELECT * FROM #__redshop_orders " . "WHERE order_id = " . (int) $order_id;
		$db->setQuery($query);
		$list = $db->loadObject();

		return $list;
	}

	public function getmultiOrderDetails($order_id)
	{
		$db = JFactory::getDbo();

		$query = "SELECT * FROM #__redshop_orders " . "WHERE order_id = " . (int) $order_id;
		$db->setQuery($query);
		$list = $db->loadObjectList();

		return $list;
	}

	public function getUserOrderDetails($user_id = 0)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$query = "SELECT * FROM #__redshop_orders " . "WHERE user_id = " . (int) $user_id . " ORDER BY `order_id` DESC";
		$db->setQuery($query);
		$list = $db->loadObjectlist();

		return $list;
	}

	public function getOrderItemDetail($order_id = 0, $product_id = 0, $order_item_id = 0)
	{
		$and = "";
		$list = null;

		if ($order_id != 0)
		{
			$order_id = explode(',', $order_id);
			JArrayHelper::toInteger($order_id);
			$order_id = implode(',', $order_id);
			$and .= " AND order_id IN (" . $order_id . ") ";
		}

		if ($product_id != 0)
		{
			$and .= " AND product_id = " . (int) $product_id . " ";
		}

		if ($order_item_id != 0)
		{
			$and .= " AND order_item_id = " . (int) $order_item_id . " ";
		}

		if (!empty($and))
		{
			$db = JFactory::getDbo();

			$query = "SELECT * FROM  #__redshop_order_item " . "WHERE 1=1 " . $and;
			$db->setQuery($query);
			$list = $db->loadObjectlist();
		}

		return $list;
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

	public function getOrderPartialPayment($order_id)
	{
		$db = JFactory::getDbo();

		$query = 'SELECT order_payment_amount FROM #__redshop_order_payment ' . 'WHERE order_id = ' . (int) $order_id;
		$db->setQuery($query);
		$list = $db->loadObjectlist();

		$spilt_payment_amount = 0;

		for ($i = 0, $in = count($list); $i < $in; $i++)
		{
			if ($list[$i]->order_payment_amount > 0)
			{
				$spilt_payment_amount = $list[$i]->order_payment_amount;
			}
		}

		return $spilt_payment_amount;
	}

	public function getShippingMethodInfo($shipping_class = '')
	{
		$and = "";
		$db = JFactory::getDbo();

		if ($shipping_class != '')
		{
			$and = "AND element = " . $db->quote($shipping_class) . " ";
		}

		$folder = strtolower('redshop_shipping');

		$query = "SELECT * FROM #__extensions " . "WHERE enabled = '1' " . "AND LOWER(`folder`) = " . $db->quote($folder) . $and . "ORDER BY ordering ASC ";
		$db->setQuery($query);
		$list = $db->loadObjectList();

		return $list;
	}

	public function getPaymentMethodInfo($payment_method_class = '')
	{
		$and = "";
		$db = JFactory::getDbo();

		if ($payment_method_class != '')
		{
			$and = "AND element = " . $db->quote($payment_method_class) . " ";
		}

		$folder = strtolower('redshop_payment');

		$query = "SELECT * FROM #__extensions " . "WHERE enabled = '1' " . $and . "AND LOWER(`folder`) = " . $db->quote($folder) . "ORDER BY ordering ASC ";

		$db->setQuery($query);
		$list = $db->loadObjectList();

		return $list;
	}

	public function getBillingAddress($user_id = 0)
	{
		$db = JFactory::getDbo();
		$helper = redhelper::getInstance();

		$user = JFactory::getUser();

		// Get Joomla Session
		$session = JFactory::getSession();
		$list = array();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($user_id)
		{
			$query = $db->getQuery(true)
				->select('*, CONCAT(firstname," ",lastname) AS text')
				->from('#__redshop_users_info')
				->where('address_type = ' . $db->q('BT'))
				->where('user_id = ' . (int) $user_id);
			$list = $db->setQuery($query)->loadObject();
		}

		return $list;
	}

	/**
	 * Order Billing User info
	 *
	 * @param   integer  $order_id  Order Id
	 *
	 * @deprecated 1.6   Use RedshopHelperOrder::getOrderBillingUserInfo($orderId) instead
	 *
	 * @return  object   Order Billing Information object
	 */
	public function getOrderBillingUserInfo($orderId)
	{
		return RedshopHelperOrder::getOrderBillingUserInfo($orderId);
	}

	public function getShippingAddress($user_id = 0)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$query = 'SELECT *,CONCAT(firstname," ",lastname) AS text FROM #__redshop_users_info '
			. 'WHERE address_type="ST" ' . 'AND user_id = ' . (int) $user_id;
		$db->setQuery($query);
		$list = $db->loadObjectlist();

		return $list;
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

	public function getUserFullname($user_id)
	{
		$fullname = "";
		$user = JFactory::getUser();
		$db = JFactory::getDbo();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$query = "SELECT firstname, lastname FROM #__redshop_users_info " . "WHERE address_type like 'BT' "
			. "AND user_id = " . (int) $user_id;
		$db->setQuery($query);
		$list = $db->loadObject();

		if ($list)
		{
			$fullname = $list->firstname . " " . $list->lastname;
		}
		else
		{
			$query = "SELECT name FROM #__users " . "WHERE id = " . (int) $user_id;
			$db->setQuery($query);
			$list = $db->loadObject();

			if ($list)
			{
				$fullname = $list->name;
			}
		}

		return $fullname;
	}

	public function getOrderItemAccessoryDetail($order_item_id = 0)
	{
		$db = JFactory::getDbo();

		if ($order_item_id != 0)
		{
			$query = "SELECT * FROM  #__redshop_order_acc_item "
				. "WHERE order_item_id = " . (int) $order_item_id;
			$db->setQuery($query);

			return $db->loadObjectlist();
		}

		return null;
	}

	public function getOrderItemAttributeDetail($order_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		$and = "";
		$db = JFactory::getDbo();

		if ($order_item_id != 0)
		{
			$and .= " AND order_item_id = " . (int) $order_item_id . " ";
		}

		if ($parent_section_id != 0)
		{
			$and .= " AND parent_section_id = " . (int) $parent_section_id . " ";
		}

		$query = "SELECT * FROM  #__redshop_order_attribute_item "
			. "WHERE is_accessory_att = " . (int) $is_accessory . " "
			. "AND section = " . $db->quote($section) . " "
			. $and;
		$db->setQuery($query);
		$list = $db->loadObjectlist();

		return $list;
	}

	public function getOrderUserfieldData($order_item_id = 0, $section = 0)
	{
		$db = JFactory::getDbo();
		$query = "SELECT fd.*,f.field_title,f.field_type,f.field_name"
			. " FROM #__redshop_fields_data AS fd "
			. "LEFT JOIN #__redshop_fields AS f ON f.field_id=fd.fieldid "
			. "WHERE fd.itemid = " . (int) $order_item_id . " "
			. "AND fd.section = " . $db->quote($section);
		$db->setQuery($query);
		$list = $db->loadObjectlist();

		return $list;
	}

	function generateOrderNumber()
	{
		$db = JFactory::getDbo();

		$query = "SELECT MAX(order_id) FROM #__redshop_orders";
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
			$query = "SELECT order_number FROM #__redshop_orders "
				. "WHERE order_id = " . (int) $maxId;
			$db->setQuery($query);
			$maxOrderNumber = $db->loadResult();
			$economic = economic::getInstance();
			$maxInvoice = $economic->getMaxOrderNumberInEconomic();
			$maxId = max(intval($maxOrderNumber), $maxInvoice);
		}
		elseif (Redshop::getConfig()->get('INVOICE_NUMBER_TEMPLATE'))
		{
			$maxId = ($maxId + Redshop::getConfig()->get('FIRST_INVOICE_NUMBER') + 1);

			$order_number = RedshopHelperOrder::parseNumberTemplate(
							Redshop::getConfig()->get('INVOICE_NUMBER_TEMPLATE'),
							$maxId
						);

			return $order_number;
		}

		$order_number = $maxId + 1;

		return ($order_number);
	}

	public function random_gen_enc_key($p_length = '30')
	{
		/* Generated a unique order number */
		$char_list = "abcdefghijklmnopqrstuvwxyz";
		$char_list .= "1234567890123456789012345678901234567890123456789012345678901234567890";

		$random = "";
		srand((double) microtime() * 1000000);

		for ($i = 0; $i < $p_length; $i++)
		{
			$random .= substr($char_list, (rand() % (strlen($char_list))), 1);
		}

		return $random;
	}

	public function getCountryName($cnt3 = "")
	{
		$db = JFactory::getDbo();
		$redhelper = redhelper::getInstance();
		$and = '';
		$cntname = '';

		if ($cnt3 != "")
		{
			$and .= ' AND country_3_code = ' . $db->quote($cnt3) . ' ';
		}
		else
		{
			return $cntname;
		}

		$query = 'SELECT country_3_code AS value,country_name AS text,country_jtext FROM '
			. '#__redshop_country ' . 'WHERE 1=1 ' . $and;
		$db->setQuery($query);
		$countries = $db->loadObjectList();
		$countries = $redhelper->convertLanguageString($countries);

		if (count($countries) > 0)
		{
			$cntname = $countries[0]->text;
		}

		return $cntname;
	}

	public function getStateName($st3 = "", $cnt3 = "")
	{
		$stname = '';
		$and = '';
		$db = JFactory::getDbo();

		if ($st3 != "")
		{
			$and .= ' AND s.state_2_code = ' . $db->quote($st3) . ' ';
		}
		else
		{
			return $stname;
		}

		if ($cnt3 != "")
		{
			$and .= ' AND c.country_3_code = ' . $db->quote($cnt3) . ' ';
		}

		$query = 'SELECT s.state_name FROM #__redshop_state AS s ' . ','
			. '#__redshop_country AS c ' . 'WHERE c.id=s.country_id ' . $and;
		$db->setQuery($query);
		$stname = $db->loadResult();

		return $stname;
	}

	public function SendDownload($order_id = 0)
	{
		$config = Redconfiguration::getInstance();
		$app = JFactory::getApplication();
		$redshopMail = redshopMail::getInstance();

		// Getting the order status changed template from mail center end
		$MailFrom = $app->getCfg('mailfrom');
		$FromName = $app->getCfg('fromname');

		$maildata = "";
		$mailsubject = "";
		$mailbcc = null;
		$mailinfo = $redshopMail->getMailtemplate(0, "downloadable_product_mail");

		if (count($mailinfo) > 0)
		{
			$maildata = $mailinfo[0]->mail_body;
			$mailsubject = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}

		// Get Downloadable Product
		$rows = $this->getDownloadProduct($order_id);

		// There is no downloadable product
		if ($rows === null || count($rows) == 0)
		{
			return false;
		}

		// Getting the order details
		$orderdetail = $this->getOrderDetails($order_id);
		$userdetail  = RedshopHelperOrder::getOrderBillingUserInfo($order_id);

		$userfullname = $userdetail->firstname . " " . $userdetail->lastname;
		$useremail    = $userdetail->email;

		$i = 0;

		$maildata = str_replace("{fullname}", $userfullname, $maildata);
		$maildata = str_replace("{order_id}", $orderdetail->order_id, $maildata);
		$maildata = str_replace("{order_number}", $orderdetail->order_number, $maildata);
		$maildata = str_replace("{order_date}", $config->convertDateFormat($orderdetail->cdate), $maildata);

		$mailtoken = "";
		$productstart = "";
		$productend = "";
		$productmiddle = "";
		$pmiddle = "";
		$mailfirst = explode("{product_serial_loop_start}", $maildata);

		if (count($mailfirst) > 1)
		{
			$productstart = $mailfirst[0];
			$mailsec = explode("{product_serial_loop_end}", $mailfirst[1]);

			if (count($mailsec) > 1)
			{
				$productmiddle = $mailsec[0];
				$productend = $mailsec[1];
			}
		}

		foreach ($rows as $row)
		{
			$datamessage = $productmiddle;
			$downloadfilename = "";
			$downloadfilename = substr(basename($row->file_name), 11);

			$mailtoken = "<a href='" . JURI::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid="
				. $row->download_id . "'>" . $downloadfilename . "</a>";

			$datamessage = str_replace("{product_serial_number}", $row->product_serial_number, $datamessage);
			$datamessage = str_replace("{product_name}", $row->product_name, $datamessage);
			$datamessage = str_replace("{token}", $mailtoken, $datamessage);
			$i++;

			$pmiddle .= $datamessage;
		}

		$maildata = $productstart . $pmiddle . $productend;
		$mailbody = $maildata;
		$mailbody = $redshopMail->imginmail($mailbody);
		$mailsubject = str_replace("{order_number}", $orderdetail->order_number, $mailsubject);

		if ($mailbody && $useremail != "")
		{
			if (!JFactory::getMailer()->sendMail($MailFrom, $FromName, $useremail, $mailsubject, $mailbody, 1, null, $mailbcc))
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DOWNLOAD_MAIL_FAIL'), 'error');
			}
		}

		return true;
	}

	public function getDownloadProduct($order_id)
	{
		$db = JFactory::getDbo();
		$query = "SELECT pd.*,product_name FROM #__redshop_product_download AS pd ,"
			. "#__redshop_product AS p "
			. "WHERE pd.product_id=p.product_id "
			. "AND order_id = " . (int) $order_id;
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function getDownloadProductLog($order_id, $did = '')
	{
		$db = JFactory::getDbo();
		$whereDownload_id = ($did != '') ? " AND pdl.download_id = " . $db->quote($did) : "";

		$query = "SELECT pdl . * , pd.order_id, pd.product_id, pd.file_name "
			. " FROM `#__redshop_product_download_log` AS pdl "
			. " LEFT JOIN #__redshop_product_download AS pd ON pd.download_id = pdl.download_id"
			. " WHERE pd.order_id = " . (int) $order_id
			. " " . $whereDownload_id;
		$db->setQuery($query);

		return $db->loadObjectList();
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
