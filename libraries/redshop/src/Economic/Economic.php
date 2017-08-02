<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

namespace Redshop\Economic;

use Joomla\Registry\Registry;
use RedshopHelperUtility;

defined('_JEXEC') or die;

/**
 * Library for Redshop E-conomic.
 * This Library provide methods for interact with E-Invoicing and support to orders.
 * For more information about E-invoicing: https://en.wikipedia.org/wiki/Electronic_invoicing
 * Using: RedshopEconomic::<method>
 *
 * @since  2.0.3
 */
class Economic
{
	/**
	 * The dispatcher to trigger events
	 *
	 * @var  \JEventDispatcher
	 */
	public static $dispatcher = null;

	/**
	 * Currently it will import plugin: economic, then
	 * pre-define a dispatcher to trigger event in other methods.
	 *
	 * @return  void
	 *
	 * @since  2.0.3
	 */
	public static function importEconomic()
	{
		\JPluginHelper::importPlugin('economic');
	}

	/**
	 * Create an user in E-conomic
	 *
	 * @param   object $row  Data to create user
	 * @param   array  $data Data of Economic
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function createUserInEconomic($row, $data = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco                 = array();
		$eco['user_id']      = $row->user_id;
		$eco['user_info_id'] = $row->users_info_id;
		$debtorHandle        = \RedshopHelperUtility::getDispatcher()->trigger('Debtor_FindByNumber', array($eco));

		$eco['currency_code'] = \Redshop::getConfig()->get('CURRENCY_CODE');
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
		$eco['country'] = \RedshopHelperOrder::getCountryName($row->country_code);

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

		if (!empty($debtorHandle[0]))
		{
			$debtorEmailHandle = \RedshopHelperUtility::getDispatcher()->trigger('Debtor_FindByEmail', array($eco));

			if (!empty($debtorEmailHandle[0]))
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

		return \RedshopHelperUtility::getDispatcher()->trigger('storeDebtor', array($eco));
	}

	/**
	 * Create Product Group in E-conomic
	 *
	 * @param   array   $row        Data to create
	 * @param   integer $isShipping Shipping flag
	 * @param   integer $isDiscount Discount flag
	 * @param   integer $isVat      VAT flag
	 *
	 * @return  null/array
	 *
	 * @since   2.0.3
	 */
	public static function createProductGroupInEconomic($row = array(), $isShipping = 0, $isDiscount = 0, $isVat = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$row = (object) $row;

		$redHelper = \redhelper::getInstance();

		$ecoProductGroupNumber         = new \stdClass;
		$ecoProductGroupNumber->Number = 1;

		$accountGroup = array();
		$eco          = array();

		if (!empty($row) && $row->accountgroup_id != 0)
		{
			$accountGroup = RedshopHelperUtility::getEconomicAccountGroup($row->accountgroup_id);
		}

		elseif (\Redshop::getConfig()->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP') != 0)
		{
			$accountGroup = RedshopHelperUtility::getEconomicAccountGroup(\Redshop::getConfig()->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP'));
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

			$groupHandle              = \RedshopHelperUtility::getDispatcher()->trigger('ProductGroup_FindByNumber', array($eco));
			$eco['eco_prdgro_number'] = "";

			if (count($groupHandle) > 0 && isset($groupHandle[0]->Number) != "")
			{
				$eco['eco_prdgro_number'] = $groupHandle[0]->Number;
			}

			return \RedshopHelperUtility::getDispatcher()->trigger('storeProductGroup', array($eco));
		}

		return null;
	}

	/**
	 * Create product in E-conomic
	 *
	 * @param   array $row Data to create
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function createProductInEconomic($row = array())
	{
		if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2 && self::getTotalProperty($row->product_id) > 0)
		{
			return;
		}

		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco                   = array();
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

		$debtorHandle          = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($eco));
		$eco['eco_prd_number'] = "";

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$eco['eco_prd_number'] = $debtorHandle[0]->Number;
		}

		$eco['product_stock'] = \RedshopHelperStockroom::getStockroomTotalAmount($row->product_id);

		return \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($eco));
	}

	/**
	 * Get Total Property
	 *
	 * @param   integer $productId Product ID
	 *
	 * @return  integer
	 *
	 * @since   2.0.3
	 */
	public static function getTotalProperty($productId)
	{
		// Collect Attributes
		$attribute   = \RedshopHelperProduct_Attribute::getProductAttribute($productId);
		$attributeId = $attribute[0]->value;

		// Collect Property
		$property = \RedshopHelperProduct_Attribute::getAttributeProperties(0, $attributeId, $productId);

		return count($property);
	}

	/**
	 * Create property product in economic
	 *
	 * @param   array $productRow Product data
	 * @param   array $row        Data property
	 *
	 * @return  array
	 *
	 * @since   2.0.3
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

		if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2)
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
		$debtorHandle          = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($eco));
		$eco['eco_prd_number'] = "";

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$eco['eco_prd_number'] = $debtorHandle[0]->Number;
		}

		$eco['product_stock'] = \RedshopHelperStockroom::getStockroomTotalAmount($row->property_id, "property");

		return \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($eco));
	}

	/**
	 * Create Sub Property in Economic
	 *
	 * @param   array $productRow Product info
	 * @param   array $row        Data of property
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function createSubpropertyInEconomic($productRow = array(), $row = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$eco = array();

		$eco['product_desc']   = '';
		$eco['product_s_desc'] = '';

		$ecoProductGroupNumber = self::createProductGroupInEconomic($productRow);

		if (isset($ecoProductGroupNumber[0]->Number))
		{
			$eco['product_group'] = $ecoProductGroupNumber[0]->Number;
		}

		$eco['product_number'] = $row->subattribute_color_number;

		if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2)
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
		$debtorHandle          = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($eco));
		$eco['eco_prd_number'] = "";

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$eco['eco_prd_number'] = $debtorHandle[0]->Number;
		}

		$eco['product_stock'] = \RedshopHelperStockroom::getStockroomTotalAmount($row->subattribute_color_id, "subproperty");

		return \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($eco));
	}

	/**
	 * Import Stock from Economic
	 *
	 * @param   object $productRow Product Info
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function importStockFromEconomic($productRow)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco = array('product_number' => $productRow->product_number);

		return \RedshopHelperUtility::getDispatcher()->trigger('getProductStock', array($eco));
	}

	/**
	 * Create Shipping rate in economic
	 *
	 * @param   integer $shippingNumber Shipping Number
	 * @param   string  $shippingName   Shipping Name
	 * @param   integer $shippingRate   Shipping Rate
	 * @param   integer $isVat          VAT flag
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function createShippingRateInEconomic($shippingNumber, $shippingName, $shippingRate = 0, $isVat = 1)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$eco = array();

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
		$debtorHandle          = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($eco));
		$eco['eco_prd_number'] = "";

		if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
		{
			$eco['eco_prd_number'] = $debtorHandle[0]->Number;
		}

		$eco['product_stock'] = 1;

		return \RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($eco));
	}

	/**
	 * Get Max User Number in E-conomic
	 *
	 * @return  integer
	 *
	 * @since   2.0.3
	 */
	public static function getMaxDebtorInEconomic()
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$result = \RedshopHelperUtility::getDispatcher()->trigger('getMaxDebtor');

		if (empty($result))
		{
			return 0;
		}

		return $result[0];
	}

	/**
	 * Get Max Order Number in Economic
	 *
	 * @return  integer
	 *
	 * @since   2.0.3
	 */
	public static function getMaxOrderNumberInEconomic()
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$ecoMaxInvoiceNumber = \RedshopHelperUtility::getDispatcher()->trigger('getMaxInvoiceNumber');
		$ecoMaxDraftNumber   = \RedshopHelperUtility::getDispatcher()->trigger('getMaxDraftInvoiceNumber');

		return max($ecoMaxInvoiceNumber[0], $ecoMaxDraftNumber[0]);
	}

