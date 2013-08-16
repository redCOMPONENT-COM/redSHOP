<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/mail.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/economic.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/cart.php';

class order_functions
{
	public $_data = null;

	public $_db = null;

	public $_table_prefix = null;

	public $_carthelper = null;

	public $_orderstatuslist = null;

	public $_customorderstatuslist = null;

	public function __construct()
	{
		$this->_db = JFactory::getDBO();

		$this->_table_prefix     = '#__redshop_';
		$this->_table_prefix_crm = '#__redcrm_';
	}

	public function resetOrderId()
	{
		$query = 'TRUNCATE TABLE `' . $this->_table_prefix . 'orders`';
		$this->_db->setQuery($query);
		$this->_db->query();

		$query = 'TRUNCATE TABLE `' . $this->_table_prefix . 'order_item`';
		$this->_db->setQuery($query);
		$this->_db->query();

		$query = 'TRUNCATE TABLE `' . $this->_table_prefix . 'order_users_info`';
		$this->_db->setQuery($query);
		$this->_db->query();

		$query = 'TRUNCATE TABLE `' . $this->_table_prefix . 'order_status_log`';
		$this->_db->setQuery($query);
		$this->_db->query();

		$query = 'TRUNCATE TABLE `' . $this->_table_prefix . 'order_acc_item`';
		$this->_db->setQuery($query);
		$this->_db->query();

		$query = 'TRUNCATE TABLE `' . $this->_table_prefix . 'order_attribute_item`';
		$this->_db->setQuery($query);
		$this->_db->query();

		$query = 'TRUNCATE TABLE `' . $this->_table_prefix . 'order_payment`';
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	/*
	 * get order status Title
	 *
	 * @params: orderstatus code
	 * @return: string
	 */
	public function getOrderStatusTitle($order_status_code)
	{
		$query = 'SELECT order_status_name FROM ' . $this->_table_prefix . 'order_status ' . 'WHERE order_status_code ="'
			. $order_status_code . '"';
		$this->_db->setQuery($query);
		$res = $this->_db->loadResult();

		return $res;
	}

	public function updateOrderStatus($order_id, $newstatus)
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'orders ' . 'SET order_status="' . $newstatus . '", mdate=' . time()
			. ' WHERE order_id IN(' . $order_id . ')';
		$this->_db->setQuery($query);
		$this->_db->query();

		$query = "SELECT p.element,op.order_transfee,op.order_payment_trans_id,op.order_payment_amount FROM #__extensions AS p " . "LEFT JOIN "
			. $this->_table_prefix . "order_payment AS op ON op.payment_method_class=p.element " . "WHERE op.order_id='"
			. $order_id . "' " . "AND p.folder='redshop_payment' ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectlist();
		$authorize_status = $result[0]->authorize_status;

		$paymentmethod = $this->getPaymentMethodInfo($result[0]->element);
		$paymentmethod = $paymentmethod[0];

		// Getting the order details
		$orderdetail = $this->getOrderDetails($order_id);

		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod->element . '.xml';
		$paymentparams = new JRegistry($paymentmethod->params);
		$order_status_capture = $paymentparams->get('capture_status', '');
		$auth_type = $paymentparams->get('auth_type', '');
		$order_status_code = $order_status_capture;

		if ($order_status_capture == $newstatus && ($authorize_status == "Authorized" || $authorize_status == ""))
		{
			$values["order_number"] = $orderdetail->order_number;
			$values["order_id"] = $order_id;
			$values["order_transactionid"] = $result[0]->order_payment_trans_id;
			$values["order_amount"] = $orderdetail->order_total + $result[0]->order_transfee;
			$values["order_userid"] = $values['billinginfo']->user_id;
			$values['shippinginfo'] = $this->getOrderShippingUserInfo($order_id);
			$values['billinginfo'] = $this->getOrderBillingUserInfo($order_id);

			JPluginHelper::importPlugin('redshop_payment');
			$dispatcher = JDispatcher::getInstance();
			$data = $dispatcher->trigger('onCapture_Payment' . $result[0]->element, array($result[0]->element, $values));
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

		// Refund Money while cancel the order
		$refund_type = $paymentparams->get('refund', '0');

		if ($newstatus == "X" && $refund_type == 1)
		{
			$values["order_number"] = $orderdetail->order_number;
			$values["order_id"] = $order_id;
			$values["order_transactionid"] = $result[0]->order_payment_trans_id;
			$values["order_amount"] = $orderdetail->order_total + $result[0]->order_transfee;
			$values["order_userid"] = $values['billinginfo']->user_id;

			JPluginHelper::importPlugin('redshop_payment');
			$dispatcher = JDispatcher::getInstance();

			// Get status and refund if capture/cancel if authorize (for quickpay only)
			$data = $dispatcher->trigger('onStatus_Payment' . $result[0]->element, array($result[0]->element, $values));
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

	public function generateParcel($order_id, $specifiedSendDate)
	{
		$order_details             = $this->getOrderDetails($order_id);
		$producthelper             = new producthelper;
		$orderproducts             = $this->getOrderItemDetail($order_id);
		$billingInfo               = $this->getOrderBillingUserInfo($order_id);
		$shippingInfo              = $this->getOrderShippingUserInfo($order_id);
		$shippinghelper            = new shipping;
		$shippingRateDecryptDetail = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $order_details->ship_method_id)));

		// Get Shipping Delivery Type
		$shippingDeliveryType = 1;

		if (isset($shippingRateDecryptDetail[8]) === true)
		{
			$shippingDeliveryType = (int) $shippingRateDecryptDetail[8];
		}

		$sql = "SELECT country_2_code FROM " . $this->_table_prefix . "country WHERE country_3_code = '" . SHOP_COUNTRY . "'";
		$this->_db->setQuery($sql);
		$billingInfo->country_code = $this->_db->loadResult();

		$sql = "SELECT country_name FROM " . $this->_table_prefix . "country WHERE country_2_code = '" . $billingInfo->country_code . "'";
		$this->_db->setQuery($sql);
		$country_name = $this->_db->loadResult();

		$sql = "SELECT country_2_code FROM " . $this->_table_prefix . "country WHERE country_3_code = '" . $shippingInfo->country_code . "'";
		$this->_db->setQuery($sql);
		$shippingInfo->country_code = $this->_db->loadResult();

		// For product conetent
		$totalWeight = 0;
		$content_products = array();
		$qty = 0;

		for ($c = 0; $c < count($orderproducts); $c++)
		{
			$product_id[] = $orderproducts [$c]->product_id;
			$qty += $orderproducts [$c]->product_quantity;
			$content_products[] = $orderproducts[$c]->order_item_name;

			// Product Weight
			$sql = "SELECT weight FROM " . $this->_table_prefix . "product WHERE product_id ='" . $orderproducts [$c]->product_id . "'";
			$this->_db->setQuery($sql);
			$weight = $this->_db->loadResult();

			// Accessory Weight
			$orderAccItemdata = $this->getOrderItemAccessoryDetail($orderproducts[$c]->order_item_id);
			$acc_weight = 0;

			if (count($orderAccItemdata) > 0)
			{
				for ($a = 0; $a < count($orderAccItemdata); $a++)
				{
					$accessory_quantity = $orderAccItemdata[$a]->product_quantity;
					$acc_sql = "SELECT weight FROM " . $this->_table_prefix . "product WHERE product_id ='"
						. $orderAccItemdata[$a]->product_id . "'";
					$this->_db->setQuery($acc_sql);
					$accessory_weight = $this->_db->loadResult();
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

		// Total quantity
		$total_qty = $qty;
		$firstname = mb_convert_encoding($shippingInfo->firstname, "ISO-8859-1", "UTF-8");
		$lastname = mb_convert_encoding($shippingInfo->lastname, "ISO-8859-1", "UTF-8");
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
			$fproductCode = "P19DK";
			$addon = "<addon adnid='DLV'></addon>";
			$finaladdress1 = $address;
			$finaladdress2 = "";
		}

		// When shipping delivery set to post office don't need to send DLV or POD addon.
		if ($shippingDeliveryType == 0)
		{
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

		$xmlnew = '<?xml version="1.0" encoding="ISO-8859-1"?>
				<pacsoftonline>
				<meta>
				<val n="printer">1</val>
				</meta>
				<receiver rcvid="' . $shippingInfo->users_info_id . '">
				<val n="name"><![CDATA[' . $full_name . ']]></val>
				<val n="address1"><![CDATA[' . $finaladdress1 . ']]></val>
				<val n="address2"><![CDATA[' . $finaladdress2 . ']]></val>
				<val n="zipcode">' . $shippingInfo->zipcode . '</val>
				<val n="city">' . $city . '</val>
				<val n="country">' . $shippingInfo->country_code . '</val>
				<val n="contact">' . $firstname . '</val>
				<val n="phone">' . $shippingInfo->phone . '</val>
				<val n="doorcode"/>
				<val n="email">' . $shippingInfo->user_email . '</val>
				<val n="sms">' . $shippingInfo->phone . '</val>
				</receiver>
				<shipment orderno="' . $shippingInfo->order_id . '">
				<val n="from">1</val>
				<val n="to">' . $shippingInfo->users_info_id . '</val>
				<val n="reference">' . $order_details->order_number . '</val>
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
				</pacsoftonline>';

		$postURL = "https://www.pacsoftonline.com/ufoweb/order?session=po_DK&user=" . POSTDK_CUSTOMER_NO . "&pin=" . POSTDK_CUSTOMER_PASSWORD . "&type=xml";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $postURL);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlnew);
		$response = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);

		$oXML = new SimpleXMLElement($response);

		if ($oXML->val[1] == "201" && $oXML->val[2] == "Created")
		{
			$query = 'UPDATE ' . $this->_table_prefix . 'orders SET `order_label_create` = 1 WHERE order_id=' . $order_id;
			$this->_db->setQuery($query);
			$this->_db->query();

			return "success";
		}
		else
		{
			JError::raiseWarning(21, $oXML->val[1] . "-" . $oXML->val[2]);
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
		$helper = new redhelper;

		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		$order_id = $data->order_id;

		$pos = strpos(JURI::base(), 'plugins');

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
			if ($data->order_status_code == "C")
			{
				$this->SendDownload($order_id);
			}

			// Order status valid and change the status
			$query = "UPDATE " . $this->_table_prefix . "orders set order_status = '" . $data->order_status_code
				. "',order_payment_status = '" . $data->order_payment_status_code . "' where order_id = " . $order_id;
			$this->_db->SetQuery($query);
			$this->_db->Query();

			$query = "UPDATE " . $this->_table_prefix . "order_payment SET order_transfee ='" . $data->transfee
				. "', order_payment_trans_id = '" . $data->transaction_id . "' where order_id = '" . $this->_db->getEscaped($order_id) . "'";
			$this->_db->SetQuery($query);
			$this->_db->Query();

			$statusmsg = $data->msg;
			$query = "INSERT INTO  " . $this->_table_prefix . "order_status_log set order_status = '" . $data->order_status_code
				. "' ,order_payment_status ='" . $data->order_payment_status_code . "', date_changed='" . time() . "',order_id = "
				. $order_id . ",customer_note = '" . $data->log . "'";
			$this->_db->SetQuery($query);
			$this->_db->Query();
			$this->changeOrderStatusMail($order_id, $data->order_status_code);

			if ($data->order_payment_status_code == "Paid")
			{
				require_once JPATH_SITE . '/components/com_redshop/models/checkout.php';

				$checkoutModelcheckout = new checkoutModelcheckout;
				$checkoutModelcheckout->sendGiftCard($order_id);

				// Send the Order mail
				$redshopMail = new redshopMail;

				// Send Order Mail After Payment
				if (ORDER_MAIL_AFTER)
				{
					$redshopMail->sendOrderMail($order_id);
				}

				// Send Invoice mail only if order mail is set to before payment.
				elseif (INVOICE_MAIL_ENABLE)
				{
					$redshopMail->sendInvoiceMail($order_id);
				}
			}

			if ($data->order_payment_status_code == "Paid" && $data->order_status_code == "S")
			{
				// For Consignor Label generation
				JPluginHelper::importPlugin('redshop_shippinglabel');
				$dispatcher = JDispatcher::getInstance();
				$results = $dispatcher->trigger('onChangeStatusToShipped',
					array($order_id, $data->order_status_code, $data->order_payment_status_code)
				);
			}

			// For Webpack Postdk Label Generation
			$this->createWebPacklabel($order_id, $specifiedSendDate, $data->order_status_code, $data->order_payment_status_code);
			$this->createBookInvoice($order_id, $data->order_status_code);

			/**
			 * redCRM includes
			 */
			if ($helper->isredCRM() && ENABLE_ITEM_TRACKING_SYSTEM)
			{
				// Supplier order helper object
				$crmSupplierOrderHelper = new crmSupplierOrderHelper;

				$getStatus = array();
				$getStatus['orderstatus'] = $data->order_status_code;
				$getStatus['paymentstatus'] = $data->order_payment_status_code;

				$crmSupplierOrderHelper->redSHOPOrderUpdate($order_id, $getStatus);
				unset($getStatus);
			}
		}
	}

