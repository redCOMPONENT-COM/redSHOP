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

	public function resetOrderId()
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
		$this->_db->setQuery($query);
		$this->_db->execute();

		$query = 'TRUNCATE TABLE `#__redshop_product_download_log`';
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	/*
	 * get order status Title
	 *
	 * @params: orderstatus code
	 * @return: string
	 */
	public function getOrderStatusTitle($order_status_code)
	{
		$db = JFactory::getDbo();

		$query = 'SELECT order_status_name FROM #__redshop_order_status ' . 'WHERE order_status_code = '
			. $db->quote($order_status_code);
		$db->setQuery($query);
		$res = $db->loadResult();

		return $res;
	}

	public function updateOrderStatus($order_id, $newstatus)
	{
		$db = JFactory::getDbo();

		$query = 'UPDATE #__redshop_orders ' . 'SET order_status = ' . $db->quote($newstatus) . ', mdate = ' . (int) time()
			. ' WHERE order_id = ' . (int) $order_id;
		$db->setQuery($query);
		$db->execute();

		RedshopHelperOrder::generateInvoiceNumber($order_id);

		$query = "SELECT p.element,op.order_transfee,op.order_payment_trans_id,op.order_payment_amount, op.authorize_status FROM #__extensions AS p " . "LEFT JOIN "
			. "#__redshop_order_payment AS op ON op.payment_method_class=p.element WHERE op.order_id = "
			. (int) $order_id . " " . "AND p.folder='redshop_payment' ";
		$result = $db->setQuery($query, 0, 1)->loadObject();

		$authorize_status = $result->authorize_status;

		$paymentmethod = $this->getPaymentMethodInfo($result->element);
		$paymentmethod = $paymentmethod[0];

		// Getting the order details
		$orderdetail = $this->getOrderDetails($order_id);
		$paymentparams = new JRegistry($paymentmethod->params);
		$order_status_capture = $paymentparams->get('capture_status', '');
		$order_status_code = $order_status_capture;

		if ($order_status_capture == $newstatus
			&& ($authorize_status == "Authorized" || $authorize_status == ""))
		{
			$values["order_number"] = $orderdetail->order_number;
			$values["order_id"] = $order_id;
			$values["order_transactionid"] = $result->order_payment_trans_id;
			$values["order_amount"] = $orderdetail->order_total + $result->order_transfee;
			$values['shippinginfo'] = RedshopHelperOrder::getOrderShippingUserInfo($order_id);
			$values['billinginfo'] = RedshopHelperOrder::getOrderBillingUserInfo($order_id);
			$values["order_userid"] = $values['billinginfo']->user_id;

			JPluginHelper::importPlugin('redshop_payment');
			$data = JDispatcher::getInstance()->trigger('onCapture_Payment' . $result->element, array($result->element, $values));
			$results = $data[0];

			if (!empty($data))
			{
				$message = $results->message;

				$orderstatuslog = JTable::getInstance('order_status_log', 'Table');
				$orderstatuslog->order_id = $order_id;
				$orderstatuslog->order_status = $order_status_code;
				$orderstatuslog->date_changed = time();
				$orderstatuslog->customer_note = $message;
				$orderstatuslog->store();
			}
		}

		if (($newstatus == "X" || $newstatus == "R")
			&& $paymentparams->get('refund', 0) == 1)
		{
			$values["order_number"]        = $orderdetail->order_number;
			$values["order_id"]            = $order_id;
			$values["order_transactionid"] = $result->order_payment_trans_id;
			$values["order_amount"]        = $orderdetail->order_total + $result->order_transfee;
			$values["order_userid"]        = $values['billinginfo']->user_id;

			JPluginHelper::importPlugin('redshop_payment');

			// Get status and refund if capture/cancel if authorize (for quickpay only)
			$data = JDispatcher::getInstance()->trigger('onStatus_Payment' . $result->element, array($result->element, $values));
			$results = $data[0];

			if (!empty($data))
			{
				$message = $results->message;
				$orderstatuslog = JTable::getInstance('order_status_log', 'Table');
				$orderstatuslog->order_id = $order_id;
				$orderstatuslog->order_status = $newstatus;
				$orderstatuslog->date_changed = time();
				$orderstatuslog->customer_note = $message;
				$orderstatuslog->store();
			}
		}
	}

	public function generateParcel($orderId)
	{
		$db                        = JFactory::getDbo();
		$orderDetail             = $this->getOrderDetails($orderId);
		$producthelper             = RedshopSiteProduct::getInstance();
		$orderproducts             = $this->getOrderItemDetail($orderId);
		$billingInfo               = RedshopHelperOrder::getOrderBillingUserInfo($orderId);
		$shippingInfo              = RedshopHelperOrder::getOrderShippingUserInfo($orderId);
		$shippingRateDecryptDetail = RedshopShippingRate::decrypt($orderDetail->ship_method_id);

		// Get Shipping Delivery Type
		$shippingDeliveryType = 1;

		if (isset($shippingRateDecryptDetail[8]) === true)
		{
			$shippingDeliveryType = (int) $shippingRateDecryptDetail[8];
		}

		$sql = "SELECT country_2_code FROM #__redshop_country WHERE country_3_code = " . $db->quote(SHOP_COUNTRY);
		$db->setQuery($sql);
		$billingInfo->country_code = $db->loadResult();

		$sql = "SELECT country_2_code FROM #__redshop_country WHERE country_3_code = " . $db->quote($shippingInfo->country_code);
		$db->setQuery($sql);
		$shippingInfo->country_code = $db->loadResult();

		// For product conetent
		$totalWeight = 0;
		$content_products = array();
		$qty = 0;

		for ($c = 0, $cn = count($orderproducts); $c < $cn; $c++)
		{
			$qty += $orderproducts [$c]->product_quantity;
			$content_products[] = $orderproducts[$c]->order_item_name;

			// Product Weight
			$sql = "SELECT weight FROM #__redshop_product WHERE product_id = " . (int) $orderproducts [$c]->product_id;
			$db->setQuery($sql);
			$weight = $db->loadResult();

			// Accessory Weight
			$orderAccItemdata = $this->getOrderItemAccessoryDetail($orderproducts[$c]->order_item_id);
			$acc_weight = 0;

			if (count($orderAccItemdata) > 0)
			{
				for ($a = 0, $an = count($orderAccItemdata); $a < $an; $a++)
				{
					$accessory_quantity = $orderAccItemdata[$a]->product_quantity;
					$acc_sql = "SELECT weight FROM #__redshop_product WHERE product_id = "
						. (int) $orderAccItemdata[$a]->product_id;
					$db->setQuery($acc_sql);
					$accessory_weight = $db->loadResult();
					$acc_weight += ($accessory_weight * $accessory_quantity);
				}
			}

			// Total weight
			$totalWeight += (($weight * $orderproducts [$c]->product_quantity) + $acc_weight);
		}

		$unitRatio = $producthelper->getUnitConversation('kg', DEFAULT_WEIGHT_UNIT);

		if ($unitRatio != 0)
		{
			// Converting weight in pounds
			$totalWeight = $totalWeight * $unitRatio;
		}

		if (SHOW_PRODUCT_DETAIL)
		{
			$content_products = array_unique($content_products);
			$content_products = implode(",", $content_products);
			$content_products = mb_convert_encoding($content_products, "ISO-8859-1", "UTF-8");
			$content_products_remark = substr(mb_convert_encoding($content_products, "ISO-8859-1", "UTF-8"), 0, 29);
		}
		else
		{
			$content_products = " ";
			$content_products_remark = " ";
		}

		$filter    = JFilterInput::getInstance();

		// Filter name to remove special characters
		$firstname = $filter->clean(
						mb_convert_encoding($shippingInfo->firstname, "ISO-8859-1", "UTF-8"),
						'username'
					);
		$lastname = $filter->clean(
						mb_convert_encoding($shippingInfo->lastname, "ISO-8859-1", "UTF-8"),
						'username'
					);
		$full_name = $firstname . " " . $lastname;
		$address = mb_convert_encoding($shippingInfo->address, "ISO-8859-1", "UTF-8");
		$city = mb_convert_encoding($shippingInfo->city, "ISO-8859-1", "UTF-8");

		if ($billingInfo->is_company)
		{
			$company_name = mb_convert_encoding($shippingInfo->company_name, "ISO-8859-1", "UTF-8");
			$fproductCode = "PDKEP";
			$addon = "<addon adnid='POD'></addon>";
			$finaladdress1 = $company_name;
			$finaladdress2 = $address;
		}
		else
		{
			// Post Danmark MyPack Home
			$fproductCode = "PDK17";
			$addon = "<addon adnid='DLV'></addon>";
			$finaladdress1 = $address;
			$finaladdress2 = "";
		}

		// When shipping delivery set to post office don't need to send DLV or POD addon.
		if ($shippingDeliveryType == 0)
		{
			// Post Danmark MyPack Collect
			$fproductCode = "P19DK";
			$addon = "";
		}

		if (WEBPACK_ENABLE_EMAIL_TRACK)
		{
			$addon .= '<addon adnid="NOTEMAIL"></addon>';
		}

		if (WEBPACK_ENABLE_SMS)
		{
			$addon .= '<addon adnid="NOTSMS"></addon>';
		}

		// No pickup agent by default
		$agentEle = '';

		// Only when we have store to send parcel - i.e Pickup Location
		if ('' != trim($order_details->shop_id))
		{
			// Get shop location stored using postdanmark plugin or other similar plugin.
			$shopLocation = explode('|', $order_details->shop_id);

			// Sending shop location id as an agent code.
			$agentEle = '<val n="agentto">' . $shopLocation[0] . '</val>';

			// PUPOPT is stands for "Optional Service Point".
			$addon .= '<addon adnid="PUPOPT"></addon>';
		}

		$xmlnew = '<?xml version="1.0" encoding="ISO-8859-1"?>
				<unifaunonline>
				<meta>
				<val n="doorcode">"' . date('Y-m-d H:i') . '"</val>
				</meta>
				<receiver rcvid="' . $shippingInfo->users_info_id . '">
				<val n="name"><![CDATA[' . $full_name . ']]></val>
				<val n="address1"><![CDATA[' . $finaladdress1 . ']]></val>
				<val n="address2"><![CDATA[' . $finaladdress2 . ']]></val>
				<val n="zipcode">' . $shippingInfo->zipcode . '</val>
				<val n="city">' . $city . '</val>
				<val n="country">' . $shippingInfo->country_code . '</val>
				<val n="contact"><![CDATA[' . $firstname . ']]></val>
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
				<service srvid="' . $fproductCode . '">
				' . $addon . '
				</service>
				<container type="parcel">
				<val n="copies">1</val>
				<val n="weight">' . $totalWeight . '</val>
				<val n="contents">' . $content_products . '</val>
				<val n="packagecode">PC</val>
				</container>
				</shipment>
				</unifaunonline>';

		$postURL = "https://www.pacsoftonline.com/ufoweb/order?session=po_DK"
					. "&user=" . POSTDK_CUSTOMER_NO
					. "&pin=" . POSTDK_CUSTOMER_PASSWORD
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

			$xmlResponse = JFactory::getXML($response, false)->val;

			if ('201' == (string) $xmlResponse[1] && 'Created' == (string) $xmlResponse[2])
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
				JError::raiseWarning(
					21,
					(string) $xmlResponse[1] . "-" . (string) $xmlResponse[2] . "-" . (string) $xmlResponse[0]
				);
			}
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}
	}

	/*
	 * Change order status
	 *
	 * @params: data
	 * @key1 => order_status_code
	 * @key2 => transaction_id
	 * @key3 => msg
	 * @key4 => log
	 * @key5 => order_payment_status_code
	 * @key6 => order_id
	 * @return: array
	 */
	public function changeorderstatus($data)
	{
		$helper = RedshopSiteHelper::getInstance();
		$db       = JFactory::getDbo();
		$order_id = $data->order_id;
		$pos      = strpos(JURI::base(), 'plugins');

		if ($pos !== false)
		{
			$explode = explode("plugins", JURI::base());
			$uri = $explode[0];
		}
		else
		{
			$uri = JURI::base();
		}

		$data->order_status_code = trim($data->order_status_code);
		$data->order_payment_status_code = trim($data->order_payment_status_code);
		$checkupdateordersts = $this->checkupdateordersts($data);

		if ($checkupdateordersts == 0 && $data->order_status_code != "" && $data->order_payment_status_code != "")
		{
			// Order status valid and change the status
			$query = "UPDATE #__redshop_orders set order_status = " . $db->quote($data->order_status_code)
				. ", order_payment_status = " . $db->quote($data->order_payment_status_code) . " where order_id = " . (int) $order_id;
			$db->SetQuery($query);
			$db->execute();

			// Generate Invoice Number
			if ("C" == $data->order_status_code
				&& "Paid" == $data->order_payment_status_code)
			{
				$this->SendDownload($order_id);
				RedshopHelperOrder::generateInvoiceNumber($order_id);
			}

			if (!isset($data->transfee))
			{
				$data->transfee = null;
			}

			$query = "UPDATE #__redshop_order_payment SET order_transfee = " . $db->quote($data->transfee)
				. ", order_payment_trans_id = " . $db->quote($data->transaction_id) . " where order_id = '" . (int) $order_id . "'";
			$db->SetQuery($query);
			$db->execute();

			$query = "INSERT INTO  #__redshop_order_status_log set order_status = " . $db->quote($data->order_status_code)
				. ", order_payment_status = " . $db->quote($data->order_payment_status_code) . ", date_changed = " . (int) time()
				. ", order_id = " . (int) $order_id . ", customer_note = " . $db->quote($data->log);
			$db->SetQuery($query);
			$db->execute();

			// Send status change email only if config is set to Before order mail or Order is not confirmed.
			if (!ORDER_MAIL_AFTER
				|| (ORDER_MAIL_AFTER && $data->order_status_code != "C"))
			{
				$this->changeOrderStatusMail($order_id, $data->order_status_code);
			}

			if ($data->order_payment_status_code == "Paid")
			{
				JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_redshop/models');
				$checkoutModelcheckout = JModelLegacy::getInstance('Checkout', 'RedshopModel');
				$checkoutModelcheckout->sendGiftCard($order_id);

				// Send the Order mail
				$redshopMail = redshopMail::getInstance();

				// Send Order Mail After Payment
				if (ORDER_MAIL_AFTER && $data->order_status_code == "C")
				{
					$redshopMail->sendOrderMail($order_id);
				}

				// Send Invoice mail only if order mail is set to before payment.
				elseif (INVOICE_MAIL_ENABLE)
				{
					$redshopMail->sendInvoiceMail($order_id);
				}
			}

			// Trigger function on Order Status change
			JPluginHelper::importPlugin('order');
			JDispatcher::getInstance()->trigger('onAfterOrderStatusUpdate', array($this->getOrderDetails($order_id)));

			// For Webpack Postdk Label Generation
			$this->createWebPacklabel($order_id, $data->order_status_code, $data->order_payment_status_code);
			$this->createBookInvoice($order_id, $data->order_status_code);
		}
	}

	public function updateOrderPaymentStatus($order_id, $newstatus)
	{
		$db = JFactory::getDbo();

		$query = 'UPDATE #__redshop_orders ' . 'SET order_payment_status = ' . $db->quote($newstatus) . ', mdate = '
			. $db->quote(time()) . ' WHERE order_id = ' . (int) $order_id;
		$db->setQuery($query);
		$db->execute();
	}

	public function updateOrderComment($order_id, $comment = '')
	{
		$db = JFactory::getDbo();

		$query = 'UPDATE #__redshop_orders ' . 'SET customer_note = ' . $db->quote($comment) . ' '
			. 'WHERE order_id = ' . (int) $order_id;
		$db->setQuery($query);
		$db->execute();
	}

	public function updateOrderRequisitionNumber($order_id, $requisition_number = '')
	{
		$db = JFactory::getDbo();

		$query = 'UPDATE #__redshop_orders ' . 'SET requisition_number = ' . $db->quote($requisition_number) . ' '
			. 'WHERE order_id = ' . (int) $order_id;
		$db->setQuery($query);
		$db->execute();
		$affected_rows = $db->getAffectedRows();

		if ($affected_rows)
		{
			// Economic Integration start for invoice generate and book current invoice
			if (ECONOMIC_INTEGRATION == 1)
			{
				$economic = economic::getInstance();
				$oid = explode(",", $order_id);

				for ($i = 0, $in = count($oid); $i < $in; $i++)
				{
					if (isset($oid[$i]) && $oid[$i] != 0 && $oid[$i] != "")
					{
						$orderdata = $this->getOrderDetails($oid[$i]);
						$economic->renewInvoiceInEconomic($orderdata);
					}
				}
			}
		}
	}

	/**
	 * Update Order Item Status
	 *
	 * @param   int     $orderId      Order id
	 * @param   int     $productId    Product id
	 * @param   string  $newStatus    New status
	 * @param   string  $comment      Comment
	 * @param   int     $orderItemId  Order item id
	 *
	 * @return  void
	 */
	public function updateOrderItemStatus($orderId = 0, $productId = 0, $newStatus = '', $comment = '', $orderItemId = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_order_item'))
			->set('order_status = ' . $db->q($newStatus))
			->where('order_id = ' . (int) $orderId);

		if ($productId != 0)
		{
			$query->set('customer_note = ' . $db->q($comment))
				->where('product_id = ' . (int) $productId);
		}

		if ($orderItemId != 0)
		{
			$query->where('order_item_id = ' . (int) $orderItemId);
		}

		$db->setQuery($query);

		if (!$db->execute())
		{
			JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'error');
		}
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

	public function getstatuslist($name = 'statuslist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		if (!$this->_orderstatuslist)
		{
			$this->_orderstatuslist = $this->getOrderStatus();
		}

		$types[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_STATUS_LBL') . ' -');
		$types = array_merge($types, $this->_orderstatuslist);

		$tot_status = @explode(",", $selected);
		$mylist['statuslist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $tot_status);

		return $mylist['statuslist'];
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
		$helper          = RedshopSiteHelper::getInstance();
		$producthelper   = RedshopSiteProduct::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();

		$newStatus       = $app->input->getCmd('status');
		$paymentStatus   = $app->input->getCmd('order_paymentstatus');
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

				if (ORDER_MAIL_AFTER && $newStatus == 'C')
				{
					$redshopMail->sendOrderMail($orderId);
				}

				elseif (INVOICE_MAIL_ENABLE)
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
		$helper = RedshopSiteHelper::getInstance();

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
		if (ECONOMIC_INTEGRATION && JPluginHelper::isEnabled('economic'))
		{
			$query = "SELECT order_number FROM #__redshop_orders "
				. "WHERE order_id = " . (int) $maxId;
			$db->setQuery($query);
			$maxOrderNumber = $db->loadResult();
			$economic = economic::getInstance();
			$maxInvoice = $economic->getMaxOrderNumberInEconomic();
			$maxId = max(intval($maxOrderNumber), $maxInvoice);
		}
		elseif (INVOICE_NUMBER_TEMPLATE)
		{
			$maxId = ($maxId + FIRST_INVOICE_NUMBER + 1);

			$order_number = RedshopHelperOrder::parseNumberTemplate(
							INVOICE_NUMBER_TEMPLATE,
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
		$redhelper = RedshopSiteHelper::getInstance();
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
			. '#__redshop_country AS c ' . 'WHERE c.country_id=s.country_id ' . $and;
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

		if ($rows && count($rows) > 0)
		{
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

		if (strstr($ordermailbody, "{barcode}") || strstr($invoicemailbody, "{barcode}")
			|| strstr($receipttempbody, "{barcode}") || $barcodekey == 1)
		{
			$aZ09 = array_merge(range(1, 9));
			$rand_barcode = '';

			for ($c = 0; $c < $lenth; $c++)
			{
				$rand_barcode .= $aZ09[mt_rand(0, count($aZ09) - 1)];
			}

			if (function_exists("curl_init"))
			{
				$url = JURI::root() . 'administrator/components/com_redshop/helpers/barcode/barcode.php?code='
					. $rand_barcode . '&encoding=EAN&scale=2&mode=png';

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_FAILONERROR, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 3);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "code='.$rand_barcode.'&encoding=EAN&scale=2&mode=png");
				curl_close($ch);
			}

			return $rand_barcode;
		}
		else
		{
			$rand_barcode = "";

			return $rand_barcode;
		}
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
		$carthelper      = RedshopSiteCart::getInstance();
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

			// For barcode
			if (strstr($maildata, "{barcode}"))
			{
				if ($barcode_code != "" && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "barcode/" . $barcode_code . ".png"))
				{
					$barcode_code = $barcode_code;
				}
				else
				{
					$barcode_code = $this->barcode_randon_number(12, 1);
					$this->updatebarcode($order_id, $barcode_code);
				}

				$img_url = REDSHOP_FRONT_IMAGES_ABSPATH . "barcode/" . $barcode_code . ".png";
				$bar_replace = '<img alt="" src="' . $img_url . '">';
				$search[] = "{barcode}";
				$replace[] = $bar_replace;
			}

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
			$replace[] = SHOP_NAME;

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

			$order_trackURL = 'http://www.pacsoftonline.com/ext.po.dk.dk.track?key=' . POSTDK_CUSTOMER_NO . '&order=' . $order_id;
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
		if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT != 1)
		{
			$economic = economic::getInstance();

			if (ECONOMIC_INVOICE_DRAFT == 2 && $order_status == BOOKING_ORDER_STATUS)
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

				$economicdata['split_payment'] = 0;
				$economic->createInvoiceInEconomic($order_id, $economicdata);
			}

			$bookinvoicepdf = $economic->bookInvoiceInEconomic($order_id, ECONOMIC_INVOICE_DRAFT);

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
		if (!POSTDK_INTEGRATION)
		{
			return;
		}

		// If auto generation is disable then return
		if (!AUTO_GENERATE_LABEL)
		{
			return;
		}

		// Only Execute this function for selected status match
		if ($order_status == GENERATE_LABEL_ON_STATUS && $paymentstatus == "Paid")
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
		$helper = RedshopSiteHelper::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();
		$producthelper = RedshopSiteProduct::getInstance();
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

		if (CLICKATELL_ENABLE)
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
