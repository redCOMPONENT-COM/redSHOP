<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

jimport('joomla.filesystem.file');

class economic
{
	public $_table_prefix = null;

	public $_db = null;

	public $_producthelper = null;

	public $_shippinghelper = null;

	public $_order_functions = null;

	public $_stockroomhelper = null;

	public $_dispatcher = null;

	protected static $instance = null;

	/**
	 * Returns the economic object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  economic  The economic object
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

	public function __construct()
	{
		$db                     = JFactory::getDbo();
		$this->_table_prefix    = '#__redshop_';
		$this->_db              = $db;
		$this->_producthelper   = productHelper::getInstance();
		$this->_shippinghelper  = shipping::getInstance();
		$this->_redhelper       = redhelper::getInstance();
		$this->_order_functions = order_functions::getInstance();
		$this->_stockroomhelper = rsstockroomhelper::getInstance();

		JPluginHelper::importPlugin('economic');
		$this->_dispatcher = JDispatcher::getInstance();
	}

	/**
	 * Create an user in E-conomic
	 *
	 * @param   array  $row   Data to create user
	 * @param   array  $data  Data of Economic
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createUserInEconomic() instead
	 */
	public function createUserInEconomic($row = array(), $data = array())
	{
		return RedshopEconomic::createUserInEconomic($row, $data);
	}

	/**
	 * Create Product Group in E-conomic
	 *
	 * @param   array    $row         Data to create
	 * @param   integer  $isShipping  Shipping flag
	 * @param   integer  $isDiscount  Discount flag
	 * @param   integer  $isvat       VAT flag
	 *
	 * @return  null/array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createProductGroupInEconomic() instead
	 */
	public function createProductGroupInEconomic($row = array(), $isShipping = 0, $isDiscount = 0, $isvat = 0)
	{
		return RedshopEconomic::createProductGroupInEconomic($row, $isShipping, $isDiscount, $isvat);
	}

	/**
	 * Create product in E-conomic
	 *
	 * @param   array  $row  Data to create
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createProductInEconomic() instead
	 */
	public function createProductInEconomic($row = array())
	{
		return RedshopEconomic::createProductInEconomic($row);
	}

	/**
	 * Get Total Property
	 *
	 * @param   integer  $productId  Product ID
	 *
	 * @return  integer
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::getTotalProperty() instead
	 */
	public function getTotalProperty($productId)
	{
		return RedshopEconomic::getTotalProperty($productId);
	}

	/**
	 * Create property product in economic
	 *
	 * @param   array  $prdrow  Product data
	 * @param   array  $row     Data property
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createPropertyInEconomic() instead
	 */
	public function createPropertyInEconomic($prdrow = array(), $row = array())
	{
		return RedshopEconomic::createPropertyInEconomic($prdrow, $row);
	}

	/**
	 * Create Sub Property in Economic
	 *
	 * @param   array  $prdrow  Product info
	 * @param   array  $row     Data of property
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createSubpropertyInEconomic() instead
	 */
	public function createSubpropertyInEconomic($prdrow = array(), $row = array())
	{
		return RedshopEconomic::createSubpropertyInEconomic($prdrow, $row);
	}

	/**
	 * Import Stock from Economic
	 *
	 * @param   array  $prdrow  Product Info
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::importStockFromEconomic() instead
	 */
	public function importStockFromEconomic($prdrow = array())
	{
		return RedshopEconomic::importStockFromEconomic($prdrow);
	}

	/**
	 * Create Shipping rate in economic
	 *
	 * @param   integer  $shipping_number  Shipping Number
	 * @param   string   $shipping_name    Shipping Name
	 * @param   integer  $shipping_rate    Shipping Rate
	 * @param   integer  $isvat            VAT flag
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createShippingRateInEconomic() instead
	 */
	public function createShippingRateInEconomic($shipping_number, $shipping_name, $shipping_rate = 0, $isvat = 1)
	{
		return RedshopEconomic::createShippingRateInEconomic($shipping_number, $shipping_name, $shipping_rate, $isvat);
	}