	public function updateOrderPaymentStatus($order_id, $newstatus)
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'orders ' . 'SET order_payment_status="' . $newstatus . '", mdate=' . time()
			. ' WHERE order_id IN(' . $order_id . ')';
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	public function updateOrderComment($order_id, $comment = '')
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'orders ' . 'SET customer_note="' . $comment . '" '
			. 'WHERE order_id IN(' . $order_id . ') ';
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	public function updateOrderRequisitionNumber($order_id, $requisition_number = '')
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'orders ' . 'SET requisition_number="' . $requisition_number . '" '
			. 'WHERE order_id IN(' . $order_id . ') ';
		$this->_db->setQuery($query);
		$this->_db->query();
		$affected_rows = $this->_db->getAffectedRows();

		if ($affected_rows)
		{
			// Economic Integration start for invoice generate and book current invoice
			if (ECONOMIC_INTEGRATION == 1)
			{
				$economic = new economic;
				$oid = explode(",", $order_id);

				for ($i = 0; $i < count($oid); $i++)
				{
					if (isset($oid[$i]) && $oid[$i] != 0 && $oid[$i] != "")
					{
						$orderdata = $this->getOrderDetails($oid[$i]);
						$invoiceHandle = $economic->renewInvoiceInEconomic($orderdata);
					}
				}
			}
		}
	}

	public function updateOrderItemStatus($order_id = 0, $product_id = 0, $newstatus = "", $comment = "", $order_item_id = 0)
	{
		$and = "";
		$field = "";
		$and_order_item = "";

		if ($product_id != 0)
		{
			$and = " AND product_id='" . $product_id . "' ";
		}

		if ($order_item_id != 0)
		{
			$and_order_item = " AND order_item_id='" . $order_item_id . "' ";
		}

		if ($product_id != 0)
		{
			$field = ", customer_note='" . $comment . "' ";
		}

		$query = "UPDATE " . $this->_table_prefix . "order_item " . "SET order_status='" . $newstatus . "' " . $field
			. "WHERE order_id IN(" . $order_id . ") " . $and . $and_order_item;
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	public function manageContainerStock($product_id, $quantity, $container_id)
	{
		// Adding the products from the container. means decreasing stock

		$query = "SELECT quantity FROM " . $this->_table_prefix . "container_product_xref " . "WHERE container_id='"
			. $container_id . "' AND product_id='" . $product_id . "' ";
		$this->_db->setQuery($query);
		$con_product_qun = $this->_db->loadResult();
		$con_product_qun = $con_product_qun + $quantity;

		if ($con_product_qun > 0)
		{
			$query = 'UPDATE ' . $this->_table_prefix . 'container_product_xref ' . 'SET quantity = "' . $con_product_qun
				. '" ' . ' WHERE container_id="' . $container_id . '" AND product_id="' . $product_id . '" ';
			$this->_db->setQuery($query);
			$this->_db->query();
		}
	}

	public function getOrderStatus()
	{
		$query = "SELECT order_status_code AS value, order_status_name AS text " . "FROM " . $this->_table_prefix
			. "order_status " . "where published='1' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();
		$this->_orderstatuslist = $list;

		return $list;
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

	public function getCustomOrderStatus($field)
	{
		if ($field == "shipping")
		{
			$fieldname = "shipping_custom_order_status";
		}

		if ($field == "confirmed")
		{
			$fieldname = "confirmed_custom_order_status";
		}

		if ($field == "canceled")
		{
			$fieldname = "canceled_custom_order_status";
		}

		$query = "SELECT " . $fieldname . " AS value " . "FROM " . $this->_table_prefix_crm . "configuration ";
		$this->_db->setQuery($query);

		$list = $this->_db->loadObjectList();
		$this->_customorderstatuslist = $list;

		return $list;
	}

	public function getcustomstatuslist($name = 'customstatuslist', $selected = '', $attributes = ' class="inputbox" size="1" ', $field = '')
	{
		$this->_customorderstatuslist = array();

		if (!$this->_customorderstatuslist)
		{
			$this->_customorderstatuslist = $this->getCustomOrderStatus($field);
		}

		$tot_status_change = @explode(",", $this->_customorderstatuslist[0]->value);

		for ($t = 0; $t < count($tot_status_change); $t++)
		{
			$this->_customorderstatuslist[$t]->value = $tot_status_change[$t];
			$this->_customorderstatuslist[$t]->text = $this->getOrderStatusTitle($tot_status_change[$t]);
		}

		$types[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_STATUS_LBL') . ' -');
		$types = array_merge($types, $this->_customorderstatuslist);

		$mylist['customstatuslist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $selected);

		return $mylist['customstatuslist'];
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

	public function update_status()
	{
		$app = JFactory::getApplication();
		$helper = new redhelper;
		$producthelper = new producthelper;
		$stockroomhelper = new rsstockroomhelper;

		$post = JRequest::get('post');
		$tmpl = JRequest::getVar('tmpl');
		$newstatus = JRequest::getVar('status');
		$paymentstatus = JRequest::getVar('order_paymentstatus');
		$option = JRequest::getVar('option');
		$return = JRequest::getVar('return');

		$customer_note = JRequest::getVar('customer_note', '', 'request', 'string', JREQUEST_ALLOWRAW);
		$requisition_number = JRequest::getVar('requisition_number', '');
		$oid = JRequest::getVar('order_id', array(), 'method', 'array');
		$order_id = $oid[0];
		$sendordermail = JRequest::getVar("order_sendordermail");

		$isproduct = JRequest::getInt('isproduct', 0);
		$product_id = JRequest::getInt('product_id', 0);
		$order_item_id = JRequest::getInt('order_item_id', 0);

		if (JRequest::getVar('isarchive') != 0 && JRequest::getVar('isarchive') != "")
		{
			$isarchive = "&isarchive=1";
		}

		else
		{
			$isarchive = "";
		}

		/**
		 * redCRM includes
		 */
		if ($helper->isredCRM())
		{
			if ($newstatus == 'C')
			{
				if (JRequest::getVar('customstatusconfirmed'))
				{
					$customstatus = JRequest::getVar('customstatusconfirmed');
				}
				else
				{
					$customstatus = "";
				}
			}
			elseif ($newstatus == 'S')
			{
				if (JRequest::getVar('customstatusshipping'))
				{
					$customstatus = JRequest::getVar('customstatusshipping');
				}
				else
				{
					$customstatus = "";
				}
			}
			elseif ($newstatus == 'X')
			{
				if (JRequest::getVar('customstatuscanceled'))
				{
					$customstatus = JRequest::getVar('customstatuscanceled');
				}
				else
				{
					$customstatus = "";
				}
			}
			else
			{
				$customstatus = "";
			}

			$crmSupplierOrderHelper = new crmSupplierOrderHelper;
			$crmSupplierOrderHelper->redSHOPCustomOrderUpdate($order_id, $customstatus);

			if (ENABLE_ITEM_TRACKING_SYSTEM)
			{
				// Supplier order helper object
				$getStatus = array();
				$getStatus['orderstatus'] = $newstatus;
				$getStatus['paymentstatus'] = $paymentstatus;

				$crmSupplierOrderHelper->redSHOPOrderUpdate($order_id, $getStatus);
				unset($getStatus);
			}
		}

		if (isset($paymentstatus))
		{
			$this->updateOrderPaymentStatus($order_id, $paymentstatus);
		}

		$order_log = JTable::getInstance('order_status_log', 'Table');

		if (!$isproduct)
		{
			$data['order_id'] = $order_id;
			$data['order_status'] = $newstatus;
			$data['order_payment_status'] = $paymentstatus;
			$data['date_changed'] = time();
			$data['customer_note'] = $customer_note;

			if (!$order_log->bind($data))
			{
				return JError::raiseWarning(500, $order_log->getError());
			}

			if (!$order_log->store())
			{
				JError::raiseError(500, $order_log->getError());
			}

			$this->updateOrderComment($order_id, $customer_note);

			if (isset($post['requisition_number']))
			{
				$this->updateOrderRequisitionNumber($order_id, $post['requisition_number']);
			}

			// Changing the status of the order
			$this->updateOrderStatus($order_id, $newstatus, $order_log->order_status_log_id);

			if ($paymentstatus == "Paid" && $newstatus == "S")
			{
				// For Consignor Label generation
				JPluginHelper::importPlugin('redshop_shippinglabel');
				$dispatcher = JDispatcher::getInstance();
				$results = $dispatcher->trigger('onChangeStatusToShipped', array($order_id, $newstatus, $paymentstatus));
			}

			if ($paymentstatus == "Paid")
			{
				JModel::addIncludePath(JPATH_SITE . '/components/com_redshop/models');
				$checkoutModelcheckout = JModel::getInstance('checkout', 'checkoutModel');
				$checkoutModelcheckout->sendGiftCard($order_id);

				// Send the Order mail
				$redshopMail = new redshopMail;

				if (ORDER_MAIL_AFTER)
				{
					$redshopMail->sendOrderMail($order_id);
				}

				elseif (INVOICE_MAIL_ENABLE)
				{
					$redshopMail->sendInvoiceMail($order_id);
				}
			}

			$this->createWebPacklabel($order_id, $specifiedSendDate, $newstatus, $paymentstatus);
		}

		$this->updateOrderItemStatus($order_id, $product_id, $newstatus, $customer_note, $order_item_id);
		$helper->clickatellSMS($order_id);

		switch ($newstatus)
		{
			case "X";

				// If order is cancelled then, putting stock in the container from where it was dedcuted
				$orderproducts = $this->getOrderItemDetail($order_id);

				for ($i = 0; $i < count($orderproducts); $i++)
				{
					$conid = $orderproducts[$i]->container_id;
					$prodid = $orderproducts[$i]->product_id;
					$prodqty = $orderproducts[$i]->stockroom_quantity;

					// When the order is set to "cancelled",product will return to stock
					$stockroomhelper->manageStockAmount($prodid, $prodqty, $orderproducts[$i]->stockroom_id);
					$producthelper->makeAttributeOrder($orderproducts[$i]->order_item_id, 0, $prodid, 1);

					// If order is cancelled then, putting stock in the container from where it was dedcuted end
					if (USE_CONTAINER)
					{
						$this->manageContainerStock($prodid, $prodqty, $conid);
					}
				}
				break;

			case "RT":
				if ($isproduct)
				{
					if (USE_CONTAINER)
					{
						$orderproductdetail = $this->getOrderItemDetail($order_id, $product_id);
						$conid = $orderproductdetail[0]->container_id;
						$prodqty = $orderproductdetail[0]->product_quantity;

						$this->manageContainerStock($product_id, $prodqty, $conid);
					}

					// Changing the status of the order item to Returned
					$this->updateOrderItemStatus($order_id, $product_id, "RT", $customer_note, $order_item_id);

					// Changing the status of the order to Partially Returned
					$this->updateOrderStatus($order_id, "PRT");
				}
				else
				{
					$orderproducts = $this->getOrderItemDetail($order_id);

					for ($i = 0; $i < count($orderproducts); $i++)
					{
						$conid = $orderproducts[$i]->container_id;
						$prodid = $orderproducts[$i]->product_id;
						$prodqty = $orderproducts[$i]->product_quantity;

						if (USE_CONTAINER)
						{
							$this->manageContainerStock($prodid, $prodqty, $conid);
						}
					}
				}
				break;

			case "RC":
				if ($isproduct)
				{
					// Changing the status of the order item to Reclamation
					$this->updateOrderItemStatus($order_id, $product_id, "RC", $customer_note, $order_item_id);

					// Changing the status of the order to Partially Reclamation
					$this->updateOrderStatus($order_id, "PRC");
				}
				else
				{
					$orderproducts = $this->getOrderItemDetail($order_id);

					for ($i = 0; $i < count($orderproducts); $i++)
					{
						$conid = $orderproducts[$i]->container_id;
						$prodid = $orderproducts[$i]->product_id;
						$prodqty = $orderproducts[$i]->product_quantity;

						if (USE_CONTAINER)
						{
							$this->manageContainerStock($prodid, $prodqty, $conid);
						}
					}
				}
				break;

			case "S":
				if ($isproduct)
				{
					// Changing the status of the order item to Reclamation
					$this->updateOrderItemStatus($order_id, $product_id, "S", $customer_note, $order_item_id);

					// Changing the status of the order to Partially Reclamation
					$this->updateOrderStatus($order_id, "PS");
				}
				break;

			case "C":
				// SensDownload Products
				$this->SendDownload($order_id);
				break;
		}

		if ($sendordermail == 'true')
		{
			$this->changeOrderStatusMail($order_id, $newstatus, $customer_note);
		}

		$this->createBookInvoice($order_id, $newstatus);

		$msg = JText::_('COM_REDSHOP_ORDER_STATUS_SUCCESSFULLY_SAVED_FOR_ORDER_ID') . " " . $order_id;

		if ($return == 'order')
		{
			if ($option == 'com_redcrm')
			{
				$app->Redirect('index.php?option=' . $option . '&view=' . $return . '&cid[]=' . $order_id . '' . $isarchive . '', $msg);
			}
			else
			{
				$app->Redirect('index.php?option=' . $option . '&view=' . $return . '' . $isarchive . '', $msg);
			}
		}
		else
		{
			if ($tmpl != "")
			{
				$app->Redirect('index.php?option=' . $option . '&view=' . $return . '&cid[]=' . $order_id . '&tmpl=' . $tmpl . '' . $isarchive . '', $msg);
			}
			else
			{
				$app->Redirect('index.php?option=' . $option . '&view=' . $return . '&cid[]=' . $order_id . '' . $isarchive . '', $msg);
			}
		}
	}

	public function update_status_all()
	{
		$app = JFactory::getApplication();

		$helper = new redhelper;
		$stockroomhelper = new rsstockroomhelper;
		$producthelper = new producthelper;
		$newstatus = JRequest::getVar('order_status_all');
		$option = JRequest::getVar('option');
		$return = JRequest::getVar('return');
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');

		$data['order_status'] = $newstatus;
		$data['date_changed'] = time();
		$invociepdfname = "";

		for ($i = 0; $i < count($cid); $i++)
		{
			$oid = array((int) $cid[$i]);

			$nc = JRequest::getVar('nc' . $oid[0]);
			$c_note = JRequest::getVar('customer_note' . $oid[0]);
			$isproduct = JRequest::getVar('isproduct');

			// Add status log...
			$order_log = JTable::getInstance('order_status_log', 'Table');
			$data['order_id'] = $oid[0];
			$data['customer_note'] = $c_note;

			if (!$order_log->bind($data))
			{
				return JError::raiseWarning(500, $order_log->getError());
			}

			if (!$order_log->store())
			{
				JError::raiseError(500, $order_log->getError());
			}

			// Changing the status of the order
			$this->updateOrderStatus($oid[0], $newstatus);
			$paymentstatus = JRequest::getVar('order_paymentstatus' . $oid[0]);

			if (isset($paymentstatus))
			{
				$this->updateOrderPaymentStatus($oid[0], $paymentstatus);
			}

			if ($paymentstatus == "Paid")
			{
				JModel::addIncludePath(JPATH_SITE . '/components/com_redshop/models');
				$checkoutModelcheckout = JModel::getInstance('checkout', 'checkoutModel');
				$checkoutModelcheckout->sendGiftCard($oid[0]);

				// Send the Order mail
				$redshopMail = new redshopMail;

				if (ORDER_MAIL_AFTER)
				{
					$redshopMail->sendOrderMail($oid[0]);
				}
				elseif (INVOICE_MAIL_ENABLE)
				{
					$redshopMail->sendInvoiceMail($oid[0]);
				}
			}

			if ($paymentstatus == "Paid" && $newstatus == 'S')
			{
				// For shipped pdf generaton
				$order_shipped_id = $oid[0];
				$invociepdfname = $this->createShippedInvoicePdf($order_shipped_id);

				// For Consignor Label generation
				JPluginHelper::importPlugin('redshop_shippinglabel');
				$dispatcher = JDispatcher::getInstance();
				$results = $dispatcher->trigger('onChangeStatusToShipped', array($oid[0], $newstatus, $paymentstatus));

			}

			// For Webpack Postdk Label Generation
			$this->createWebPacklabel($oid[0], $specifiedSendDate, $newstatus, $paymentstatus);

			// Changing the status of the order end
			$helper->clickatellSMS($data['order_id']);

			// If changing the status of the order then there item status need to change
			if ($isproduct != 1)
			{
				$this->updateOrderItemStatus($oid[0], 0, $newstatus);
			}

			// If order is cancelled then, putting stock in the container from where it was dedcuted
			if ($newstatus == 'X')
			{
				$orderproducts = $this->getOrderItemDetail($oid[0]);

				for ($j = 0; $j < count($orderproducts); $j++)
				{
					$conid = $orderproducts[$j]->container_id;
					$prodid = $orderproducts[$j]->product_id;
					$prodqty = $orderproducts[$j]->stockroom_quantity;

					// When the order is set to "cancelled",product will return to stock
					$stockroomhelper->manageStockAmount($prodid, $prodqty, $orderproducts[$j]->stockroom_id);
					$producthelper->makeAttributeOrder($orderproducts[$j]->order_item_id, 0, $prodid, 1);

					// If order is cancelled then, putting stock in the container from where it was dedcuted end
					if (USE_CONTAINER)
					{
						$this->manageContainerStock($prodid, $prodqty, $conid);
					}
				}
			}

			// If any of the item from the order is returuned back then,
			// change the status of whole order and also put back to stock.
			if ($newstatus == 'RT')
			{
				if ($isproduct)
				{
					$pid = JRequest::getVar('product_id');

					$orderproductdetail = $this->getOrderItemDetail($oid[0], $pid);

					$conid = $orderproductdetail[0]->container_id;
					$prodid = $orderproductdetail[0]->product_id;
					$prodqty = $orderproductdetail[0]->product_quantity;

					if (USE_CONTAINER)
					{
						$this->manageContainerStock($prodid, $prodqty, $conid);
					}

					// Changing the status of the order item to Returned
					$this->updateOrderItemStatus($oid[0], $prodid, "RT");

					// Changing the status of the order to Partially Returned
					$this->updateOrderStatus($oid[0], "PRT");
				}
				else
				{
					$orderproducts = $this->getOrderItemDetail($oid[0]);

					for ($k = 0; $k < count($orderproducts); $k++)
					{
						$conid = $orderproducts[$k]->container_id;
						$prodid = $orderproducts[$k]->product_id;
						$prodqty = $orderproducts[$k]->product_quantity;

						if (USE_CONTAINER)
						{
							$this->manageContainerStock($prodid, $prodqty, $conid);
						}
					}
				}
			}

			// If any of the item from the order is reclamation back then,
			// change the status of whole order and also put back to stock.
			if ($newstatus == 'RC')
			{
				if ($isproduct)
				{
					$pid = JRequest::getVar('product_id');

					// Changing the status of the order item to Reclamation
					$this->updateOrderItemStatus($oid[0], $pid, "RC");

					// Changing the status of the order to Partially Reclamation
					$this->updateOrderStatus($oid[0], "PRC");
				}
				else
				{
					$orderproducts = $this->getOrderItemDetail($oid[0]);

					for ($l = 0; $l < count($orderproducts); $l++)
					{
						$conid = $orderproducts[$l]->container_id;
						$prodid = $orderproducts[$l]->product_id;
						$prodqty = $orderproducts[$l]->product_quantity;

						if (USE_CONTAINER)
						{
							$this->manageContainerStock($prodid, $prodqty, $conid);
						}
					}
				}
			}

			// If any of the item from the order is reclamation back then,
			// change the status of whole order and also put back to stock.
			if ($newstatus == 'S')
			{
				if ($isproduct)
				{
					$pid = JRequest::getVar('product_id');

					// Changing the status of the order item to Reclamation
					$this->updateOrderItemStatus($oid[0], $pid, "S");

					// Changing the status of the order to Partially Reclamation
					$this->updateOrderStatus($oid[0], "PS");
				}
			}

			// Mail to customer of order status change
			$this->changeOrderStatusMail($oid[0], $newstatus, $c_note);

			$this->createBookInvoice($oid[0], $newstatus);
		}

		if ($return == 'order')
		{
			$link = 'index.php?option=' . $option . '&view=' . $return;
		}
		else
		{
			$link = 'index.php?option=' . $option . '&view=' . $return . '&cid[]=' . $oid[0];
		}
		?>
    <script type="text/javascript">
    <?php
		if ($invociepdfname != "")
		{
			if (file_exists(REDSHOP_FRONT_DOCUMENT_RELPATH . "invoice/" . $invociepdfname . ".pdf"))
			{
	?>
            	window.open("<?php echo REDSHOP_FRONT_DOCUMENT_ABSPATH?>invoice/<?php echo $invociepdfname?>.pdf");
	<?php
			}
		}
	?>
   		window.parent.location = '<?php echo $link?>';
    </script>
	<?php
	}

	public function getOrderDetails($order_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "orders " . "WHERE order_id='" . $order_id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function getmultiOrderDetails($order_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "orders " . "WHERE order_id='" . $order_id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getUserOrderDetails($user_id = 0, $order_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "orders " . "WHERE user_id='" . $user_id . "' ORDER BY `order_id` DESC";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getOrderItemDetail($order_id = 0, $product_id = 0, $order_item_id = 0)
	{
		$and = "";

		if ($order_id != 0)
		{
			$and .= " AND order_id IN (" . $order_id . ") ";
		}

		if ($product_id != 0)
		{
			$and .= " AND product_id='" . $product_id . "' ";
		}

		if ($order_item_id != 0)
		{
			$and .= " AND order_item_id='" . $order_item_id . "' ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "order_item " . "WHERE 1=1 " . $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getOrderPaymentDetail($order_id, $payment_order_id = 0)
	{
		$and = '';

		if ($payment_order_id != 0)
		{
			$and = ' AND payment_order_id="' . $payment_order_id . '" ';
		}

		$query = 'SELECT * FROM ' . $this->_table_prefix . 'order_payment ' . 'WHERE order_id="' . $order_id . '" ' . $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getOrderPartialPayment($order_id)
	{
		$query = 'SELECT order_payment_amount FROM ' . $this->_table_prefix . 'order_payment ' . 'WHERE order_id="' . $order_id . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		$spilt_payment_amount = 0;

		for ($i = 0; $i < count($list); $i++)
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

		if ($shipping_class != '')
		{
			$and = "AND element='" . $shipping_class . "' ";
		}

		$folder = strtolower('redshop_shipping');

		$query = "SELECT * FROM #__extensions " . "WHERE enabled = '1' " . "AND LOWER(`folder`) = '{$folder}' " . $and . "ORDER BY ordering ASC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getPaymentMethodInfo($payment_method_class = '')
	{
		$and = "";

		if ($payment_method_class != '')
		{
			$and = "AND element='" . $payment_method_class . "' ";
		}

		$folder = strtolower('redshop_payment');

		$query = "SELECT * FROM #__extensions " . "WHERE enabled = '1' " . $and . "AND LOWER(`folder`) = '{$folder}' " . "ORDER BY ordering ASC ";

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getBillingAddress($user_id = 0)
	{
		$helper = new redhelper;
		$option = JRequest::getVar('option');

		$user = JFactory::getUser();

		// Get Joomla Session
		$session = JFactory::getSession();

		// Get redCRM Contact person session array
		$isredcrmuser = $session->get('isredcrmuser', false);
		$list = array();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($helper->isredCRM())
		{
			$crmHelper = new crmHelper;
			$crmDebitorHelper = new crmDebitorHelper;

			$cp_user_id = $user_id;

			/*
			 * function will check loign redshop user
			 * is redCRM contact person or not
			 *
			 * @return: joomla user of redSHOP billing user belong to contact person
			 */
			$crmusers = $crmHelper->getBillingUserId($user_id);

			if (isset($crmusers->user_id))
			{
				$user_id = $crmusers->user_id;
			}

			$query = 'SELECT ui.*,CONCAT(firstname," ",lastname) AS text,d.*,ui.users_info_id FROM '
				. $this->_table_prefix . 'users_info as ui ' . 'LEFT JOIN ' . $this->_table_prefix_crm
				. 'debitors as d ON d.users_info_id = ui.users_info_id ' . 'WHERE address_type like "BT" '
				. 'AND user_id="' . $user_id . '" ';
			$this->_db->setQuery($query);
			$list = $this->_db->loadObject();

			if ($isredcrmuser)
			{
				$contact_person = $crmDebitorHelper->getContactPersons(0, 0, 0, $cp_user_id);

				if (count($contact_person) > 0)
				{
					$contact_person = $contact_person[0];
					$person_name = explode(" ", $contact_person->person_name);

					$list->firstname = @$person_name[0];
					$list->lastname = @$person_name[1];
					$list->text = $contact_person->person_name;
					$list->user_email = $contact_person->person_email;
				}
			}

			return $list;
		}

		$query = 'SELECT *,CONCAT(firstname," ",lastname) AS text FROM ' . $this->_table_prefix
			. 'users_info ' . 'WHERE address_type like "BT" ' . 'AND user_id="' . $user_id . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function getOrderBillingUserInfo($order_id)
	{
		$helper = new redhelper;

		$query = 'SELECT * FROM ' . $this->_table_prefix . 'order_users_info ' . 'WHERE address_type LIKE "BT" '
			. 'AND order_id="' . $order_id . '" ';

		if ($helper->isredCRM())
		{
			$query = 'SELECT oui.*,cd.customer_number,IFNULL(cp.person_name ,CONCAT(oui.firstname," ",oui.lastname)) AS text FROM '
				. $this->_table_prefix . 'order_users_info as oui '
				. 'LEFT JOIN ' . $this->_table_prefix_crm . 'order as co ON co.order_id = oui.order_id ' . 'LEFT JOIN '
				. $this->_table_prefix_crm . 'contact_persons as cp ON cp.person_id  = co.person_id ' . 'LEFT JOIN '
				. $this->_table_prefix_crm . 'debitors as cd ON cd.users_info_id = co.debitor_id ' . 'WHERE oui.address_type LIKE "BT" '
				. 'AND oui.order_id="' . $order_id . '" ';
		}

		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function getShippingAddress($user_id = 0)
	{
		$helper = new redhelper;

		$user = JFactory::getUser();

		$session = JFactory::getSession();

		// Get redCRM Contact person session array
		$isredcrmuser = $session->get('isredcrmuser', false);

		$isredcrmuser_debitor = $session->get('isredcrmuser_debitor', false);

		$app = JFactory::getApplication();
		$is_admin = $app->isAdmin();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($helper->isredCRM())
		{
			$crmHelper = new crmHelper;
			$crmDebitorHelper = new crmDebitorHelper;

			/*
			 * function will check loign redshop user
			 * is redCRM contact person or not
			 *
			 * @return: joomla user of redSHOP shipping user belong to contact person
			 */
			$crmuserssid = 0;
			$crmusers = array();

			if ($isredcrmuser)
			{
				$contact_person = $crmDebitorHelper->getContactPersons(0, 0, 0, $user_id);
				$contact_person = $contact_person[0];

				$crmuserssid = $contact_person->shipping_id;
				$crmusers = $crmHelper->getShippingUserId(0, $crmuserssid);
			}

			$list = array();

			if (count($crmusers) > 0)
			{
				$crmusersinfo = '';
				$crmusersinfo = implode(",", $crmusers);

				$query = 'SELECT ui.*,IFNULL(destination_name,CONCAT(firstname," ",lastname)) AS text FROM '
					. $this->_table_prefix . 'users_info as ui' . ' LEFT JOIN '
					. $this->_table_prefix_crm . 'shipping as rcs ON rcs.users_info_id = ui.users_info_id '
					. ' WHERE address_type like "ST" ' . ' AND ui.users_info_id IN (' . $crmusersinfo . ') ';
			}
			else
			{
				if (!$is_admin)
				{
					$query = 'SELECT ui.*,IFNULL(destination_name,CONCAT(firstname," ",lastname)) AS text FROM '
						. $this->_table_prefix . 'users_info as ui'
						. ' LEFT JOIN #__redcrm_shipping as rcs ON rcs.users_info_id = ui.users_info_id '
						. ' WHERE address_type like "ST" AND rcs.destination_name!="" '
						. ' AND rcs.debitor_id IN (' . $isredcrmuser_debitor . ') ';
				}
				else
				{
					$query = 'SELECT ui.*,IFNULL(destination_name,CONCAT(firstname," ",lastname)) AS text FROM '
						. $this->_table_prefix . 'users_info as ui'
						. ' LEFT JOIN #__redcrm_shipping as rcs ON rcs.users_info_id = ui.users_info_id '
						. ' WHERE address_type like "ST" ' . ' AND ui.user_id IN (' . $user_id . ') ';
				}
			}

			$this->_db->setQuery($query);
			$list = $this->_db->loadObjectlist();

			return $list;
		}

		$query = 'SELECT *,CONCAT(firstname," ",lastname) AS text FROM ' . $this->_table_prefix . 'users_info '
			. 'WHERE address_type="ST" ' . 'AND user_id="' . $user_id . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getOrderShippingUserInfo($order_id)
	{
		$helper = new redhelper;

		if ($helper->isredCRM())
		{
			$order = $this->getOrderDetails($order_id);
			$crmDebitorHelper = new crmDebitorHelper;

			/*
			 * get shippinginfo for redCRM
			 */
			$crmusers = $crmDebitorHelper->getShippingInfo(0, $order->user_info_id);

			if (count($crmusers) > 0)
			{
				$crmusers = $crmusers[0];

				return $crmusers;
			}
		}

		$query = 'SELECT * FROM ' . $this->_table_prefix . 'order_users_info ' . 'WHERE address_type LIKE "ST" '
			. 'AND order_id="' . $order_id . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function getUserFullname($user_id)
	{
		$fullname = "";
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$query = "SELECT firstname, lastname FROM " . $this->_table_prefix . "users_info " . "WHERE address_type like 'BT' "
			. "AND user_id='" . $user_id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		if ($list)
		{
			$fullname = $list->firstname . " " . $list->lastname;
		}
		else
		{
			$query = "SELECT name FROM #__users " . "WHERE id='" . $user_id . "' ";
			$this->_db->setQuery($query);
			$list = $this->_db->loadObject();

			if ($list)
			{
				$fullname = $list->name;
			}
		}

		return $fullname;
	}

	public function getOrderItemAccessoryDetail($order_item_id = 0)
	{
		$and = "";

		if ($order_item_id != 0)
		{
			$and .= " AND order_item_id='" . $order_item_id . "' ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "order_acc_item " . "WHERE 1=1 " . $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getOrderItemAttributeDetail($order_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		$and = "";

		if ($order_item_id != 0)
		{
			$and .= " AND order_item_id='" . $order_item_id . "' ";
		}

		if ($parent_section_id != 0)
		{
			$and .= " AND parent_section_id='" . $parent_section_id . "' ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "order_attribute_item " . "WHERE is_accessory_att='" . $is_accessory . "' " . "AND section='" . $section . "' " . $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getOrderUserfieldData($order_item_id = 0, $section = 0)
	{
		$query = "SELECT fd.*,f.field_title,f.field_type,f.field_name FROM " . $this->_table_prefix . "fields_data AS fd " . "LEFT JOIN " . $this->_table_prefix . "fields AS f ON f.field_id=fd.fieldid " . "WHERE fd.itemid='" . $order_item_id . "' " . "AND fd.section='" . $section . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	function generateOrderNumber($p_length = '30')
	{
		$query = "SELECT MAX(order_id) FROM " . $this->_table_prefix . "orders";
		$this->_db->setQuery($query);
		$maxId = $this->_db->loadResult();

		/*
		 * if Economic Integration is on !!!
		 * We are not using Order Invoice Number Template
		 * Economic Order Number Only Support (int) value.
		 * Invoice Number May be varchar or int.
		 */
		if (ECONOMIC_INTEGRATION)
		{
			$query = "SELECT order_number FROM " . $this->_table_prefix . "orders " . "WHERE order_id='" . $maxId . "'";
			$this->_db->setQuery($query);
			$maxOrderNumber = $this->_db->loadResult();
			$economic = new economic;
			$maxInvoice = $economic->getMaxOrderNumberInEconomic();
			$maxId = max(intval($maxOrderNumber), $maxInvoice);
		}
		elseif (INVOICE_NUMBER_TEMPLATE)
		{
			$maxId = ($maxId + FIRST_INVOICE_NUMBER + 1);

			$format = sprintf("%06d", $maxId);
			$order_number = str_replace("XXXXXX", $format, INVOICE_NUMBER_TEMPLATE);
			$order_number = str_replace("xxxxxx", $format, INVOICE_NUMBER_TEMPLATE);
			$order_number = str_replace("######", $format, INVOICE_NUMBER_TEMPLATE);

			$format = sprintf("%05d", $maxId);
			$order_number = str_replace("XXXXX", $format, $order_number);
			$order_number = str_replace("xxxxx", $format, $order_number);
			$order_number = str_replace("#####", $format, $order_number);

			$format = sprintf("%04d", $maxId);
			$order_number = str_replace("XXXX", $format, $order_number);
			$order_number = str_replace("xxxx", $format, $order_number);
			$order_number = str_replace("####", $format, $order_number);

			$format = sprintf("%03d", $maxId);
			$order_number = str_replace("XXX", $format, $order_number);
			$order_number = str_replace("xxx", $format, $order_number);
			$order_number = str_replace("###", $format, $order_number);

			$format = sprintf("%02d", $maxId);
			$order_number = str_replace("XX", $format, $order_number);
			$order_number = str_replace("xx", $format, $order_number);
			$order_number = str_replace("##", $format, $order_number);

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
		$redhelper = new redhelper;
		$and = '';
		$cntname = '';

		if ($cnt3 != "")
		{
			$and .= ' AND country_3_code="' . $cnt3 . '" ';
		}
		else
		{
			return $cntname;
		}

		$query = 'SELECT country_3_code AS value,country_name AS text,country_jtext FROM '
			. $this->_table_prefix . 'country ' . 'WHERE 1=1 ' . $and;
		$this->_db->setQuery($query);
		$countries = $this->_db->loadObjectList();
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

		if ($st3 != "")
		{
			$and .= ' AND s.state_2_code="' . $st3 . '" ';
		}
		else
		{
			return $stname;
		}

		if ($cnt3 != "")
		{
			$and .= ' AND c.country_3_code="' . $cnt3 . '" ';
		}

		$query = 'SELECT s.state_name FROM ' . $this->_table_prefix . 'state AS s ' . ','
			. $this->_table_prefix . 'country AS c ' . 'WHERE c.country_id=s.country_id ' . $and;
		$this->_db->setQuery($query);
		$stname = $this->_db->loadResult();

		return $stname;
	}

	public function SendDownload($order_id = 0)
	{
		$config = new Redconfiguration;
		$app = JFactory::getApplication();
		$redshopMail = new redshopMail;

		// Getting the order status changed template from mail center end
		$MailFrom = $app->getCfg('mailfrom');
		$FromName = $app->getCfg('fromname');
		$SiteName = $app->getCfg('sitename');

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

		// Getting the order details
		$orderdetail = $this->getOrderDetails($order_id);

		// Getting user details
		$query = "SELECT uf.firstname, uf.lastname, IFNULL( u.email , uf.`user_email`) AS email
				FROM " . $this->_table_prefix . "users_info AS uf
				LEFT JOIN #__users AS u ON uf.user_id = u.id
				WHERE uf.user_id = '" . $rows[0]->user_id . "'
				AND uf.`address_type` = 'BT'";
		$this->_db->setQuery($query);
		$userdetail = $this->_db->loadObject();

		$userfullname = $userdetail->firstname . " " . $userdetail->lastname;
		$useremail = $userdetail->email;

		$mailbody = "";

		$i = 0;

		if (count($rows) > 0)
		{
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

				$mailtoken = "<a href='" . JUri::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid="
					. $row->download_id . "'>" . $downloadfilename . "</a>";

				$datamessage = str_replace("{product_serial_number}", $row->product_serial_number, $datamessage);
				$datamessage = str_replace("{product_name}", $row->product_name, $datamessage);
				$datamessage = str_replace("{token}", $mailtoken, $datamessage);
				$i++;

				$pmiddle .= $datamessage;
			}

			$maildata = $productstart . $pmiddle . $productend;
			$mailbody = $maildata;
			$mailsubject = str_replace("{order_number}", $orderdetail->order_number, $mailsubject);

			if ($mailbody && $useremail != "")
			{
				JUtility::sendMail($MailFrom, $FromName, $useremail, $mailsubject, $mailbody, 1, null, $mailbcc);
			}
		}

		return true;
	}

	public function getDownloadProduct($order_id)
	{
		$query = "SELECT pd.*,product_name FROM " . $this->_table_prefix . "product_download AS pd " . ","
			. $this->_table_prefix . "product AS p " . "WHERE pd.product_id=p.product_id " . "AND order_id='" . $order_id . "' ";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function getDownloadProductLog($order_id, $did = '')
	{
		$whereDownload_id = ($did != '') ? " AND pdl.download_id = '" . $did . "'" : "";

		$query = "SELECT pdl . * , pd.order_id, pd.product_id, pd.file_name " . " FROM `"
			. $this->_table_prefix . "product_download_log` AS pdl " . " LEFT JOIN " . $this->_table_prefix
			. "product_download AS pd ON pd.download_id = pdl.download_id" . " WHERE pd.order_id = '"
			. $order_id . "' " . $whereDownload_id;
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function getparameters($payment)
	{
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$this->_db->setQuery($sql);
		$params = $this->_db->loadObjectList();

		return $params;
	}

	public function getpaymentinformation($row, $post)
	{
		$app = JFactory::getApplication();
		require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
		$redconfig = new Redconfiguration;

		$plugin_parameters = $this->getparameters($post['payment_method_class']);
		$paymentinfo = $plugin_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$is_creditcard = $paymentparams->get('is_creditcard', '');

		$order = $this->getOrderDetails($row->order_id);

		$adminpath = JPATH_ADMINISTRATOR . '/components/com_redshop';
		$invalid_elements = $paymentparams->get('invalid_elements', '');

		// Send the order_id and orderpayment_id to the payment plugin so it knows which DB record to update upon successful payment
		$objorder = new order_functions;
		$user = JFactory::getUser();

		$userbillinginfo = $this->getOrderBillingUserInfo($row->order_id);

		$users_info_id = JRequest::getInt('users_info_id');

		$task = JRequest::getVar('task');

		$shippingaddresses = $this->getOrderShippingUserInfo($row->order_id);

		$shippingaddress = array();

		if (isset($shippingaddresses))
		{
			$shippingaddress = $shippingaddresses;

			$shippingaddress->country_2_code = $redconfig->getCountryCode2($shippingaddress->country_code);

			$shippingaddress->state_2_code = $redconfig->getCountryCode2($shippingaddress->state_code);

		}

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
			if ($values['payment_plugin'] == "rs_payment_banktransfer" || $values['payment_plugin'] == "rs_payment_banktransfer2" || $values['payment_plugin'] == "rs_payment_banktransfer3" || $values['payment_plugin'] == "rs_payment_cashtransfer" || $values['payment_plugin'] == "rs_payment_cashsale" || $values['payment_plugin'] == "rs_payment_banktransfer_discount")
			{
				$app->redirect(JURI::base() . "index.php?option=com_redshop&view=order_detail&layout=creditcardpayment&plugin=" . $values['payment_plugin'] . "&order_id=" . $row->order_id);
			}

			JPluginHelper::importPlugin('redshop_payment');
			$dispatcher = JDispatcher::getInstance();
			$results = $dispatcher->trigger('onPrePayment', array($values['payment_plugin'], $values));

		}
		else
		{
			$app->redirect(JURI::base() . "index.php?option=com_redshop&view=order_detail&layout=creditcardpayment&plugin=" . $values['payment_plugin'] . "&order_id=" . $row->order_id);

		}
	}

	function getshippinglocationinfo($shippingname)
	{
		$sql = "SELECT shipping_location_info FROM " . $this->_table_prefix . "shipping_rate WHERE shipping_rate_name='" . $shippingname . "'";
		$this->_db->setQuery($sql);
		$shippingloc = $this->_db->loadObjectList();

		return $shippingloc;
	}

	public function barcode_randon_number($lenth = 12, $barcodekey = 0)
	{
		$mainhelper = new redshopMail;
		$redTemplate = new Redtemplate;

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
				$url = JUri::root() . 'administrator/components/com_redshop/helpers/barcode/barcode.php?code='
					. $rand_barcode . '&encoding=EAN&scale=2&mode=png';

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_FAILONERROR, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 3);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "code='.$rand_barcode.'&encoding=EAN&scale=2&mode=png");
				$result = curl_exec($ch);
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
		$barcodequery = 'UPDATE ' . $this->_table_prefix . 'orders SET barcode="' . $barcode . '" WHERE order_id =' . $oid;
		$this->_db->setQuery($barcodequery);
		$this->_db->query();
	}

	public function checkupdateordersts($data)
	{
		$res = 1;
		$query = "SELECT * FROM " . $this->_table_prefix . "orders " . "WHERE order_status='" . $data->order_status_code . "' " . "AND order_payment_status='" . $data->order_payment_status_code . "' " . "AND order_id='" . $data->order_id . "' ";
		$this->_db->setQuery($query);
		$order_payment = $this->_db->loadObjectList();

		if (count($order_payment) == 0)
		{
			$res = 0;
		}

		return $res;
	}

	public function changeOrderStatusMail($order_id, $newstatus, $order_comment = '')
	{
		$app = JFactory::getApplication();
		$config = new Redconfiguration;
		$carthelper = new rsCarthelper;
		$order_functions = new order_functions;
		$redshopMail = new redshopMail;
		$shippinghelper = new shipping;
		$MailFrom = $app->getCfg('mailfrom');
		$FromName = $app->getCfg('fromname');
		$mailbcc = null;
		$mailtemplate = $redshopMail->getMailtemplate(0, '', 'mail_section LIKE "order_status" AND mail_order_status LIKE "' . $newstatus . '" ');

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

			for ($d = 0; $d < count($arr_discount); $d++)
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
			$maildata = str_replace($search_sub, $replace_sub, $maildata);

			// Changes to parse all tags same as order mail end
			$userdetail = $this->getOrderBillingUserInfo($order_id);

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
			$shippingaddresses = $this->getOrderShippingUserInfo($order_id);

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

			$details = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $orderdetail->ship_method_id)));

			if (count($details) <= 1)
			{
				$details = explode("|", $orderdetail->ship_method_id);
			}

			$shopLocation = $orderdetail->shop_id;

			if ($details[0] != 'plgredshop_shippingdefault_shipping_GLS')
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
			$mailsubject = str_replace($search, $replace, $mailsubject);

			if ($userdetail->user_email != '' && $mailbody)
			{
				JUtility::sendMail($MailFrom, $FromName, $userdetail->user_email, $mailsubject, $mailbody, 1, null, $mailbcc);
			}
		}
	}

	public function createBookInvoice($order_id, $order_status)
	{
		// Economic Integration start for invoice generate and book current invoice
		if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT != 1)
		{
			$economic = new economic;

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
				$invoiceHandle = $economic->createInvoiceInEconomic($order_id, $economicdata);
			}

			$bookinvoicepdf = $economic->bookInvoiceInEconomic($order_id, ECONOMIC_INVOICE_DRAFT);

			if (is_file($bookinvoicepdf))
			{
				$redshopMail = new redshopMail;
				$ret = $redshopMail->sendEconomicBookInvoiceMail($order_id, $bookinvoicepdf);
			}
		}
	}

	public function createMultiprintInvoicePdf($order_id)
	{
		$invoice = "";
		$redshopMail = new redshopMail;

		$invoice = $redshopMail->createMultiprintInvoicePdf($order_id);

		return $invoice;
	}

	public function createWebPacklabel($order_id, $specifiedSendDate, $order_status, $paymentstatus)
	{
		if (POSTDK_INTEGRATION && ($order_status == "S" && $paymentstatus == "Paid"))
		{
			$shippinghelper = new shipping;
			$order_details = $this->getOrderDetails($order_id);
			$details = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $order_details->ship_method_id)));

			if ($details[0] === 'plgredshop_shippingdefault_shipping' && !$order_details->order_label_create)
			{
				$generate_label = $this->generateParcel($order_id, $specifiedSendDate);

				if ($generate_label != "success")
				{
					JError::raiseWarning(21, $generate_label);
				}
			}
		}
	}

	public function createShippedInvoicePdf($order_id)
	{
		$redconfig = new Redconfiguration;
		$producthelper = new producthelper;
		$extra_field = new extra_field;
		$config = JFactory::getConfig();
		$redTemplate = new Redtemplate;
		$carthelper = new rsCarthelper;
		$redshopMail = new redshopMail;
		$message = "";
		$subject = "";
		$cart = '';

		$arr_discount_type = array();

		$mailinfo = $redTemplate->getTemplate("shippment_invoice_template");

		if (count($mailinfo) > 0)
		{
			$message = $mailinfo[0]->template_desc;
		}
		else
		{
			return false;
		}

		$row = $this->getOrderDetails($order_id);

		$barcode_code = $row->barcode;
		$arr_discount = explode('@', $row->discount_type);
		$discount_type = '';

		for ($d = 0; $d < count($arr_discount); $d++)
		{
			if ($arr_discount[$d])
			{
				$arr_discount_type = explode(':', $arr_discount[$d]);

				if ($arr_discount_type[0] == 'c')
				{
					$discount_type .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
				}

				if ($arr_discount_type[0] == 'v')
				{
					$discount_type .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
				}
			}
		}

		if (!$discount_type)
		{
			$discount_type = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
		}

		$search[] = "{discount_type}";
		$replace[] = $discount_type;

		$message = str_replace($search, $replace, $message);

		$message = $redshopMail->imginmail($message);
		$user = JFactory::getUser();
		$billingaddresses = $this->getOrderBillingUserInfo($order_id);
		$email = $billingaddresses->user_email;
		$userfullname = $billingaddresses->firstname . " " . $billingaddresses->lastname;
		$message = $carthelper->replaceOrderTemplate($row, $message);

		echo "<div id='redshopcomponent' class='redshop'>";

		if (strstr($message, "{barcode}"))
		{
			$img_url = REDSHOP_FRONT_IMAGES_RELPATH . "barcode/" . $barcode_code . ".png";

			if (function_exists("curl_init"))
			{
				$bar_codeIMG = '<img src="' . $img_url . '" alt="Barcode"  border="0" />';
				$message = str_replace("{barcode}", $bar_codeIMG, $message);
			}
		}

		$body = $message;

		return $body;
	}

	public function orderStatusUpdate($order_id, $post = array())
	{
		$helper = new redhelper;
		$stockroomhelper = new rsstockroomhelper;
		$producthelper = new producthelper;
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

		// For Consignor Label generation
		JPluginHelper::importPlugin('redshop_shippinglabel');
		$dispatcher = JDispatcher::getInstance();
		$results = $dispatcher->trigger('onChangeStatusToShipped', array($order_id, $newstatus, $paymentstatus));

		// For Webpack Postdk Label Generation
		$this->createWebPacklabel($order_id, "", $newstatus, $paymentstatus);

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

		// If order is cancelled then, putting stock in the container from where it was dedcuted
		if ($newstatus == 'X')
		{
			$orderproducts = $this->getOrderItemDetail($order_id);

			for ($j = 0; $j < count($orderproducts); $j++)
			{
				$conid = $orderproducts[$j]->container_id;
				$prodid = $orderproducts[$j]->product_id;
				$prodqty = $orderproducts[$j]->stockroom_quantity;

				// When the order is set to "cancelled",product will return to stock
				$stockroomhelper->manageStockAmount($prodid, $prodqty, $orderproducts[$j]->stockroom_id);
				$producthelper->makeAttributeOrder($orderproducts[$j]->order_item_id, 0, $prodid, 1);

				if (USE_CONTAINER)
				{
					$this->manageContainerStock($prodid, $prodqty, $conid);
				}
			}
		}
		elseif ($newstatus == 'RT')
		{
			// If any of the item from the order is returuned back then,
			// change the status of whole order and also put back to stock.
			if ($isproduct)
			{
				$orderproductdetail = $this->getOrderItemDetail($order_id, $product_id);

				$conid = $orderproductdetail[0]->container_id;
				$prodid = $orderproductdetail[0]->product_id;
				$prodqty = $orderproductdetail[0]->product_quantity;

				if (USE_CONTAINER)
				{
					$this->manageContainerStock($prodid, $prodqty, $conid);
				}

				// Changing the status of the order item to Returned
				$this->updateOrderItemStatus($order_id, $prodid, "RT");

				// Changing the status of the order to Partially Returned
				$this->updateOrderStatus($order_id, "PRT");
			}
			else
			{
				$orderproducts = $this->getOrderItemDetail($order_id);

				for ($k = 0; $k < count($orderproducts); $k++)
				{
					$conid = $orderproducts[$k]->container_id;
					$prodid = $orderproducts[$k]->product_id;
					$prodqty = $orderproducts[$k]->product_quantity;

					if (USE_CONTAINER)
					{
						$this->manageContainerStock($prodid, $prodqty, $conid);
					}
				}
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
			else
			{
				$orderproducts = $this->getOrderItemDetail($order_id);

				for ($l = 0; $l < count($orderproducts); $l++)
				{
					$conid = $orderproducts[$l]->container_id;
					$prodid = $orderproducts[$l]->product_id;
					$prodqty = $orderproducts[$l]->product_quantity;

					if (USE_CONTAINER)
					{
						$this->manageContainerStock($prodid, $prodqty, $conid);
					}
				}
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
