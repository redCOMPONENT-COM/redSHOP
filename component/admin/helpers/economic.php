<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

jimport('joomla.filesystem.file');

/**
 * Library for Redshop E-conomic.
 *
 * @since       2.0.3
 *
 * @deprecated  2.0.3 Use RedshopEconomic instead
 */
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

	/**
	 * Constructor
	 */
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
	 * @deprecated  2.0.3 Use RedshopEconomic::createUserInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::createProductGroupInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::createProductInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::getTotalProperty() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::createPropertyInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::createSubpropertyInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::importStockFromEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::createShippingRateInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::getMaxDebtorInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::getMaxOrderNumberInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::createInvoiceInEconomic() instead
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
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::createInvoiceLineInEconomic() instead
	 */
	public function createInvoiceLineInEconomic($orderitem = array(), $invoice_no = "", $user_id = 0)
	{
		RedshopEconomic::createInvoiceLineInEconomic($orderitem, $invoice_no, $user_id);
	}

	/**
	 * Create Invoice line in E-conomic for GiftCard
	 *
	 * @param   array   $orderitem   Order Item
	 * @param   string  $invoice_no  Invoice Number
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::createGiftCardInvoiceLineInEconomic() instead
	 */
	public function createGFInvoiceLineInEconomic($orderitem = array(), $invoice_no = "")
	{
		RedshopEconomic::createGiftCardInvoiceLineInEconomic($orderitem, $invoice_no);
	}

	/**
	 * Method to create Invoice line in E-conomic as Product
	 *
	 * @param   array    $orderitem   Order Item
	 * @param   string   $invoice_no  Invoice Number
	 * @param   integer  $user_id     User ID
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::createInvoiceLineInEconomicAsProduct() instead
	 */
	public function createInvoiceLineInEconomicAsProduct($orderitem = array(), $invoice_no = "", $user_id = 0)
	{
		RedshopEconomic::createInvoiceLineInEconomicAsProduct($orderitem, $invoice_no, $user_id);
	}

	/**
	 * Method to create Invoice line for shipping in E-conomic
	 *
	 * @param   string  $ship_method_id  Shipping method ID
	 * @param   string  $invoice_no      Invoice Number
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::createInvoiceShippingLineInEconomic() instead
	 */
	public function createInvoiceShippingLineInEconomic($ship_method_id = "", $invoice_no = "")
	{
		RedshopEconomic::createInvoiceShippingLineInEconomic($ship_method_id, $invoice_no);
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
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::createInvoiceDiscountLineInEconomic() instead
	 */
	public function createInvoiceDiscountLineInEconomic($orderdetail = array(), $invoice_no = "", $data = array(), $isPaymentDiscount = 0,
		$isVatDiscount = 0)
	{
		RedshopEconomic::createInvoiceDiscountLineInEconomic($orderdetail, $invoice_no, $data, $isPaymentDiscount, $isVatDiscount);
	}

	/**
	 * Method to create Invoice and send mail in E-conomic
	 *
	 * @param   array  $orderdata  Order data
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::renewInvoiceInEconomic() instead
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
	 * @deprecated  2.0.3 Use RedshopEconomic::deleteInvoiceInEconomic() instead
	 */
	public function deleteInvoiceInEconomic($orderdata = array())
	{
		RedshopEconomic::deleteInvoiceInEconomic($orderdata);
	}

	/**
	 * Method to check invoice is draft or booked in E-conomic
	 *
	 * @param   array  $orderdetail  Order detail
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::checkInvoiceDraftorBookInEconomic() instead
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
	 * @return  object
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::updateInvoiceDateInEconomic() instead
	 */
	public function updateInvoiceDateInEconomic($orderdetail, $bookinvoicedate = 0)
	{
		return RedshopEconomic::updateInvoiceDateInEconomic($orderdetail, $bookinvoicedate);
	}

	/**
	 * Method to book invoice and send mail in E-conomic
	 *
	 * @param   integer  $order_id          Order ID
	 * @param   integer  $checkOrderStatus  Check Order status
	 * @param   integer  $bookinvoicedate   Booking invoice date
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::bookInvoiceInEconomic() instead
	 */
	public function bookInvoiceInEconomic($order_id, $checkOrderStatus = 1, $bookinvoicedate = 0)
	{
		return RedshopEconomic::bookInvoiceInEconomic($order_id, $checkOrderStatus, $bookinvoicedate);
	}

	/**
	 * Update invoice number
	 *
	 * @param   integer  $order_id    Order ID
	 * @param   integer  $invoice_no  Invoice number
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::updateInvoiceNumber() instead
	 */
	public function updateInvoiceNumber($order_id = 0, $invoice_no = 0)
	{
		return RedshopEconomic::updateInvoiceNumber($order_id, $invoice_no);
	}

	/**
	 * Update booking invoice
	 *
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::updateBookInvoice() instead
	 */
	public function updateBookInvoice($order_id = 0)
	{
		return RedshopEconomic::updateBookInvoice($order_id);
	}

	/**
	 * Update booking invoice number
	 *
	 * @param   integer  $order_id            Order ID
	 * @param   integer  $bookinvoice_number  Booking invoice number
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::updateBookInvoiceNumber() instead
	 */
	public function updateBookInvoiceNumber($order_id = 0, $bookinvoice_number = 0)
	{
		return RedshopEconomic::updateBookInvoiceNumber($order_id, $bookinvoice_number);
	}

	/**
	 * Get product number
	 *
	 * @param   string  $product_number  Product Number
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::getProductByNumber() instead
	 */
	public function getProductByNumber($product_number = '')
	{
		return RedshopEconomic::getProductByNumber($product_number);
	}

	/**
	 * Make Accessory Order
	 *
	 * @param   string   $invoice_no  Invoice number
	 * @param   object   $orderItem   Order item
	 * @param   integer  $user_id     User ID
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::makeAccessoryOrder() instead
	 */
	public function makeAccessoryOrder($invoice_no, $orderItem, $user_id = 0)
	{
		return RedshopEconomic::makeAccessoryOrder($invoice_no, $orderItem, $user_id);
	}

	/**
	 * Make Attribute Order
	 *
	 * @param   string   $invoice_no         Invoice number
	 * @param   object   $orderItem          Order Item
	 * @param   integer  $is_accessory       Is accessory
	 * @param   integer  $parent_section_id  Parent Section ID
	 * @param   integer  $user_id            User ID
	 *
	 * @return  integer
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::makeAttributeOrder() instead
	 */
	public function makeAttributeOrder($invoice_no, $orderItem, $is_accessory = 0, $parent_section_id = 0, $user_id = 0)
	{
		return RedshopEconomic::makeAttributeOrder($invoice_no, $orderItem, $is_accessory, $parent_section_id, $user_id);
	}

	/**
	 * Create Attribute Invoice Line In Economic
	 *
	 * @param   string  $invoice_no    Invoice number
	 * @param   array   $orderItem     Order Item
	 * @param   array   $orderAttitem  Ordere Attribute Item
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::createAttributeInvoiceLineInEconomic() instead
	 */
	public function createAttributeInvoiceLineInEconomic($invoice_no, $orderItem, $orderAttitem)
	{
		return RedshopEconomic::createAttributeInvoiceLineInEconomic($invoice_no, $orderItem, $orderAttitem);
	}

	/**
	 * Get economic Tax zone
	 *
	 * @param   string  $country_code  Country code
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::getEconomicTaxZone() instead
	 */
	public function getEconomicTaxZone($country_code = "")
	{
		return RedshopEconomic::getEconomicTaxZone($country_code);
	}

	/**
	 * Check country is belong to EU
	 *
	 * @param   string  $country  Country code
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.3 Use RedshopEconomic::isEuCountry() instead
	 */
	public function isEUCountry($country)
	{
		return RedshopEconomic::isEuCountry($country);
	}
}