	/**
	 * Get Max User Number in E-conomic
	 *
	 * @return  integer
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::getMaxDebtorInEconomic() instead
	 */
	public function getMaxDebtorInEconomic()
	{
		return RedshopEconomic::getMaxDebtorInEconomic();
	}

	/**
	 * Get Max Order Number in Economic
	 *
	 * @return  integer
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::getMaxOrderNumberInEconomic() instead
	 */
	public function getMaxOrderNumberInEconomic()
	{
		return RedshopEconomic::getMaxOrderNumberInEconomic();
	}

	/**
	 * Create Invoice in economic
	 *
	 * @param   integer  $order_id  Order ID
	 * @param   array    $data      Data to create
	 *
	 * @return  boolean/string
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createInvoiceInEconomic() instead
	 */
	public function createInvoiceInEconomic($order_id, $data = array())
	{
		return RedshopEconomic::createInvoiceInEconomic($order_id, $data);
	}

	/**
	 * Create Invoice Line In Economic
	 *
	 * @param   array    $orderitem   Order Items
	 * @param   string   $invoice_no  Invoice Number
	 * @param   integer  $user_id     User ID
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createInvoiceLineInEconomic() instead
	 */
	public function createInvoiceLineInEconomic($orderitem = array(), $invoice_no = "", $user_id = 0)
	{
		return RedshopEconomic::createInvoiceLineInEconomic($orderitem, $invoice_no, $user_id);
	}

	/**
	 * Create Invoice line in E-conomic for GiftCard
	 *
	 * @param   array   $orderitem   Order Item
	 * @param   string  $invoice_no  Invoice Number
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createGiftCardInvoiceLineInEconomic() instead
	 */
	public function createGFInvoiceLineInEconomic($orderitem = array(), $invoice_no = "")
	{
		return RedshopEconomic::createGiftCardInvoiceLineInEconomic($orderitem, $invoice_no);
	}

	/**
	 * Method to create Invoice line in E-conomic as Product
	 *
	 * @param   array    $orderitem   Order Item
	 * @param   string   $invoice_no  Invoice Number
	 * @param   integer  $user_id     User ID
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createInvoiceLineInEconomicAsProduct() instead
	 */
	public function createInvoiceLineInEconomicAsProduct($orderitem = array(), $invoice_no = "", $user_id = 0)
	{
		return RedshopEconomic::createInvoiceLineInEconomicAsProduct($orderitem, $invoice_no, $user_id);
	}

	/**
	 * Method to create Invoice line for shipping in E-conomic
	 *
	 * @param   string  $ship_method_id  Shipping method ID
	 * @param   string  $invoice_no      Invoice Number
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createInvoiceShippingLineInEconomic() instead
	 */
	public function createInvoiceShippingLineInEconomic($ship_method_id = "", $invoice_no = "")
	{
		return RedshopEconomic::createInvoiceShippingLineInEconomic($ship_method_id, $invoice_no);
	}

	/**
	 * Method to create Invoice line for discount in E-conomic
	 *
	 * @param   array    $orderdetail        Order detail
	 * @param   string   $invoice_no         Invoice Number
	 * @param   array    $data               Data
	 * @param   integer  $isPaymentDiscount  Is payment discount or not
	 * @param   integer  $isVatDiscount      Is VAT discount or not
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::createInvoiceDiscountLineInEconomic() instead
	 */
	public function createInvoiceDiscountLineInEconomic($orderdetail = array(), $invoice_no = "", $data = array(), $isPaymentDiscount = 0,
		$isVatDiscount = 0)
	{
		return RedshopEconomic::createInvoiceDiscountLineInEconomic($orderdetail, $invoice_no, $data, $isPaymentDiscount, $isVatDiscount);
	}