	/**
	 * Create Invoice in economic
	 *
	 * @param   integer $orderId Order ID
	 * @param   array   $data    Data to create
	 *
	 * @return  mixed
	 *
	 * @since   2.0.3
	 */
	public static function createInvoiceInEconomic($orderId, $data = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$eco = array();

		$orderDetail = \RedshopHelperOrder::getOrderDetails($orderId);

		if ($orderDetail->is_booked == 0 && !$orderDetail->invoice_no)
		{
			$userBillingInfo  = \RedshopHelperOrder::getOrderBillingUserInfo($orderId);
			$userShippingInfo = \RedshopHelperOrder::getOrderShippingUserInfo($orderId);
			$orderItem        = \RedshopHelperOrder::getOrderItemDetail($orderId);

			$eco['shop_name']                 = \Redshop::getConfig()->get('SHOP_NAME');
			$eco['economic_payment_terms_id'] = $data['economic_payment_terms_id'];
			$eco['economic_design_layout']    = $data['economic_design_layout'];

			$ecodebtorNumber = self::createUserInEconomic($userBillingInfo, $data);

			if (!empty($ecodebtorNumber[0]))
			{
				$eco['order_id']   = $orderDetail->order_id;
				$eco['setAttname'] = 0;

				if ($userBillingInfo->is_company == 1)
				{
					$eco['setAttname'] = 1;
				}

				$eco['name'] = $userBillingInfo->firstname . " " . $userBillingInfo->lastname;

				$eco['isvat']              = ($orderDetail->order_tax != 0) ? 1 : 0;
				$currency                  = \Redshop::getConfig()->get('CURRENCY_CODE');
				$eco['email']              = $userBillingInfo->user_email;
				$eco['phone']              = $userBillingInfo->phone;
				$eco['currency_code']      = $currency;
				$eco['order_number']       = $orderDetail->order_number;
				$eco['amount']             = $orderDetail->order_total;
				$eco['debtorHandle']       = intval($ecodebtorNumber[0]->Number);
				$eco['user_info_id']       = $userBillingInfo->users_info_id;
				$eco['customer_note']      = $orderDetail->customer_note;
				$eco['requisition_number'] = $orderDetail->requisition_number;
				$eco['vatzone']            = self::getEconomicTaxZone($userBillingInfo->country_code);

				$invoiceHandle = \RedshopHelperUtility::getDispatcher()->trigger('createInvoice', array($eco));

				if (!empty($invoiceHandle[0]))
				{
					$invoiceNo = $invoiceHandle[0]->Id;
					self::updateInvoiceNumber($orderId, $invoiceNo);

					$eco['invoiceHandle'] = $invoiceNo;
					$eco['name_ST']       = ($userShippingInfo->is_company == 1 && $userShippingInfo->company_name != '')
						? $userShippingInfo->company_name : $userShippingInfo->firstname . ' ' . $userShippingInfo->lastname;
					$eco['address_ST']    = $userShippingInfo->address;
					$eco['city_ST']       = $userShippingInfo->city;
					$eco['country_ST']    = \RedshopHelperOrder::getCountryName($userShippingInfo->country_code);
					$eco['zipcode_ST']    = $userShippingInfo->zipcode;

					\RedshopHelperUtility::getDispatcher()->trigger('setDeliveryAddress', array($eco));

					if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2)
					{
						self::createInvoiceLineInEconomicAsProduct($orderItem, $invoiceNo, $orderDetail->user_id);
					}
					else
					{
						self::createInvoiceLineInEconomic($orderItem, $invoiceNo, $orderDetail->user_id);
					}

					self::createInvoiceShippingLineInEconomic($orderDetail->ship_method_id, $invoiceNo);

					$isVatDiscount = 0;

					if (\Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') == '0'
						&& (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
						&& $orderDetail->order_discount != "0.00"
						&& $orderDetail->order_tax
						&& !empty($orderDetail->order_discount)
					)
					{
						$totalDiscount               = $orderDetail->order_discount;
						$vatRateTotalDiscount        = (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $totalDiscount;
						$vatRateAfterDiscount        = 1 + (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT');
						$discountVat                 = $vatRateTotalDiscount / $vatRateAfterDiscount;
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
				return \JText::_('COM_REDSHOP_USER_NOT_SAVED_IN_ECONOMIC');
			}
		}

		return true;
	}

	/**
	 * Create Invoice Line In Economic
	 *
	 * @param   array   $orderItem Order Items
	 * @param   string  $invoiceNo Invoice Number
	 * @param   integer $userId    User ID
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function createInvoiceLineInEconomic($orderItem = array(), $invoiceNo = "", $userId = 0)
	{
		if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 2)
		{
			return;
		}

		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$eco = array();

		for ($i = 0, $in = count($orderItem); $i < $in; $i++)
		{
			$displayWrapper   = "";
			$displayAttribute = "";
			$displayAccessory = "";

			// Create Gift Card Entry for invoice
			if ($orderItem[$i]->is_giftcard)
			{
				self::createGiftCardInvoiceLineInEconomic($orderItem[$i], $invoiceNo);
				continue;
			}

			$productId     = $orderItem[$i]->product_id;
			$product       = \Redshop::product((int) $productId);
			$productHelper = \productHelper::getInstance();
			self::createProductInEconomic($product);

			if ($orderItem[$i]->wrapper_id)
			{
				$wrapper = $productHelper->getWrapper($orderItem[$i]->product_id, $orderItem[$i]->wrapper_id);

				if (count($wrapper) > 0)
				{
					$wrapperName = $wrapper[0]->wrapper_name;

					$displayWrapper = "\n" . \JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName . "(" . $orderItem[$i]->wrapper_price . ")";
				}
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
			$pUserfield     = $productHelper->getuserfield($orderItem[$i]->order_item_id);
			$displayWrapper = $displayWrapper . "\n" . strip_tags($pUserfield);

			$eco['product_name']     = $orderItem[$i]->order_item_name . $displayWrapper . $displayAttribute . $discountCalc . $displayAccessory;
			$eco['product_price']    = $orderItem[$i]->product_item_price_excl_vat;
			$eco['product_quantity'] = $orderItem[$i]->product_quantity;
			$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

			$invoiceLineNo = \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco));

			$displayAttribute = self::makeAttributeOrder($invoiceNo, $orderItem[$i], 0, $orderItem[$i]->product_id, $userId);

			if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
			{
				$orderItem[$i]->product_item_price_excl_vat -= $displayAttribute;
				$displayAttribute                           = '';
			}

			$displayAccessory = self::makeAccessoryOrder($invoiceNo, $orderItem[$i], $userId);

			$orderItem[$i]->product_item_price_excl_vat -= $displayAccessory;
			$displayAccessory                           = '';

			if (count($invoiceLineNo) > 0 && $invoiceLineNo[0]->Number)
			{
				$updateInvoiceLine       = $invoiceLineNo[0]->Number;
				$eco['updateInvoice']    = 1;
				$eco['invoiceHandle']    = $invoiceNo;
				$eco['order_item_id']    = $updateInvoiceLine;
				$eco['product_number']   = $orderItem[$i]->order_item_sku;
				$eco['product_name']     = $orderItem[$i]->order_item_name . $displayWrapper . $displayAttribute . $discountCalc . $displayAccessory;
				$eco['product_price']    = $orderItem[$i]->product_item_price_excl_vat;
				$eco['product_quantity'] = $orderItem[$i]->product_quantity;
				$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

				$invoiceLineNo = \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco));
			}
		}
	}

	/**
	 * Create Invoice line in E-conomic for GiftCard
	 *
	 * @param   array  $orderItem Order Item
	 * @param   string $invoiceNo Invoice Number
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function createGiftCardInvoiceLineInEconomic($orderItem = array(), $invoiceNo = "")
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$productHelper = \productHelper::getInstance();

		$product                 = new \stdClass;
		$orderItem               = (object) $orderItem;
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

		\RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco));
	}

	/**
	 * Method to create Invoice line in E-conomic as Product
	 *
	 * @param   array   $orderItem Order Item
	 * @param   string  $invoiceNo Invoice Number
	 * @param   integer $userId    User ID
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function createInvoiceLineInEconomicAsProduct($orderItem = array(), $invoiceNo = "", $userId = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$productHelper = \productHelper::getInstance();
		$eco           = array();

		for ($i = 0, $in = count($orderItem); $i < $in; $i++)
		{
			$displayWrapper = "";

			$productId = $orderItem[$i]->product_id;
			$product   = \Redshop::product((int) $productId);
			self::createProductInEconomic($product);

			if ($orderItem[$i]->wrapper_id)
			{
				$wrapper = $productHelper->getWrapper($orderItem[$i]->product_id, $orderItem[$i]->wrapper_id);

				if (count($wrapper) > 0)
				{
					$wrapperName = $wrapper[0]->wrapper_name;

					$displayWrapper = "\n" . \JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName . "(" . $orderItem[$i]->wrapper_price . ")";
				}
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

			// Collect Order Attribute Items
			$orderItemAttData = \RedshopHelperOrder::getOrderItemAttributeDetail(
				$orderItem[$i]->order_item_id, 0, "attribute", $orderItem[$i]->product_id
			);

			if (count($orderItemAttData) > 0)
			{
				$attributeId = $orderItemAttData[0]->section_id;
				$productId   = $orderItem[$i]->product_id;

				$orderPropData = \RedshopHelperOrder::getOrderItemAttributeDetail($orderItem[$i]->order_item_id, 0, "property", $attributeId);

				if (count($orderPropData) > 0)
				{
					$propertyId = $orderPropData[0]->section_id;

					// Collect Attribute Property
					$orderProperty = \RedshopHelperProduct_Attribute::getAttributeProperties($propertyId, $attributeId, $productId);

					$propertyNumber = $orderProperty[0]->property_number;
					$propertyName   = $orderPropData[0]->section_name;

					if ($propertyNumber)
					{
						$eco['product_number'] = $propertyNumber;
					}

					$eco['product_name'] = $orderItem[$i]->order_item_name . " " . $propertyName . $displayWrapper . $discountCalc;
				}
			}

			\RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco));
		}
	}

	/**
	 * Method to create Invoice line for shipping in E-conomic
	 *
	 * @param   string $shipMethodId Shipping method ID
	 * @param   string $invoiceNo    Invoice Number
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function createInvoiceShippingLineInEconomic($shipMethodId = "", $invoiceNo = "")
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$eco = array();

		if ($shipMethodId != "")
		{
			$orderShipping = \RedshopShippingRate::decrypt($shipMethodId);

			if (count($orderShipping) > 5)
			{
				// Load language file of the shipping plugin
				\JFactory::getLanguage()->load(
					'plg_redshop_shipping_' . strtolower(str_replace('plgredshop_shipping', '', $orderShipping[0])),
					JPATH_ADMINISTRATOR
				);

				$shippingName      = \JText::_($orderShipping[1]);
				$shippingShortname = (strlen($shippingName) > 15) ? substr($shippingName, 0, 15) : $shippingName;
				$shippingNumber    = $shippingShortname . ' ' . $orderShipping[4];
				$shippingName      = $orderShipping[2];
				$shippingRate      = $orderShipping[3];

				$isVat = 0;

				if (isset($orderShipping[6]) && $orderShipping[6] != 0)
				{
					$isVat        = 1;
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

					\RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco));
				}
			}
		}
	}

	/**
	 * Method to create Invoice line for discount in E-conomic
	 *
	 * @param   array   $orderDetail       Order detail
	 * @param   string  $invoiceNo         Invoice Number
	 * @param   array   $data              Data
	 * @param   integer $isPaymentDiscount Is payment discount or not
	 * @param   integer $isVatDiscount     Is VAT discount or not
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function createInvoiceDiscountLineInEconomic($orderDetail = array(), $invoiceNo = "", $data = array(), $isPaymentDiscount = 0,
	                                                           $isVatDiscount = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$eco       = array();

		if (\Redshop::getConfig()->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP'))
		{
			$accountGroup = RedshopHelperUtility::getEconomicAccountGroup(\Redshop::getConfig()->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP'), 1);

			if (count($accountGroup) > 0)
			{
				$ecoProductGroupNumber = self::createProductGroupInEconomic(array(), 0, 1, $isVatDiscount);

				if (isset($ecoProductGroupNumber[0]->Number))
				{
					$eco['product_group'] = $ecoProductGroupNumber[0]->Number;
				}

				$orderDetail = (object) $orderDetail;
				$discount    = $orderDetail->order_discount + $orderDetail->special_discount_amount;
				$productName = \JText::_('COM_REDSHOP_ORDER_DISCOUNT');

				$productNumber = $accountGroup[0]->economic_discount_product_number;

				if ($isPaymentDiscount)
				{
					$productNumber = $accountGroup[0]->economic_discount_product_number . "_" . $data['economic_payment_method'];
					$productName   = ($orderDetail->payment_oprand == '+') ? \JText::_('PAYMENT_CHARGES_LBL') : \JText::_('PAYMENT_DISCOUNT_LBL');
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

				$debtorHandle          = \RedshopHelperUtility::getDispatcher()->trigger('Product_FindByNumber', array($eco));
				$eco['eco_prd_number'] = "";

				if (count($debtorHandle) > 0 && isset($debtorHandle[0]->Number) != "")
				{
					$eco['eco_prd_number'] = $debtorHandle[0]->Number;
				}

				$eco['product_stock'] = 1;
				\RedshopHelperUtility::getDispatcher()->trigger('storeProduct', array($eco));
				\RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco));
			}
		}
	}

	/**
	 * Method to create Invoice and send mail in E-conomic
	 *
	 * @param   object $orderData Order data
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function renewInvoiceInEconomic($orderData)
	{
		if ($orderData->is_booked == 0)
		{
			$data        = array();
			$paymentInfo = \RedshopHelperOrder::getPaymentInfo($orderData->order_id);

			if ($paymentInfo)
			{
				$paymentName = $paymentInfo->payment_method_class;
				$paymentArr  = explode("rs_payment_", $paymentInfo[0]->payment_method_class);

				if (count($paymentArr) > 0)
				{
					$paymentName = $paymentArr[1];
				}

				$data['economic_payment_method'] = $paymentName;
				$paymentMethod                   = \RedshopHelperOrder::getPaymentMethodInfo($paymentInfo->payment_method_class);

				if (count($paymentMethod) > 0)
				{
					$paymentParams                     = new Registry($paymentMethod[0]->params);
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
	 * @param   array $orderData Order data to delete
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function deleteInvoiceInEconomic($orderData = array())
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		if (empty($orderData->invoice_no))
		{
			return;
		}

		$eco = array('invoiceHandle' => $orderData->invoice_no);
		\RedshopHelperUtility::getDispatcher()->trigger('deleteInvoice', array($eco));
		self::updateInvoiceNumber($orderData->order_id, 0);
	}

	/**
	 * Method to check invoice is draft or booked in E-conomic
	 *
	 * @param   object $orderDetail Order detail
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function checkInvoiceDraftorBookInEconomic($orderDetail)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$eco = array();

		$eco['invoiceHandle'] = $orderDetail->invoice_no;
		$eco['order_number']  = $orderDetail->order_number;
		$bookInvoiceData      = \RedshopHelperUtility::getDispatcher()->trigger('checkBookInvoice', array($eco));

		if (count($bookInvoiceData) > 0 && isset($bookInvoiceData[0]->InvoiceHandle))
		{
			$bookInvoiceData = $bookInvoiceData[0]->InvoiceHandle;

			if (isset($bookInvoiceData->Number) && is_numeric($bookInvoiceData->Number))
			{
				$bookInvoiceNumber = $bookInvoiceData->Number;
				self::updateBookInvoice($orderDetail->order_id);
				self::updateBookInvoiceNumber($orderDetail->order_id, $bookInvoiceNumber);
				$bookInvoiceData[0]->invoiceStatus = "booked";
			}
		}

		else
		{
			$bookInvoiceData[0]->invoiceStatus = "draft";
		}

		return $bookInvoiceData;
	}

	/**
	 * Method to update invoice draft for changing the date in E-conomic
	 *
	 * @param   array   $orderDetail     Order detail
	 * @param   integer $bookInvoiceDate Booking invoice date
	 *
	 * @return  mixed
	 *
	 * @since   2.0.3
	 */
	public static function updateInvoiceDateInEconomic($orderDetail, $bookInvoiceDate = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$db  = \JFactory::getDbo();
		$eco = array();

		$eco['invoiceHandle'] = $orderDetail->invoice_no;

		if ($bookInvoiceDate != 0)
		{
			$eco['invoiceDate'] = $bookInvoiceDate . "T" . date("h:i:s");
		}
		else
		{
			$eco['invoiceDate'] = date("Y-m-d") . "T" . date("h:i:s");
		}

		$bookInvoiceDate = strtotime($eco['invoiceDate']);

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_orders'))
			->set($db->qn('bookinvoice_date') . ' = ' . $db->quote($bookInvoiceDate))
			->where($db->qn('order_id') . ' = ' . (int) $orderDetail->order_id);

		$db->setQuery($query);
		$db->execute();

		$result = \RedshopHelperUtility::getDispatcher()->trigger('updateInvoiceDate', array($eco));

		if (empty($result))
		{
			return false;
		}

		return $result[0];
	}

	/**
	 * Method to book invoice and send mail in E-conomic
	 *
	 * @param   integer $orderId          Order ID
	 * @param   integer $checkOrderStatus Check Order status
	 * @param   integer $bookInvoiceDate  Booking invoice date
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function bookInvoiceInEconomic($orderId, $checkOrderStatus = 1, $bookInvoiceDate = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$file = '';
		$eco  = array();

		if (\Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$orderDetail = \RedshopHelperOrder::getOrderDetails($orderId);

			if ($orderDetail->invoice_no != '' && $orderDetail->is_booked == 0)
			{
				if ((\Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2
						&& $orderDetail->order_status == \Redshop::getConfig()->get('BOOKING_ORDER_STATUS'))
					|| $checkOrderStatus == 0
				)
				{
					$userBillingInfo = \RedshopHelperOrder::getOrderBillingUserInfo($orderId);

					if ($userBillingInfo->is_company == 0 || (!$userBillingInfo->ean_number && $userBillingInfo->is_company == 1))
					{
						$currency = \Redshop::getConfig()->get('CURRENCY_CODE');

						$eco['invoiceHandle'] = $orderDetail->invoice_no;
						$eco['debtorHandle']  = intval($userBillingInfo->users_info_id);
						$eco['currency_code'] = $currency;
						$eco['amount']        = $orderDetail->order_total;
						$eco['order_number']  = $orderDetail->order_number;
						$eco['order_id']      = $orderDetail->order_id;

						$currentInvoiceData = \RedshopHelperUtility::getDispatcher()->trigger('checkDraftInvoice', array($eco));

						if (count($currentInvoiceData) > 0 && trim($currentInvoiceData[0]->OtherReference) == $orderDetail->order_number)
						{
							self::updateInvoiceDateInEconomic($orderDetail, $bookInvoiceDate);

							if ($userBillingInfo->is_company == 1 && $userBillingInfo->company_name != '')
							{
								$eco['name'] = $userBillingInfo->company_name;
							}

							else
							{
								$eco['name'] = $userBillingInfo->firstname . " " . $userBillingInfo->lastname;
							}

							$paymentInfo = \RedshopHelperOrder::getPaymentInfo($orderDetail->order_id);

							if ($paymentInfo)
							{
								$paymentMethod = \RedshopHelperOrder::getPaymentMethodInfo($paymentInfo->payment_method_class);

								if (count($paymentMethod) > 0)
								{
									$paymentParams                    = new Registry($paymentMethod[0]->params);
									$eco['economic_payment_terms_id'] = $paymentParams->get('economic_payment_terms_id');
									$eco['economic_design_layout']    = $paymentParams->get('economic_design_layout');
								}

								// Setting merchant fees for economic
								if ($paymentInfo->order_transfee > 0)
								{
									$eco['order_transfee'] = $paymentInfo->order_transfee;
								}
							}

							if (\Redshop::getConfig()->get('ECONOMIC_BOOK_INVOICE_NUMBER') == 1)
							{
								$bookHandle = \RedshopHelperUtility::getDispatcher()->trigger('CurrentInvoice_Book', array($eco));
							}
							else
							{
								$bookHandle = \RedshopHelperUtility::getDispatcher()->trigger('CurrentInvoice_BookWithNumber', array($eco));
							}

							if (count($bookHandle) > 0 && isset($bookHandle[0]->Number))
							{
								$bookInvoiceNumber = $eco['bookinvoice_number'] = $bookHandle[0]->Number;

								if (\Redshop::getConfig()->get('ECONOMIC_BOOK_INVOICE_NUMBER') == 1)
								{
									self::updateBookInvoiceNumber($orderId, $bookInvoiceNumber);
								}

								$bookInvoicePdf = \RedshopHelperUtility::getDispatcher()->trigger('bookInvoice', array($eco));

								if (\JError::isError(\JError::getError()))
								{
									return $file;
								}
								elseif ($bookInvoicePdf != "")
								{
									$file = JPATH_ROOT . '/components/com_redshop/assets/orders/rsInvoice_' . $orderId . '.pdf';
									\JFile::write($file, $bookInvoicePdf);

									if (JFile::exists($file))
									{
										self::updateBookInvoice($orderId);
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
	 * Update invoice number
	 *
	 * @param   integer $orderId   Order ID
	 * @param   integer $invoiceNo Invoice number
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function updateInvoiceNumber($orderId = 0, $invoiceNo = 0)
	{
		$db = \JFactory::getDbo();

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_orders'))
			->set($db->qn('invoice_no') . ' = ' . $db->quote($invoiceNo))
			->where($db->qn('order_id') . ' = ' . (int) $orderId);
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Update booking invoice
	 *
	 * @param   integer $orderId Order ID
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function updateBookInvoice($orderId = 0)
	{
		$db = \JFactory::getDbo();

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_orders'))
			->set($db->qn('is_booked') . ' = 1')
			->where($db->qn('order_id') . ' = ' . (int) $orderId);
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Update booking invoice number
	 *
	 * @param   integer $orderId           Order ID
	 * @param   integer $bookInvoiceNumber Booking invoice number
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function updateBookInvoiceNumber($orderId = 0, $bookInvoiceNumber = 0)
	{
		$db = \JFactory::getDbo();

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_orders'))
			->set($db->qn('bookinvoice_number') . ' = ' . (int) $bookInvoiceNumber)
			->where($db->qn('order_id') . ' = ' . (int) $orderId);
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Get product number
	 *
	 * @param   string $productNumber Product Number
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getProductByNumber($productNumber = '')
	{
		$db = \JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_number') . ' = ' . $db->quote($productNumber));
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Make Accessory Order
	 *
	 * @param   string  $invoiceNo Invoice number
	 * @param   array   $orderItem Order item
	 * @param   integer $userId    User ID
	 *
	 * @return  integer
	 *
	 * @since   2.0.3
	 */
	public static function makeAccessoryOrder($invoiceNo, $orderItem, $userId = 0)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();

		$eco              = array();
		$displayAccessory = "";
		$setPrice         = 0;
		$orderItem        = (object) $orderItem;
		$orderItemData    = \RedshopHelperOrder::getOrderItemAccessoryDetail($orderItem->order_item_id);

		if (count($orderItemData) > 0)
		{
			$displayAccessory .= "\n" . \JText::_("COM_REDSHOP_ACCESSORY");

			for ($i = 0, $in = count($orderItemData); $i < $in; $i++)
			{
				$product = self::getProductByNumber($orderItemData[$i]->order_acc_item_sku);

				if (count($product) > 0)
				{
					self::createProductInEconomic($product);
				}

				$accessoryQuantity = " (" . \JText::_('COM_REDSHOP_ACCESSORY_QUANTITY_LBL') . " " . $orderItemData[$i]->product_quantity . ") ";
				$displayAccessory  .= "\n" . urldecode($orderItemData[$i]->order_acc_item_name)
					. " (" . ($orderItemData[$i]->order_acc_price + $orderItemData[$i]->order_acc_vat) . ")" . $accessoryQuantity;

				$setPrice += $orderItemData[$i]->product_acc_item_price;

				$eco['updateInvoice']    = 0;
				$eco['invoiceHandle']    = $invoiceNo;
				$eco['order_item_id']    = $orderItem->order_item_id;
				$eco['product_number']   = $orderItemData[$i]->order_acc_item_sku;
				$eco['product_name']     = $orderItemData[$i]->order_acc_item_name;
				$eco['product_price']    = $orderItemData[$i]->product_acc_item_price;
				$eco['product_quantity'] = $orderItemData[$i]->product_quantity;
				$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");
				$invoiceLineNo           = \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco));

				$displayAttribute = self::makeAttributeOrder($invoiceNo, $orderItem, 1, $orderItemData[$i]->product_id, $userId);
				$displayAccessory .= $displayAttribute;

				if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
				{
					$orderItemData[$i]->product_acc_item_price -= $displayAttribute;
					$displayAttribute                          = '';
				}

				if (count($invoiceLineNo) > 0 && $invoiceLineNo[0]->Number)
				{
					$eco['updateInvoice']    = 1;
					$eco['invoiceHandle']    = $invoiceNo;
					$eco['order_item_id']    = $invoiceLineNo[0]->Number;
					$eco['product_number']   = $orderItemData[$i]->order_acc_item_sku;
					$eco['product_name']     = $orderItemData[$i]->order_acc_item_name . $displayAttribute;
					$eco['product_price']    = $orderItemData[$i]->product_acc_item_price;
					$eco['product_quantity'] = $orderItemData[$i]->product_quantity;
					$eco['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

					$invoiceLineNo = \RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco));
				}
			}
		}

		$displayAccessory = $setPrice;

		return $displayAccessory;
	}

