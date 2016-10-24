<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

/**
 * Include filesystem to handle files
 */
jimport('joomla.filesystem.file');

/**
 * Library for Redshop E-conomic.
 * This Library provide methods for interact with E-Invoicing and support to orders.
 * For more information about E-invoicing: https://en.wikipedia.org/wiki/Electronic_invoicing
 * Using: new RedshopEconomic
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopEconomic
{
	/**
	 * The dispatcher to trigger events
	 *
	 * @var  JEventDispatcher
	 */
	public static $dispatcher = null;

	/**
	 * Currently it will import plugin: economic, then
	 * pre-define a dispatcher to trigger event in other methods.
	 *
	 * @return  void
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function importEconomic()
	{
		JPluginHelper::importPlugin('economic');
		self::getDispatcher();
	}

	/**
	 * Get priority Dispatcher because JEventDispatcher does not exist in Joomla 2.5.
	 * In that case, change JEventDispatcher to JDispatcher.
	 * Using JDispatcher is possible in Joomla 3.x but will generate a deprecated notice.
	 *
	 * @return  JEventDispatcher/JDispatcher
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getDispatcher()
	{
		if (!self::$dispatcher)
		{
			self::$dispatcher = version_compare(JVERSION, '3.0', 'lt') ? JDispatcher::getInstance() : JEventDispatcher::getInstance();
		}

		return self::$dispatcher;
	}

	/**
	 * Create an user in E-conomic
	 *
	 * @param   array  $row   Data to create user
	 * @param   array  $data  Data of Economic
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createUserInEconomic($row = array(), $data = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco                 = array();
		$eco['user_id']      = $row->user_id;
		$eco['user_info_id'] = $row->users_info_id;
		$debtorHandle        = self::$dispatcher->trigger('Debtor_FindByNumber', array($eco));

		$eco['currency_code'] = Redshop::getConfig()->get('CURRENCY_CODE');
		$eco['vatzone']       = self::getEconomicTaxZone($row->country_code);
		$eco['email']         = $row->user_email;

		if ($row->is_company == 1)
		{
			if ($row->vat_number != "")
			{
				$eco['vatnumber'] = $row->vat_number;
			}

			if ($row->ean_number != "")
			{
				$eco['ean_number'] = $row->ean_number;
			}
		}
		else
		{
			$eco['vatnumber'] = "";
		}

		$name = $row->firstname . ' ' . $row->lastname;

		if ($row->is_company == 1 && $row->company_name != '')
		{
			$name = $row->company_name;
		}

		$eco['name']    = $name;
		$eco['phone']   = $row->phone;
		$eco['address'] = $row->address;
		$eco['zipcode'] = $row->zipcode;
		$eco['city']    = $row->city;
		$eco['country'] = RedshopHelperOrder::getCountryName($row->country_code);

		if (isset($data['economic_payment_terms_id']))
		{
			$eco['economic_payment_terms_id'] = $data['economic_payment_terms_id'];
		}

		if (isset($data['economic_design_layout']))
		{
			$eco['economic_design_layout'] = $data['economic_design_layout'];
		}

		$eco['eco_user_number'] = "";
		$eco['newuserFlag']     = false;

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$debtorEmailHandle = self::$dispatcher->trigger('Debtor_FindByEmail', array($eco));

			if (count($debtorEmailHandle) > 0 && isset($debtorEmailHandle[0]->DebtorHandle) != "")
			{
				$emailarray = $debtorEmailHandle[0]->DebtorHandle;

				if (count($emailarray) > 1)
				{
					for ($i = 0, $in = count($emailarray); $i < $in; $i++)
					{
						if ($debtorHandle[0]->Number == $emailarray[$i]->Number)
						{
							$eco['eco_user_number'] = $debtorHandle[0]->Number;
						}
					}
				}
				elseif (count($emailarray) > 0)
				{
					$eco['eco_user_number'] = $emailarray->Number;
				}
			}
			else
			{
				$eco['newuserFlag'] = true;
			}
		}

		return self::$dispatcher->trigger('storeDebtor', array($eco));
	}

	/**
	 * Create Product Group in E-conomic
	 *
	 * @param   array    $row         Data to create
	 * @param   integer  $isShipping  Shipping flag
	 * @param   integer  $isDiscount  Discount flag
	 * @param   integer  $isVat       VAT flag
	 *
	 * @return  null/array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createProductGroupInEconomic($row = array(), $isShipping = 0, $isDiscount = 0, $isVat = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$redHelper = redhelper::getInstance();

		$ecoProductGroupNumber         = new stdclass;
		$ecoProductGroupNumber->Number = 1;

		$accountGroup = array();
		$eco          = array();

		if (count($row) > 0 && $row->accountgroup_id != 0)
		{
			$accountGroup = $redHelper->getEconomicAccountGroup($row->accountgroup_id);
		}

		elseif (Redshop::getConfig()->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP') != 0)
		{
			$accountGroup = $redHelper->getEconomicAccountGroup(Redshop::getConfig()->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP'));
		}

		if (count($accountGroup) > 0)
		{
			if ($isShipping == 1)
			{
				if ($isVat == 1)
				{
					$eco['productgroup_id']   = $accountGroup[0]->economic_shipping_vat_account;
					$eco['productgroup_name'] = $accountGroup[0]->accountgroup_name . ' shipping vat';
					$eco['vataccount']        = $accountGroup[0]->economic_shipping_vat_account;
					$eco['novataccount']      = $accountGroup[0]->economic_shipping_nonvat_account;
				}
				else
				{
					$eco['productgroup_id']   = $accountGroup[0]->economic_shipping_nonvat_account;
					$eco['productgroup_name'] = $accountGroup[0]->accountgroup_name . ' shipping novat';
					$eco['vataccount']        = $accountGroup[0]->economic_shipping_nonvat_account;
					$eco['novataccount']      = $accountGroup[0]->economic_shipping_nonvat_account;
				}
			}
			elseif ($isDiscount == 1)
			{
				if ($isVat == 1)
				{
					$eco['productgroup_id']   = $accountGroup[0]->economic_discount_vat_account;
					$eco['productgroup_name'] = $accountGroup[0]->accountgroup_name . ' discount vat';
					$eco['vataccount']        = $accountGroup[0]->economic_discount_vat_account;
					$eco['novataccount']      = $accountGroup[0]->economic_discount_nonvat_account;
				}
				else
				{
					$eco['productgroup_id']   = $accountGroup[0]->economic_discount_nonvat_account;
					$eco['productgroup_name'] = $accountGroup[0]->accountgroup_name . ' discount novat';
					$eco['vataccount']        = $accountGroup[0]->economic_discount_nonvat_account;
					$eco['novataccount']      = $accountGroup[0]->economic_discount_nonvat_account;
				}
			}
			else
			{
				$eco['productgroup_id']   = $accountGroup[0]->accountgroup_id;
				$eco['productgroup_name'] = $accountGroup[0]->accountgroup_name;
				$eco['vataccount']        = $accountGroup[0]->economic_vat_account;
				$eco['novataccount']      = $accountGroup[0]->economic_nonvat_account;
			}

			$groupHandle              = self::$dispatcher->trigger('ProductGroup_FindByNumber', array($eco));
			$eco['eco_prdgro_number'] = "";

			if (count($groupHandle) > 0 && isset($groupHandle[0]->Number) != "")
			{
				$eco['eco_prdgro_number'] = $groupHandle[0]->Number;
			}

			return self::$dispatcher->trigger('storeProductGroup', array($eco));
		}

		return null;
	}

	/**
	 * Create product in E-conomic
	 *
	 * @param   array  $row  Data to create
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createProductInEconomic($row = array())
	{
		if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2 && self::getTotalProperty($row->product_id) > 0)
		{
			return;
		}

		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$redHelper = redhelper::getInstance();

		$eco['product_desc']   = utf8_encode(substr(strip_tags($row->product_desc), 0, 499));
		$eco['product_s_desc'] = utf8_encode(substr(strip_tags($row->product_s_desc), 0, 499));

		$ecoProductGroupNumber = self::createProductGroupInEconomic($row);

		if (isset($ecoProductGroupNumber[0]->Number))
		{
			$eco['product_group'] = $ecoProductGroupNumber[0]->Number;
		}

		$eco['product_number'] = trim($row->product_number);
		$eco['product_name']   = addslashes($row->product_name);
		$eco['product_price']  = $row->product_price;
		$eco['product_volume'] = $row->product_volume;
		$eco['eco_prd_number'] = "";
		$debtorHandle          = self::$dispatcher->trigger('Product_FindByNumber', array($eco));

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$eco['eco_prd_number'] = $debtorHandle[0]->Number;
		}

		$eco['product_stock'] = RedshopHelperStockroom::getStockroomTotalAmount($row->product_id);

		return self::$dispatcher->trigger('storeProduct', array($eco));
	}

	/**
	 * Get Total Property
	 *
	 * @param   integer  $productId  Product ID
	 *
	 * @return  integer
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getTotalProperty($productId)
	{
		$producthelper = productHelper::getInstance();

		// Collect Attributes
		$attribute   = $producthelper->getProductAttribute($productId);
		$attributeId = $attribute[0]->value;

		// Collect Property
		$property = $producthelper->getAttibuteProperty(0, $attributeId, $productId);

		return count($property);
	}

	/**
	 * Create property product in economic
	 *
	 * @param   array  $productRow  Product data
	 * @param   array  $row         Data property
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createPropertyInEconomic($productRow = array(), $row = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco                   = array();
		$eco['product_desc']   = '';
		$eco['product_s_desc'] = '';

		$ecoProductGroupNumber = self::createProductGroupInEconomic($productRow);

		if (isset($ecoProductGroupNumber[0]->Number))
		{
			$eco['product_group'] = $ecoProductGroupNumber[0]->Number;
		}

		$eco['product_number'] = $row->property_number;

		if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2)
		{
			$eco['product_name'] = addslashes($productRow->product_name) . " " . addslashes($row->property_name);

			$string = trim($productRow->product_price . $row->oprand . $row->property_price);
			eval('$eco["product_price"] = ' . $string . ';');
		}

		else
		{
			$eco['product_name']  = addslashes($row->property_name);
			$eco['product_price'] = $row->property_price;
		}

		$eco['product_volume'] = 1;
		$eco['eco_prd_number'] = "";
		$debtorHandle          = self::$dispatcher->trigger('Product_FindByNumber', array($eco));

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$eco['eco_prd_number'] = $debtorHandle[0]->Number;
		}

		$eco['product_stock'] = RedshopHelperStockroom::getStockroomTotalAmount($row->property_id, "property");

		return self::$dispatcher->trigger('storeProduct', array($eco));
	}

	/**
	 * Create Sub Property in Economic
	 *
	 * @param   array  $productRow  Product info
	 * @param   array  $row         Data of property
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createSubpropertyInEconomic($productRow = array(), $row = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco['product_desc']   = '';
		$eco['product_s_desc'] = '';

		$ecoProductGroupNumber = self::createProductGroupInEconomic($productRow);

		if (isset($ecoProductGroupNumber[0]->Number))
		{
			$eco['product_group'] = $ecoProductGroupNumber[0]->Number;
		}

		$eco['product_number'] = $row->subattribute_color_number;

		if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2)
		{
			$eco['product_name'] = addslashes($row->subattribute_color_name);
			$string              = trim($productRow->product_price . $row->oprand . $row->subattribute_color_price);
			eval('$eco["product_price"] = ' . $string . ';');
		}
		else
		{
			$eco['product_name']  = addslashes($row->subattribute_color_name);
			$eco['product_price'] = $row->subattribute_color_price;
		}

		$eco['product_volume'] = 1;
		$eco['eco_prd_number'] = "";
		$debtorHandle          = self::$dispatcher->trigger('Product_FindByNumber', array($eco));

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$eco['eco_prd_number'] = $debtorHandle[0]->Number;
		}

		$eco['product_stock'] = RedshopHelperStockroom::getStockroomTotalAmount($row->subattribute_color_id, "subproperty");

		return self::$dispatcher->trigger('storeProduct', array($eco));
	}

	/**
	 * Import Stock from Economic
	 *
	 * @param   array  $productRow  Product Info
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function importStockFromEconomic($productRow = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco = array();
		$eco['product_number'] = $productRow->product_number;

		return self::$dispatcher->trigger('getProductStock', array($eco));
	}

	/**
	 * Create Shipping rate in economic
	 *
	 * @param   integer  $shippingNumber  Shipping Number
	 * @param   string   $shippingName    Shipping Name
	 * @param   integer  $shippingRate    Shipping Rate
	 * @param   integer  $isVat           VAT flag
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createShippingRateInEconomic($shippingNumber, $shippingName, $shippingRate = 0, $isVat = 1)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco['product_desc']   = "";
		$eco['product_s_desc'] = "";

		$ecoProductGroupNumber = self::createProductGroupInEconomic(array(), 1, 0, $isVat);

		if (isset($ecoProductGroupNumber[0]->Number))
		{
			$eco['product_group'] = $ecoProductGroupNumber[0]->Number;
		}

		if (strlen($shippingNumber) > 25)
		{
			$shippingNumber = substr($shippingNumber, 0, 25);
		}

		$eco['product_number'] = $shippingNumber;
		$eco['product_name']   = addslashes($shippingName);
		$eco['product_price']  = $shippingRate;
		$eco['product_volume'] = 1;
		$debtorHandle          = self::$dispatcher->trigger('Product_FindByNumber', array($eco));
		$eco['eco_prd_number'] = "";

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$eco['eco_prd_number'] = $debtorHandle[0]->Number;
		}

		$eco['product_stock'] = 1;

		return self::$dispatcher->trigger('storeProduct', array($eco));
	}

	/**
	 * Get Max User Number in E-conomic
	 *
	 * @return  integer
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getMaxDebtorInEconomic()
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		return self::$dispatcher->trigger('getMaxDebtor');
	}

	/**
	 * Get Max Order Number in Economic
	 *
	 * @return  integer
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getMaxOrderNumberInEconomic()
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$ecoMaxInvoiceNumber = self::$dispatcher->trigger('getMaxInvoiceNumber');
		$ecoMaxDraftNumber   = self::$dispatcher->trigger('getMaxDraftInvoiceNumber');

		return max($ecoMaxInvoiceNumber[0], $ecoMaxDraftNumber[0]);
	}

	/**
	 * Create Invoice in economic
	 *
	 * @param   integer  $orderId  Order ID
	 * @param   array    $data     Data to create
	 *
	 * @return  boolean/string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createInvoiceInEconomic($orderId, $data = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$orderDetail = RedshopHelperOrder::getOrderDetails($orderId);

		if ($orderDetail->is_booked == 0 && !$orderDetail->invoice_no)
		{
			$userBillingInfo  = RedshopHelperOrder::getOrderBillingUserInfo($orderId);
			$userShippingInfo = RedshopHelperOrder::getOrderShippingUserInfo($orderId);
			$orderItem        = RedshopHelperOrder::getOrderItemDetail($orderId);

			$eco['shop_name']                 = Redshop::getConfig()->get('SHOP_NAME');
			$eco['economic_payment_terms_id'] = $data['economic_payment_terms_id'];
			$eco['economic_design_layout']    = $data['economic_design_layout'];

			$ecodebtorNumber = self::createUserInEconomic($userBillingInfo, $data);

			if (count($ecodebtorNumber) > 0 && is_object($ecodebtorNumber[0]))
			{
				$eco['order_id']   = $orderDetail->order_id;
				$eco['setAttname'] = 0;

				if ($userBillingInfo->is_company == 1)
				{
					$eco['setAttname'] = 1;
				}

				$eco['name'] = $userBillingInfo->firstname . " " . $userBillingInfo->lastname;

				$eco['isvat']              = ($orderDetail->order_tax != 0) ? 1 : 0;
				$currency                  = Redshop::getConfig()->get('CURRENCY_CODE');
				$eco['email']              = $userBillingInfo->user_email;
				$eco['phone']              = $userBillingInfo->phone;
				$eco['currency_code']      = $currency;
				$eco['order_number']       = $orderDetail->order_number;
				$eco['amount']             = $orderDetail->order_total;
				$eco['debtorHandle']       = intVal($ecodebtorNumber[0]->Number);
				$eco['user_info_id']       = $userBillingInfo->users_info_id;
				$eco['customer_note']      = $orderDetail->customer_note;
				$eco['requisition_number'] = $orderDetail->requisition_number;
				$eco['vatzone']            = self::getEconomicTaxZone($userBillingInfo->country_code);

				$invoiceHandle = self::$dispatcher->trigger('createInvoice', array($eco));

				if (count($invoiceHandle) > 0 && $invoiceHandle[0]->Id)
				{
					$invoiceNo = $invoiceHandle[0]->Id;
					self::updateInvoiceNumber($orderId, $invoiceNo);

					$eco['invoiceHandle'] = $invoiceNo;
					$eco['name_ST']       = ($userShippingInfo->is_company == 1 && $userShippingInfo->company_name != '')
						? $userShippingInfo->company_name : $userShippingInfo->firstname . ' ' . $userShippingInfo->lastname;
					$eco['address_ST']    = $userShippingInfo->address;
					$eco['city_ST']       = $userShippingInfo->city;
					$eco['country_ST']    = RedshopHelperOrder::getCountryName($userShippingInfo->country_code);
					$eco['zipcode_ST']    = $userShippingInfo->zipcode;

					self::$dispatcher->trigger('setDeliveryAddress', array($eco));

					if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2)
					{
						self::createInvoiceLineInEconomicAsProduct($orderItem, $invoiceNo, $orderDetail->user_id);
					}
					else
					{
						self::createInvoiceLineInEconomic($orderItem, $invoiceNo, $orderDetail->user_id);
					}

					self::createInvoiceShippingLineInEconomic($orderDetail->ship_method_id, $invoiceNo);

					$isVatDiscount = 0;

					if (Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') == '0' && (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') && $orderDetail->order_discount != "0.00" && $orderDetail->order_tax && !empty($orderDetail->order_discount))
					{
						$totalDiscount               = $orderDetail->order_discount;
						$discountVat                 = ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $totalDiscount) / (1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'));
						$orderDetail->order_discount = $totalDiscount - $discountVat;
						$isVatDiscount               = 1;
					}

					$orderDiscount = $orderDetail->order_discount + $orderDetail->special_discount_amount;

					if ($orderDiscount)
					{
						self::createInvoiceDiscountLineInEconomic($orderDetail, $invoiceNo, $data, 0, $isVatDiscount);
					}

					if ($orderDetail->payment_discount != 0)
					{
						self::createInvoiceDiscountLineInEconomic($orderDetail, $invoiceNo, $data, 1);
					}
				}

				return $invoiceHandle;
			}

			else
			{
				return "USRE_NOT_SAVED_IN_ECONOMIC";
			}
		}

		return true;
	}

	/**
	 * Create Invoice Line In Economic
	 *
	 * @param   array    $orderItem  Order Items
	 * @param   string   $invoiceNo  Invoice Number
	 * @param   integer  $userId     User ID
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createInvoiceLineInEconomic($orderItem = array(), $invoiceNo = "", $userId = 0)
	{
		if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2)
		{
			return;
		}

		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		for ($i = 0, $in = count($orderItem); $i < $in; $i++)
		{
			$displayWrapper   = "";
			$displayAttribute = "";
			$displayAccessory = "";

			// Create Gift Card Entry for invoice
			if ($orderItem[$i]->is_giftcard)
			{
				self::createGFInvoiceLineInEconomic($orderItem[$i], $invoiceNo);
				continue;
			}

			$productId     = $orderItem[$i]->product_id;
			$product       = Redshop::product((int) $productId);
			$productHelper = productHelper::getInstance();
			self::createProductInEconomic($product);

			if ($orderItem[$i]->wrapper_id)
			{
				$wrapper = $productHelper->getWrapper($orderItem[$i]->product_id, $orderItem[$i]->wrapper_id);

				if (count($wrapper) > 0)
				{
					$wrapperName = $wrapper[0]->wrapper_name;
				}

				$displayWrapper = "\n" . JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName . "(" . $orderItem[$i]->wrapper_price . ")";
			}

			$eco['updateInvoice']  = 0;
			$eco['invoiceHandle']  = $invoiceNo;
			$eco['order_item_id']  = $orderItem[$i]->order_item_id;
			$eco['product_number'] = $orderItem[$i]->order_item_sku;

			$discountCalc = "";

			if ($orderItem[$i]->discount_calc_data)
			{
				$discountCalc = $orderItem[$i]->discount_calc_data;
				$discountCalc = str_replace("<br />", "\n", $discountCalc);
				$discountCalc = "\n" . $discountCalc;
			}

			// Product user field Information
			$pUserfield    = $productHelper->getuserfield($orderItem[$i]->order_item_id);
			$displayWrapper = $displayWrapper . "\n" . strip_tags($pUserfield);

			$eco['product_name']     = $orderItem[$i]->order_item_name . $displayWrapper . $displayAttribute . $discountCalc . $displayAccessory;
			$eco['product_price']    = $orderItem[$i]->product_item_price_excl_vat;
			$eco['product_quantity'] = $orderItem[$i]->product_quantity;
			$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

			$invoiceLineNo = self::$dispatcher->trigger('createInvoiceLine', array($eco));

			$displayAttribute = self::makeAttributeOrder($invoiceNo, $orderItem[$i], 0, $orderItem[$i]->product_id, $userId);

			if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
			{
				$orderItem[$i]->product_item_price_excl_vat -= $displayAttribute;
				$displayAttribute = '';
			}

			$displayAccessory = self::makeAccessoryOrder($invoiceNo, $orderItem[$i], $userId);

			$orderItem[$i]->product_item_price_excl_vat -= $displayAccessory;
			$displayAccessory = '';

			if (count($invoiceLineNo) > 0 && $invoiceLineNo[0]->Number)
			{
				$updateInvoiceLine        = $invoiceLineNo[0]->Number;
				$eco['updateInvoice']    = 1;
				$eco['invoiceHandle']    = $invoiceNo;
				$eco['order_item_id']    = $updateInvoiceLine;
				$eco['product_number']   = $orderItem[$i]->order_item_sku;
				$eco['product_name']     = $orderItem[$i]->order_item_name . $displayWrapper . $displayAttribute . $discountCalc . $displayAccessory;
				$eco['product_price']    = $orderItem[$i]->product_item_price_excl_vat;
				$eco['product_quantity'] = $orderItem[$i]->product_quantity;
				$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

				$invoiceLineNo = self::$dispatcher->trigger('createInvoiceLine', array($eco));
			}
		}
	}

	/**
	 * Create Invoice line in E-conomic for GiftCard
	 *
	 * @param   array   $orderItem  Order Item
	 * @param   string  $invoiceNo  Invoice Number
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createGiftCardInvoiceLineInEconomic($orderItem = array(), $invoiceNo = "")
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$productHelper = productHelper::getInstance();

		$product                 = new stdClass;
		$product->product_id     = $orderItem->product_id;
		$product->product_number = $orderItem->order_item_sku = "gift_" . $orderItem->product_id . "_" . $orderItem->order_item_name;
		$product->product_name   = $orderItem->order_item_name;
		$product->product_price  = $orderItem->product_item_price_excl_vat;

		$giftData                 = $productHelper->getGiftcardData($orderItem->product_id);
		$product->accountgroup_id = $giftData->accountgroup_id;
		$product->product_volume  = 0;

		self::createProductInEconomic($product);

		$eco                     = array();
		$eco['updateInvoice']    = 0;
		$eco['invoiceHandle']    = $invoiceNo;
		$eco['order_item_id']    = $orderItem->order_item_id;
		$eco['product_number']   = $orderItem->order_item_sku;
		$eco['product_name']     = $orderItem->order_item_name;
		$eco['product_price']    = $orderItem->product_item_price_excl_vat;
		$eco['product_quantity'] = $orderItem->product_quantity;
		$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

		self::$dispatcher->trigger('createInvoiceLine', array($eco));
	}

	/**
	 * Method to create Invoice line in E-conomic as Product
	 *
	 * @param   array    $orderItem  Order Item
	 * @param   string   $invoiceNo  Invoice Number
	 * @param   integer  $userId     User ID
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createInvoiceLineInEconomicAsProduct($orderItem = array(), $invoiceNo = "", $userId = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$productHelper = productHelper::getInstance();

		for ($i = 0, $in = count($orderItem); $i < $in; $i++)
		{
			$displayWrapper   = "";
			$displayAccessory = "";

			$productId = $orderItem[$i]->product_id;
			$product    = Redshop::product((int) $productId);
			self::createProductInEconomic($product);

			if ($orderItem[$i]->wrapper_id)
			{
				$wrapper = $productHelper->getWrapper($orderItem[$i]->product_id, $orderItem[$i]->wrapper_id);

				if (count($wrapper) > 0)
				{
					$wrapperName = $wrapper[0]->wrapper_name;
				}

				$displayWrapper = "\n" . JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName . "(" . $orderItem[$i]->wrapper_price . ")";
			}

			// Fetch Accessory from Order Item
			$displayAccessory = self::makeAccessoryOrder($invoiceNo, $orderItem[$i], $userId);

			$eco['updateInvoice']  = 0;
			$eco['invoiceHandle']  = $invoiceNo;
			$eco['order_item_id']  = $orderItem[$i]->order_item_id;
			$eco['product_number'] = $orderItem[$i]->order_item_sku;

			$discountCalc = "";

			if ($orderItem[$i]->discount_calc_data)
			{
				$discountCalc = $orderItem[$i]->discount_calc_data;
				$discountCalc = str_replace("<br />", "\n", $discountCalc);
				$discountCalc = "\n" . $discountCalc;
			}

			// Product user field Information
			$pUserfield     = $productHelper->getuserfield($orderItem[$i]->order_item_id);
			$displayWrapper = $displayWrapper . "\n" . strip_tags($pUserfield);

			$eco['product_name']     = $orderItem[$i]->order_item_name . $displayWrapper . $discountCalc . $displayAccessory;
			$eco['product_price']    = $orderItem[$i]->product_item_price_excl_vat;
			$eco['product_quantity'] = $orderItem[$i]->product_quantity;
			$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

			// Collect Order Attrubute Items
			$orderItemAttdata = RedshopHelperOrder::getOrderItemAttributeDetail($orderItem[$i]->order_item_id, 0, "attribute", $orderItem[$i]->product_id);

			if (count($orderItemAttdata) > 0)
			{
				$attributeId = $orderItemAttdata[0]->section_id;
				$productId   = $orderItem[$i]->product_id;

				$orderPropdata = RedshopHelperOrder::getOrderItemAttributeDetail($orderItem[$i]->order_item_id, 0, "property", $attributeId);

				if (count($orderPropdata) > 0)
				{
					$propertyId = $orderPropdata[0]->section_id;

					// Collect Attribute Property
					$orderProperty = $productHelper->getAttibuteProperty($propertyId, $attributeId, $productId);

					$propertyNumber = $orderProperty[0]->property_number;
					$propertyName   = $orderPropdata[0]->section_name;

					if ($propertyNumber)
					{
						$eco['product_number'] = $propertyNumber;
					}

					$eco['product_name']   = $orderItem[$i]->order_item_name . " " . $propertyName . $displayWrapper . $discountCalc;
				}
			}

			self::$dispatcher->trigger('createInvoiceLine', array($eco));
		}
	}

	/**
	 * Method to create Invoice line for shipping in E-conomic
	 *
	 * @param   string  $shipMethodId  Shipping method ID
	 * @param   string  $invoiceNo     Invoice Number
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createInvoiceShippingLineInEconomic($shipMethodId = "", $invoiceNo = "")
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		if ($shipMethodId != "")
		{
			$orderShipping = RedshopShippingRate::decrypt($shipMethodId);

			if (count($orderShipping) > 5)
			{
				// Load language file of the shipping plugin
				JFactory::getLanguage()->load(
					'plg_redshop_shipping_' . strtolower(str_replace('plgredshop_shipping', '', $orderShipping[0])),
					JPATH_ADMINISTRATOR
				);

				$shippingName       = JText::_($orderShipping[1]);
				$shippingShortname  = (strlen($shippingName) > 15) ? substr($shippingName, 0, 15) : $shippingName;
				$shippingNumber     = $shippingShortname . ' ' . $orderShipping[4];
				$shippingName       = $orderShipping[2];
				$shippingRate       = $orderShipping[3];

				$isVat = 0;

				if (isset($orderShipping[6]) && $orderShipping[6] != 0)
				{
					$isVat         = 1;
					$shippingRate = $shippingRate - $orderShipping[6];
				}

				if (isset($orderShipping[7]) && $orderShipping[7] != '')
				{
					$shippingNumber = $orderShipping[7];
				}

				$ecoShippingrateNumber = self::createShippingRateInEconomic($shippingNumber, $shippingName, $shippingRate, $isVat);

				if (isset($ecoShippingrateNumber[0]->Number))
				{
					$eco['product_number'] = $ecoShippingrateNumber[0]->Number;

					$eco['invoiceHandle']    = $invoiceNo;
					$eco['product_name']     = $shippingName;
					$eco['order_item_id']    = "";
					$eco['product_id']       = $shippingNumber;
					$eco['product_quantity'] = 1;
					$eco['product_price']    = $shippingRate;
					$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
					$eco['shipping']         = 1;

					self::$dispatcher->trigger('createInvoiceLine', array($eco));
				}
			}
		}
	}

	/**
	 * Method to create Invoice line for discount in E-conomic
	 *
	 * @param   array    $orderDetail        Order detail
	 * @param   string   $invoiceNo          Invoice Number
	 * @param   array    $data               Data
	 * @param   integer  $isPaymentDiscount  Is payment discount or not
	 * @param   integer  $isVatDiscount      Is VAT discount or not
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function createInvoiceDiscountLineInEconomic($orderDetail = array(), $invoiceNo = "", $data = array(), $isPaymentDiscount = 0, $isVatDiscount = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$productHelper = productHelper::getInstance();
		$redHelper     = redhelper::getInstance();

		if (Redshop::getConfig()->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP'))
		{
			$accountGroup = $redHelper->getEconomicAccountGroup(Redshop::getConfig()->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP'), 1);

			if (count($accountGroup) > 0)
			{
				$ecoProductGroupNumber = self::createProductGroupInEconomic(array(), 0, 1, $isVatDiscount);

				if (isset($ecoProductGroupNumber[0]->Number))
				{
					$eco['product_group'] = $ecoProductGroupNumber[0]->Number;
				}

				$discount     = $orderDetail->order_discount + $orderDetail->special_discount_amount;
				$productName  = JText::_('COM_REDSHOP_ORDER_DISCOUNT');

				$productNumber = $accountGroup[0]->economic_discount_product_number;

				if ($isPaymentDiscount)
				{
					$productNumber = $accountGroup[0]->economic_discount_product_number . "_" . $data['economic_payment_method'];
					$productName   = ($orderDetail->payment_oprand == '+') ? JText::_('PAYMENT_CHARGES_LBL') : JText::_('PAYMENT_DISCOUNT_LBL');
					$discount      = ($orderDetail->payment_oprand == "+") ? (0 - $orderDetail->payment_discount) : $orderDetail->payment_discount;
				}

				$discountShort = (strlen($productNumber) > 20) ? substr($productNumber, 0, 20) : $productNumber;

				$eco['invoiceHandle']    = $invoiceNo;
				$eco['product_number']   = $discountShort;
				$eco['product_name']     = $productName;
				$eco['order_item_id']    = "";
				$eco['product_desc']     = "";
				$eco['product_s_desc']   = "";
				$eco['product_id']       = $discountShort;
				$eco['product_quantity'] = 1;
				$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
				$eco['product_price']    = (0 - $discount);
				$eco['product_volume']   = 1;

				$debtorHandle          = self::$dispatcher->trigger('Product_FindByNumber', array($eco));
				$eco['eco_prd_number'] = "";

				if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
				{
					$eco['eco_prd_number'] = $debtorHandle[0]->Number;
				}

				$eco['product_stock'] = 1;
				self::$dispatcher->trigger('storeProduct', array($eco));
				self::$dispatcher->trigger('createInvoiceLine', array($eco));
			}
		}
	}

	/**
	 * Method to create Invoice and send mail in E-conomic
	 *
	 * @param   array  $orderData  Order data
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function renewInvoiceInEconomic($orderData)
	{
		$invoiceHandle = array();

		if ($orderData->is_booked == 0)
		{
			$data        = array();
			$paymentInfo = RedshopHelperOrder::getOrderPaymentDetail($orderData->order_id);

			if (count($paymentInfo) > 0)
			{
				$paymentName  = $paymentInfo[0]->payment_method_class;
				$paymentArr   = explode("rs_payment_", $paymentInfo[0]->payment_method_class);

				if (count($paymentArr) > 0)
				{
					$paymentName = $paymentArr[1];
				}

				$data['economic_payment_method'] = $paymentName;
				$paymentMethod                   = RedshopHelperOrder::getPaymentMethodInfo($paymentInfo[0]->payment_method_class);

				if (count($paymentMethod) > 0)
				{
					$paymentParams                     = new JRegistry($paymentMethod[0]->params);
					$data['economic_payment_terms_id'] = $paymentParams->get('economic_payment_terms_id');
					$data['economic_design_layout']    = $paymentParams->get('economic_design_layout');
					$data['economic_is_creditcard']    = $paymentParams->get('is_creditcard');
				}
			}

			// Delete existing draft invoice from e-conomic
			if ($orderData->invoice_no)
			{
				self::deleteInvoiceInEconomic($orderData);
			}

			return self::createInvoiceInEconomic($orderData->order_id, $data);
		}

		return array();
	}

	/**
	 * Method to delete invoice in E-conomic
	 *
	 * @param   array  $orderData  Order data to delete
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function deleteInvoiceInEconomic($orderData = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		if ($orderData->invoice_no)
		{
			$eco['invoiceHandle'] = $orderData->invoice_no;
			self::$dispatcher->trigger('deleteInvoice', array($eco));
			self::updateInvoiceNumber($orderData->order_id, 0);
		}
	}

	/**
	 * Method to check invoice is draft or booked in E-conomic
	 *
	 * @param   array  $orderDetail  Order detail
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function checkInvoiceDraftorBookInEconomic($orderDetail)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco['invoiceHandle'] = $orderDetail->invoice_no;
		$eco['order_number']  = $orderDetail->order_number;
		$bookInvoiceData      = self::$dispatcher->trigger('checkBookInvoice', array($eco));

		if (count($bookInvoiceData) > 0 && isset($bookInvoiceData[0]->InvoiceHandle))
		{
			$bookInvoiceData = $bookInvoiceData[0]->InvoiceHandle;

			if (isset($bookInvoiceData->Number) && is_numeric($bookInvoiceData->Number))
			{
				$bookInvoiceNumber = $bookInvoiceData->Number;
				self::updateBookInvoice($orderDetail->order_id);
				self::updateBookInvoiceNumber($orderDetail->order_id, $bookInvoiceNumber);
				$invoiceData[0]->invoiceStatus = "booked";
			}
		}

		else
		{
			$invoiceData[0]->invoiceStatus = "draft";
		}

		return $invoiceData;
	}

	/**
	 * [updateInvoiceDateInEconomic description]
	 *
	 * @param   [type]   $orderDetail      [description]
	 * @param   integer  $bookinvoicedate  [description]
	 *
	 * @return  [type]                     [description]
	 */
	public function updateInvoiceDateInEconomic($orderDetail, $bookinvoicedate = 0)
	{
		$db = JFactory::getDbo();
		$eco['invoiceHandle'] = $orderDetail->invoice_no;

		if ($bookinvoicedate != 0)
		{
			$eco['invoiceDate'] = $bookinvoicedate . "T" . date("h:i:s");
		}

		else
		{
			$eco['invoiceDate'] = date("Y-m-d") . "T" . date("h:i:s");
		}

		$bookinvoice_date = strtotime($eco['invoiceDate']);
		$query = 'UPDATE ' . $this->_table_prefix . 'orders '
			. 'SET bookinvoice_date = ' . $db->quote($bookinvoice_date) . ' '
			. 'WHERE order_id = ' . (int) $orderDetail->order_id;

		$this->_db->setQuery($query);
		$this->_db->execute();

		$InvoiceNumber = self::$dispatcher->trigger('updateInvoiceDate', array($eco));

		return $InvoiceNumber;
	}

	/**
	 * [bookInvoiceInEconomic description]
	 *
	 * @param   [type]   $orderId           [description]
	 * @param   integer  $checkOrderStatus  [description]
	 * @param   integer  $bookinvoicedate   [description]
	 *
	 * @return  [type]                      [description]
	 */
	public function bookInvoiceInEconomic($orderId, $checkOrderStatus = 1, $bookinvoicedate = 0)
	{
		$file = '';

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$orderDetail = RedshopHelperOrder::getOrderDetails($orderId);

			if ($orderDetail->invoice_no != '' && $orderDetail->is_booked == 0)
			{
				if ((Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2 && $orderDetail->order_status == Redshop::getConfig()->get('BOOKING_ORDER_STATUS')) || $checkOrderStatus == 0)
				{
					$userBillingInfo = RedshopHelperOrder::getOrderBillingUserInfo($orderId);

					if ($userBillingInfo->is_company == 0 || (!$userBillingInfo->ean_number && $userBillingInfo->is_company == 1))
					{
						$currency = Redshop::getConfig()->get('CURRENCY_CODE');

						$eco['invoiceHandle'] = $orderDetail->invoice_no;
						$eco['debtorHandle']  = intVal($userBillingInfo->users_info_id);
						$eco['currency_code'] = $currency;
						$eco['amount']        = $orderDetail->order_total;
						$eco['order_number']  = $orderDetail->order_number;
						$eco['order_id']      = $orderDetail->order_id;

						$currectinvoiceData = self::$dispatcher->trigger('checkDraftInvoice', array($eco));

						if (count($currectinvoiceData) > 0 && trim($currectinvoiceData[0]->OtherReference) == $orderDetail->order_number)
						{
							$this->updateInvoiceDateInEconomic($orderDetail, $bookinvoicedate);

							if ($userBillingInfo->is_company == 1 && $userBillingInfo->company_name != '')
							{
								$eco['name'] = $userBillingInfo->company_name;
							}

							else
							{
								$eco['name'] = $userBillingInfo->firstname . " " . $userBillingInfo->lastname;
							}

							$paymentInfo = RedshopHelperOrder::getOrderPaymentDetail($orderDetail->order_id);

							if (count($paymentInfo) > 0)
							{
								$paymentMethod = RedshopHelperOrder::getPaymentMethodInfo($paymentInfo[0]->payment_method_class);

								if (count($paymentMethod) > 0)
								{
									$paymentParams                    = new JRegistry($paymentMethod[0]->params);
									$eco['economic_payment_terms_id'] = $paymentParams->get('economic_payment_terms_id');
									$eco['economic_design_layout']    = $paymentParams->get('economic_design_layout');
								}

								// Setting merchant fees for economic
								if ($paymentInfo[0]->order_transfee > 0)
								{
									$eco['order_transfee'] = $paymentInfo[0]->order_transfee;
								}
							}

							if (Redshop::getConfig()->get('ECONOMIC_BOOK_INVOICE_NUMBER') == 1)
							{
								$bookhandle = self::$dispatcher->trigger('CurrentInvoice_Book', array($eco));
							}
							else
							{
								$bookhandle = self::$dispatcher->trigger('CurrentInvoice_BookWithNumber', array($eco));
							}

							if (count($bookhandle) > 0 && isset($bookhandle[0]->Number))
							{
								$bookInvoiceNumber = $eco['bookinvoice_number'] = $bookhandle[0]->Number;

								if (Redshop::getConfig()->get('ECONOMIC_BOOK_INVOICE_NUMBER') == 1)
								{
									$this->updateBookInvoiceNumber($orderId, $bookInvoiceNumber);
								}

								$bookinvoicepdf = self::$dispatcher->trigger('bookInvoice', array($eco));

								if (JError::isError(JError::getError()))
								{
									return $file;
								}
								elseif ($bookinvoicepdf != "")
								{
									$file = JPATH_ROOT . '/components/com_redshop/assets/orders/rsInvoice_' . $orderId . '.pdf';
									JFile::write($file, $bookinvoicepdf);

									if (is_file($file))
									{
										$this->updateBookInvoice($orderId);
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

	/**
	 * [updateInvoiceNumber description]
	 *
	 * @param   integer  $orderId    [description]
	 * @param   integer  $invoiceNo  [description]
	 *
	 * @return  [type]               [description]
	 */
	public function updateInvoiceNumber($orderId = 0, $invoiceNo = 0)
	{
		$db = JFactory::getDbo();

		$query = 'UPDATE ' . $this->_table_prefix . 'orders '
			. 'SET invoice_no = ' . $db->quote($invoiceNo) . ' '
			. 'WHERE order_id = ' . (int) $orderId;
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	/**
	 * [updateBookInvoice description]
	 *
	 * @param   integer  $orderId  [description]
	 *
	 * @return  [type]             [description]
	 */
	public function updateBookInvoice($orderId = 0)
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'orders '
			. 'SET is_booked="1" '
			. 'WHERE order_id = ' . (int) $orderId;
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	/**
	 * [updateBookInvoiceNumber description]
	 *
	 * @param   integer  $orderId            [description]
	 * @param   integer  $bookInvoiceNumber  [description]
	 *
	 * @return  [type]                       [description]
	 */
	public function updateBookInvoiceNumber($orderId = 0, $bookInvoiceNumber = 0)
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'orders '
			. 'SET bookinvoice_number = ' . (int) $bookInvoiceNumber . ' '
			. 'WHERE order_id = ' . (int) $orderId;
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	/**
	 * [getProductByNumber description]
	 *
	 * @param   string  $productNumber  [description]
	 *
	 * @return  [type]                  [description]
	 */
	public function getProductByNumber($productNumber = '')
	{
		$db = JFactory::getDbo();

		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product '
			. 'WHERE product_number = ' . $db->quote($productNumber);
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	/**
	 * [makeAccessoryOrder description]
	 *
	 * @param   [type]   $invoiceNo  [description]
	 * @param   [type]   $orderItem  [description]
	 * @param   integer  $userId     [description]
	 *
	 * @return  [type]               [description]
	 */
	public function makeAccessoryOrder($invoiceNo, $orderItem, $userId = 0)
	{
		$displayAccessory = "";
		$retPrice         = 0;
		$orderItemdata    = RedshopHelperOrder::getOrderItemAccessoryDetail($orderItem->order_item_id);

		if (count($orderItemdata) > 0)
		{
			$displayAccessory .= "\n" . JText::_("COM_REDSHOP_ACCESSORY");

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
				$displayAccessory .= "\n" . urldecode($orderItemdata[$i]->order_acc_item_name) . " (" . ($orderItemdata[$i]->order_acc_price + $orderItemdata[$i]->order_acc_vat) . ")" . $accessory_quantity;

				if (true)
				{
					$retPrice += $orderItemdata[$i]->product_acc_item_price;

					$eco['updateInvoice']    = 0;
					$eco['invoiceHandle']    = $invoiceNo;
					$eco['order_item_id']    = $orderItem->order_item_id;
					$eco['product_number']   = $orderItemdata[$i]->order_acc_item_sku;
					$eco['product_name']     = $orderItemdata[$i]->order_acc_item_name;
					$eco['product_price']    = $orderItemdata[$i]->product_acc_item_price;
					$eco['product_quantity'] = $orderItemdata[$i]->product_quantity;
					$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
					$invoiceLineNo           = self::$dispatcher->trigger('createInvoiceLine', array($eco));
				}

				$displayAttribute = $this->makeAttributeOrder($invoiceNo, $orderItem, 1, $orderItemdata[$i]->product_id, $userId);
				$displayAccessory .= $displayAttribute;

				if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
				{
					$orderItemdata[$i]->product_acc_item_price -= $displayAttribute;
					$displayAttribute = '';
				}

				if (true && count($invoiceLineNo) > 0 && $invoiceLineNo[0]->Number)
				{
					$eco['updateInvoice']    = 1;
					$eco['invoiceHandle']    = $invoiceNo;
					$eco['order_item_id']    = $invoiceLineNo[0]->Number;
					$eco['product_number']   = $orderItemdata[$i]->order_acc_item_sku;
					$eco['product_name']     = $orderItemdata[$i]->order_acc_item_name . $displayAttribute;
					$eco['product_price']    = $orderItemdata[$i]->product_acc_item_price;
					$eco['product_quantity'] = $orderItemdata[$i]->product_quantity;
					$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

					$invoiceLineNo = self::$dispatcher->trigger('createInvoiceLine', array($eco));
				}
			}
		}

		if (true)
		{
			$displayAccessory = $retPrice;
		}

		return $displayAccessory;
	}

	/**
	 * [makeAttributeOrder description]
	 *
	 * @param   [type]   $invoiceNo        [description]
	 * @param   [type]   $orderItem        [description]
	 * @param   integer  $isAccessory      [description]
	 * @param   integer  $parentSectionId  [description]
	 * @param   integer  $userId           [description]
	 *
	 * @return  [type]                     [description]
	 */
	public function makeAttributeOrder($invoiceNo, $orderItem, $isAccessory = 0, $parentSectionId = 0, $userId = 0)
	{
		$displayAttribute = "";
		$retPrice         = 0;
		$chktag           = $productHelper->getApplyattributeVatOrNot('', $userId);
		$orderItemAttdata = RedshopHelperOrder::getOrderItemAttributeDetail($orderItem->order_item_id, $isAccessory, "attribute", $parentSectionId);

		if (count($orderItemAttdata) > 0)
		{
			$product = Redshop::product((int) $parentSectionId);

			for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++)
			{
				$attribute            = $productHelper->getProductAttribute(0, 0, $orderItemAttdata[$i]->section_id);
				$hide_attribute_price = 0;

				if (count($attribute) > 0)
				{
					$hide_attribute_price = $attribute[0]->hide_attribute_price;
				}

				$displayAttribute .= "\n" . urldecode($orderItemAttdata[$i]->section_name) . " : ";
				$orderPropdata = RedshopHelperOrder::getOrderItemAttributeDetail($orderItem->order_item_id, $isAccessory, "property", $orderItemAttdata[$i]->section_id);

				for ($p = 0, $pn = count($orderPropdata); $p < $pn; $p++)
				{
					$property      = $productHelper->getAttibuteProperty($orderPropdata[$p]->section_id);
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

						$disPrice = " (" . $orderPropdata[$p]->section_oprand . $productHelper->getProductFormattedPrice($property_price) . ")";
					}

					$displayAttribute .= urldecode($orderPropdata[$p]->section_name) . $disPrice . $virtualNumber;

					if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
					{
						$retPrice += $orderPropdata[$p]->section_price;
						$this->createAttributeInvoiceLineInEconomic($invoiceNo, $orderItem, array($orderPropdata[$p]));
					}

					$orderSubpropdata = RedshopHelperOrder::getOrderItemAttributeDetail($orderItem->order_item_id, $isAccessory, "subproperty", $orderPropdata[$p]->section_id);

					if (count($orderSubpropdata) > 0)
					{
						for ($sp = 0; $sp < count($orderSubpropdata); $sp++)
						{
							$subproperty   = $productHelper->getAttibuteSubProperty($orderSubpropdata[$sp]->section_id);
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

								$disPrice = " (" . $orderSubpropdata[$sp]->section_oprand . $productHelper->getProductFormattedPrice($subproperty_price) . ")";
							}

							$displayAttribute .= "\n" . urldecode($orderSubpropdata[$sp]->section_name) . $disPrice . $virtualNumber;

							if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
							{
								$retPrice += $orderSubpropdata[$sp]->section_price;
							}
						}

						if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
						{
							$this->createAttributeInvoiceLineInEconomic($invoiceNo, $orderItem, $orderSubpropdata);
						}
					}
				}
			}
		}

		if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
		{
			$displayAttribute = $retPrice;
		}

		return $displayAttribute;
	}

	/**
	 * [createAttributeInvoiceLineInEconomic description]
	 *
	 * @param   [type]  $invoiceNo     [description]
	 * @param   [type]  $orderItem     [description]
	 * @param   [type]  $orderAttitem  [description]
	 *
	 * @return  [type]                 [description]
	 */
	public function createAttributeInvoiceLineInEconomic($invoiceNo, $orderItem, $orderAttitem)
	{
		for ($i = 0, $in = count($orderAttitem); $i < $in; $i++)
		{
			$eco[$i]['invoiceHandle']    = $invoiceNo;
			$eco[$i]['order_item_id']    = $orderItem->order_item_id;
			$eco[$i]['product_number']   = $orderAttitem[$i]->virtualNumber;
			$eco[$i]['product_name']     = $orderAttitem[$i]->section_name;
			$eco[$i]['product_price']    = $orderAttitem[$i]->section_price;
			$eco[$i]['product_quantity'] = $orderItem->product_quantity;
			$eco[$i]['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
			self::$dispatcher->trigger('createInvoiceLine', array($eco[$i]));
		}
	}

	/**
	 * [getEconomicTaxZone description]
	 *
	 * @param   string  $countryCode  [description]
	 *
	 * @return  [type]                [description]
	 */
	public function getEconomicTaxZone($countryCode = "")
	{
		if ($countryCode == Redshop::getConfig()->get('SHOP_COUNTRY'))
		{
			$taxzone = 'HomeCountry';
		}
		elseif ($this->isEUCountry($countryCode))
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

	/**
	 * [isEUCountry description]
	 *
	 * @param   [type]  $country  [description]
	 *
	 * @return  boolean            [description]
	 */
	public function isEUCountry($country)
	{
		$eu_country = array('AUT', 'BGR', 'BEL', 'CYP', 'CZE', 'DEU', 'DNK', 'ESP', 'EST', 'FIN',
			'FRA', 'FXX', 'GBR', 'GRC', 'HUN', 'IRL', 'ITA', 'LVA', 'LTU', 'LUX',
			'MLT', 'NLD', 'POL', 'PRT', 'ROM', 'SVK', 'SVN', 'SWE');

		return in_array($country, $eu_country);
	}
}