	/**
	 * Method to create Invoice and send mail in E-conomic
	 *
	 * @param   array  $orderdata  Order data
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::renewInvoiceInEconomic() instead
	 */
	public function renewInvoiceInEconomic($orderdata)
	{
		return RedshopEconomic::renewInvoiceInEconomic($orderdata);
	}

	/**
	 * Method to delete invoice in E-conomic
	 *
	 * @param   array  $orderdata  Order data to delete
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::deleteInvoiceInEconomic() instead
	 */
	public function deleteInvoiceInEconomic($orderdata = array())
	{
		return RedshopEconomic::deleteInvoiceInEconomic($orderdata);
	}

	/**
	 * Method to check invoice is draft or booked in E-conomic
	 *
	 * @param   array  $orderdetail  Order detail
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::checkInvoiceDraftorBookInEconomic() instead
	 */
	public function checkInvoiceDraftorBookInEconomic($orderdetail)
	{
		return RedshopEconomic::checkInvoiceDraftorBookInEconomic($orderdetail);
	}

	/**
	 * Method to update invoice draft for changing the date in E-conomic
	 *
	 * @param   array    $orderdetail      Order detail
	 * @param   integer  $bookinvoicedate  Booking invoice date
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopEconomic::updateInvoiceDateInEconomic() instead
	 */
	public function updateInvoiceDateInEconomic($orderdetail, $bookinvoicedate = 0)
	{
		return RedshopEconomic::updateInvoiceDateInEconomic($orderdetail, $bookinvoicedate);
	}

	/**
	 * Method to book invoice and send mail in E-conomic
	 *
	 * @access public
	 * @return array
	 */
	public function bookInvoiceInEconomic($order_id, $checkOrderStatus = 1, $bookinvoicedate = 0)
	{
		$file = '';

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$orderdetail = $this->_order_functions->getOrderDetails($order_id);

			if ($orderdetail->invoice_no != '' && $orderdetail->is_booked == 0)
			{
				if ((Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2 && $orderdetail->order_status == Redshop::getConfig()->get('BOOKING_ORDER_STATUS')) || $checkOrderStatus == 0)
				{
					$user_billinginfo = RedshopHelperOrder::getOrderBillingUserInfo($order_id);

					if ($user_billinginfo->is_company == 0 || (!$user_billinginfo->ean_number && $user_billinginfo->is_company == 1))
					{
						$currency = Redshop::getConfig()->get('CURRENCY_CODE');

						$eco['invoiceHandle'] = $orderdetail->invoice_no;
						$eco['debtorHandle']  = intVal($user_billinginfo->users_info_id);
						$eco['currency_code'] = $currency;
						$eco['amount']        = $orderdetail->order_total;
						$eco['order_number']  = $orderdetail->order_number;
						$eco['order_id']      = $orderdetail->order_id;

						$currectinvoiceData = $this->_dispatcher->trigger('checkDraftInvoice', array($eco));

						if (count($currectinvoiceData) > 0 && trim($currectinvoiceData[0]->OtherReference) == $orderdetail->order_number)
						{
							$this->updateInvoiceDateInEconomic($orderdetail, $bookinvoicedate);

							if ($user_billinginfo->is_company == 1 && $user_billinginfo->company_name != '')
							{
								$eco['name'] = $user_billinginfo->company_name;
							}

							else
							{
								$eco['name'] = $user_billinginfo->firstname . " " . $user_billinginfo->lastname;
							}

							$paymentInfo = $this->_order_functions->getOrderPaymentDetail($orderdetail->order_id);

							if (count($paymentInfo) > 0)
							{
								$paymentmethod = $this->_order_functions->getPaymentMethodInfo($paymentInfo[0]->payment_method_class);

								if (count($paymentmethod) > 0)
								{
									$paymentparams                    = new JRegistry($paymentmethod[0]->params);
									$eco['economic_payment_terms_id'] = $paymentparams->get('economic_payment_terms_id');
									$eco['economic_design_layout']    = $paymentparams->get('economic_design_layout');
								}

								// Setting merchant fees for economic
								if($paymentInfo[0]->order_transfee > 0)
								{
									$eco['order_transfee'] = $paymentInfo[0]->order_transfee;
								}
							}

							if (Redshop::getConfig()->get('ECONOMIC_BOOK_INVOICE_NUMBER') == 1)
							{
								$bookhandle = $this->_dispatcher->trigger('CurrentInvoice_Book', array($eco));
							}
							else
							{
								$bookhandle = $this->_dispatcher->trigger('CurrentInvoice_BookWithNumber', array($eco));
							}

							if (count($bookhandle) > 0 && isset($bookhandle[0]->Number))
							{
								$bookinvoice_number = $eco['bookinvoice_number'] = $bookhandle[0]->Number;

								if (Redshop::getConfig()->get('ECONOMIC_BOOK_INVOICE_NUMBER') == 1)
								{
									$this->updateBookInvoiceNumber($order_id, $bookinvoice_number);
								}

								$bookinvoicepdf = $this->_dispatcher->trigger('bookInvoice', array($eco));

								if (JError::isError(JError::getError()))
								{
									return $file;
								}
								elseif ($bookinvoicepdf != "")
								{
									$file = JPATH_ROOT . '/components/com_redshop/assets/orders/rsInvoice_' . $order_id . '.pdf';
									JFile::write($file, $bookinvoicepdf);

									if (is_file($file))
									{
										$this->updateBookInvoice($order_id);
									}
								}
							}
						}
					}
				}
			}
		}