	/**
	 * Make Attribute Order
	 *
	 * @param   string  $invoiceNo       Invoice number
	 * @param   integer $orderItem       Order Item
	 * @param   integer $isAccessory     Is accessory
	 * @param   integer $parentSectionId Parent Section ID
	 * @param   integer $userId          User ID
	 *
	 * @return  integer
	 *
	 * @since   2.0.3
	 */
	public static function makeAttributeOrder($invoiceNo, $orderItem, $isAccessory = 0, $parentSectionId = 0, $userId = 0)
	{
		$productHelper    = \productHelper::getInstance();
		$displayAttribute = "";
		$setPrice         = 0;
		$orderItem        = (object) $orderItem;
		$checkShowVAT     = $productHelper->getApplyattributeVatOrNot('', $userId);
		$orderItemAttData = \RedshopHelperOrder::getOrderItemAttributeDetail($orderItem->order_item_id, $isAccessory, "attribute", $parentSectionId);

		if (count($orderItemAttData) > 0)
		{
			$product = \Redshop::product((int) $parentSectionId);

			for ($i = 0, $in = count($orderItemAttData); $i < $in; $i++)
			{
				$attribute          = \RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $orderItemAttData[$i]->section_id);
				$hideAttributePrice = 0;

				if (count($attribute) > 0)
				{
					$hideAttributePrice = $attribute[0]->hide_attribute_price;
				}

				$displayAttribute .= "\n" . urldecode($orderItemAttData[$i]->section_name) . " : ";
				$orderPropData    = \RedshopHelperOrder::getOrderItemAttributeDetail(
					$orderItem->order_item_id,
					$isAccessory, "property",
					$orderItemAttData[$i]->section_id
				);

				for ($p = 0, $pn = count($orderPropData); $p < $pn; $p++)
				{
					$property      = \RedshopHelperProduct_Attribute::getAttributeProperties($orderPropData[$p]->section_id);
					$virtualNumber = "";

					if (count($property) > 0 && $property[0]->property_number)
					{
						$virtualNumber = "[" . $property[0]->property_number . "]";

						if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
						{
							$orderPropData[$p]->virtualNumber = $property[0]->property_number;
							self::createPropertyInEconomic($product, $property[0]);
						}
					}

					$disPrice = "";

					if (!$hideAttributePrice)
					{
						$propertyPrice = $orderPropData[$p]->section_price;

						if (!empty($checkShowVAT))
						{
							$propertyPrice = $orderPropData[$p]->section_price + $orderPropData[$p]->section_vat;
						}

						$disPrice = " (" . $orderPropData[$p]->section_oprand . $productHelper->getProductFormattedPrice($propertyPrice) . ")";
					}

					$displayAttribute .= urldecode($orderPropData[$p]->section_name) . $disPrice . $virtualNumber;

					if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
					{
						$setPrice += $orderPropData[$p]->section_price;
						self::createAttributeInvoiceLineInEconomic($invoiceNo, $orderItem, array($orderPropData[$p]));
					}

					$orderSubPropertyData = \RedshopHelperOrder::getOrderItemAttributeDetail(
						$orderItem->order_item_id,
						$isAccessory,
						"subproperty",
						$orderPropData[$p]->section_id
					);

					if (count($orderSubPropertyData) > 0)
					{
						for ($sp = 0; $sp < count($orderSubPropertyData); $sp++)
						{
							$subproperty   = \RedshopHelperProduct_Attribute::getAttributeSubProperties($orderSubPropertyData[$sp]->section_id);
							$virtualNumber = "";

							if (count($subproperty) > 0 && $subproperty[0]->subattribute_color_number)
							{
								$virtualNumber = "[" . $subproperty[0]->subattribute_color_number . "]";

								if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
								{
									$orderSubPropertyData[$sp]->virtualNumber = $subproperty[0]->subattribute_color_number;
									self::createSubpropertyInEconomic($product, $subproperty[0]);
								}
							}

							$disPrice = "";

							if (!$hideAttributePrice)
							{
								$subpropertyPrice = $orderSubPropertyData[$sp]->section_price;

								if (!empty($checkShowVAT))
								{
									$subpropertyPrice = $orderSubPropertyData[$sp]->section_price + $orderSubPropertyData[$sp]->section_vat;
								}

								$disPrice = " (" . $orderSubPropertyData[$sp]->section_oprand . $productHelper->getProductFormattedPrice($subpropertyPrice) . ")";
							}

							$displayAttribute .= "\n" . urldecode($orderSubPropertyData[$sp]->section_name) . $disPrice . $virtualNumber;

							if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
							{
								$setPrice += $orderSubPropertyData[$sp]->section_price;
							}
						}

						if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
						{
							self::createAttributeInvoiceLineInEconomic($invoiceNo, $orderItem, $orderSubPropertyData);
						}
					}
				}
			}
		}

