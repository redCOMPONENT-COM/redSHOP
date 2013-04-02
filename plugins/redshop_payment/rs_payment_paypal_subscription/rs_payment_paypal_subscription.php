<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';

class plgRedshop_paymentrs_payment_paypal_subscription extends JPlugin
{
	public $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgRedshop_paymentrs_payment_paypal_subscription(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_paypal_subscription');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_paypal_subscription')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $plugin . DS . $plugin . DS . 'extra_info.php';
		include $paymentpath;
	}

	public function onNotifyPaymentrs_payment_paypal_subscription($element, $request)
	{
		if ($element != 'rs_payment_paypal_subscription')
		{
			return;
		}

		$order_functions = new order_functions;
		$producthelper = new producthelper;
		$stockroomhelper = new rsstockroomhelper;
		$redshopMail = new redshopMail;
		$db = JFactory::getDBO();
		$request = JRequest::get('request');
		$Itemid = $request["Itemid"];

		$paypal_parameters = $this->getparameters('rs_payment_paypal_subscription');
		$paymentinfo = $paypal_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$is_test = $paymentparams->get('sandbox', '');
		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$cancel_status = $paymentparams->get('cancel_status', '');

		$txn_type = $request["txn_type"];
		$subscr_id = $request["subscr_id"];
		$tid = $request['txn_id'];
		$status = $request['payment_status'];
		$values = new stdClass;

		if ($txn_type == 'subscr_payment')
		{
			$order_id = $request["item_number"];

			if ($this->checkFirstRecurringPayment($db, $order_id, $subscr_id))
			{
				if ($status == 'Completed')
				{
					$this->updateFirstRecurringPayment($db, $order_id, $subscr_id);
					$values->order_status_code = $verify_status;
					$values->order_payment_status_code = 'Paid';
					$values->log = JTEXT::_('ORDER_PLACED');
					$values->msg = JTEXT::_('ORDER_PLACED');
				}
				else
				{
					$values->order_status_code = $invalid_status;
					$values->order_payment_status_code = 'Unpaid';
					$values->log = JTEXT::_('ORDER_NOT_PLACED');
					$values->msg = JTEXT::_('ORDER_NOT_PLACED');
				}

				$values->transaction_id = $tid;
				$values->order_id = $order_id;

				return $values;
			}
			else
			{
				// Place New order for Recuurening
				$timestamp = time();
				$main_order_detail = $order_functions->getOrderDetails($order_id);
				$main_orderitemdetail = $order_functions->getOrderItemDetail($order_id);
				JTable::addIncludePath(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'tables');
				$row = JTable::getInstance('order_detail', 'Table');

				$row->user_id = $main_order_detail->user_id;
				$row->order_number = $order_functions->generateOrderNumber();
				$row->user_info_id = $main_order_detail->user_info_id;
				$row->order_total = $main_order_detail->order_total;
				$row->order_subtotal = $main_order_detail->order_subtotal;
				$row->order_tax = $main_order_detail->order_tax;
				$row->tax_after_discount = $main_order_detail->tax_after_discount;
				$row->order_tax_details = '';
				$row->analytics_status = 0;
				$row->order_shipping = $main_order_detail->order_shipping;
				$row->order_shipping_tax = $main_order_detail->order_shipping_tax;
				$row->coupon_discount = $main_order_detail->coupon_discount;
				$row->shop_id = $main_order_detail->shop_id;
				$row->order_discount = $main_order_detail->order_discount;
				$row->order_discount_vat = $main_order_detail->order_discount_vat;
				$row->payment_discount = $main_order_detail->payment_discount;
				$row->payment_oprand = $main_order_detail->payment_oprand;
				$row->order_status = 'P';
				$row->order_payment_status = 'Unpaid';
				$row->cdate = $timestamp;
				$row->mdate = $timestamp;
				$row->ship_method_id = $main_order_detail->ship_method_id;
				$row->customer_note = $main_order_detail->customer_note;
				$row->requisition_number = $main_order_detail->requisition_number;
				$row->ip_address = $main_order_detail->ip_address;
				$row->encr_key = $order_functions->random_gen_enc_key(35);
				$row->split_payment = $main_order_detail->split_payment;
				$row->discount_type = $main_order_detail->discount_type;
				$row->barcode = $main_order_detail->barcode;

				if ($row->store())
				{
					$new_oid = $row->order_id;

					$rowitem = JTable::getInstance('order_item_detail', 'Table');

					for ($ri = 0; $ri < count($main_orderitemdetail); $ri++)
					{
						$rowitem->delivery_time = $main_orderitemdetail[$ri]->delivery_time;
						$rowitem->attribute_image = $main_orderitemdetail[$ri]->attribute_image;
						$rowitem->product_id = $main_orderitemdetail[$ri]->product_id;
						$rowitem->product_item_old_price = $main_orderitemdetail[$ri]->product_item_old_price;
						$rowitem->supplier_id = $main_orderitemdetail[$ri]->supplier_id;
						$rowitem->order_item_sku = $main_orderitemdetail[$ri]->order_item_sku;
						$rowitem->order_item_name = $main_orderitemdetail[$ri]->order_item_name;

						$rowitem->product_item_price = $main_orderitemdetail[$ri]->product_item_price;
						$rowitem->product_quantity = $main_orderitemdetail[$ri]->product_quantity;
						$rowitem->product_item_price_excl_vat = $main_orderitemdetail[$ri]->product_item_price_excl_vat;
						$rowitem->product_final_price = $main_orderitemdetail[$ri]->product_final_price;
						$rowitem->is_giftcard = $main_orderitemdetail[$ri]->is_giftcard;
						$rowitem->order_id = $new_oid;
						$rowitem->user_info_id = $main_orderitemdetail[$ri]->user_info_id;
						$rowitem->order_item_currency = REDCURRENCY_SYMBOL;
						$rowitem->order_status = $main_orderitemdetail[$ri]->order_status;
						$rowitem->cdate = $timestamp;
						$rowitem->mdate = $timestamp;
						$rowitem->product_attribute = $main_orderitemdetail[$ri]->product_attribute;
						$rowitem->discount_calc_data = $main_orderitemdetail[$ri]->discount_calc_data;
						$rowitem->product_accessory = $main_orderitemdetail[$ri]->product_accessory;
						$rowitem->container_id = $main_orderitemdetail[$ri]->container_id;
						$rowitem->wrapper_id = $main_orderitemdetail[$ri]->wrapper_id;
						$rowitem->wrapper_price = $main_orderitemdetail[$ri]->wrapper_price;
						$rowitem->giftcard_user_email = $main_orderitemdetail[$ri]->giftcard_user_email;
						$rowitem->giftcard_user_name = $main_orderitemdetail[$ri]->giftcard_user_name;

						if (!$main_orderitemdetail[$ri]->is_giftcard)
						{
							$updatestock = $stockroomhelper->updateStockroomQuantity($main_orderitemdetail[$ri]->product_id, $main_orderitemdetail[$ri]->product_quantity);
							$stockroom_id_list = $updatestock['stockroom_list'];
							$stockroom_quantity_list = $updatestock['stockroom_quantity_list'];
							$rowitem->stockroom_id = $stockroom_id_list;
							$rowitem->stockroom_quantity = $stockroom_quantity_list;
						}

						if ($producthelper->checkProductDownload($main_orderitemdetail[$ri]->product_id))
						{
							$medianame = $producthelper->getProductMediaName($main_orderitemdetail[$ri]->product_id);

							for ($j = 0; $j < count($medianame); $j++)
							{
								$product_serial_number = $producthelper->getProdcutSerialNumber($main_orderitemdetail[$ri]->product_id);
								$producthelper->insertProductDownload($main_orderitemdetail[$ri]->product_id, $order->user_id, $new_oid, $medianame[$j]->media_name, $product_serial_number->serial_number);
							}
						}

						if ($rowitem->store())
						{
							$OrderItemAccessoryDetail = $order_functions->getOrderItemAccessoryDetail($rowitem->order_item_id);

							if (count($OrderItemAccessoryDetail) > 0)
							{
								for ($ac = 0; $ac < count($OrderItemAccessoryDetail); $ac++)
								{
									$accdata = JTable::getInstance('accessory_detail', 'Table');
									$accdata->order_item_id = $rowitem->order_item_id;
									$accdata->order_acc_item_sku = $OrderItemAccessoryDetail[$ac]->order_acc_item_sku;
									$accdata->order_acc_item_name = $OrderItemAccessoryDetail[$ac]->order_acc_item_name;
									$accdata->order_acc_price = $OrderItemAccessoryDetail[$ac]->order_acc_price;
									$accdata->order_acc_vat = $OrderItemAccessoryDetail[$ac]->order_acc_vat;
									$accdata->product_quantity = $OrderItemAccessoryDetail[$ac]->product_quantity;
									$accdata->product_acc_item_price = $OrderItemAccessoryDetail[$ac]->product_acc_item_price;
									$accdata->product_acc_final_price = $OrderItemAccessoryDetail[$ac]->product_acc_final_price;
									$accdata->product_attribute = $OrderItemAccessoryDetail[$ac]->product_attribute;
									$accdata->store();
								}
							}

							$OrderItemAttrDetail = $order_functions->getOrderItemAttributeDetail($rowitem->order_item_id);

							if (count($OrderItemAttrDetail) > 0)
							{
								for ($at = 0; $at < count($OrderItemAttrDetail); $at++)
								{
									$attdata = JTable::getInstance('order_attribute_item', 'Table');
									$attdata->order_item_id = $rowitem->order_item_id;
									$attdata->section_id = $orderItemdata[$at]->section_id;
									$attdata->section = $orderItemdata[$at]->section;
									$attdata->parent_section_id = $orderItemdata[$at]->parent_section_id;
									$attdata->section_name = $orderItemdata[$at]->section_name;
									$attdata->section_price = $orderItemdata[$at]->section_price;
									$attdata->section_vat = $orderItemdata[$at]->section_vat;
									$attdata->section_oprand = $orderItemdata[$at]->section_oprand;
									$attdata->is_accessory_att = $orderItemdata[$at]->is_accessory_att;
									$attdata->store();
								}
							}
						}
					}

					// For order Payment
					$paymentmethod = $order_functions->getPaymentMethodInfo('rs_payment_paypal_subscription');
					$paymentmethod = $paymentmethod[0];

					$xmlpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $paymentmethod->element . DS . $paymentmethod->element . '.xml';
					$params = new JRegistry($paymentmethod->params, $xmlpath);

					$rowpayment = JTable::getInstance('order_payment', 'Table');

					$rowpayment->order_id = $new_oid;
					$rowpayment->payment_method_id = 0;
					$rowpayment->order_payment_amount = $row->order_total;
					$rowpayment->order_payment_expire = '';
					$rowpayment->order_payment_name = $paymentmethod->name;
					$rowpayment->payment_method_class = $paymentmethod->element;
					$rowpayment->order_payment_trans_id = '';
					$rowpayment->authorize_status = "";

					$rowpayment->store();

					// Add billing Info
					$userrow = & JTable::getInstance('user_detail', 'Table');
					$userrow->load($main_order_detail->users_info_id);
					$orderuserrow = JTable::getInstance('order_user_detail', 'Table');
					$orderuserrow->bind($userrow);
					$orderuserrow->order_id = $new_oid;
					$orderuserrow->address_type = 'BT';
					$orderuserrow->store();

					// Add shipping Info
					$userrow = & JTable::getInstance('user_detail', 'Table');
					$userrow->load($main_order_detail->users_info_id);
					$orderuserrow = JTable::getInstance('order_user_detail', 'Table');
					$orderuserrow->bind($userrow);
					$orderuserrow->order_id = $new_oid;
					$orderuserrow->address_type = 'ST';
					$orderuserrow->store();

					// Economic Integration start for invoice generate and book current invoice
					if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT != 2)
					{
						$issplit = null;
						$economic = new economic;
						$economic_payment_terms_id = $params->get('economic_payment_terms_id');
						$economic_design_layout = $params->get('economic_design_layout');
						$is_creditcard = $params->get('is_creditcard', '');
						$economicdata['split_payment'] = $issplit;
						$economicdata['economic_payment_terms_id'] = $economic_payment_terms_id;
						$economicdata['economic_design_layout'] = $economic_design_layout;
						$economicdata['economic_is_creditcard'] = $is_creditcard;

						$payment_name = $paymentmethod->element;
						$paymentArr = explode("rs_payment_", $paymentmethod->element);

						if (count($paymentArr) > 0)
						{
							$payment_name = $paymentArr[1];
						}

						$economicdata['economic_payment_method'] = $payment_name;

						$invoiceHandle = $economic->createInvoiceInEconomic($new_oid, $economicdata);

						if (ECONOMIC_INVOICE_DRAFT == 0)
						{
							$bookinvoicepdf = $economic->bookInvoiceInEconomic($new_oid, $checkOrderStatus);

							if (is_file($bookinvoicepdf))
							{
								$ret = $redshopMail->sendEconomicBookInvoiceMail($new_oid, $bookinvoicepdf);
							}
						}
					}

					// End Economic

					// Send the Order mail
					$redshopMail->sendOrderMail($new_oid, 0);
				}

				if ($status == 'Completed')
				{
					$this->updateFirstRecurringPayment($db, $new_oid, $subscr_id);
					$values->order_status_code = $verify_status;
					$values->order_payment_status_code = 'Paid';
					$values->log = JTEXT::_('ORDER_PLACED');
					$values->msg = JTEXT::_('ORDER_PLACED');
				}
				else
				{
					$values->order_status_code = $invalid_status;
					$values->order_payment_status_code = 'Unpaid';
					$values->log = JTEXT::_('ORDER_NOT_PLACED');
					$values->msg = JTEXT::_('ORDER_NOT_PLACED');
				}

				$values->transaction_id = $tid;
				$values->order_id = $new_oid;

				return $values;
			}
		}
	}

	public function getparameters($payment)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__plugins WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function checkFirstRecurringPayment($db, $order_id, $subscr_id)
	{
		$db = JFactory::getDBO();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM `#__redshop_orders` WHERE `order_id` = '" . $order_id . "' and recuuring_subcription_id = '" . $subscr_id . "'";

		$db->SetQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function updateFirstRecurringPayment($db, $order_id, $subscr_id)
	{
		$db = JFactory::getDBO();
		$res = false;
		$query = "UPDATE `#__redshop_orders` set recuuring_subcription_id ='" . $subscr_id . "'  WHERE `order_id` = '" . $order_id . "'";
		$db->SetQuery($query);
		$db->query();
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDBO();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '" . $order_id . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->SetQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}
}