		return $file;
	}

	public function updateInvoiceNumber($order_id = 0, $invoice_no = 0)
	{
		$db = JFactory::getDbo();

		$query = 'UPDATE ' . $this->_table_prefix . 'orders '
			. 'SET invoice_no = ' . $db->quote($invoice_no) . ' '
			. 'WHERE order_id = ' . (int) $order_id ;
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	public function updateBookInvoice($order_id = 0)
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'orders '
			. 'SET is_booked="1" '
			. 'WHERE order_id = ' . (int) $order_id;
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	public function updateBookInvoiceNumber($order_id = 0, $bookinvoice_number = 0)
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'orders '
			. 'SET bookinvoice_number = ' . (int) $bookinvoice_number . ' '
			. 'WHERE order_id = ' . (int) $order_id;
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	public function getProductByNumber($product_number = '')
	{
		$db = JFactory::getDbo();

		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product '
			. 'WHERE product_number = ' . $db->quote($product_number);
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	public function makeAccessoryOrder($invoice_no, $orderItem, $user_id = 0)
	{
		$displayaccessory = "";
		$retPrice         = 0;
		$orderItemdata    = $this->_order_functions->getOrderItemAccessoryDetail($orderItem->order_item_id);

		if (count($orderItemdata) > 0)
		{
			$displayaccessory .= "\n" . JText::_("COM_REDSHOP_ACCESSORY");

			for ($i = 0, $in = count($orderItemdata); $i < $in; $i++)
			{
				if (true)
				{
					$product = $this->getProductByNumber($orderItemdata[$i]->order_acc_item_sku);

					if (count($product) > 0)
					{
						$this->createProductInEconomic($product);
					}
				}

				$accessory_quantity = " (" . JText::_('COM_REDSHOP_ACCESSORY_QUANTITY_LBL') . " " . $orderItemdata[$i]->product_quantity . ") ";
				$displayaccessory .= "\n" . urldecode($orderItemdata[$i]->order_acc_item_name) . " (" . ($orderItemdata[$i]->order_acc_price + $orderItemdata[$i]->order_acc_vat) . ")" . $accessory_quantity;

				if (true)
				{
					$retPrice += $orderItemdata[$i]->product_acc_item_price;

					$eco['updateInvoice']    = 0;
					$eco['invoiceHandle']    = $invoice_no;
					$eco['order_item_id']    = $orderItem->order_item_id;
					$eco['product_number']   = $orderItemdata[$i]->order_acc_item_sku;
					$eco['product_name']     = $orderItemdata[$i]->order_acc_item_name;
					$eco['product_price']    = $orderItemdata[$i]->product_acc_item_price;
					$eco['product_quantity'] = $orderItemdata[$i]->product_quantity;
					$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
					$InvoiceLine_no           = $this->_dispatcher->trigger('createInvoiceLine', array($eco));
				}

				$displayattribute = $this->makeAttributeOrder($invoice_no, $orderItem, 1, $orderItemdata[$i]->product_id, $user_id);
				$displayaccessory .= $displayattribute;

				if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
				{
					$orderItemdata[$i]->product_acc_item_price -= $displayattribute;
					$displayattribute = '';
				}

				if (true && count($InvoiceLine_no) > 0 && $InvoiceLine_no[0]->Number)
				{
					$eco['updateInvoice']    = 1;
					$eco['invoiceHandle']    = $invoice_no;
					$eco['order_item_id']    = $InvoiceLine_no[0]->Number;
					$eco['product_number']   = $orderItemdata[$i]->order_acc_item_sku;
					$eco['product_name']     = $orderItemdata[$i]->order_acc_item_name . $displayattribute;
					$eco['product_price']    = $orderItemdata[$i]->product_acc_item_price;
					$eco['product_quantity'] = $orderItemdata[$i]->product_quantity;
					$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

					$InvoiceLine_no = $this->_dispatcher->trigger('createInvoiceLine', array($eco));
				}
			}
		}

		if (true)
		{
			$displayaccessory = $retPrice;
		}

		return $displayaccessory;
	}

	public function makeAttributeOrder($invoice_no, $orderItem, $is_accessory = 0, $parent_section_id = 0, $user_id = 0)
	{
		$displayattribute = "";
		$retPrice         = 0;
		$chktag           = $this->_producthelper->getApplyattributeVatOrNot('', $user_id);
		$orderItemAttdata = $this->_order_functions->getOrderItemAttributeDetail($orderItem->order_item_id, $is_accessory, "attribute", $parent_section_id);

		if (count($orderItemAttdata) > 0)
		{
			$product = Redshop::product((int) $parent_section_id);

			for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++)
			{
				$attribute            = $this->_producthelper->getProductAttribute(0, 0, $orderItemAttdata[$i]->section_id);
				$hide_attribute_price = 0;

				if (count($attribute) > 0)
				{
					$hide_attribute_price = $attribute[0]->hide_attribute_price;
				}

				$displayattribute .= "\n" . urldecode($orderItemAttdata[$i]->section_name) . " : ";
				$orderPropdata = $this->_order_functions->getOrderItemAttributeDetail($orderItem->order_item_id, $is_accessory, "property", $orderItemAttdata[$i]->section_id);

				for ($p = 0, $pn = count($orderPropdata); $p < $pn; $p++)
				{
					$property      = $this->_producthelper->getAttibuteProperty($orderPropdata[$p]->section_id);
					$virtualNumber = "";

					if (count($property) > 0 && $property[0]->property_number)
					{
						$virtualNumber = "[" . $property[0]->property_number . "]";

						if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
						{
							$orderPropdata[$p]->virtualNumber = $property[0]->property_number;
							$this->createPropertyInEconomic($product, $property[0]);
						}
					}

					$disPrice = "";

					if (!$hide_attribute_price)
					{
						$property_price = $orderPropdata[$p]->section_price;

						if (!empty($chktag))
						{
							$property_price = $orderPropdata[$p]->section_price + $orderPropdata[$p]->section_vat;
						}

						$disPrice = " (" . $orderPropdata[$p]->section_oprand . $this->_producthelper->getProductFormattedPrice($property_price) . ")";
					}

					$displayattribute .= urldecode($orderPropdata[$p]->section_name) . $disPrice . $virtualNumber;

					if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
					{
						$retPrice += $orderPropdata[$p]->section_price;
						$this->createAttributeInvoiceLineInEconomic($invoice_no, $orderItem, array($orderPropdata[$p]));
					}

					$orderSubpropdata = $this->_order_functions->getOrderItemAttributeDetail($orderItem->order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

					if (count($orderSubpropdata) > 0)
					{
						for ($sp = 0; $sp < count($orderSubpropdata); $sp++)
						{
							$subproperty   = $this->_producthelper->getAttibuteSubProperty($orderSubpropdata[$sp]->section_id);
							$virtualNumber = "";

							if (count($subproperty) > 0 && $subproperty[0]->subattribute_color_number)
							{
								$virtualNumber = "[" . $subproperty[0]->subattribute_color_number . "]";

								if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
								{
									$orderSubpropdata[$sp]->virtualNumber = $subproperty[0]->subattribute_color_number;
									$this->createSubpropertyInEconomic($product, $subproperty[0]);
								}
							}

							$disPrice = "";

							if (!$hide_attribute_price)
							{
								$subproperty_price = $orderSubpropdata[$sp]->section_price;

								if (!empty($chktag))
								{
									$subproperty_price = $orderSubpropdata[$sp]->section_price + $orderSubpropdata[$sp]->section_vat;
								}

								$disPrice = " (" . $orderSubpropdata[$sp]->section_oprand . $this->_producthelper->getProductFormattedPrice($subproperty_price) . ")";
							}

							$displayattribute .= "\n" . urldecode($orderSubpropdata[$sp]->section_name) . $disPrice . $virtualNumber;

							if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
							{
								$retPrice += $orderSubpropdata[$sp]->section_price;
							}
						}

						if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
						{
							$this->createAttributeInvoiceLineInEconomic($invoice_no, $orderItem, $orderSubpropdata);
						}
					}
				}
			}
		}

		if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
		{
			$displayattribute = $retPrice;
		}

		return $displayattribute;
	}

	public function createAttributeInvoiceLineInEconomic($invoice_no, $orderItem, $orderAttitem)
	{
		for ($i = 0, $in = count($orderAttitem); $i < $in; $i++)
		{
			$eco[$i]['invoiceHandle']    = $invoice_no;
			$eco[$i]['order_item_id']    = $orderItem->order_item_id;
			$eco[$i]['product_number']   = $orderAttitem[$i]->virtualNumber;
			$eco[$i]['product_name']     = $orderAttitem[$i]->section_name;
			$eco[$i]['product_price']    = $orderAttitem[$i]->section_price;
			$eco[$i]['product_quantity'] = $orderItem->product_quantity;
			$eco[$i]['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
			$this->_dispatcher->trigger('createInvoiceLine', array($eco[$i]));
		}
	}

	public function getEconomicTaxZone($country_code = "")
	{
		if ($country_code == Redshop::getConfig()->get('SHOP_COUNTRY'))
		{
			$taxzone = 'HomeCountry';
		}
		elseif ($this->isEUCountry($country_code))
		{
			$taxzone = 'EU';
		}
		else
		{
			// Non EU Country
			$taxzone = 'Abroad';
		}

		return $taxzone;
	}

	public function isEUCountry($country)
	{
		$eu_country = array('AUT', 'BGR', 'BEL', 'CYP', 'CZE', 'DEU', 'DNK', 'ESP', 'EST', 'FIN',
			'FRA', 'FXX', 'GBR', 'GRC', 'HUN', 'IRL', 'ITA', 'LVA', 'LTU', 'LUX',
			'MLT', 'NLD', 'POL', 'PRT', 'ROM', 'SVK', 'SVN', 'SWE');

		return in_array($country, $eu_country);
	}
}