		if (\Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') != 0)
		{
			$displayAttribute = $setPrice;
		}

		return $displayAttribute;
	}

	/**
	 * Create Attribute Invoice Line In Economic
	 *
	 * @param   string  $invoiceNo            Invoice number
	 * @param   object  $orderItem            Order Item
	 * @param   array   $orderAttributeItems  Order Attribute Item
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function createAttributeInvoiceLineInEconomic($invoiceNo, $orderItem, $orderAttributeItems)
	{
		// If using Dispatcher, must call plugin Economic first
		self::importEconomic();
		$eco       = array();
		$orderItem = (object) $orderItem;

		for ($i = 0, $in = count($orderAttributeItems); $i < $in; $i++)
		{
			$eco[$i]['invoiceHandle']    = $invoiceNo;
			$eco[$i]['order_item_id']    = $orderItem->order_item_id;
			$eco[$i]['product_number']   = $orderAttributeItems[$i]->virtualNumber;
			$eco[$i]['product_name']     = $orderAttributeItems[$i]->section_name;
			$eco[$i]['product_price']    = $orderAttributeItems[$i]->section_price;
			$eco[$i]['product_quantity'] = $orderItem->product_quantity;
			$eco[$i]['delivery_date']    = date("Y-m-d") . "T" . date("h:i:s");

			\RedshopHelperUtility::getDispatcher()->trigger('createInvoiceLine', array($eco[$i]));
		}
	}

	/**
	 * Get economic Tax zone
	 *
	 * @param   string $countryCode Country code
	 *
	 * @return  string
	 *
	 * @since   2.0.3
	 */
	public static function getEconomicTaxZone($countryCode = "")
	{
		if ($countryCode == \Redshop::getConfig()->get('SHOP_COUNTRY'))
		{
			$taxzone = 'HomeCountry';
		}
		elseif (self::isEuCountry($countryCode))
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
	 * Check country is belong to EU
	 *
	 * @param   string $country Country code
	 *
	 * @return  boolean
	 *
	 * @since   2.0.3
	 */
	public static function isEuCountry($country)
	{
		$euCountry = array('AUT', 'BGR', 'BEL', 'CYP', 'CZE', 'DEU', 'DNK', 'ESP', 'EST', 'FIN',
			'FRA', 'FXX', 'GBR', 'GRC', 'HUN', 'IRL', 'ITA', 'LVA', 'LTU', 'LUX',
			'MLT', 'NLD', 'POL', 'PRT', 'ROM', 'SVK', 'SVN', 'SWE');

		return in_array($country, $euCountry);
	}
}
