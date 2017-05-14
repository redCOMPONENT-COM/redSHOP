<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class rsCarthelper
{
	public $_table_prefix = null;

	public $_db = null;

	public $_session = null;

	public $_order_functions = null;

	public $_extra_field = null;

	public $_redhelper = null;

	public $_producthelper = null;

	public $_show_with_vat = 0;

	public $_shippinghelper = null;

	public $_globalvoucher = 0;

	protected static $instance = null;

	/**
	 * Returns the rsCarthelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  rsCarthelper  The rsCarthelper object
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
		$this->_table_prefix    = '#__redshop_';
		$this->_db              = JFactory::getDBO();
		$this->_session         = JFactory::getSession();
		$this->_order_functions = order_functions::getInstance();
		$this->_extra_field     = extra_field::getInstance();
		$this->_extraFieldFront = extraField::getInstance();
		$this->_redhelper       = redhelper::getInstance();
		$this->_producthelper   = productHelper::getInstance();
		$this->_shippinghelper  = shipping::getInstance();
	}

	/**
	 * replace Conditional tag from Redshop tax
	 *
	 * @param string $data
	 * @param int    $amount
	 * @param int    $discount
	 * @param int    $check
	 * @param int    $quotation_mode
	 *
	 * @return mixed|string
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function replaceTax($data = '', $amount = 0, $discount = 0, $check = 0, $quotation_mode = 0)
	{
		return RedshopHelperCart::replaceTax($data, $amount, $discount, $check, $quotation_mode);
	}

	/**
	 * Calculate tax after Discount is apply
	 *
	 * @param   float  $tax       Tax amount
	 * @param   float  $discount  Discount amount.
	 *
	 * @return  float             Tax after apply discount.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::calculateTaxAfterDiscount() instead.
	 **/
	public function calculateTaxafterDiscount($tax = 0.0, $discount = 0.0)
	{
		return RedshopHelperCart::calculateTaxAfterDiscount($tax, $discount);
	}

	/*
	 * replace Conditional tag from Redshop Discount
	 */
	public function replaceDiscount($data = '', $discount = 0, $subtotal = 0, $quotation_mode = 0)
	{
		if (strpos($data, '{if discount}') !== false && strpos($data, '{discount end if}') !== false)
		{
			$percentage = '';

			if ($discount <= 0)
			{
				$template_discount_sdata = explode('{if discount}', $data);
				$template_discount_edata = explode('{discount end if}', $template_discount_sdata[1]);
				$data                    = $template_discount_sdata[0] . $template_discount_edata[1];
			}
			else
			{
				$data = str_replace("{if discount}", '', $data);

				if ($quotation_mode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
				{
					$data = str_replace("{discount}", "", $data);
					$data = str_replace("{discount_in_percentage}", $percentage, $data);

				}
				else
				{
					$data = str_replace("{discount}", $this->_producthelper->getProductFormattedPrice($discount, true), $data);
					$data = str_replace("{order_discount}", $this->_producthelper->getProductFormattedPrice($discount, true), $data);

					if (!empty($subtotal) && $subtotal > 0)
					{
						$percentage = round(($discount * 100 / $subtotal), 2) . " %";
					}

					$data = str_replace("{discount_in_percentage}", $percentage, $data);
				}

				$data = str_replace("{discount_lbl}", JText::_('COM_REDSHOP_CHECKOUT_DISCOUNT_LBL'), $data);
				$data = str_replace("{discount end if}", '', $data);
			}
		}

		return $data;
	}

	/**
	 * replace Conditional tag from Redshop payment Discount/charges
	 *
	 * @param string $data
	 * @param int    $amount
	 * @param int    $cart
	 * @param string $payment_oprand
	 *
	 * @return mixed|string
	 */
	public function replacePayment($data = '', $amount = 0, $cart = 0, $payment_oprand = '-')
	{
		if (strpos($data, '{if payment_discount}') !== false && strpos($data, '{payment_discount end if}') !== false)
		{
			if ($cart == 1 || $amount == 0)
			{
				$template_pdiscount_sdata = explode('{if payment_discount}', $data);
				$template_pdiscount_edata = explode('{payment_discount end if}', $template_pdiscount_sdata[1]);
				$data                     = $template_pdiscount_sdata[0] . $template_pdiscount_edata[1];

				return $data;
			}

			if ($amount <= 0)
			{
				$template_pd_sdata = explode('{if payment_discount}', $data);
				$template_pd_edata = explode('{payment_discount end if}', $template_pd_sdata[1]);
				$data              = $template_pd_sdata[0] . $template_pd_edata[1];
			}
			else
			{
				$data    = str_replace("{payment_order_discount}", $this->_producthelper->getProductFormattedPrice($amount), $data);
				$payText = ($payment_oprand == '+') ? JText::_('COM_REDSHOP_PAYMENT_CHARGES_LBL') : JText::_('COM_REDSHOP_PAYMENT_DISCOUNT_LBL');
				$data    = str_replace("{payment_discount_lbl}", $payText, $data);
				$data    = str_replace("{payment_discount end if}", '', $data);
				$data    = str_replace("{if payment_discount}", '', $data);
			}
		}

		return $data;
	}

	/**
	 * Calculate payment Discount/charges
	 *
	 * @param   float   $total        Total price
	 * @param   object  $paymentinfo  Payment information
	 * @param   float   $finalAmount  Final amount
	 *
	 * @return  array                 Array of payment price. False otherwise.
	 *
	 * @deprecated   __DEPLOY_VERSION__  Use RedshopHelperPayment::calculatePayment instead
	 */
	public function calculatePayment($total = 0, $paymentinfo = null, $finalAmount = 0.0)
	{
		return RedshopHelperPayment::calculatePayment($total, $paymentinfo, $finalAmount);
	}

	/**
	 * replace Billing Address
	 *
	 * @param $data
	 * @param $billingaddresses
	 *
	 * @return mixed
	 */
	public function replaceBillingAddress($data, $billingaddresses, $sendmail = false)
	{
		if (strpos($data, '{billing_address_start}') !== false && strpos($data, '{billing_address_end}') !== false)
		{
			$user           = JFactory::getUser();
			$template_sdata = explode('{billing_address_start}', $data);
			$template_edata = explode('{billing_address_end}', $template_sdata[1]);
			$billingdata    = $template_edata[0];

			$billing_extrafield = '';

			if (isset($billingaddresses))
			{
				$extra_section = ($billingaddresses->is_company == 1) ? 8 : 7;

				if ($billingaddresses->is_company == 1 && $billingaddresses->company_name != "")
				{
					$billingdata = str_replace("{companyname}", $billingaddresses->company_name, $billingdata);
					$billingdata = str_replace("{companyname_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $billingdata);
				}

				if ($billingaddresses->firstname != "")
				{
					$billingdata = str_replace("{firstname}", $billingaddresses->firstname, $billingdata);
					$billingdata = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $billingdata);
				}

				if ($billingaddresses->lastname != "")
				{
					$billingdata = str_replace("{lastname}", $billingaddresses->lastname, $billingdata);
					$billingdata = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $billingdata);
				}

				if ($billingaddresses->address != "")
				{
					$billingdata = str_replace("{address}", $billingaddresses->address, $billingdata);
					$billingdata = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $billingdata);
				}

				if ($billingaddresses->zipcode != "")
				{
					$billingdata = str_replace("{zip}", $billingaddresses->zipcode, $billingdata);
					$billingdata = str_replace("{zip_lbl}", JText::_('COM_REDSHOP_ZIP'), $billingdata);
				}

				if ($billingaddresses->city != "")
				{
					$billingdata = str_replace("{city}", $billingaddresses->city, $billingdata);
					$billingdata = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $billingdata);
				}

				$cname = $this->_order_functions->getCountryName($billingaddresses->country_code);

				if ($cname != "")
				{
					$billingdata = str_replace("{country}", JText::_($cname), $billingdata);
					$billingdata = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $billingdata);
				}

				$sname = $this->_order_functions->getStateName($billingaddresses->state_code, $billingaddresses->country_code);

				if ($sname != "")
				{
					$billingdata = str_replace("{state}", $sname, $billingdata);
					$billingdata = str_replace("{state_lbl}", JText::_('COM_REDSHOP_STATE'), $billingdata);
				}

				if ($billingaddresses->phone != "")
				{
					$billingdata = str_replace("{phone}", $billingaddresses->phone, $billingdata);
					$billingdata = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $billingdata);
				}

				if ($billingaddresses->user_email != "")
				{
					$billingdata = str_replace("{email}", $billingaddresses->user_email, $billingdata);
					$billingdata = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL'), $billingdata);
				}
				elseif ($user->email != '')
				{
					$billingdata = str_replace("{email}", $billingaddresses->email, $billingdata);
					$billingdata = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL'), $billingdata);
				}

				if ($billingaddresses->is_company == 1)
				{
					if ($billingaddresses->vat_number != "")
					{
						$billingdata = str_replace("{vatnumber}", $billingaddresses->vat_number, $billingdata);
						$billingdata = str_replace("{vatnumber_lbl}", JText::_('COM_REDSHOP_VAT_NUMBER'), $billingdata);
					}

					if ($billingaddresses->ean_number != "")
					{
						$billingdata = str_replace("{ean_number}", $billingaddresses->ean_number, $billingdata);
						$billingdata = str_replace("{ean_number_lbl}", JText::_('COM_REDSHOP_EAN_NUMBER'), $billingdata);
					}

					if (Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT'))
					{
						if ($billingaddresses->tax_exempt == 1)
						{
							$taxexe = JText::_("COM_REDSHOP_TAX_YES");
						}
						else
						{
							$taxexe = JText::_("COM_REDSHOP_TAX_NO");
						}

						$billingdata = str_replace("{taxexempt}", $taxexe, $billingdata);
						$billingdata = str_replace("{taxexempt_lbl}", JText::_('COM_REDSHOP_TAX_EXEMPT'), $billingdata);

						if ($billingaddresses->requesting_tax_exempt == 1)
						{
							$taxexereq = JText::_("COM_REDSHOP_YES");
						}
						else
						{
							$taxexereq = JText::_("COM_REDSHOP_NO");
						}

						$billingdata = str_replace("{user_taxexempt_request}", $taxexereq, $billingdata);
						$billingdata = str_replace("{user_taxexempt_request_lbl}", JText::_('COM_REDSHOP_USER_TAX_EXEMPT_REQUEST_LBL'), $billingdata);
					}
				}

				$billing_extrafield = $this->_extra_field->list_all_field_display($extra_section, $billingaddresses->users_info_id, 1);
			}

			$billingdata = str_replace("{companyname}", "", $billingdata);
			$billingdata = str_replace("{companyname_lbl}", "", $billingdata);
			$billingdata = str_replace("{firstname}", "", $billingdata);
			$billingdata = str_replace("{firstname_lbl}", "", $billingdata);
			$billingdata = str_replace("{lastname}", "", $billingdata);
			$billingdata = str_replace("{lastname_lbl}", "", $billingdata);
			$billingdata = str_replace("{address}", "", $billingdata);
			$billingdata = str_replace("{address_lbl}", "", $billingdata);
			$billingdata = str_replace("{zip}", "", $billingdata);
			$billingdata = str_replace("{zip_lbl}", "", $billingdata);
			$billingdata = str_replace("{city}", "", $billingdata);
			$billingdata = str_replace("{city_lbl}", "", $billingdata);
			$billingdata = str_replace("{country}", "", $billingdata);
			$billingdata = str_replace("{country_lbl}", "", $billingdata);
			$billingdata = str_replace("{state}", "", $billingdata);
			$billingdata = str_replace("{state_lbl}", "", $billingdata);
			$billingdata = str_replace("{email}", "", $billingdata);
			$billingdata = str_replace("{email_lbl}", "", $billingdata);
			$billingdata = str_replace("{phone}", "", $billingdata);
			$billingdata = str_replace("{phone_lbl}", "", $billingdata);
			$billingdata = str_replace("{vatnumber}", "", $billingdata);
			$billingdata = str_replace("{vatnumber_lbl}", "", $billingdata);
			$billingdata = str_replace("{ean_number}", "", $billingdata);
			$billingdata = str_replace("{ean_number_lbl}", "", $billingdata);
			$billingdata = str_replace("{taxexempt}", "", $billingdata);
			$billingdata = str_replace("{taxexempt_lbl}", "", $billingdata);
			$billingdata = str_replace("{user_taxexempt_request}", "", $billingdata);
			$billingdata = str_replace("{user_taxexempt_request_lbl}", "", $billingdata);
			$billingdata = str_replace("{billing_extrafield}", $billing_extrafield, $billingdata);

			$data = $template_sdata[0] . $billingdata . $template_edata[1];
		}
		elseif (strpos($data, '{billing_address}') !== false)
		{
			$billadd = '';

			if (isset($billingaddresses))
			{
				$billingLayout = 'cart.billing';

				if ($sendmail)
				{
					$billingLayout = 'mail.billing';
				}

				$billadd = RedshopLayoutHelper::render(
					$billingLayout,
					array('billingaddresses' => $billingaddresses),
					null,
					array('client' => 0)
				);

				if (strpos($data, '{quotation_custom_field_list}') !== false)
				{
					$data = str_replace('{quotation_custom_field_list}', '', $data);

					if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
					{
						$billadd .= $this->_extra_field->list_all_field(16, $billingaddresses->users_info_id, '', '');
					}
				}
				elseif (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
				{
					$data = $this->_extra_field->list_all_field(16, $billingaddresses->users_info_id, '', '', $data);
				}
			}

			$data = str_replace("{billing_address}", $billadd, $data);
		}

		$data = str_replace("{billing_address}", "", $data);
		$data = str_replace("{billing_address_information_lbl}", JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'), $data);

		return $data;
	}

	/**
	 * Replace Shipping Address
	 *
	 * @param $data
	 * @param $shippingaddresses
	 *
	 * @return mixed
	 */
	public function replaceShippingAddress($data, $shippingaddresses, $sendmail = false)
	{
		if (strpos($data, '{shipping_address_start}') !== false && strpos($data, '{shipping_address_end}') !== false)
		{
			$template_sdata = explode('{shipping_address_start}', $data);
			$template_edata = explode('{shipping_address_end}', $template_sdata[1]);
			$shippingdata   = (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) ? $template_edata[0] : '';

			$shipping_extrafield = '';

			if (isset($shippingaddresses) && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
			{
				$extra_section = ($shippingaddresses->is_company == 1) ? 15 : 14;

				if ($shippingaddresses->is_company == 1 && $shippingaddresses->company_name != "")
				{
					$shippingdata = str_replace("{companyname}", $shippingaddresses->company_name, $shippingdata);
					$shippingdata = str_replace("{companyname_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $shippingdata);
				}

				if ($shippingaddresses->firstname != "")
				{
					$shippingdata = str_replace("{firstname}", $shippingaddresses->firstname, $shippingdata);
					$shippingdata = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $shippingdata);
				}

				if ($shippingaddresses->lastname != "")
				{
					$shippingdata = str_replace("{lastname}", $shippingaddresses->lastname, $shippingdata);
					$shippingdata = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $shippingdata);
				}

				if ($shippingaddresses->address != "")
				{
					$shippingdata = str_replace("{address}", $shippingaddresses->address, $shippingdata);
					$shippingdata = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $shippingdata);
				}

				if ($shippingaddresses->zipcode != "")
				{
					$shippingdata = str_replace("{zip}", $shippingaddresses->zipcode, $shippingdata);
					$shippingdata = str_replace("{zip_lbl}", JText::_('COM_REDSHOP_ZIP'), $shippingdata);
				}

				if ($shippingaddresses->city != "")
				{
					$shippingdata = str_replace("{city}", $shippingaddresses->city, $shippingdata);
					$shippingdata = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $shippingdata);
				}

				$cname = $this->_order_functions->getCountryName($shippingaddresses->country_code);

				if ($cname != "")
				{
					$shippingdata = str_replace("{country}", JText::_($cname), $shippingdata);
					$shippingdata = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $shippingdata);
				}

				$sname = $this->_order_functions->getStateName($shippingaddresses->state_code, $shippingaddresses->country_code);

				if ($sname != "")
				{
					$shippingdata = str_replace("{state}", $sname, $shippingdata);
					$shippingdata = str_replace("{state_lbl}", JText::_('COM_REDSHOP_STATE'), $shippingdata);
				}

				if ($shippingaddresses->phone != "")
				{
					$shippingdata = str_replace("{phone}", $shippingaddresses->phone, $shippingdata);
					$shippingdata = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $shippingdata);
				}

				// Additional functionality - more flexible way
				$shippingdata = $this->_extraFieldFront->extra_field_display($extra_section, $shippingaddresses->users_info_id, "", $shippingdata);

				$shipping_extrafield = $this->_extra_field->list_all_field_display($extra_section, $shippingaddresses->users_info_id, 1);
			}

			$shippingdata = str_replace("{companyname}", "", $shippingdata);
			$shippingdata = str_replace("{companyname_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{firstname}", "", $shippingdata);
			$shippingdata = str_replace("{firstname_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{lastname}", "", $shippingdata);
			$shippingdata = str_replace("{lastname_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{address}", "", $shippingdata);
			$shippingdata = str_replace("{address_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{zip}", "", $shippingdata);
			$shippingdata = str_replace("{zip_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{city}", "", $shippingdata);
			$shippingdata = str_replace("{city_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{country}", "", $shippingdata);
			$shippingdata = str_replace("{country_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{state}", "", $shippingdata);
			$shippingdata = str_replace("{state_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{phone}", "", $shippingdata);
			$shippingdata = str_replace("{phone_lbl}", "", $shippingdata);
			$shippingdata = str_replace("{shipping_extrafield}", $shipping_extrafield, $shippingdata);

			$data = $template_sdata[0] . $shippingdata . $template_edata[1];
		}
		elseif (strpos($data, '{shipping_address}') !== false)
		{
			$shipadd = '';

			if (isset($shippingaddresses) && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
			{
				$shippingLayout = 'cart.shipping';

				if ($sendmail)
				{
					$shippingLayout = 'mail.shipping';
				}

				$shipadd = RedshopLayoutHelper::render(
					$shippingLayout,
					array('shippingaddresses' => $shippingaddresses),
					null,
					array('client' => 0)
				);

				if ($shippingaddresses->is_company == 1)
				{
					// Additional functionality - more flexible way
					$data = $this->_extraFieldFront->extra_field_display(15, $shippingaddresses->users_info_id, "", $data);
				}
				else
				{
					// Additional functionality - more flexible way
					$data = $this->_extraFieldFront->extra_field_display(14, $shippingaddresses->users_info_id, "", $data);
				}
			}

			$data = str_replace("{shipping_address}", $shipadd, $data);
		}

		$shippingtext = (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) ? JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL') : '';
		$data         = str_replace("{shipping_address}", "", $data);
		$data         = str_replace("{shipping_address_information_lbl}", $shippingtext, $data);

		return $data;
	}

	/**
	 * Replace shipping method
	 *
	 * @param array  $row
	 * @param string $data
	 *
	 * @return mixed
	 */
	public function replaceShippingMethod($row = array(), $data = "")
	{
		$search = array();
		$search[] = "{shipping_method}";
		$search[] = "{order_shipping}";
		$search[] = "{shipping_excl_vat}";
		$search[] = "{shipping_rate_name}";
		$search[] = "{shipping}";
		$search[] = "{vat_shipping}";
		$search[] = "{order_shipping_shop_location}";

		if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			$details = RedshopShippingRate::decrypt($row->ship_method_id);

			if (count($details) <= 1)
			{
				$details = explode("|", $row->ship_method_id);
			}

			$shipping_method    = "";
			$shipping_rate_name = "";

			if (count($details) > 0)
			{
				// Load language file of the shipping plugin
				JFactory::getLanguage()->load(
					'plg_redshop_shipping_' . strtolower(str_replace('plgredshop_shipping', '', $details[0])),
					JPATH_ADMINISTRATOR
				);

				if (array_key_exists(1, $details))
				{
					$shipping_method = $details[1];
				}

				if (array_key_exists(2, $details))
				{
					$shipping_rate_name = $details[2];
				}
			}

			$shopLocation = $row->shop_id;
			$replace      = array();
			$replace[]    = JText::_($shipping_method);
			$replace[]    = $this->_producthelper->getProductFormattedPrice($row->order_shipping);
			$replace[]    = $this->_producthelper->getProductFormattedPrice($row->order_shipping - $row->order_shipping_tax);
			$replace[]    = $shipping_rate_name;
			$replace[]    = $this->_producthelper->getProductFormattedPrice($row->order_shipping);
			$replace[]    = $this->_producthelper->getProductFormattedPrice($row->order_shipping_tax);

			if ($details[0] != 'plgredshop_shippingdefault_shipping_gls')
			{
				$shopLocation = '';
			}

			$mobilearr = array();

			if ($shopLocation)
			{
				$mobilearr          = explode('###', $shopLocation);
				$arrLocationDetails = explode('|', $shopLocation);
				$countLocDet = count($arrLocationDetails);
				$shopLocation = '';

				if ($countLocDet > 1)
				{
					$shopLocation .= '<b>' . $arrLocationDetails[0] . ' ' . $arrLocationDetails[1] . '</b>';
				}

				if ($countLocDet > 2)
				{
					$shopLocation .= '<br>' . $arrLocationDetails[2];
				}

				if ($countLocDet > 3)
				{
					$shopLocation .= '<br>' . $arrLocationDetails[3];
				}

				if ($countLocDet > 4)
				{
					$shopLocation .= ' ' . $arrLocationDetails[4];
				}

				if ($countLocDet > 5)
				{
					$shopLocation .= '<br>' . $arrLocationDetails[5];
				}

				if ($countLocDet > 6)
				{
					$arrLocationTime = explode('  ', $arrLocationDetails[6]);
					$shopLocation .= '<br>';

					for ($t = 0, $tn = count($arrLocationTime); $t < $tn; $t++)
					{
						$shopLocation .= $arrLocationTime[$t] . '<br>';
					}
				}
			}

			if (isset($mobilearr[1]))
			{
				$shopLocation .= ' ' . $mobilearr[1];
			}

			$replace[] = $shopLocation;
			$data = str_replace($search, $replace, $data);
		}
		else
		{
			$data = str_replace($search, array("", "", "", ""), $data);
		}

		return $data;
	}

	public function replaceCartItem($data, $cart = array(), $replace_button, $quotation_mode = 0)
	{
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = JDispatcher::getInstance();
		$prdItemid  = JRequest::getInt('Itemid');
		$Itemid     = RedshopHelperUtility::getCheckoutItemId();
		$url        = JURI::base(true);
		$mainview   = JRequest::getVar('view');

		if ($Itemid == 0)
		{
			$Itemid = JRequest::getInt('Itemid');
		}

		$cart_tr = '';

		$idx        = $cart['idx'];
		$fieldArray = $this->_extraFieldFront->getSectionFieldList(17, 0, 0);

		if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_DELETE')))
		{
			$delete_img = Redshop::getConfig()->get('ADDTOCART_DELETE');
		}
		else
		{
			$delete_img = "defaultcross.png";
		}

		for ($i = 0; $i < $idx; $i++)
		{
			$quantity = $cart[$i]['quantity'];

			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
			{
				$giftcard_id  = $cart[$i]['giftcard_id'];
				$giftcardData = $this->_producthelper->getGiftcardData($giftcard_id);
				$link         = JRoute::_('index.php?option=com_redshop&view=giftcard&gid=' . $giftcard_id . '&Itemid=' . $Itemid);
				$reciverInfo = '<div class="reciverInfo">' . JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL') . ': ' . $cart[$i]['reciver_name']
					. '<br />' . JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL') . ': ' . $cart[$i]['reciver_email'] . '</div>';

				$product_name = "<div  class='product_name'><a href='" . $link . "'>" . $giftcardData->giftcard_name . "</a></div>" . $reciverInfo;

				if (strpos($data, "{product_name_nolink}") !== false)
				{
					$product_name_nolink = "<div  class=\"product_name\">" . $giftcardData->giftcard_name . "</div><" . $reciverInfo;
					$cart_mdata          = str_replace("{product_name_nolink}", $product_name_nolink, $data);

					if (strpos($data, "{product_name}") !== false)
						$cart_mdata = str_replace("{product_name}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_name}", $product_name, $data);
				}

				$cart_mdata = str_replace("{product_attribute}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_accessory}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_wrapper}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_old_price}", '', $cart_mdata);
				$cart_mdata = str_replace("{vat_info}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_number_lbl}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_number}", '', $cart_mdata);
				$cart_mdata = str_replace("{attribute_price_without_vat}", '', $cart_mdata);
				$cart_mdata = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

				if ($quotation_mode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
				{
					$cart_mdata = str_replace("{product_total_price}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_price}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_price}", $this->_producthelper->getProductFormattedPrice($cart[$i]['product_price']), $cart_mdata);
					$cart_mdata = str_replace("{product_total_price}", $this->_producthelper->getProductFormattedPrice($cart[$i]['product_price'] * $cart[$i]['quantity'], true), $cart_mdata);
				}

				$cart_mdata     = str_replace("{if product_on_sale}", '', $cart_mdata);
				$cart_mdata     = str_replace("{product_on_sale end if}", '', $cart_mdata);

				$thumbUrl = RedShopHelperImages::getImagePath(
					$giftcardData->giftcard_image,
					'',
					'thumb',
					'giftcard',
					Redshop::getConfig()->get('CART_THUMB_WIDTH'),
					Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);

				$giftcard_image = "&nbsp;";

				if ($thumbUrl)
				{
					$giftcard_image = "<div  class='giftcard_image'><img src='" . $thumbUrl . "'></div>";
				}

				$cart_mdata     = str_replace("{product_thumb_image}", $giftcard_image, $cart_mdata);
				$user_fields    = $this->_producthelper->GetProdcutUserfield($i, 13);
				$cart_mdata     = str_replace("{product_userfields}", $user_fields, $cart_mdata);
				$cart_mdata     = str_replace("{product_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($cart[$i]['product_price']), $cart_mdata);
				$cart_mdata     = str_replace("{product_total_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($cart[$i]['product_price'] * $cart[$i]['quantity']), $cart_mdata);
				$cart_mdata     = str_replace("{attribute_change}", '', $cart_mdata);
				$cart_mdata     = str_replace("{product_attribute_price}", "", $cart_mdata);
				$cart_mdata     = str_replace("{product_attribute_number}", "", $cart_mdata);
				$cart_mdata     = str_replace("{product_tax}", "", $cart_mdata);

				// ProductFinderDatepicker Extra Field
				$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $giftcard_id, $fieldArray, $giftcard = 1);

				$remove_product = '<form style="" class="rs_hiddenupdatecart" name="delete_cart' . $i . '" method="POST" >
				<input type="hidden" name="giftcard_id" value="' . $cart[$i]['giftcard_id'] . '">
				<input type="hidden" name="cart_index" value="' . $i . '">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="Itemid" value="' . $Itemid . '">
				<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $delete_img
					. '" title="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL')
					. '" alt="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL')
					. '" onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';

				if (Redshop::getConfig()->get('QUANTITY_TEXT_DISPLAY'))
				{
					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}

				// Replace attribute tags to empty on giftcard
				if (strpos($cart_mdata, "{product_attribute_loop_start}") !== false && strpos($cart_mdata, "{product_attribute_loop_end}") !== false)
				{
					$templateattibute_sdata  = explode('{product_attribute_loop_start}', $cart_mdata);
					$templateattibute_edata  = explode('{product_attribute_loop_end}', $templateattibute_sdata[1]);
					$templateattibute_middle = $templateattibute_edata[0];

					$cart_mdata = str_replace($templateattibute_middle, "", $cart_mdata);
				}

				$cartItem = 'giftcard_id';
			}
			else
			{
				$product_id     = $cart[$i]['product_id'];
				$product        = $this->_producthelper->getProductById($product_id);
				$retAttArr      = $this->_producthelper->makeAttributeCart($cart [$i] ['cart_attribute'], $product_id, 0, 0, $quantity, $data);
				$cart_attribute = $retAttArr[0];

				$retAccArr      = $this->_producthelper->makeAccessoryCart($cart [$i] ['cart_accessory'], $product_id, $data);
				$cart_accessory = $retAccArr[0];

				$ItemData = $this->_producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $product_id);

				if (count($ItemData) > 0)
				{
					$Itemid = $ItemData->id;
				}
				else
				{
					$Itemid = RedshopHelperUtility::getItemId($product_id);
				}

				$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product_id . '&Itemid=' . $Itemid);

				$pname         = $product->product_name;
				$product_name  = "<div  class='product_name'><a href='" . $link . "'>" . $pname . "</a></div>";
				$product_image = "";
				$prd_image     = '';
				$type          = 'product';

				if (Redshop::getConfig()->get('WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART') && isset($cart[$i]['hidden_attribute_cartimage']))
				{
					$image_path    = REDSHOP_FRONT_IMAGES_ABSPATH;
					$product_image = str_replace($image_path, '', $cart[$i]['hidden_attribute_cartimage']);
				}

				if ($product_image && is_file(REDSHOP_FRONT_IMAGES_RELPATH . $product_image))
				{
					$val        = explode("/", $product_image);
					$prd_image  = $val[1];
					$type       = $val[0];
				}
				elseif ($product->product_full_image && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
				{
					$prd_image = $product->product_full_image;
					$type      = 'product';
				}
				elseif (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
				{
					$prd_image = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
					$type      = 'product';
				}

				$isAttributeImage = false;

				if (isset($cart[$i]['attributeImage']))
				{
					$isAttributeImage = is_file(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $cart[$i]['attributeImage']);
				}

				if ($isAttributeImage)
				{
					$prd_image = $cart[$i]['attributeImage'];
					$type      = 'mergeImages';
				}

				if ($prd_image !== '')
				{
					if (Redshop::getConfig()->get('WATERMARK_CART_THUMB_IMAGE') && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('WATERMARK_IMAGE')))
					{
						$product_cart_img = RedshopHelperMedia::watermark(
							$type, $prd_image, Redshop::getConfig()->get('CART_THUMB_WIDTH'), Redshop::getConfig()->get('CART_THUMB_HEIGHT'), Redshop::getConfig()->get('WATERMARK_CART_THUMB_IMAGE')
						);

						$product_image = "<div  class='product_image'><img src='" . $product_cart_img . "'></div>";
					}
					else
					{
						$thumbUrl = RedShopHelperImages::getImagePath(
								$prd_image,
								'',
								'thumb',
								$type,
								Redshop::getConfig()->get('CART_THUMB_WIDTH'),
								Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);

						$product_image = "<div  class='product_image'><img src='" . $thumbUrl . "'></div>";
					}
				}
				else
				{
					$product_image = "<div  class='product_image'></div>";
				}

				// Trigger to change product image.
				$dispatcher->trigger('OnSetCartOrderItemImage', array(&$cart, &$product_image, $product, $i));

				$chktag              = $this->_producthelper->getApplyVatOrNot($data);
				$product_total_price = "<div class='product_price'>";

				if (!$quotation_mode || ($quotation_mode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					if (!$chktag)
					{
						$product_total_price .= $this->_producthelper->getProductFormattedPrice($cart[$i]['product_price_excl_vat'] * $quantity);
					}
					else
					{
						$product_total_price .= $this->_producthelper->getProductFormattedPrice($cart[$i]['product_price'] * $quantity);
					}
				}

				$product_total_price .= "</div>";

				$product_old_price = "";
				$product_price     = "<div class='product_price'>";

				if (!$quotation_mode || ($quotation_mode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					if (!$chktag)
					{
						$product_price .= $this->_producthelper->getProductFormattedPrice($cart[$i]['product_price_excl_vat'], true);
					}
					else
					{
						$product_price .= $this->_producthelper->getProductFormattedPrice($cart[$i]['product_price'], true);
					}

					if (isset($cart[$i]['product_old_price']))
					{
						$product_old_price = $cart[$i]['product_old_price'];

						if (!$chktag)
						{
							$product_old_price = $cart[$i]['product_old_price_excl_vat'];
						}

						// Set Product Old Price without format
						$productOldPriceNoFormat = $product_old_price;

						$product_old_price = $this->_producthelper->getProductFormattedPrice($product_old_price, true);
					}
				}

				$product_price .= "</div>";

				$wrapper_name = "";

				if ((array_key_exists('wrapper_id', $cart[$i])) && $cart[$i]['wrapper_id'])
				{
					$wrapper = $this->_producthelper->getWrapper($product_id, $cart[$i]['wrapper_id']);

					if (count($wrapper) > 0)
					{
						$wrapper_name = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper[0]->wrapper_name;

						if (!$quotation_mode || ($quotation_mode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
						{
							$wrapper_name .= "(" . $this->_producthelper->getProductFormattedPrice($cart[$i]['wrapper_price'], true) . ")";
						}
					}
				}

				$cart_mdata = '';

				if (strpos($data, "{product_name_nolink}") !== false)
				{
					$product_name_nolink = "";
					$product_name_nolink = "<div  class='product_name'>$product->product_name</a></div>";
					$cart_mdata          = str_replace("{product_name_nolink}", $product_name_nolink, $data);

					if (strpos($data, "{product_name}") !== false)
						$cart_mdata = str_replace("{product_name}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_name}", $product_name, $data);
				}

				$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);

				// Replace Attribute data
				if (strpos($cart_mdata, "{product_attribute_loop_start}") !== false && strpos($cart_mdata, "{product_attribute_loop_end}") !== false)
				{
					$templateattibute_sdata  = explode('{product_attribute_loop_start}', $cart_mdata);
					$templateattibute_start  = $templateattibute_sdata[0];
					$templateattibute_edata  = explode('{product_attribute_loop_end}', $templateattibute_sdata[1]);
					$templateattibute_end    = $templateattibute_edata[1];
					$templateattibute_middle = $templateattibute_edata[0];
					$pro_detail              = '';
					$sum_total               = count($cart[$i]['cart_attribute']);
					$temp_tpi                = $cart[$i]['cart_attribute'];

					if ($sum_total > 0)
					{
						$propertyCalculatedPriceSum = $productOldPriceNoFormat;

						for ($tpi = 0; $tpi < $sum_total; $tpi++)
						{
							$product_attribute_name        = "";
							$product_attribute_value       = "";
							$product_attribute_value_price = "";
							$product_attribute_name        = $temp_tpi[$tpi]['attribute_name'];

							if (count($temp_tpi[$tpi]['attribute_childs']) > 0)
							{
								$product_attribute_value = ": " . $temp_tpi[$tpi]['attribute_childs'][0]['property_name'];

								if (count($temp_tpi[$tpi]['attribute_childs'][0]['property_childs']) > 0)
								{
									$product_attribute_value .= ": " . $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subattribute_color_title'] . ": " . $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_name'];
								}

								$product_attribute_value_price = $temp_tpi[$tpi]['attribute_childs'][0]['property_price'];
								$propertyOperand               = $temp_tpi[$tpi]['attribute_childs'][0]['property_oprand'];

								if (count($temp_tpi[$tpi]['attribute_childs'][0]['property_childs']) > 0)
								{
									$product_attribute_value_price = $product_attribute_value_price + $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_price'];
									$propertyOperand               = $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_oprand'];
								}

								// Show actual productive price
								if ($product_attribute_value_price > 0)
								{
									$productAttributeCalculatedPriceBase = RedshopHelperUtility::setOperandForValues(
										$propertyCalculatedPriceSum, $propertyOperand, $product_attribute_value_price
									);

									$productAttributeCalculatedPrice = $productAttributeCalculatedPriceBase - $propertyCalculatedPriceSum;
									$propertyCalculatedPriceSum      = $productAttributeCalculatedPriceBase;
								}

								$product_attribute_value_price = $this->_producthelper->getProductFormattedPrice($product_attribute_value_price);
							}

							$productAttributeCalculatedPrice = $this->_producthelper->getProductFormattedPrice(
								$productAttributeCalculatedPrice
							);
							$productAttributeCalculatedPrice = JText::sprintf('COM_REDSHOP_CART_PRODUCT_ATTRIBUTE_CALCULATED_PRICE', $productAttributeCalculatedPrice);

							$data_add_pro = $templateattibute_middle;
							$data_add_pro = str_replace("{product_attribute_name}", $product_attribute_name, $data_add_pro);
							$data_add_pro = str_replace("{product_attribute_value}", $product_attribute_value, $data_add_pro);
							$data_add_pro = str_replace("{product_attribute_value_price}", $product_attribute_value_price, $data_add_pro);
							$data_add_pro = str_replace(
								"{product_attribute_calculated_price}",
								$productAttributeCalculatedPrice,
								$data_add_pro
							);
							$pro_detail .= $data_add_pro;
						}
					}

					$cart_mdata = str_replace($templateattibute_middle, $pro_detail, $cart_mdata);
				}

				if (count($cart [$i] ['cart_attribute']) > 0)
				{
					$cart_mdata = str_replace("{attribute_label}", JText::_("COM_REDSHOP_ATTRIBUTE"), $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{attribute_label}", "", $cart_mdata);
				}

				$cart_mdata           = str_replace("{product_number}", $product->product_number, $cart_mdata);
				$cart_mdata           = str_replace("{product_vat}", $cart[$i]['product_vat'] * $cart[$i]['quantity'], $cart_mdata);
				$user_fields          = $this->_producthelper->GetProdcutUserfield($i);
				$cart_mdata           = str_replace("{product_userfields}", $user_fields, $cart_mdata);
				$user_custom_fields   = $this->_producthelper->GetProdcutfield($i);
				$cart_mdata           = str_replace("{product_customfields}", $user_custom_fields, $cart_mdata);
				$cart_mdata           = str_replace("{product_customfields_lbl}", JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD"), $cart_mdata);
				$discount_calc_output = (isset($cart[$i]['discount_calc_output']) && $cart[$i]['discount_calc_output']) ? $cart[$i]['discount_calc_output'] . "<br />" : "";

				$cart_mdata           = RedshopTagsReplacer::_(
											'attribute',
											$cart_mdata,
											array(
												'product_attribute' => $discount_calc_output . $cart_attribute,
											)
										);

				$cart_mdata           = str_replace("{product_accessory}", $cart_accessory, $cart_mdata);
				$cart_mdata           = str_replace("{product_attribute_price}", "", $cart_mdata);
				$cart_mdata           = str_replace("{product_attribute_number}", "", $cart_mdata);
				$cart_mdata           = $this->_producthelper->getProductOnSaleComment($product, $cart_mdata, $product_old_price);
				$cart_mdata           = str_replace("{product_old_price}", $product_old_price, $cart_mdata);
				$cart_mdata           = str_replace("{product_wrapper}", $wrapper_name, $cart_mdata);
				$cart_mdata           = str_replace("{product_thumb_image}", $product_image, $cart_mdata);
				$cart_mdata           = str_replace("{attribute_price_without_vat}", '', $cart_mdata);
				$cart_mdata           = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

				// ProductFinderDatepicker Extra Field Start
				$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray);

				$product_price_excl_vat = $cart[$i]['product_price_excl_vat'];

				if (!$quotation_mode || ($quotation_mode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					$cart_mdata = str_replace("{product_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($product_price_excl_vat), $cart_mdata);
					$cart_mdata = str_replace("{product_total_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($product_price_excl_vat * $quantity), $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_price_excl_vat}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_total_price_excl_vat}", "", $cart_mdata);
				}

				// $cart[$i]['product_price_excl_vat'] = $product_price_excl_vat;
				$this->_session->set('cart', $cart);

				if ($product->product_type == 'subscription')
				{
					$subscription_detail   = $this->_producthelper->getProductSubscriptionDetail($product->product_id, $cart[$i]['subscription_id']);
					$selected_subscription = $subscription_detail->subscription_period . " " . $subscription_detail->period_type;
					$cart_mdata            = str_replace("{product_subscription_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION'), $cart_mdata);
					$cart_mdata            = str_replace("{product_subscription}", $selected_subscription, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_subscription_lbl}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_subscription}", "", $cart_mdata);
				}

				if ($replace_button)
				{
					$update_attribute = '';

					if ($mainview == 'cart')
					{
						$attchange        = JURI::root() . 'index.php?option=com_redshop&view=cart&layout=change_attribute&tmpl=component&pid=' . $product_id . '&cart_index=' . $i;
						$update_attribute = '<a class="modal" rel="{handler: \'iframe\', size: {x: 550, y: 400}}" href="' . $attchange . '">' . JText::_('COM_REDSHOP_CHANGE_ATTRIBUTE') . '</a>';
					}

					if ($cart_attribute != "")
					{
						$cart_mdata = str_replace("{attribute_change}", $update_attribute, $cart_mdata);
					}
					else
					{
						$cart_mdata = str_replace("{attribute_change}", "", $cart_mdata);
					}
				}
				else
				{
					$cart_mdata = str_replace("{attribute_change}", '', $cart_mdata);
				}

				$cartItem = 'product_id';
				$cart_mdata = $this->_producthelper->replaceVatinfo($cart_mdata);
				$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);
				$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);
			}

			if ($replace_button)
			{
				$update_cart_none = '<label>' . $quantity . '</label>';

				$update_img = '';

				if ($mainview == 'checkout')
				{
					$update_cart = $quantity;
				}
				else
				{
					$update_cart = '<form style="padding:0px;margin:0px;" name="update_cart' . $i . '" method="POST" >';
					$update_cart .= '<input class="inputbox input-mini" type="text" value="' . $quantity . '" name="quantity" id="quantitybox' . $i . '" size="' . Redshop::getConfig()->get('DEFAULT_QUANTITY') . '" maxlength="' . Redshop::getConfig()->get('DEFAULT_QUANTITY') . '" onchange="validateInputNumber(this.id);">';
					$update_cart .= '<input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '">
								<input type="hidden" name="cart_index" value="' . $i . '">
								<input type="hidden" name="Itemid" value="' . $Itemid . '">
								<input type="hidden" name="task" value="">';

					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_UPDATE')))
					{
						$update_img = Redshop::getConfig()->get('ADDTOCART_UPDATE');
					}
					else
					{
						$update_img = "defaultupdate.png";
					}

					$update_cart .= '<img class="update_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $update_img . '" title="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" onclick="document.update_cart' . $i . '.task.value=\'update\';document.update_cart' . $i . '.submit();">';

					$update_cart .= '</form>';
				}

				$update_cart_minus_plus = '<form name="update_cart' . $i . '" method="POST">';

				$update_cart_minus_plus .= '<input type="text" id="quantitybox' . $i . '" name="quantity"  size="1"  value="' . $quantity . '" /><input type="button" id="minus" value="-"
						onClick="quantity.value = (quantity.value) ; var qty1 = quantity.value; if( !isNaN( qty1 ) &amp;&amp; qty1 > 1 ) quantity.value--;return false;">';

				$update_cart_minus_plus .= '<input type="button" value="+"
						onClick="quantity.value = (+quantity.value+1)"><input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '">
						<input type="hidden" name="cart_index" value="' . $i . '">
						<input type="hidden" name="Itemid" value="' . $Itemid . '">
						<input type="hidden" name="task" value=""><img class="update_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $update_img . '" title="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" onclick="document.update_cart' . $i . '.task.value=\'update\';document.update_cart' . $i . '.submit();">
						</form>';

				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_DELETE')))
				{
					$delete_img = Redshop::getConfig()->get('ADDTOCART_DELETE');
				}
				else
				{
					$delete_img = "defaultcross.png";
				}

				if ($mainview == 'checkout')
				{
					$remove_product = '';
				}
				else
				{
					$remove_product = '<form name="delete_cart' . $i . '" method="POST" >
							<input type="hidden" name="' . $cartItem . '" value="' . ${$cartItem} . '">
							<input type="hidden" name="cart_index" value="' . $i . '">
							<input type="hidden" name="task" value="">
							<input type="hidden" name="Itemid" value="' . $Itemid . '">
							<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $delete_img . '" title="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL') . '" onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';
				}

				if (Redshop::getConfig()->get('QUANTITY_TEXT_DISPLAY'))
				{
					if (strstr($cart_mdata, "{quantity_increase_decrease}") && $mainview == 'cart')
					{
						$cart_mdata = str_replace("{quantity_increase_decrease}", $update_cart_minus_plus, $cart_mdata);
						$cart_mdata = str_replace("{update_cart}", '', $cart_mdata);
					}
					else
					{
						$cart_mdata = str_replace("{quantity_increase_decrease}", $update_cart, $cart_mdata);
						$cart_mdata = str_replace("{update_cart}", $update_cart, $cart_mdata);
					}

					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{quantity_increase_decrease}", $update_cart_minus_plus, $cart_mdata);
					$cart_mdata = str_replace("{update_cart}", $update_cart_none, $cart_mdata);
					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}
			}
			else
			{
				$cart_mdata = str_replace("{update_cart}", $quantity, $cart_mdata);
				$cart_mdata = str_replace("{remove_product}", '', $cart_mdata);
			}

			// Plugin support:  Process the product plugin for cart item
			$dispatcher->trigger('onCartItemDisplay', array(& $cart_mdata, $cart, $i));

			$cart_tr .= $cart_mdata;
		}

		return $cart_tr;
	}

	public function repalceOrderItems($data, $rowitem = array())
	{
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = JDispatcher::getInstance();
		$mainview   = JRequest::getVar('view');
		$fieldArray = $this->_extraFieldFront->getSectionFieldList(17, 0, 0);

		$subtotal_excl_vat = 0;
		$cart              = '';
		$url               = JURI::root();
		$returnArr         = array();

		$wrapper_name = "";

		$OrdersDetail = $this->_order_functions->getOrderDetails($rowitem [0]->order_id);

		for ($i = 0, $in = count($rowitem); $i < $in; $i++)
		{
			$product_id = $rowitem [$i]->product_id;
			$quantity   = $rowitem [$i]->product_quantity;

			if ($rowitem [$i]->is_giftcard)
			{
				$giftcardData      = $this->_producthelper->getGiftcardData($product_id);
				$product_name      = $giftcardData->giftcard_name;
				$userfield_section = 13;
				$product = new stdClass;
			}
			else
			{
				$product           = $this->_producthelper->getProductById($product_id);
				$product_name      = $rowitem[$i]->order_item_name;
				$userfield_section = 12;
				$giftcardData = new stdClass;
			}

			$dirname = JPATH_COMPONENT_SITE . "/assets/images/orderMergeImages/" . $rowitem [$i]->attribute_image;

			if (is_file($dirname))
			{
				$attribute_image_path = RedShopHelperImages::getImagePath(
											$rowitem[$i]->attribute_image,
											'',
											'thumb',
											'orderMergeImages',
											Redshop::getConfig()->get('CART_THUMB_WIDTH'),
											Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
											Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
										);
				$attrib_img = '<img src="' . $attribute_image_path . '">';
			}
			else
			{
				if (is_file(JPATH_COMPONENT_SITE . "/assets/images/product_attributes/" . $rowitem [$i]->attribute_image))
				{
					$attribute_image_path = RedShopHelperImages::getImagePath(
												$rowitem[$i]->attribute_image,
												'',
												'thumb',
												'product_attributes',
												Redshop::getConfig()->get('CART_THUMB_WIDTH'),
												Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
												Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
											);
					$attrib_img = '<img src="' . $attribute_image_path . '">';
				}
				else
				{
					if ($rowitem [$i]->is_giftcard)
					{
						$product_full_image = $giftcardData->giftcard_image;
						$product_type = 'giftcard';
					}
					else
					{
						$product_full_image = $product->product_full_image;
						$product_type = 'product';
					}

					if ($product_full_image)
					{
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $product_type . "/" . $product_full_image))
						{
							$attribute_image_path = RedShopHelperImages::getImagePath(
														$product_full_image,
														'',
														'thumb',
														$product_type,
														Redshop::getConfig()->get('CART_THUMB_WIDTH'),
														Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
														Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
													);
							$attrib_img = '<img src="' . $attribute_image_path . '">';
						}
						else
						{
							if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
							{
								$attribute_image_path = RedShopHelperImages::getImagePath(
															Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE'),
															'',
															'thumb',
															'product',
															Redshop::getConfig()->get('CART_THUMB_WIDTH'),
															Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
															Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
														);
								$attrib_img = '<img src="' . $attribute_image_path . '">';
							}
						}
					}
					else
					{
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
						{
							$attribute_image_path = RedShopHelperImages::getImagePath(
														Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE'),
														'',
														'thumb',
														'product',
														Redshop::getConfig()->get('CART_THUMB_WIDTH'),
														Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
														Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
													);
							$attrib_img = '<img src="' . $attribute_image_path . '">';
						}
					}
				}
			}

			$product_name        = "<div class='product_name'>" . $product_name . "</div>";
			$product_total_price = "<div class='product_price'>";

			if (!$this->_producthelper->getApplyVatOrNot($data))
			{
				$product_total_price .= $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat * $quantity);
			}
			else
			{
				$product_total_price .= $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price * $quantity);
			}

			$product_total_price .= "</div>";

			$product_price = "<div class='product_price'>";

			if (!$this->_producthelper->getApplyVatOrNot($data))
			{
				$product_price .= $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat);
			}
			else
			{
				$product_price .= $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price);
			}

			$product_price .= "</div>";

			$product_old_price = $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_old_price);

			$product_quantity = '<div class="update_cart">' . $quantity . '</div>';

			if ($rowitem [$i]->wrapper_id)
			{
				$wrapper = $this->_producthelper->getWrapper($product_id, $rowitem [$i]->wrapper_id);

				if (count($wrapper) > 0)
				{
					$wrapper_name = $wrapper [0]->wrapper_name;
				}

				$wrapper_price = $this->_producthelper->getProductFormattedPrice($rowitem [$i]->wrapper_price);
				$wrapper_name  = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper_name . "(" . $wrapper_price . ")";
			}

			$cart_mdata = str_replace("{product_name}", $product_name, $data);

			$catId = $this->_producthelper->getCategoryProduct($product_id);
			$res   = $this->_producthelper->getSection("category", $catId);

			if (count($res) > 0)
			{
				$cname = $res->name;
				$clink = JRoute::_($url . 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $catId);
				$category_path = "<a href='" . $clink . "'>" . $cname . "</a>";
			}
			else
			{
				$category_path = '';
			}

			if (strpos($cart_mdata, '{stock_status}') !== false)
			{
				$isStockExists = RedshopHelperStockroom::isStockExists($rowitem[$i]->product_id);

				if (!$isStockExists)
				{
					$isPreorderStockExists = RedshopHelperStockroom::isPreorderStockExists($rowitem[$i]->product_id);
				}

				if (!$isStockExists)
				{
					$productPreorder = $product->preorder;

					if (($productPreorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreorder == "yes") || ($productPreorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
					{
						if (!$isPreorderStockExists)
						{
							$stockStatus = JText::_('COM_REDSHOP_OUT_OF_STOCK');
						}
						else
						{
							$stockStatus = JText::_('COM_REDSHOP_PRE_ORDER');
						}
					}
					else
					{
						$stockStatus = JText::_('COM_REDSHOP_OUT_OF_STOCK');
					}
				}
				else
				{
					$stockStatus = JText::_('COM_REDSHOP_AVAILABLE_STOCK');
				}

				$cart_mdata = str_replace("{stock_status}", $stockStatus, $cart_mdata);
			}

			$cart_mdata    = str_replace("{category_name}", $category_path, $cart_mdata);

			$cart_mdata = $this->_producthelper->replaceVatinfo($cart_mdata);

			$product_note = "<div class='product_note'>" . $wrapper_name . "</div>";

			$cart_mdata = str_replace("{product_wrapper}", $product_note, $cart_mdata);

			// Make attribute order template output
			$attribute_data = $this->_producthelper->makeAttributeOrder($rowitem[$i]->order_item_id, 0, $product_id, 0, 0, $data);

			// Assign template output into {product_attribute} tag
			$cart_mdata = RedshopTagsReplacer::_(
						'attribute',
						$cart_mdata,
						array(
							'product_attribute' => $attribute_data->product_attribute,
						)
					);

			// Assign template output into {attribute_middle_template} tag
			$cart_mdata = str_replace($attribute_data->attribute_middle_template_core, $attribute_data->attribute_middle_template, $cart_mdata);

			if (strpos($cart_mdata, '{remove_product_attribute_title}') !== false)
			{
				$cart_mdata = str_replace("{remove_product_attribute_title}", "", $cart_mdata);
			}

			if (strpos($cart_mdata, '{remove_product_subattribute_title}') !== false)
			{
				$cart_mdata = str_replace("{remove_product_subattribute_title}", "", $cart_mdata);
			}

			if (strpos($cart_mdata, '{product_attribute_number}') !== false)
			{
				$cart_mdata = str_replace("{product_attribute_number}", "", $cart_mdata);
			}

			$cart_mdata = str_replace("{product_accessory}", $this->_producthelper->makeAccessoryOrder($rowitem [$i]->order_item_id), $cart_mdata);

			$productUserFields = $this->_producthelper->getuserfield($rowitem [$i]->order_item_id, $userfield_section);

			$cart_mdata = str_replace("{product_userfields}", $productUserFields, $cart_mdata);

			$user_custom_fields = $this->_producthelper->GetProdcutfield_order($rowitem [$i]->order_item_id);
			$cart_mdata         = str_replace("{product_customfields}", $user_custom_fields, $cart_mdata);
			$cart_mdata         = str_replace("{product_customfields_lbl}", JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD"), $cart_mdata);

			if ($rowitem [$i]->is_giftcard)
			{
				$cart_mdata = str_replace(
					array('{product_sku}', '{product_number}', '{product_s_desc}', '{product_subscription}', '{product_subscription_lbl}'),
					'', $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{product_sku}", $rowitem[$i]->order_item_sku, $cart_mdata);
				$cart_mdata = str_replace("{product_number}", $rowitem[$i]->order_item_sku, $cart_mdata);
				$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);

				if ($product->product_type == 'subscription')
				{
					$user_subscribe_detail = $this->_producthelper->getUserProductSubscriptionDetail($rowitem[$i]->order_item_id);
					$subscription_detail   = $this->_producthelper->getProductSubscriptionDetail($product->product_id, $user_subscribe_detail->subscription_id);
					$selected_subscription = $subscription_detail->subscription_period . " " . $subscription_detail->period_type;

					$cart_mdata = str_replace("{product_subscription_lbl}", JText::_('COM_REDSHOP_SUBSCRIPTION'), $cart_mdata);
					$cart_mdata = str_replace("{product_subscription}", $selected_subscription, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_subscription_lbl}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_subscription}", "", $cart_mdata);
				}
			}

			$cart_mdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cart_mdata);

			$product_vat = ($rowitem [$i]->product_item_price - $rowitem [$i]->product_item_price_excl_vat) * $rowitem [$i]->product_quantity;

			$cart_mdata = str_replace("{product_vat}", $product_vat, $cart_mdata);

			$cart_mdata = $this->_producthelper->getProductOnSaleComment($product, $cart_mdata);

			$cart_mdata = str_replace("{attribute_price_without_vat}", '', $cart_mdata);

			$cart_mdata = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

			// ProductFinderDatepicker Extra Field Start
			$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray);

			// Change order item image based on plugin
			$prepareCartAttributes[$i]               = get_object_vars($attribute_data);
			$prepareCartAttributes[$i]['product_id'] = $rowitem[$i]->product_id;

			$dispatcher->trigger(
				'OnSetCartOrderItemImage',
				array(
					&$prepareCartAttributes,
					&$attrib_img,
					$rowitem[$i],
					$i
				)
			);

			$cart_mdata = str_replace(
				"{product_thumb_image}",
				"<div  class='product_image'>" . $attrib_img . "</div>",
				$cart_mdata
			);

			$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);

			$cart_mdata = str_replace("{product_old_price}", $product_old_price, $cart_mdata);

			$cart_mdata = str_replace("{product_quantity}", $quantity, $cart_mdata);

			$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);

			$cart_mdata = str_replace("{product_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat), $cart_mdata);

			$cart_mdata = str_replace("{product_total_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat * $quantity), $cart_mdata);

			$subtotal_excl_vat += $rowitem [$i]->product_item_price_excl_vat * $quantity;

			$dispatcher = RedshopHelperUtility::getDispatcher();
			JPluginHelper::importPlugin('redshop_stockroom');
			$dispatcher->trigger('onReplaceStockStatus', array($rowitem[$i], &$cart_mdata));

			if ($mainview == "order_detail")
			{
				$Itemid     = RedshopHelperUtility::getCartItemId();
				$copytocart = "<a href='" . JRoute::_('index.php?option=com_redshop&view=order_detail&task=copyorderitemtocart&order_item_id=' . $rowitem[$i]->order_item_id . '&Itemid=' . $Itemid, false) . "'>";
				$copytocart .= "<img src='" . REDSHOP_ADMIN_IMAGES_ABSPATH . "add.jpg' title='" . JText::_("COM_REDSHOP_COPY_TO_CART") . "' alt='" . JText::_("COM_REDSHOP_COPY_TO_CART") . "' /></a>";
				$cart_mdata = str_replace("{copy_orderitem}", $copytocart, $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{copy_orderitem}", "", $cart_mdata);
			}

			// Get Downloadable Products
			$downloadProducts     = $this->_order_functions->getDownloadProduct($rowitem[$i]->order_id);
			$totalDownloadProduct = count($downloadProducts);

			$dproducts = array();

			for ($t = 0; $t < $totalDownloadProduct; $t++)
			{
				$downloadProduct                                                        = $downloadProducts[$t];
				$dproducts[$downloadProduct->product_id][$downloadProduct->download_id] = $downloadProduct;
			}

			// Get Downloadable Products Logs
			$downloadProductslog     = $this->_order_functions->getDownloadProductLog($rowitem[$i]->order_id);
			$totalDownloadProductlog = count($downloadProductslog);

			$dproductslog = array();

			for ($t = 0; $t < $totalDownloadProductlog; $t++)
			{
				$downloadProductlogs                              = $downloadProductslog[$t];
				$dproductslog[$downloadProductlogs->product_id][] = $downloadProductlogs;
			}

			// Download Product Tag Replace
			if (isset($dproducts[$product_id]) && count($dproducts[$product_id]) > 0 && $OrdersDetail->order_status == "C" && $OrdersDetail->order_payment_status == "Paid")
			{
				$downloadarray = $dproducts[$product_id];
				$dpData        = "<table class='download_token'>";
				$limit         = $dpData;
				$enddate       = $dpData;
				$g             = 1;

				foreach ($downloadarray as $downloads)
				{
					$file_name    = substr(basename($downloads->file_name), 11);
					$product_name = $downloadProduct->product_name;
					$download_id  = $downloads->download_id;
					$download_max = $downloads->download_max;
					$end_date     = $downloads->end_date;
					$mailtoken    = "<a href='" . JURI::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $download_id . "'>" . $file_name . "</a>";
					$dpData .= "</tr>";
					$dpData .= "<td>(" . $g . ") " . $product_name . ": " . $mailtoken . "</td>";
					$dpData .= "</tr>";
					$limit .= "</tr>";
					$limit .= "<td>(" . $g . ") " . $download_max . "</td>";
					$limit .= "</tr>";
					$enddate .= "</tr>";
					$enddate .= "<td>(" . $g . ") " . date("d-m-Y H:i", $end_date) . "</td>";
					$enddate .= "</tr>";
					$g++;
				}

				$dpData .= "</table>";
				$limit .= "</table>";
				$enddate .= "</table>";
				$cart_mdata = str_replace("{download_token_lbl}", JText::_('COM_REDSHOP_DOWNLOAD_TOKEN'), $cart_mdata);
				$cart_mdata = str_replace("{download_token}", $dpData, $cart_mdata);
				$cart_mdata = str_replace("{download_counter_lbl}", JText::_('COM_REDSHOP_DOWNLOAD_LEFT'), $cart_mdata);
				$cart_mdata = str_replace("{download_counter}", $limit, $cart_mdata);
				$cart_mdata = str_replace("{download_date_lbl}", JText::_('COM_REDSHOP_DOWNLOAD_ENDDATE'), $cart_mdata);
				$cart_mdata = str_replace("{download_date}", $enddate, $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{download_token_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_token}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_counter_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_counter}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date}", "", $cart_mdata);
			}

			// Download Product log Tags Replace
			if (isset($dproductslog[$product_id]) && count($dproductslog[$product_id]) > 0 && $OrdersDetail->order_status == "C")
			{
				$downloadarraylog = $dproductslog[$product_id];
				$dpData           = "<table class='download_token'>";
				$g                = 1;

				foreach ($downloadarraylog as $downloads)
				{
					$file_name = substr(basename($downloads->file_name), 11);

					$download_id   = $downloads->download_id;
					$download_time = $downloads->download_time;
					$download_date = date("d-m-Y H:i:s", $download_time);
					$ip            = $downloads->ip;

					$mailtoken = "<a href='" . JURI::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid="
						. $download_id . "'>"
						. $file_name . "</a>";

					$dpData .= "</tr>";
					$dpData .= "<td>(" . $g . ") " . $mailtoken . " "
						. JText::_('COM_REDSHOP_ON') . " " . $download_date . " "
						. JText::_('COM_REDSHOP_FROM') . " " . $ip . "</td>";
					$dpData .= "</tr>";

					$g++;
				}

				$dpData .= "</table>";
				$cart_mdata = str_replace("{download_date_list_lbl}", JText::_('COM_REDSHOP_DOWNLOAD_LOG'), $cart_mdata);
				$cart_mdata = str_replace("{download_date_list}", $dpData, $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{download_date_list_lbl}", "", $cart_mdata);
				$cart_mdata = str_replace("{download_date_list}", "", $cart_mdata);
			}

			// Process the product plugin for cart item
			$dispatcher->trigger('onOrderItemDisplay', array(& $cart_mdata, &$rowitem, $i));

			$cart .= $cart_mdata;
		}

		$returnArr[0] = $cart;
		$returnArr[1] = $subtotal_excl_vat;

		return $returnArr;
	}

	public function replaceLabel($data)
	{
		$search  = array();
		$replace = array();

		if (strpos($data, '{cart_lbl}') !== false)
		{
			$search[]  = "{cart_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_LBL');
		}

		if (strpos($data, '{copy_orderitem_lbl}') !== false)
		{
			$search[]  = "{copy_orderitem_lbl}";
			$replace[] = JText::_('COM_REDSHOP_COPY_ORDERITEM_LBL');
		}

		if (strpos($data, '{totalpurchase_lbl}') !== false)
		{
			$search[]  = "{totalpurchase_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_TOTAL_PURCHASE_TBL');
		}

		if (strpos($data, '{subtotal_excl_vat_lbl}') !== false)
		{
			$search[]  = "{subtotal_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SUBTOTAL_EXCL_VAT_LBL');
		}

		if (strpos($data, '{product_name_lbl}') !== false)
		{
			$search[]  = "{product_name_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME_LBL');
		}

		if (strpos($data, '{price_lbl}') !== false)
		{
			$search[]  = "{price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');
		}

		if (strpos($data, '{quantity_lbl}') !== false)
		{
			$search[]  = "{quantity_lbl}";
			$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');
		}

		if (strpos($data, '{total_price_lbl}') !== false)
		{
			$search[]  = "{total_price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
		}

		if (strpos($data, '{total_price_exe_lbl}') !== false)
		{
			$search[]  = "{total_price_exe_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_EXEL_LBL');
		}

		if (strpos($data, '{order_id_lbl}') !== false)
		{
			$search[]  = "{order_id_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_ID_LBL');
		}

		if (strpos($data, '{order_number_lbl}') !== false)
		{
			$search[]  = "{order_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_NUMBER_LBL');
		}

		if (strpos($data, '{order_date_lbl}') !== false)
		{
			$search[]  = "{order_date_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DATE_LBL');
		}

		if (strpos($data, '{requisition_number_lbl}') !== false)
		{
			$search[]  = "{requisition_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_REQUISITION_NUMBER');
		}

		if (strpos($data, '{order_status_lbl}') !== false)
		{
			$search[]  = "{order_status_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_STAUS_LBL');
		}

		if (strpos($data, '{order_status_order_only_lbl}') !== false)
		{
			$search[]  = "{order_status_order_only_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_STAUS_LBL');
		}

		if (strpos($data, '{order_status_payment_only_lbl}') !== false)
		{
			$search[]  = "{order_status_payment_only_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_STAUS_LBL');
		}

		if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			if (strpos($data, '{shipping_lbl}') !== false)
			{
				$search[]  = "{shipping_lbl}";
				$replace[] = JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
			}

			if (strpos($data, '{tax_with_shipping_lbl}') !== false)
			{
				$search[]  = "{tax_with_shipping_lbl}";
				$replace[] = JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
			}
		}
		else
		{
			if (strpos($data, '{shipping_lbl}') !== false)
			{
				$search[]  = "{shipping_lbl}";
				$replace[] = "";
			}

			if (strpos($data, '{tax_with_shipping_lbl}') !== false)
			{
				$search[]  = "{tax_with_shipping_lbl}";
				$replace[] = "";
			}
		}

		if (strpos($data, '{order_information_lbl}') !== false)
		{
			$search[]  = "{order_information_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_INFORMATION_LBL');
		}

		if (strpos($data, '{order_detail_lbl}') !== false)
		{
			$search[]  = "{order_detail_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LBL');
		}

		if (strpos($data, '{product_name_lbl}') !== false)
		{
			$search[]  = "{product_name_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME_LBL');
		}

		if (strpos($data, '{note_lbl}') !== false)
		{
			$search[]  = "{note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_NOTE_LBL');
		}

		if (strpos($data, '{price_lbl}') !== false)
		{
			$search[]  = "{price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');
		}

		if (strpos($data, '{quantity_lbl}') !== false)
		{
			$search[]  = "{quantity_lbl}";
			$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');
		}

		if (strpos($data, '{total_price_lbl}') !== false)
		{
			$search[]  = "{total_price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
		}

		if (strpos($data, '{order_subtotal_lbl}') !== false)
		{
			$search[]  = "{order_subtotal_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_SUBTOTAL_LBL');
		}

		if (strpos($data, '{total_lbl}') !== false)
		{
			$search[]  = "{total_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_LBL');
		}

		if (strpos($data, '{discount_type_lbl}') !== false)
		{
			$search[]  = "{discount_type_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_DISCOUNT_CODE_TBL');
		}

		if (strpos($data, '{payment_lbl}') !== false)
		{
			$search[]  = "{payment_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_METHOD');
		}

		if (strpos($data, '{customer_note_lbl}') !== false)
		{
			$search [] = "{customer_note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL');
		}

		if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			if (strpos($data, '{shipping_method_lbl}') !== false)
			{
				$search[]  = "{shipping_method_lbl}";
				$replace[] = JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL');
			}
		}
		else
		{
			if (strpos($data, '{shipping_method_lbl}') !== false)
			{
				$search[]  = "{shipping_method_lbl}";
				$replace[] = '';
			}
		}

		if (strpos($data, '{product_number_lbl}') !== false)
		{
			$search[]  = "{product_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NUMBER');
		}

		if (strpos($data, '{shopname}') !== false)
		{
			$search []  = "{shopname}";
			$replace [] = Redshop::getConfig()->get('SHOP_NAME');
		}

		if (strpos($data, '{quotation_id_lbl}') !== false)
		{
			$search []  = "{quotation_id_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_ID');
		}

		if (strpos($data, '{quotation_number_lbl}') !== false)
		{
			$search []  = "{quotation_number_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_NUMBER');
		}

		if (strpos($data, '{quotation_date_lbl}') !== false)
		{
			$search []  = "{quotation_date_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DATE');
		}

		if (strpos($data, '{quotation_status_lbl}') !== false)
		{
			$search []  = "{quotation_status_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_STATUS');
		}

		if (strpos($data, '{quotation_note_lbl}') !== false)
		{
			$search []  = "{quotation_note_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_NOTE');
		}

		if (strpos($data, '{quotation_information_lbl}') !== false)
		{
			$search []  = "{quotation_information_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_INFORMATION');
		}

		if (strpos($data, '{account_information_lbl}') !== false)
		{
			$search []  = "{account_information_lbl}";
			$replace [] = JText::_('COM_REDSHOP_ACCOUNT_INFORMATION');
		}

		if (strpos($data, '{quotation_detail_lbl}') !== false)
		{
			$search []  = "{quotation_detail_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DETAILS');
		}

		if (strpos($data, '{quotation_subtotal_lbl}') !== false)
		{
			$search []  = "{quotation_subtotal_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
		}

		if (strpos($data, '{quotation_discount_lbl}') !== false)
		{
			$search []  = "{quotation_discount_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DISCOUNT_LBL');
		}

		if (strpos($data, '{thirdparty_email_lbl}') !== false)
		{
			$search [] = "{thirdparty_email_lbl}";
			$replace[] = JText::_('COM_REDSHOP_THIRDPARTY_EMAIL_LBL');
		}

		$data = str_replace($search, $replace, $data);

		return $data;
	}

	/**
	 * APPLY_VAT_ON_DISCOUNT = When the discount is a "fixed amount" the final price may vary, depending on if the discount affects "the price+VAT"
	 * or just "the price". This CONSTANT will define if the discounts needs to be applied BEFORE or AFTER the VAT is applied to the product price.
	 *
	 * @param   array    $cart      Cart data
	 * @param   integer  $shipping  Is shipping calculate
	 * @param   integer  $user_id   ID of user.
	 *
	 * @return  array               Array of calculated cart
	 *
	 * @deprecated   __DEPLOY_VERSION__  Use RedshopHelperCart::calculation instead
	 */
	public function calculation($cart, $shipping = 0, $user_id = 0)
	{
		return RedshopHelperCart::calculation($cart, $shipping, $user_id);
	}

	/**
	 * Get cart module calculation
	 *
	 * @param   array  $redArray  Data
	 *
	 * @return  float
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function GetCartModuleCalc($redArray)
	{
		return RedshopHelperCart::getCartModuleCalc($redArray);
	}

	public function replaceTemplate($cart, $cart_data, $checkout = 1)
	{
		if (strpos($cart_data, "{product_loop_start}") !== false && strpos($cart_data, "{product_loop_end}") !== false)
		{
			$template_sdata  = explode('{product_loop_start}', $cart_data);
			$template_start  = $template_sdata[0];
			$template_edata  = explode('{product_loop_end}', $template_sdata[1]);
			$template_end    = $template_edata[1];
			$template_middle = $template_edata[0];
			$template_middle = $this->replaceCartItem($template_middle, $cart, 1, Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));
			$cart_data       = $template_start . $template_middle . $template_end;
		}

		$cart_data = $this->replaceLabel($cart_data);

		$total                     = $cart ['total'];
		$subtotal_excl_vat         = $cart ['subtotal_excl_vat'];
		$product_subtotal          = $cart ['product_subtotal'];
		$product_subtotal_excl_vat = $cart ['product_subtotal_excl_vat'];
		$subtotal                  = $cart ['subtotal'];
		$discount_ex_vat           = $cart['discount_ex_vat'];
		$dis_tax                   = 0;
		$discount_total            = $cart['voucher_discount'] + $cart['coupon_discount'];
		$discount_amount           = $cart ["cart_discount"];
		$tax                       = $cart ['tax'];
		$sub_total_vat             = $cart ['sub_total_vat'];
		$shipping                  = $cart ['shipping'];
		$shippingVat               = $cart ['shipping_tax'];

		if (isset($cart ['discount_type']) === false)
		{
			$cart ['discount_type'] = 0;
		}

		$check_type                = $cart ['discount_type'];
		$chktotal                  = 0;
		$tmp_discount              = $discount_total;
		$discount_total            = $this->_producthelper->getProductFormattedPrice($discount_total + $discount_amount, true);

		if (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
		{
			if (strpos($cart_data, '{product_subtotal_lbl}') !== false)
			{
				$cart_data = str_replace("{product_subtotal_lbl}", JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{product_subtotal_excl_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{product_subtotal_excl_vat_lbl}", JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{shipping_with_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{shipping_with_vat_lbl}", JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{shipping_excl_vat_lbl}') !== false)
			{
				$cart_data = str_replace("{shipping_excl_vat_lbl}", JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL'), $cart_data);
			}

			if (strpos($cart_data, '{product_price_excl_lbl}') !== false)
			{
				$cart_data = str_replace("{product_price_excl_lbl}", JText::_('COM_REDSHOP_PRODUCT_PRICE_EXCL_LBL'), $cart_data);
			}

			$cart_data = str_replace("{total}", "<span id='spnTotal'>" . $this->_producthelper->getProductFormattedPrice($total, true) . "</span>", $cart_data);
			$cart_data = str_replace("{total_excl_vat}", "<span id='spnTotal'>" . $this->_producthelper->getProductFormattedPrice($subtotal_excl_vat) . "</span>", $cart_data);

			$chktag = $this->_producthelper->getApplyVatOrNot($cart_data);

			if (!empty($chktag))
			{
				$cart_data = str_replace("{subtotal}", $this->_producthelper->getProductFormattedPrice($subtotal), $cart_data);
				$cart_data = str_replace("{product_subtotal}", $this->_producthelper->getProductFormattedPrice($product_subtotal), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{subtotal}", $this->_producthelper->getProductFormattedPrice($subtotal_excl_vat), $cart_data);
				$cart_data = str_replace("{product_subtotal}", $this->_producthelper->getProductFormattedPrice($product_subtotal_excl_vat), $cart_data);
			}

			if ((strpos($cart_data, "{discount_denotation}") !== false || strpos($cart_data, "{shipping_denotation}") !== false) && ($discount_total != 0 || $shipping != 0))
			{
				$cart_data = str_replace("{denotation_label}", JText::_('COM_REDSHOP_DENOTATION_TXT'), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{denotation_label}", "", $cart_data);
			}

			if (strpos($cart_data, "{discount_excl_vat}") !== false)
			{
				$cart_data = str_replace("{discount_denotation}", "*", $cart_data);
			}
			else
			{
				$cart_data = str_replace("{discount_denotation}", "", $cart_data);
			}

			$cart_data = str_replace("{subtotal_excl_vat}", $this->_producthelper->getProductFormattedPrice($subtotal_excl_vat), $cart_data);
			$cart_data = str_replace("{product_subtotal_excl_vat}", $this->_producthelper->getProductFormattedPrice($product_subtotal_excl_vat), $cart_data);
			$cart_data = str_replace("{sub_total_vat}", $this->_producthelper->getProductFormattedPrice($sub_total_vat), $cart_data);
			$cart_data = str_replace("{discount_excl_vat}", $this->_producthelper->getProductFormattedPrice($discount_ex_vat), $cart_data);

			$rep = true;

			if (!$checkout)
			{
				if (!Redshop::getConfig()->get('SHOW_SHIPPING_IN_CART') || !Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
				{
					$rep = false;
				}
			}
			else
			{
				if (!Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
				{
					$rep = false;
				}
			}

			if (!empty($rep))
			{
				if (strpos($cart_data, "{shipping_excl_vat}") !== false)
				{
					$cart_data = str_replace("{shipping_denotation}", "*", $cart_data);
				}
				else
				{
					$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
				}

				$cart_data = str_replace("{order_shipping}", $this->_producthelper->getProductFormattedPrice($shipping, true), $cart_data);
				$cart_data = str_replace("{shipping_excl_vat}", "<span id='spnShippingrate'>" . $this->_producthelper->getProductFormattedPrice($shipping - $cart['shipping_tax'], true) . "</span>", $cart_data);
				$cart_data = str_replace("{shipping_lbl}", JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL'), $cart_data);
				$cart_data = str_replace("{shipping}", $this->_producthelper->getProductFormattedPrice($shipping, true), $cart_data);
				$cart_data = str_replace("{tax_with_shipping_lbl}", JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL'), $cart_data);
				$cart_data = str_replace("{vat_shipping}", $this->_producthelper->getProductFormattedPrice($shippingVat), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{order_shipping}", '', $cart_data);
				$cart_data = str_replace("{shipping_excl_vat}", '', $cart_data);
				$cart_data = str_replace("{shipping_lbl}", '', $cart_data);
				$cart_data = str_replace("{shipping}", '', $cart_data);
				$cart_data = str_replace("{tax_with_shipping_lbl}", '', $cart_data);
				$cart_data = str_replace("{vat_shipping}", '', $cart_data);
				$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
			}
		}
		else
		{
			$cart_data = str_replace("{total}", "<span id='spnTotal'></span>", $cart_data);
			$cart_data = str_replace("{shipping_excl_vat}", "<span id='spnShippingrate'></span>", $cart_data);
			$cart_data = str_replace("{order_shipping}", "", $cart_data);
			$cart_data = str_replace("{shipping_lbl}", '', $cart_data);
			$cart_data = str_replace("{shipping}", '', $cart_data);
			$cart_data = str_replace("{subtotal}", "", $cart_data);
			$cart_data = str_replace("{tax_with_shipping_lbl}", '', $cart_data);
			$cart_data = str_replace("{vat_shipping}", '', $cart_data);
			$cart_data = str_replace("{subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{shipping_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{product_subtotal_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{product_subtotal}", "", $cart_data);
			$cart_data = str_replace("{sub_total_vat}", "", $cart_data);
			$cart_data = str_replace("{discount_excl_vat}", "", $cart_data);
			$cart_data = str_replace("{discount_denotation}", "", $cart_data);
			$cart_data = str_replace("{shipping_denotation}", "", $cart_data);
			$cart_data = str_replace("{denotation_label}", "", $cart_data);
			$cart_data = str_replace("{total_excl_vat}", "", $cart_data);
		}

		if (!Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
		{
			$total_for_discount = $subtotal_excl_vat;
		}
		else
		{
			$total_for_discount = $subtotal;
		}

		$cart_data = $this->replaceDiscount($cart_data, $discount_amount + $tmp_discount, $total_for_discount, Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));

		if ($checkout)
		{
			$cart_data = $this->replacePayment($cart_data, $cart['payment_amount'], 0, $cart['payment_oprand']);
		}
		else
		{
			$paymentOprand = (isset($cart['payment_oprand'])) ? $cart['payment_oprand'] : '-';
			$cart_data     = $this->replacePayment($cart_data, 0, 1, $paymentOprand);
		}

		$cart_data = $this->replaceTax($cart_data, $tax + $shippingVat, $discount_amount + $tmp_discount, 0, Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));

		return $cart_data;
	}

	public function replaceOrderTemplate($row, $ReceiptTemplate, $sendmail = false)
	{
		$url       = JURI::base();
		$redconfig = Redconfiguration::getInstance();
		$order_id  = $row->order_id;
		$session   = JFactory::getSession();
		$orderitem = $this->_order_functions->getOrderItemDetail($order_id);

		$search    = array();
		$replace   = array();

		if (strpos($ReceiptTemplate, "{product_loop_start}") !== false && strpos($ReceiptTemplate, "{product_loop_end}") !== false)
		{
			$template_sdata  = explode('{product_loop_start}', $ReceiptTemplate);
			$template_start  = $template_sdata[0];
			$template_edata  = explode('{product_loop_end}', $template_sdata[1]);
			$template_end    = $template_edata[1];
			$template_middle = $template_edata[0];
			$cartArr         = $this->repalceOrderItems($template_middle, $orderitem);
			$ReceiptTemplate = $template_start . $cartArr[0] . $template_end;
		}

		$orderdetailurl = JURI::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $order_id . '&encr=' . $row->encr_key;

		$downloadProducts     = $this->_order_functions->getDownloadProduct($order_id);
		$paymentmethod        = $this->_order_functions->getOrderPaymentDetail($order_id);
		$paymentmethod        = $paymentmethod[0];

		// Initialize Transaction label
		$transactionIdLabel = '';

		// Check if transaction Id is set
		if ($paymentmethod->order_payment_trans_id != null)
		{
			$transactionIdLabel = JText::_('COM_REDSHOP_PAYMENT_TRANSACTION_ID_LABEL');
		}

		// Replace Transaction Id and Label
		$ReceiptTemplate      = str_replace("{transaction_id_label}", $transactionIdLabel, $ReceiptTemplate);
		$ReceiptTemplate      = str_replace("{transaction_id}", $paymentmethod->order_payment_trans_id, $ReceiptTemplate);

		// Get Payment Method information
		$paymentmethod_detail = $this->_order_functions->getPaymentMethodInfo($paymentmethod->payment_method_class);
		$paymentmethod_detail = $paymentmethod_detail [0];
		$OrderStatus          = $this->_order_functions->getOrderStatusTitle($row->order_status);

		$product_name         = "";
		$product_price        = "";
		$subtotal_excl_vat    = $cartArr[1];
		$barcode_code         = $row->barcode;
		$img_url              = REDSHOP_FRONT_IMAGES_ABSPATH . "barcode/" . $barcode_code . ".png";
		$bar_replace          = '<img alt="" src="' . $img_url . '">';

		$total_excl_vat       = $subtotal_excl_vat + ($row->order_shipping - $row->order_shipping_tax) - ($row->order_discount - $row->order_discount_vat);
		$sub_total_vat        = $row->order_tax + $row->order_shipping_tax;

		if (isset($row->voucher_discount) === false)
		{
			$row->voucher_discount = 0;
		}

		$Total_discount = $row->coupon_discount + $row->order_discount + $row->special_discount + $row->tax_after_discount + $row->voucher_discount;

		// For Payment and Shipping Extra Fields
		if (strpos($ReceiptTemplate, '{payment_extrafields}') !== false)
		{
			$PaymentExtrafields = $this->_producthelper->getPaymentandShippingExtrafields($row, 18);

			if ($PaymentExtrafields == "")
			{
				$ReceiptTemplate = str_replace("{payment_extrafields_lbl}", "", $ReceiptTemplate);
				$ReceiptTemplate = str_replace("{payment_extrafields}", "", $ReceiptTemplate);
			}
			else
			{
				$ReceiptTemplate = str_replace("{payment_extrafields_lbl}", JText::_("COM_REDSHOP_ORDER_PAYMENT_EXTRA_FILEDS"), $ReceiptTemplate);
				$ReceiptTemplate = str_replace("{payment_extrafields}", $PaymentExtrafields, $ReceiptTemplate);
			}
		}

		if (strpos($ReceiptTemplate, '{shipping_extrafields}') !== false)
		{
			$ShippingExtrafields = $this->_producthelper->getPaymentandShippingExtrafields($row, 19);

			if ($ShippingExtrafields == "")
			{
				$ReceiptTemplate = str_replace("{shipping_extrafields_lbl}", "", $ReceiptTemplate);
				$ReceiptTemplate = str_replace("{shipping_extrafields}", "", $ReceiptTemplate);
			}
			else
			{
				$ReceiptTemplate = str_replace("{shipping_extrafields_lbl}", JText::_("COM_REDSHOP_ORDER_SHIPPING_EXTRA_FILEDS"), $ReceiptTemplate);
				$ReceiptTemplate = str_replace("{shipping_extrafields}", $ShippingExtrafields, $ReceiptTemplate);
			}
		}

		// End
		$ReceiptTemplate = $this->replaceShippingMethod($row, $ReceiptTemplate);

		if (!Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
		{
			$total_for_discount = $subtotal_excl_vat;
		}
		else
		{
			$total_for_discount = $row->order_subtotal;
		}

		$ReceiptTemplate = $this->replaceLabel($ReceiptTemplate);
		$search[]        = "{order_subtotal}";
		$chktag          = $this->_producthelper->getApplyVatOrNot($ReceiptTemplate);

		if (!empty($chktag))
		{
			$replace[] = $this->_producthelper->getProductFormattedPrice($row->order_total);
		}
		else
		{
			$replace[] = $this->_producthelper->getProductFormattedPrice($total_excl_vat);
		}

		$search[]  = "{subtotal_excl_vat}";
		$replace[] = $this->_producthelper->getProductFormattedPrice($total_excl_vat);
		$search[]  = "{product_subtotal}";

		if (!empty($chktag))
		{
			$replace[] = $this->_producthelper->getProductFormattedPrice($row->order_subtotal);
		}
		else
		{
			$replace[] = $this->_producthelper->getProductFormattedPrice($subtotal_excl_vat);
		}

		$search[]   = "{product_subtotal_excl_vat}";
		$replace[]  = $this->_producthelper->getProductFormattedPrice($subtotal_excl_vat);
		$search[]   = "{order_subtotal_excl_vat}";
		$replace[]  = $this->_producthelper->getProductFormattedPrice($total_excl_vat);
		$search[]   = "{order_number_lbl}";
		$replace[]  = JText::_('COM_REDSHOP_ORDER_NUMBER_LBL');
		$search[]   = "{order_number}";
		$replace[]  = $row->order_number;
		$search  [] = "{special_discount}";
		$replace [] = $row->special_discount . '%';
		$search  [] = "{special_discount_amount}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($row->special_discount_amount);
		$search[]   = "{special_discount_lbl}";
		$replace[]  = JText::_('COM_REDSHOP_SPECIAL_DISCOUNT');

		$search[]   = "{shipping_address_info_lbl}";
		$replace[]  = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION');

		$search[]   = "{order_detail_link}";
		$replace[]  = "<a href='" . $orderdetailurl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		$dpData = "";

		if (count($downloadProducts) > 0)
		{
			$dpData .= "<table>";

			for ($d = 0, $dn = count($downloadProducts); $d < $dn; $d++)
			{
				$g                = $d + 1;
				$downloadProduct  = $downloadProducts[$d];
				$downloadfilename = substr(basename($downloadProduct->file_name), 11);
				$downloadToken    = $downloadProduct->download_id;
				$product_name     = $downloadProduct->product_name;
				$mailtoken        = $product_name . ": <a href='" . JURI::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $downloadToken . "'>" . $downloadfilename . "</a>";

				$dpData .= "</tr>";
				$dpData .= "<td>(" . $g . ") " . $mailtoken . "</td>";
				$dpData .= "</tr>";
			}

			$dpData .= "</table>";
		}

		if ($row->order_status == "C" && $row->order_payment_status == "Paid")
		{
			$search  [] = "{download_token}";
			$replace [] = $dpData;

			$search  [] = "{download_token_lbl}";

			if ($dpData != "")
			{
				$replace [] = JText::_('COM_REDSHOP_DOWNLOAD_TOKEN');
			}
			else
			{
				$replace [] = "";
			}
		}
		else
		{
			$search  [] = "{download_token}";
			$replace [] = "";
			$search  [] = "{download_token_lbl}";
			$replace [] = "";
		}

		$issplitdisplay  = "";
		$issplitdisplay2 = "";

		if ((strpos($ReceiptTemplate, "{discount_denotation}") !== false || strpos($ReceiptTemplate, "{shipping_denotation}") !== false) && ($Total_discount != 0 || $row->order_shipping != 0))
		{
			$search  [] = "{denotation_label}";
			$replace [] = JText::_('COM_REDSHOP_DENOTATION_TXT');
		}
		else
		{
			$search  [] = "{denotation_label}";
			$replace [] = "";

		}

		$search  [] = "{discount_denotation}";

		if (strpos($ReceiptTemplate, "{discount_excl_vat}") !== false)
		{
			$replace [] = "*";
		}
		else
		{
			$replace [] = "";
		}

		$search  [] = "{shipping_denotation}";

		if (strpos($ReceiptTemplate, "{shipping_excl_vat}") !== false)
		{
			$replace [] = "*";
		}
		else
		{
			$replace [] = "";
		}

		$search[] = "{payment_status}";

		if (trim($row->order_payment_status) == 'Paid')
		{
			$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_PAID');
		}
		elseif (trim($row->order_payment_status) == 'Unpaid')
		{
			$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID');
		}
		elseif (trim($row->order_payment_status) == 'Partial Paid')
		{
			$orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID');
		}
		else
		{
			$orderPaymentStatus = $row->order_payment_status;
		}

		$replace[] = $orderPaymentStatus . " " . JRequest::getVar('order_payment_log') . $issplitdisplay . $issplitdisplay2;
		$search[]  = "{order_payment_status}";
		$replace[] = $orderPaymentStatus . " " . JRequest::getVar('order_payment_log') . $issplitdisplay . $issplitdisplay2;

		$search  [] = "{order_total}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($row->order_total);
		$search  [] = "{total_excl_vat}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($total_excl_vat);
		$search  [] = "{sub_total_vat}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($sub_total_vat);
		$search  [] = "{order_id}";
		$replace [] = $order_id;
		$search  [] = "{discount_denotation}";
		$replace [] = "*";

		$arr_discount_type = array();
		$arr_discount      = explode('@', $row->discount_type);
		$discount_type     = '';

		for ($d = 0, $dn = count($arr_discount); $d < $dn; $d++)
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

		$search[]  = "{discount_type}";
		$replace[] = $discount_type;

		$search  [] = "{discount_excl_vat}";
		$replace [] = $this->_producthelper->getProductFormattedPrice($row->order_discount - $row->order_discount_vat);
		$search  [] = "{order_status}";
		$replace [] = $OrderStatus;
		$search  [] = "{order_id_lbl}";
		$replace [] = JText::_('COM_REDSHOP_ORDER_ID_LBL');
		$search  [] = "{order_date}";
		$replace [] = $redconfig->convertDateFormat($row->cdate);
		$search  [] = "{customer_note}";
		$replace [] = $row->customer_note;
		$search  [] = "{customer_message}";
		$replace [] = $row->customer_message;
		$search  [] = "{referral_code}";
		$replace [] = $row->referral_code;

        RedshopHelperPayment::loadLanguages();

		$search  [] = "{payment_method}";
		$replace [] = JText::_("$paymentmethod->order_payment_name");

		$txtextra_info = '';

		// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
		$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($paymentmethod_detail->element);

		if ($isBankTransferPaymentType)
		{
			$paymentpath   = JPATH_SITE . '/plugins/redshop_payment/'
				. $paymentmethod_detail->element . '/' . $paymentmethod_detail->element . '.xml';
			$paymentparams = new JRegistry($paymentmethod_detail->params);
			$txtextra_info = $paymentparams->get('txtextra_info', '');
		}

		$search  [] = "{payment_extrainfo}";
		$replace [] = $txtextra_info;

		// Set order transaction fee tag
		$orderTransFeeLabel = '';
		$orderTransFee      = '';

		if ($paymentmethod->order_transfee > 0)
		{
			$orderTransFeeLabel = JText::_('COM_REDSHOP_ORDER_TRANSACTION_FEE_LABEL');
			$orderTransFee      = $this->_producthelper->getProductFormattedPrice($paymentmethod->order_transfee);
		}

		$search [] = "{order_transfee_label}";
		$replace[] = $orderTransFeeLabel;

		$search [] = "{order_transfee}";
		$replace[] = $orderTransFee;

		$search [] = "{order_total_incl_transfee}";
		$replace[] = $this->_producthelper->getProductFormattedPrice(
			$paymentmethod->order_transfee + $row->order_total
		);

		if (JRequest::getVar('order_delivery'))
		{
			$search  [] = "{delivery_time_lbl}";
			$replace [] = JText::_('COM_REDSHOP_DELIVERY_TIME');
		}
		else
		{
			$search  [] = "{delivery_time_lbl}";
			$replace [] = " ";
		}

		$search  [] = "{delivery_time}";
		$replace [] = JRequest::getVar('order_delivery');
		$search  [] = "{without_vat}";
		$replace [] = '';
		$search  [] = "{with_vat}";
		$replace [] = '';

		if (strpos($ReceiptTemplate, '{order_detail_link_lbl}') !== false)
		{
			$search [] = "{order_detail_link_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LINK_LBL');
		}

		if (strpos($ReceiptTemplate, '{product_subtotal_lbl}') !== false)
		{
			$search [] = "{product_subtotal_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL');
		}

		if (strpos($ReceiptTemplate, '{product_subtotal_excl_vat_lbl}') !== false)
		{
			$search [] = "{product_subtotal_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL');
		}

		if (strpos($ReceiptTemplate, '{shipping_with_vat_lbl}') !== false)
		{
			$search [] = "{shipping_with_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL');
		}

		if (strpos($ReceiptTemplate, '{shipping_excl_vat_lbl}') !== false)
		{
			$search [] = "{shipping_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL');
		}

		if (strpos($ReceiptTemplate, '{product_price_excl_lbl}') !== false)
		{
			$search [] = "{product_price_excl_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_PRICE_EXCL_LBL');
		}

		$billingaddresses  = RedshopHelperOrder::getOrderBillingUserInfo($order_id);
		$shippingaddresses = RedshopHelperOrder::getOrderShippingUserInfo($order_id);

		$search [] = "{requisition_number}";
		$replace[] = ($row->requisition_number) ? $row->requisition_number : "N/A";

		$search [] = "{requisition_number_lbl}";
		$replace[] = JText::_('COM_REDSHOP_REQUISITION_NUMBER');

        $search [] = "{product_attribute_calculated_price}";
        $replace[] = "";

		$ReceiptTemplate = $this->replaceBillingAddress($ReceiptTemplate, $billingaddresses, $sendmail);
		$ReceiptTemplate = $this->replaceShippingAddress($ReceiptTemplate, $shippingaddresses, $sendmail);

		$message = str_replace($search, $replace, $ReceiptTemplate);
		$message = $this->replacePayment($message, $row->payment_discount, 0, $row->payment_oprand);
		$message = $this->replaceDiscount($message, $row->order_discount, $total_for_discount);
		$message = $this->replaceTax($message, $row->order_tax + $row->order_shipping_tax, $row->tax_after_discount, 1);

		return $message;
	}

	/**
	 * Method for render cart.
	 *
	 * @param   array  $cart  Cart data
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::generateCartOutput() instead.
	 */
	public function makeCart_output($cart)
	{
		return RedshopHelperCart::generateCartOutput($cart);
	}

	/**
	 * Method for get module cart parameters
	 *
	 * @return array
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function GetCartParameters()
	{
		return RedshopHelperCart::getCartParameters();
	}

	public function modifyCart($cartArr, $user_id)
	{
		$cartArr['user_id'] = $user_id;
		$idx = 0;

		if (isset($cartArr['idx']))
		{
			$idx = (int) $cartArr['idx'];
		}

		for ($i = 0; $i < $idx; $i++)
		{
			if (!isset($cartArr[$i]['giftcard_id'])
				|| (isset($cartArr[$i]['giftcard_id']) && $cartArr[$i]['giftcard_id'] <= 0))
			{
				$product_id   = $cartArr[$i]['product_id'];
				$quantity     = $cartArr[$i]['quantity'];
				$product      = RedshopHelperProduct::getProductById($product_id);
				$hasAttribute = isset($cartArr[$i]['cart_attribute']) ? true : false;

				// Attribute price
				$price = 0;

				if (!isset($cartArr['quotation']))
				{
					$cartArr['quotation'] = 0;
				}

				if ((Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || $cartArr['quotation'] == 1) && !$hasAttribute)
				{
					$price = $cartArr[$i]['product_price_excl_vat'];
				}

				if ($product->use_discount_calc)
				{
					$price = $cartArr[$i]['discount_calc_price'];
				}

				// Only set price without vat for accessories as product
				$accessoryAsProdutWithoutVat = false;

				if (isset($cartArr['AccessoryAsProduct']))
				{
					// Accessory price fix during update
					$accessoryAsProdut = RedshopHelperAccessory::getAccessoryAsProduct($cartArr['AccessoryAsProduct']);

					if (isset($accessoryAsProdut->accessory)
						&& isset($accessoryAsProdut->accessory[$cartArr[$i]['product_id']])
						&& isset($cartArr[$i]['accessoryAsProductEligible']))
					{
						$accessoryAsProdutWithoutVat = '{without_vat}';

						$accessoryPrice                        = (float) $accessoryAsProdut->accessory[$cartArr[$i]['product_id']]->newaccessory_price;
						$price                                 = $this->_producthelper->productPriceRound($accessoryPrice);
						$cartArr[$i]['product_price_excl_vat'] = $this->_producthelper->productPriceRound($accessoryPrice);
					}
				}

				$retAttArr = $this->_producthelper->makeAttributeCart(
					isset($cartArr[$i]['cart_attribute']) ? $cartArr[$i]['cart_attribute'] : array(),
					(int) $product->product_id,
					$user_id,
					$price,
					$quantity,
					$accessoryAsProdutWithoutVat
				);

				$accessoryAsProductZero = (count($retAttArr[8]) == 0 && $price == 0 && $accessoryAsProdutWithoutVat);

				// Product + attribute (price)
				$getproprice = ($accessoryAsProductZero) ? 0 : $retAttArr[1];

				// Product + attribute (VAT)
				$getprotax                  = ($accessoryAsProductZero) ? 0 : $retAttArr[2];
				$product_old_price_excl_vat = ($accessoryAsProductZero) ? 0 : $retAttArr[5];

				// Accessory calculation
				$retAccArr = $this->_producthelper->makeAccessoryCart(
					isset($cartArr[$i]['cart_accessory']) ? $cartArr[$i]['cart_accessory'] : array(),
					$product->product_id,
					$user_id
				);

				// Accessory + attribute (price)
				$getaccprice = $retAccArr[1];

				// Accessory + attribute (VAT)
				$getacctax = $retAccArr[2];
				$product_old_price_excl_vat += $retAccArr[1];

				// ADD WRAPPER PRICE
				$wrapper_vat   = 0;
				$wrapper_price = 0;

				if (array_key_exists('wrapper_id', $cartArr[$i]))
				{
					if ($cartArr[$i]['wrapper_id'])
					{
						$wrapperArr                 = $this->getWrapperPriceArr(array('product_id' => $cartArr[$i]['product_id'], 'wrapper_id' => $cartArr[$i]['wrapper_id']));
						$wrapper_vat                = $wrapperArr['wrapper_vat'];
						$wrapper_price              = $wrapperArr['wrapper_price'];
						$product_old_price_excl_vat += $wrapper_price;
					}
				}

				$product_price          = $getaccprice + $getproprice + $getprotax + $getacctax + $wrapper_price + $wrapper_vat;
				$product_vat            = ($getprotax + $getacctax + $wrapper_vat);
				$product_price_excl_vat = ($getproprice + $getaccprice + $wrapper_price);

				if ($product->product_type == 'subscription')
				{
					if (isset($cartArr[$i]['subscription_id']) && $cartArr[$i]['subscription_id'] != "")
					{
						$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($product_id, $cartArr[$i]['subscription_id']);
						$subscription_vat    = 0;
						$subscription_price  = $subscription_detail->subscription_price;

						if ($subscription_price)
						{
							$subscription_vat = $this->_producthelper->getProductTax($this->data->product_id, $subscription_price);
						}

						$product_vat += $subscription_vat;
						$product_price = $product_price + $subscription_price + $subscription_vat;
						$product_price_excl_vat += $subscription_price;
						$product_old_price_excl_vat += $subscription_price + $subscription_vat;
					}
					else
					{
						return;
					}
				}

				// Set product price
				if ($product_price < 0)
				{
					$product_price = 0;
				}

				$cartArr[$i]['product_old_price_excl_vat'] = $product_old_price_excl_vat;
				$cartArr[$i]['product_price_excl_vat']     = $product_price_excl_vat;
				$cartArr[$i]['product_vat']                = $product_vat;
				$cartArr[$i]['product_price']              = $product_price;

				JPluginHelper::importPlugin('redshop_product');
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger('onBeforeLoginCartSession', array(&$cartArr, $i));
			}
		}

		unset($cartArr[$idx]);

		return $cartArr;
	}

	public function replaceShippingBoxTemplate($box_template_desc = "", $shipping_box_post_id = 0)
	{
		// Get shipping boxes HTML
		$shippingBoxes = $this->_shippinghelper->getShippingBox();

		$box_template_desc = str_replace("{shipping_box_heading}", JText::_('COM_REDSHOP_SHIPPING_BOXES'), $box_template_desc);

		if (count($shippingBoxes) == 1 || (count($shippingBoxes) > 0 && $shipping_box_post_id == 0))
		{
			$shipping_box_post_id = $shippingBoxes[0]->shipping_box_id;
		}

		$shipping_box_list = JText::_('COM_REDSHOP_NO_SHIPPING_BOX');

		if (count($shippingBoxes) > 0)
		{
			$shipping_box_list = "";

			for ($i = 0, $in = count($shippingBoxes); $i < $in; $i++)
			{
				$shipping_box_id = $shippingBoxes[$i]->shipping_box_id;

				// Previous priority
				if ($i > 0)
				{
					$shipping_box_priority_pre = $shippingBoxes[$i - 1]->shipping_box_priority;
				}

				// Current priority
				$shipping_box_priority = $shippingBoxes[$i]->shipping_box_priority;
				$checked               = ($shipping_box_post_id == $shipping_box_id) ? "checked='checked'" : "";

				if ($i == 0 || ($shipping_box_priority == $shipping_box_priority_pre))
				{
					$shipping_box_list .= "<div class='radio'><label class=\"radio\" for='shipping_box_id" . $shipping_box_id . "'><input " . $checked . " type='radio' id='shipping_box_id" . $shipping_box_id . "' name='shipping_box_id'  onclick='javascript:onestepCheckoutProcess(this.name,\'\');' value='" . $shipping_box_id . "' />";
					$shipping_box_list .= "" . $shippingBoxes[$i]->shipping_box_name . "</label></div>";
				}
			}
		}

		$box_template_desc = str_replace("{shipping_box_list}", $shipping_box_list, $box_template_desc);
		$style             = 'none';

		$shippingmethod = $this->_order_functions->getShippingMethodInfo();

		for ($s = 0, $sn = count($shippingmethod); $s < $sn; $s++)
		{
			if ($shippingmethod[$s]->element == 'bring' || $shippingmethod[$s]->element == 'ups' || $shippingmethod[$s]->element == 'uspsv4')
			{
				$style = 'block';
			}
		}

		if (count($shippingBoxes) <= 1 || count($shippingmethod) <= 1)
		{
			$style = 'none';
		}

		$box_template_desc = "<div style='display:$style;'>" . $box_template_desc . "</div>";

		return $box_template_desc;
	}

	/**
	 * Method for get GLS Location
	 *
	 * @param   integer $users_info_id User Infor ID
	 * @param   string  $classname    Class name
	 * @param   integer $shop_id      ID of shop
	 *
	 * @return string
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 */
	public function getGLSLocation($users_info_id, $classname, $shop_id = 0)
	{
		return RedshopHelperShipping::getGLSLocation($users_info_id, $classname, $shop_id);
	}

	public function replaceShippingTemplate($template_desc = "", $shipping_rate_id = 0, $shipping_box_post_id = 0, $user_id = 0, $users_info_id = 0, $ordertotal = 0, $order_subtotal = 0)
	{
		$shippingmethod       = $this->_order_functions->getShippingMethodInfo();
		$adminpath            = JPATH_ADMINISTRATOR . '/components/com_redshop';
		$rateExist            = 0;
		$d['user_id']         = $user_id;
		$d['users_info_id']   = $users_info_id;
		$d['shipping_box_id'] = $shipping_box_post_id;
		$d['ordertotal']      = $ordertotal;
		$d['order_subtotal']  = $order_subtotal;
		$template_desc        = str_replace("{shipping_heading}", JText::_('COM_REDSHOP_SHIPPING_METHOD'), $template_desc);
		$extrafield_total     = "";

		if (strpos($template_desc, "{shipping_method_loop_start}") !== false && strpos($template_desc, "{shipping_method_loop_end}") !== false)
		{
			$template1       = explode("{shipping_method_loop_start}", $template_desc);
			$template1       = explode("{shipping_method_loop_end}", $template1[1]);
			$template_middle = $template1[0];

			$template_rate_middle = "";

			if (strpos($template_middle, "{shipping_rate_loop_start}") !== false && strpos($template_middle, "{shipping_rate_loop_end}") !== false)
			{
				$template1            = explode("{shipping_rate_loop_start}", $template_middle);
				$template1            = explode("{shipping_rate_loop_end}", $template1[1]);
				$template_rate_middle = $template1[0];
			}

			$oneShipping = false;

			if (count($shippingmethod) == 1)
			{
				$oneShipping = true;
			}

			$rate_data = "";

			if ($template_middle != "" && count($shippingmethod) > 0)
			{
				JPluginHelper::importPlugin('redshop_shipping');
				$dispatcher   = JDispatcher::getInstance();
				$shippingrate = $dispatcher->trigger('onListRates', array(&$d));

				for ($s = 0, $sn = count($shippingmethod); $s < $sn; $s++)
				{
					if (isset($shippingrate[$s]) === false)
					{
						continue;
					}

					$rate = $shippingrate[$s];

					if (count($rate) > 0)
					{
						if (empty($shipping_rate_id))
						{
							$shipping_rate_id = $rate[0]->value;
						}

						$rs        = $shippingmethod[$s];
						$classname = $rs->element;
						$rate_data .= $template_middle;
						$rate_data = str_replace("{shipping_method_title}", JText::_($rs->name), $rate_data);

						if ($template_rate_middle != "")
						{
							$data         = "";
							$mainlocation = "";

							for ($i = 0, $in = count($rate); $i < $in; $i++)
							{
								$glsLocation = '';
								$data .= $template_rate_middle;

								$displayrate = (trim($rate[$i]->rate) > 0) ? " (" . $this->_producthelper->getProductFormattedPrice(trim($rate[$i]->rate)) . " )" : "";

								$checked = ($rateExist == 0 || $shipping_rate_id == $rate[$i]->value) ? "checked" : "";

								if ($checked == "checked")
								{
									$shipping_rate_id = $rate[$i]->value;
								}

								if ($classname == 'default_shipping_gls')
								{
									$glsLocation = RedshopHelperShipping::getGLSLocation($users_info_id, $classname);
									$style       = ($checked != "checked") ? "style='display:none;'" : "style='display:block;'";

									if ($glsLocation)
									{
										$glsLocation = "<div $style id='rs_glslocationId'>" . $glsLocation . "</div>";
									}
								}

								$shipping_rate_name = '<label class="radio inline" for="shipping_rate_id_' . $shippingmethod[$s]->extension_id . '_' . $i . '"><input type="radio" id="shipping_rate_id_'
									. $shippingmethod[$s]->extension_id . '_' . $i . '" name="shipping_rate_id" value="'
									. $rate[$i]->value . '" '
									. $checked
									. ' onclick="javascript:onestepCheckoutProcess(this.name,\'' . $classname . '\');">'
									. '' . html_entity_decode($rate[$i]->text) . '</label>';

								$shipping_rate_short_desc = '';

								if (isset($rate[$i]->shortdesc) === true)
								{
									$shipping_rate_short_desc = html_entity_decode($rate[$i]->shortdesc);
								}

								$shipping_rate_desc = '';

								if (isset($rate[$i]->longdesc) === true)
								{
									$shipping_rate_desc = html_entity_decode($rate[$i]->longdesc);
								}

								$rateExist++;
								$data = str_replace("{shipping_rate_name}", $shipping_rate_name, $data);
								$data = str_replace("{shipping_rate_short_desc}", $shipping_rate_short_desc, $data);
								$data = str_replace("{shipping_rate_desc}", $shipping_rate_desc, $data);
								$data = str_replace("{shipping_rate}", $displayrate, $data);

								if (strpos($data, "{shipping_location}") !== false)
								{
									$shippinglocation = $this->_order_functions->getshippinglocationinfo($rate[$i]->text);

									for ($k = 0, $kn = count($shippinglocation); $k < $kn; $k++)
									{
										if ($shippinglocation[$k] != '')
										{
											$mainlocation = $shippinglocation[$k]->shipping_location_info;
										}
									}

									$data = str_replace("{shipping_location}", $mainlocation, $data);
								}

								$data = str_replace("{gls_shipping_location}", $glsLocation, $data);
							}

							$rate_data = str_replace("{shipping_rate_loop_start}", "", $rate_data);
							$rate_data = str_replace("{shipping_rate_loop_end}", "", $rate_data);
							$rate_data = str_replace($template_rate_middle, $data, $rate_data);
						}
					}

					if (strpos($rate_data, "{shipping_extrafields}") !== false)
					{
						$extraField         = extraField::getInstance();
						$paymentparams_new  = new JRegistry($shippingmethod[$s]->params);
						$extrafield_payment = $paymentparams_new->get('extrafield_shipping');

						$extrafield_hidden  = "";

						if (count($extrafield_payment) > 0)
						{
							for ($ui = 0; $ui < count($extrafield_payment); $ui++)
							{
								$productUserFields = $extraField->list_all_user_fields($extrafield_payment[$ui], 19, '', 0, 0, 0);
								$extrafield_total .= $productUserFields[0] . " " . $productUserFields[1] . "<br>";
								$extrafield_hidden .= "<input type='hidden' name='extrafields[]' value='" . $extrafield_payment[$ui] . "'>";
							}

							$rate_data = str_replace("{shipping_extrafields}", "<div id='extrafield_shipping'></div>", $rate_data);
						}
						else
						{
							$rate_data = str_replace("{shipping_extrafields}", "", $rate_data);
						}
					}
				}
			}

			$template_desc = str_replace("{shipping_method_loop_start}", "", $template_desc);
			$template_desc = str_replace("{shipping_method_loop_end}", "", $template_desc);
			$template_desc = str_replace($template_middle, $rate_data, $template_desc);
		}

		if ($rateExist == 0)
		{
			$errorMSG = '';

			if (count($shippingmethod) > 0)
			{
				$errorMSG = $this->_shippinghelper->getShippingRateError($d);
			}

			$template_desc = "<div></div>";
		}
		elseif ($rateExist == 1 && $extrafield_total == "" && $classname != "default_shipping_gls")
		{
			$template_desc = "<div style='display:none;'>" . $template_desc . "</div>";
		}

		$returnarr = array("template_desc" => $template_desc, "shipping_rate_id" => $shipping_rate_id);

		return $returnarr;
	}

	/**
	 * Display credit card form based on payment method
	 *
	 * @param   integer  $payment_method_id  Payment Method ID for which form needs to be prepare
	 *
	 * @return  string     Credit Card form display data in HTML
	 */
	public function replaceCreditCardInformation($payment_method_id = 0)
	{
		if (empty($payment_method_id))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::_('COM_REDSHOP_PAYMENT_NO_CREDIT_CARDS_PLUGIN_LIST_FOUND'),
				'error'
			);

			return '';
		}

		$paymentmethod = $this->_order_functions->getPaymentMethodInfo($payment_method_id);
		$paymentmethod = $paymentmethod[0];

		$cardinfo = "";

		if (file_exists(JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod->element . '/' . $paymentmethod->element . '.php'))
		{
			$paymentparams = new JRegistry($paymentmethod->params);
			$acceptedCredictCard = $paymentparams->get("accepted_credict_card", array());

			if ($paymentparams->get('is_creditcard', 0)
				&& !empty($acceptedCredictCard))
			{
				$cardinfo = RedshopLayoutHelper::render(
						'order.payment.creditcard',
						array(
							'pluginParams' => $paymentparams,
						)
					);
			}
			else
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_PAYMENT_CREDIT_CARDS_NOT_FOUND'), 'error');
			}
		}

		return $cardinfo;
	}

	public function replacePaymentTemplate($template_desc = "", $payment_method_id = 0, $is_company = 0, $eanNumber = 0)
	{
		$ccdata = $this->_session->get('ccdata');

		$rsUserhelper = rsUserHelper::getInstance();
		$url          = JURI::base();
		$user         = JFactory::getUser();
		$user_id      = $user->id;

		$cc_list                 = array();
		$cc_list['VISA']         = new stdClass;
		$cc_list['VISA']->img    = 'visa.jpg';

		$cc_list['MC']           = new stdClass;
		$cc_list['MC']->img      = 'master.jpg';

		$cc_list['amex']         = new stdClass;
		$cc_list['amex']->img    = 'blue.jpg';

		$cc_list['maestro']      = new stdClass;
		$cc_list['maestro']->img = 'mastero.jpg';

		$cc_list['jcb']          = new stdClass;
		$cc_list['jcb']->img     = 'jcb.jpg';

		$cc_list['diners']       = new stdClass;
		$cc_list['diners']->img  = 'dinnersclub.jpg';

		$montharr   = array();
		$montharr[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_MONTH'));
		$montharr[] = JHTML::_('select.option', '01', JText::_('COM_REDSHOP_JAN'));
		$montharr[] = JHTML::_('select.option', '02', JText::_('COM_REDSHOP_FEB'));
		$montharr[] = JHTML::_('select.option', '03', JText::_('COM_REDSHOP_MAR'));
		$montharr[] = JHTML::_('select.option', '04', JText::_('COM_REDSHOP_APR'));
		$montharr[] = JHTML::_('select.option', '05', JText::_('COM_REDSHOP_MAY'));
		$montharr[] = JHTML::_('select.option', '06', JText::_('COM_REDSHOP_JUN'));
		$montharr[] = JHTML::_('select.option', '07', JText::_('COM_REDSHOP_JUL'));
		$montharr[] = JHTML::_('select.option', '08', JText::_('COM_REDSHOP_AUG'));
		$montharr[] = JHTML::_('select.option', '09', JText::_('COM_REDSHOP_SEP'));
		$montharr[] = JHTML::_('select.option', '10', JText::_('COM_REDSHOP_OCT'));
		$montharr[] = JHTML::_('select.option', '11', JText::_('COM_REDSHOP_NOV'));
		$montharr[] = JHTML::_('select.option', '12', JText::_('COM_REDSHOP_DEC'));

		$template_desc = str_replace("{payment_heading}", JText::_('COM_REDSHOP_PAYMENT_METHOD'), $template_desc);

		if (strpos($template_desc, "{split_payment}") !== false)
		{
			$template_desc = str_replace("{split_payment}", "", $template_desc);
		}

		$paymentMethods = RedshopHelperPayment::info();

		if (strpos($template_desc, "{payment_loop_start}") !== false && strpos($template_desc, "{payment_loop_end}") !== false)
		{
			$template1       = explode("{payment_loop_start}", $template_desc);
			$template1       = explode("{payment_loop_end}", $template1[1]);
			$template_middle = $template1[0];
			$shopperGroupId  = $rsUserhelper->getShopperGroup($user_id);
			$payment_display = "";
			$flag            = false;

			// Filter payment gateways array for shopperGroups
			$paymentMethods = array_filter(
				$paymentMethods,
				function ($paymentMethod) use ($shopperGroupId)
				{
					$paymentFilePath = JPATH_SITE
									. '/plugins/redshop_payment/'
									. $paymentMethod->name . '/' . $paymentMethod->name . '.php';

					if (!file_exists($paymentFilePath))
					{
						return false;
					}

					$shopperGroups  = $paymentMethod->params->get('shopper_group_id', array());

					if (!is_array($shopperGroups))
					{
						$shopperGroups = array($shopperGroups);
					}

					JArrayHelper::toInteger($shopperGroups);

					if (in_array((int) $shopperGroupId, $shopperGroups) || (!isset($shopperGroups[0]) || 0 == $shopperGroups[0]))
					{
						return true;
					}

					return false;
				}
			);

			$totalPaymentMethod = count($paymentMethods);

			if ($totalPaymentMethod > 0)
			{
				foreach ($paymentMethods as $p => $oneMethod)
				{
					$cardinfo        = "";
					$display_payment = "";
					$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $oneMethod->name . '/' . $oneMethod->name . '.php';

					include_once $paymentpath;

					$private_person = $oneMethod->params->get('private_person', '');
					$business       = $oneMethod->params->get('business', '');
					$is_creditcard  = $oneMethod->params->get('is_creditcard', 0);

					$checked = '';
					$payment_chcked_class = '';

					if ($payment_method_id === $oneMethod->name || $totalPaymentMethod <= 1)
					{
						$checked = "checked";
						$payment_chcked_class = "paymentgtwchecked";
					}

					$payment_radio_output = '<div id="' . $oneMethod->name . '" class="' . $payment_chcked_class . '"><label class="radio" for="' . $oneMethod->name . $p . '"><input  type="radio" name="payment_method_id" id="' . $oneMethod->name . $p . '" value="' . $oneMethod->name . '" ' . $checked . ' onclick="javascript:onestepCheckoutProcess(this.name,\'\');" />' . JText::_('PLG_' . strtoupper($oneMethod->name)) . '</label></div>';

					$is_subscription = false;

					// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
					$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($oneMethod->name);

					if ($oneMethod->name == 'rs_payment_eantransfer' || $isBankTransferPaymentType)
					{
						if ($is_company == 0 && $private_person == 1)
						{
							$display_payment = $payment_radio_output;
							$flag = true;
						}
						else
						{
							if ($is_company == 1 && $business == 1 && ($oneMethod->name != 'rs_payment_eantransfer' || ($oneMethod->name == 'rs_payment_eantransfer' && $eanNumber != 0)))
							{
								$display_payment = $payment_radio_output;
								$flag = true;
							}
						}
					}
					elseif ($is_subscription)
					{
						$display_payment = '<label class="radio" for="' . $oneMethod->name . $p . '"><input id="' . $oneMethod->name . $p . '" type="radio" name="payment_method_id" value="'
							. $oneMethod->name . '" '
							. $checked . ' onclick="javascript:onestepCheckoutProcess(this.name);" />'
							. '' . JText::_($oneMethod->name) . '</label><br>';
						$display_payment .= '<table><tr><td>'
							. JText::_('COM_REDSHOP_SUBSCRIPTION_PLAN')
							. '</td><td>' . $this->getSubscriptionPlans()
							. '<td></tr><table>';
					}
					else
					{
						$display_payment = $payment_radio_output;
						$flag = true;
					}

					if ($is_creditcard)
					{
						$cardinfo = '<div id="divcardinfo_' . $oneMethod->name . '">';

						$cart = JFactory::getSession()->get('cart');

						if ($checked != "" && Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE')  && $cart['total'] > 0)
						{
							$cardinfo .= $this->replaceCreditCardInformation($oneMethod->name);
						}

						$cardinfo .= '</div>';
					}

					$payment_display .= $template_middle;
					$payment_display = str_replace("{payment_method_name}", $display_payment, $payment_display);
					$payment_display = str_replace("{creditcard_information}", $cardinfo, $payment_display);

					if (strpos($payment_display, "{payment_extrafields}") !== false)
					{
						$paymentExtraFieldsHtml = '';

						if ($checked != '')
						{
							$layoutFile = new JLayoutFile('order.payment.extrafields');

							// Append plugin JLayout path to improve view based on plugin if needed.
							$layoutFile->addIncludePath(JPATH_SITE . '/plugins/' . $oneMethod->type . '/' . $oneMethod->name . '/layouts');
							$paymentExtraFieldsHtml =  $layoutFile->render(array('plugin' => $oneMethod));
						}

						$payment_display = str_replace(
							'{payment_extrafields}',
							'<div class="extrafield_payment">' . $paymentExtraFieldsHtml . '</div>',
							$payment_display
						);
					}
				}
			}

			$template_desc = str_replace("{payment_loop_start}", "", $template_desc);
			$template_desc = str_replace("{payment_loop_end}", "", $template_desc);
			$template_desc = str_replace($template_middle, $payment_display, $template_desc);
		}

		if (count($paymentMethods) == 1 && $is_creditcard == "0")
		{
			$template_desc = "<div style='display:none;'>" . $template_desc . "</div>";
		}

		return $template_desc;
	}

	public function replaceTermsConditions($template_desc = "", $Itemid = 1)
	{
		if (strpos($template_desc, "{terms_and_conditions") !== false)
		{
			$user    = JFactory::getUser();
			$session = JFactory::getSession();
			$auth    = $session->get('auth');
			$list    = array();

			if ($user->id)
			{
				$query = "SELECT u.* FROM " . $this->_table_prefix . "users_info AS u "
					. "WHERE u.user_id='" . $user->id . "' "
					. "AND address_type='BT' ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
			}
			elseif (isset($auth['users_info_id']) && $auth['users_info_id'] > 0)
			{
				$query = "SELECT u.* FROM " . $this->_table_prefix . "users_info AS u "
					. "WHERE u.users_info_id='" . $auth['users_info_id'] . "' "
					. "AND address_type='BT' ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
			}

			$terms_left_final = "";

			if (strpos($template_desc, "{terms_and_conditions:") !== false && strpos($template_desc, "}") !== false)
			{
				$terms_left_one   = explode("{terms_and_conditions:", $template_desc);
				$terms_left_two   = explode("}", $terms_left_one[1]);
				$terms_left_three = explode(":", $terms_left_two[0]);
				$terms_left_final = $terms_left_three[0];
			}

			$finaltag       = ($terms_left_final != "") ? "{terms_and_conditions:$terms_left_final}" : "{terms_and_conditions}";
			$termscondition = '';

			if (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 0 || (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 1 && ((count($list) > 0 && $list->accept_terms_conditions == 0) || count($list) == 0)))
			{
				$finalwidth  = "500";
				$finalheight = "450";

				if ($terms_left_final != "")
				{
					$dimension = explode(" ", $terms_left_final);

					if (count($dimension) > 0)
					{
						if (strpos($dimension[0], "width") !== false)
						{
							$width      = explode("width=", $dimension[0]);
							$finalwidth = (isset($width[1])) ? $width[1] : "500";
						}
						else
						{
							$height      = explode("height=", $dimension[0]);
							$finalheight = (isset($height[1])) ? $height[0] : "450";
						}

						if (strpos($dimension[1], "height") !== false)
						{
							$height      = explode("height=", $dimension[1]);
							$finalheight = (isset($height[1])) ? $height[1] : "450";
						}
						else
						{
							$width      = explode("width=", $dimension[1]);
							$finalwidth = (isset($width[1])) ? $width[1] : "500";
						}
					}
				}

				$url            = JURI::base();
				$article_link   = $url . "index.php?option=com_content&amp;view=article&amp;id=" . Redshop::getConfig()->get('TERMS_ARTICLE_ID') . "&Itemid=" . $Itemid . "&tmpl=component";
				$termscondition = '<label class="checkbox"><input type="checkbox" id="termscondition" name="termscondition" value="1" /> ';
				$termscondition .= JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL');
				$termscondition .= ' <a class="modal" href="' . $article_link . '" rel="{handler: \'iframe\', size: {x: ' . $finalwidth . ', y: ' . $finalheight . '}}">' . JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_FOR_LBL') . '</a></label>';
			}

			$template_desc = str_replace($finaltag, $termscondition, $template_desc);
		}

		return $template_desc;
	}

	public function replaceNewsletterSubscription($template_desc = "", $onchange = 0)
	{
		$db = JFactory::getDbo();

		if (strpos($template_desc, "{newsletter_signup_chk}") !== false)
		{
			$Itemid               = JRequest::getVar('Itemid');
			$newslettersignup     = "";
			$newslettersignup_lbl = "";
			$link                 = "";

			if (Redshop::getConfig()->get('DEFAULT_NEWSLETTER') != 0)
			{
				$user  = JFactory::getUser();
				$query = "SELECT subscription_id FROM " . $this->_table_prefix . "newsletter_subscription"
					. " WHERE user_id=" . (int) $user->id . " AND email=" . $db->quote($user->email);
				$this->_db->setQuery($query);
				$subscribe = $this->_db->loadResult();

				if ($subscribe == 0)
				{
					if ($onchange)
					{
						$link = " onchange='window.location.href=\"" . JURI::root() . "index.php?option=com_redshop&view=account&task=newsletterSubscribe&tmpl=component&Itemid=" . $Itemid . "\"";

					}

					$newslettersignup     = "<input type='checkbox' name='newsletter_signup' value='1' '$link'>";
					$newslettersignup_lbl = JText::_('COM_REDSHOP_SIGN_UP_FOR_NEWSLETTER');
				}
			}

			$template_desc = str_replace("{newsletter_signup_chk}", $newslettersignup, $template_desc);
			$template_desc = str_replace("{newsletter_signup_lbl}", $newslettersignup_lbl, $template_desc);
			$template_desc = str_replace("{newsletter_unsubscribe}", "", $template_desc);
		}

		return $template_desc;
	}

	/**
	 * Method for get product price from cart
	 *
	 * @param   integer $product_id   ID of product
	 * @param   array   $cart         Cart data
	 * @param   integer $voucher_left Voucher left
	 *
	 * @return array
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 */
	public function getCartProductPrice($product_id, $cart, $voucher_left)
	{
		return RedshopHelperCart::getCartProductPrice($product_id, $cart, $voucher_left);
	}

	/**
	 * Method for get coupon
	 *
	 * @param   array  $c_data  Array data.
	 *
	 * @return  mixed
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function coupon($c_data = array())
	{
		return RedshopHelperDiscount::coupon($c_data);
	}

	/**
	 * Method for get voucher
	 *
	 * @param   array  $v_data  Array data.
	 *
	 * @return  mixed
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function voucher($v_data = array())
	{
		return RedshopHelperDiscount::voucher($v_data);
	}

	/**
	 * Re-calculate the Voucher/Coupon value when the product is already discount
	 *
	 * @param   float  $value  Voucher/Coupon value
	 * @param   array  $cart   Cart array
	 *
	 * @return  float          Voucher/Coupon value
	 *
	 * @deprecated  __DEPLOY_VERSION__ Use RedshopHelperTax::calculateAlreadyDiscount instead
	 */
	public function calcAlreadyDiscount($value, $cart)
	{
		return RedshopHelperTax::calculateAlreadyDiscount($value, $cart);
	}

	public function rs_multi_array_key_exists($needle, $haystack)
	{
		foreach ($haystack as $key => $value)
		{
			if ($needle === $key)
			{
				return true;
			}

			if (is_array($value))
			{
				if ($this->rs_multi_array_key_exists($needle, $value))
				{
					return true;
				}
			}
		}

		return false;
	}

	public function rs_recursiveArraySearch($haystack, $needle, $index = null)
	{
		$aIt = new RecursiveArrayIterator($haystack);
		$it  = new RecursiveIteratorIterator($aIt);

		while ($it->valid())
		{
			if (((isset($index) AND ($it->key() == $index)) OR (!isset($index))) AND ($it->current() == $needle))
			{
				return true;
			}

			$it->next();
		}

		return false;
	}

	/**
	 * Calculate discount
	 *
	 * @param   string  $type     Type of discount ("voucher", "coupon")
	 * @param   array   $typeArr  Data.
	 *
	 * @return  float             Voucher/Coupon discount value
	 *
	 * @deprecated   __DEPLOY_VERSION__  Use RedshopHelperTax::calculateDiscount instead
	 */
	public function calculateDiscount($type, $typeArr)
	{
		return RedshopHelperTax::calculateDiscount($type, $typeArr);
	}

	public function getVoucherData($voucher_code, $product_id = 0)
	{
		$db = JFactory::getDbo();

		$user         = JFactory::getUser();
		$voucher      = array();
		$current_time = time();

		$gbvoucher = $this->globalvoucher($voucher_code);

		if ($this->_globalvoucher != 1)
		{
			if ($user->id)
			{
				$subQuery = $db->getQuery(true)
					->select('GROUP_CONCAT(DISTINCT pv.product_id SEPARATOR ' . $db->quote(', ') . ') AS product_id')
					->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
					->where('v.voucher_id = pv.voucher_id');

				$query = $db->getQuery(true)
					->select(
						array('vt.transaction_voucher_id', 'vt.amount AS total', 'vt.product_id', 'v.*', '(' . $subQuery . ') AS nproduct')
					)
					->from($db->qn('#__redshop_product_voucher', 'v'))
					->leftJoin($db->qn('#__redshop_product_voucher_transaction', 'vt') . ' ON vt.voucher_id = v.voucher_id')
					->where('vt.voucher_code = ' . $db->quote($voucher_code))
					->where('vt.amount > 0')
					->where('v.voucher_type = ' . $db->quote('Total'))
					->where('v.published = 1')
					->where('((start_date <= ' . $db->quote($current_time) . ' AND end_date >= ' . $db->quote($current_time) . ') OR (start_date = 0 AND end_date = 0))')
					->where('vt.user_id = ' . (int) $user->id)
					->order('transaction_voucher_id DESC');
				$db->setQuery($query);
				$voucher = $db->loadObject();

				if (count($voucher) > 0)
					$this->_r_voucher = 1;
			}

			if ((count($voucher)) <= 0)
			{
				$subQuery = $db->getQuery(true)
					->select('GROUP_CONCAT(DISTINCT pv.product_id SEPARATOR ' . $db->quote(', ') . ') AS product_id')
					->from($db->qn('#__redshop_product_voucher_xref', 'pv'))
					->where('v.voucher_id = pv.voucher_id');

				$query = $db->getQuery(true)
					->select(
						array(
							'(' . $subQuery . ') AS nproduct', 'v.amount AS total', 'v.voucher_type',
							'v.free_shipping', 'v.voucher_id', 'v.voucher_code', 'v.voucher_left')
					)
					->from($db->qn('#__redshop_product_voucher', 'v'))
					->where('v.published = 1')
					->where('v.voucher_code = ' . $db->quote($voucher_code))
					->where('((v.start_date <= ' . $db->quote($current_time) . ' AND v.end_date >= ' . $db->quote($current_time) . ') OR (v.start_date = 0 AND v.end_date = 0))')
					->where('v.voucher_left > 0');
				$db->setQuery($query);
				$voucher = $db->loadObject();
			}
		}
		else
		{
			$voucher = $gbvoucher;
		}

		return $voucher;
	}

	public function globalvoucher($voucher_code)
	{
		$db = JFactory::getDbo();

		$current_time = time();
		$query        = "SELECT product_id,v.* from " . $this->_table_prefix . "product_voucher_xref as pv  "
			. "left join " . $this->_table_prefix . "product_voucher as v on v.voucher_id = pv.voucher_id "
			. " \nWHERE v.published = 1"
			. " AND v.voucher_code=" . $db->quote($voucher_code)
			. " AND ((v.start_date<=" . $db->quote($current_time) . " AND v.end_date>=" . $db->quote($current_time) . ")"
			. " OR ( v.start_date =0 AND v.end_date = 0) ) AND v.voucher_left>0 limit 0,1";
		$this->_db->setQuery($query);
		$voucher = $this->_db->loadObject();

		if (count($voucher) <= 0)
		{
			$this->_globalvoucher = 1;
			$query                = "SELECT v.*,v.amount as total from " . $this->_table_prefix . "product_voucher as v "
				. "WHERE v.published = 1 "
				. "AND v.voucher_code=" . $db->quote($voucher_code)
				. "AND ((v.start_date<=" . $db->quote($current_time) . " AND v.end_date>=" . $db->quote($current_time) . ")"
				. " OR ( v.start_date =0 AND v.end_date = 0) ) "
				. "AND v.voucher_left>0 LIMIT 0,1 ";
			$this->_db->setQuery($query);
			$voucher = $this->_db->loadObject();
		}

		return $voucher;
	}

	/**
	 * Method for get Coupon Data.
	 *
	 * @param   string   $coupon_code  Coupon code
	 * @param   integer  $subtotal     Subtotal
	 *
	 * @return  object
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 */
	public function getcouponData($coupon_code, $subtotal = 0)
	{
		return RedshopHelperDiscount::getCouponData($coupon_code, $subtotal);
	}

	public function modifyDiscount($cart)
	{
		$calArr                            = RedshopHelperCart::calculation($cart);
		$cart['product_subtotal']          = $calArr[1];
		$cart['product_subtotal_excl_vat'] = $calArr[2];
		$c_index                           = 0;
		$v_index                           = 0;
		$discount_amount                   = 0;
		$voucherDiscount                   = 0;
		$couponDiscount                    = 0;
		$discount_excl_vat                 = 0;

		if (!empty($cart['coupon']))
		{
			$c_index = count($cart['coupon']);
		}

		if (!empty($cart['voucher']))
		{
			$v_index = count($cart['voucher']);
		}

		$totaldiscount = 0;

		if (Redshop::getConfig()->get('DISCOUNT_ENABLE') == 1)
		{
			$discount_amount = $this->_producthelper->getDiscountAmount($cart);

			if ($discount_amount > 0)
			{
				$cart = $this->_session->get('cart');
			}
		}

		if (!isset($cart['quotation_id']) || (isset($cart['quotation_id']) && !$cart['quotation_id']))
		{
			$cart['cart_discount'] = $discount_amount;
		}

		for ($v = 0; $v < $v_index; $v++)
		{
			$voucher_code = $cart['voucher'][$v]['voucher_code'];
			unset($cart['voucher'][$v]);
			$voucher_code = JRequest::setVar('discount_code', $voucher_code);
			$cart         = RedshopHelperDiscount::voucher($cart);
		}

		if (array_key_exists('voucher', $cart))
		{
			$voucherDiscount = RedshopHelperTax::calculateDiscount('voucher', $cart['voucher']);
		}

		$cart['voucher_discount'] = $voucherDiscount;

		for ($c = 0; $c < $c_index; $c++)
		{
			$coupon_code = $cart['coupon'][$c]['coupon_code'];
			unset($cart['coupon'][$c]);
			$coupon_code = JRequest::setVar('discount_code', $coupon_code);
			$cart        = RedshopHelperDiscount::coupon($cart);
		}

		if (array_key_exists('coupon', $cart))
		{
			$couponDiscount = RedshopHelperTax::calculateDiscount('coupon', $cart['coupon']);
		}

		$cart['coupon_discount'] = $couponDiscount;
		$codeDsicount            = $voucherDiscount + $couponDiscount;
		$totaldiscount           = $cart['cart_discount'] + $codeDsicount;

		$calArr 	 = RedshopHelperCart::calculation($cart);
		$tax         = $calArr[5];
		$Discountvat = 0;
		$chktag      = RedshopHelperCart::taxExemptAddToCart();

		if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') && !empty($chktag) && !Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
		{
			$vatData = $this->_producthelper->getVatRates();

			if (isset($vatData->tax_rate) && !empty($vatData->tax_rate))
			{
				$productPriceExclVAT = $cart['product_subtotal_excl_vat'];
				$productVAT 		 = $cart['product_subtotal'] - $cart['product_subtotal_excl_vat'];

				if ((int) $productPriceExclVAT > 0)
				{
					$avgVAT      = (($productPriceExclVAT + $productVAT) / $productPriceExclVAT) - 1;
					$Discountvat = ($avgVAT * $totaldiscount) / (1 + $avgVAT);
				}
			}
		}

		$cart['total'] = $calArr[0] - $totaldiscount;

		if ($cart['total'] < 0)
		{
			$cart['total'] = 0;
		}

		$cart['subtotal'] = $calArr[1] + $calArr[3] - $totaldiscount;

		if ($cart['subtotal'] < 0)
		{
			$cart['subtotal'] = 0;
		}

		$cart['subtotal_excl_vat'] = $calArr[2] + ($calArr[3] - $calArr[6]) - ($totaldiscount - $Discountvat);

		if ($cart['total'] <= 0)
		{
			$cart['subtotal_excl_vat'] = 0;
		}

		$cart['product_subtotal']          = $calArr[1];
		$cart['product_subtotal_excl_vat'] = $calArr[2];
		$cart['shipping']                  = $calArr[3];
		$cart['tax']                       = $tax;
		$cart['sub_total_vat']             = $tax + $calArr[6];
		$cart['discount_vat']              = $Discountvat;
		$cart['shipping_tax']              = $calArr[6];
		$cart['discount_ex_vat']           = $totaldiscount - $Discountvat;
		$cart['mod_cart_total']            = RedshopHelperCart::getCartModuleCalc($cart);

		$this->_session->set('cart', $cart);

		return $cart;
	}

	public function getWrapperPriceArr($cartArr = array())
	{
		$wrapper     = $this->_producthelper->getWrapper($cartArr['product_id'], $cartArr['wrapper_id']);
		$wrapper_vat = 0;
		$wrapperArr  = array();

		if (count($wrapper) > 0)
		{
			if ($wrapper[0]->wrapper_price > 0)
			{
				$wrapper_vat = $this->_producthelper->getProducttax($cartArr['product_id'], $wrapper[0]->wrapper_price);
			}

			$wrapper_price = $wrapper[0]->wrapper_price;
		}

		$wrapperArr['wrapper_vat']   = $wrapper_vat;
		$wrapperArr['wrapper_price'] = $wrapper_price;

		return $wrapperArr;
	}

	/**
	 * Check quantity in stock
	 *
	 * @param   array    $data         Array of data.
	 * @param   integer  $newquantity  New quantity
	 * @param   integer  $minQuantity  Minimum quantity.
	 *
	 * @return  integer                New quantity if success.
	 *
	 * @deprecated   __DEPLOY_VERSION__  Use RedshopHelperStockroom::checkQuantityInStock instead
	 */
	public function checkQuantityInStock($data = array(), $newquantity = 1, $minQuantity = 0)
	{
		return RedshopHelperStockroom::checkQuantityInStock($data, $newquantity, $minQuantity);
	}

	/**
	 * Method for calculate final price of cart.
	 *
	 * @param   bool  $callmodify  Is modify cart?
	 *
	 * @return  array
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::cartFinalCalculation() instead.
	 */
	public function cartFinalCalculation($callmodify = true)
	{
		return RedshopHelperCart::cartFinalCalculation($callmodify);
	}

	/**
	 * Store Cart to Database
	 *
	 * @param   array  $cart   Cart
	 *
	 * @return  null
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::addCartToDatabase() instead.
	 */
	public function carttodb($cart = array())
	{
		return RedshopHelperCart::addCartToDatabase($cart);
	}

	/**
	 * Store Cart Attribute to Database
	 *
	 * @param   array    $attribute      Cart attribute data.
	 * @param   int      $cart_item_id   Cart item ID
	 * @param   int      $product_id     Cart product ID.
	 * @param   boolean  $isAccessary    Is this accessory?
	 *
	 * @return  boolean       True on success. False otherwise.
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::addCartToDatabase() instead.
	 */
	public function attributetodb($attribute = array(), $cart_item_id = 0, $product_id = 0, $isAccessary = false)
	{
		return RedshopHelperCart::addCartAttributeToDatabase($attribute, $cart_item_id, $product_id, $isAccessary);
	}

	/**
	 * Remove cart entry from table
	 *
	 * @param   int  $cart_id   #__redshop_usercart table key id
	 * @param   int  $userid    user information id - joomla #__users table key id
	 * @param   bool $delCart   remove cart from #__redshop_usercart table
	 *
	 * @return bool
	 *
	 * @deprecated  2.0.3  Use edshopHelperCart::removeCartFromDatabase() instead.
	 */
	public function removecartfromdb($cart_id = 0, $userid = 0, $delCart = false)
	{
		return RedshopHelperCart::removeCartFromDatabase($cart_id, $userid, $delCart);
	}

	/**
	 * Method for convert data from database to cart.
	 *
	 * @param   int  $userId  ID of user.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::databaseToCart() instead.
	 */
	public function dbtocart($userId = 0)
	{
		RedshopHelperCart::databaseToCart($userId);
	}

	/**
	 * Method for generate attribute from cart.
	 *
	 * @param   int  $cart_item_id       ID of cart item.
	 * @param   int  $is_accessory       Is accessory?
	 * @param   int  $parent_section_id  ID of parent section
	 * @param   int  $quantity           Quantity of product.
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3
	 */
	public function generateAttributeFromCart($cart_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		return RedshopHelperCart::generateAttributeFromCart($cart_item_id, $is_accessory, $parent_section_id, $quantity);
	}

	/**
	 * Method for generate Accessory data from cart.
	 *
	 * @param   int  $cart_item_id
	 * @param   int  $product_id
	 * @param   int  $quantity
	 *
	 * @return  array
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function generateAccessoryFromCart($cart_item_id = 0, $product_id = 0, $quantity = 1)
	{
		return RedshopHelperCart::generateAccessoryFromCart($cart_item_id, $product_id, $quantity);
	}

	/**
	 * Method for get cart item accessory from user cart DB
	 *
	 * @param   integer  $cart_item_id  Cart item ID
	 *
	 * @return  mixed
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function getCartItemAccessoryDetail($cart_item_id = 0)
	{
		return RedshopHelperCart::getCartItemAccessoryDetail($cart_item_id);
	}

	/**
	 * Method for get cart item attributes
	 *
	 * @param   integer  $cart_item_id
	 * @param   integer  $is_accessory
	 * @param   string   $section
	 * @param   integer  $parent_section_id
	 *
	 * @return  mixed
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 */
	public function getCartItemAttributeDetail($cart_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		return RedshopHelperCart::getCartItemAttributeDetail($cart_item_id, $is_accessory, $section, $parent_section_id);
	}

	/**
	 * Add GiftCard To Cart
	 *
	 * @param   array  &$cartItem  Cart item
	 * @param   array  $data       User cart data
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperCart::addGiftCardToCart instead
	 */
	public function addGiftCardToCart(&$cartItem, $data)
	{
		RedshopHelperCart::addGiftCardToCart($cartItem, $data);
	}

	/**
	 * Add Product To Cart
	 *
	 * @param   array  $data   Product data
	 *
	 * @return  boolean
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperCart::addProductToCart instead
	 */
	public function addProductToCart($data = array())
	{
		return RedshopHelperCart::addProductToCart($data);
	}

	/**
	 * Method for validate user field
	 *
	 * @param   array    $data      Array of data
	 * @param   string   $data_add  Template content
	 * @param   integer  $section   Field section
	 *
	 * @return  string              Empty string if passed. Error message if fail.
	 *
	 * @deprecated    __DEPLOY_VERSION__  Use RedshopHelperExtrafields::userFieldValidation instead
	 */
	public function userfieldValidation($data, $data_add, $section = 12)
	{
		return RedshopHelperExtrafields::userFieldValidation($data, $data_add, $section);
	}

	/**
	 * Generate Accessories cart
	 *
	 * @param   array    $data     Data
	 * @param   integer  $user_id  ID user
	 *
	 * @return  mixed              Array of accessories cart if success. False otherwise.
	 *
	 * @deprecated    __DEPLOY_VERSION__  Use RedshopHelperExtrafields::generateAccessoriesCart instead
	 */
	public function generateAccessoryArray($data, $user_id = 0)
	{
		return RedshopHelperCart::generateAccessoriesCart($data, $user_id);
	}

	public function getProductAccAttribute($product_id = 0, $attribute_set_id = 0, $attribute_id = 0, $published = 0, $attribute_required = 0, $notAttributeId = 0)
	{
		$and          = "";
		$astpublished = "";

		if ($product_id != 0)
		{
			// Secure productsIds
			if ($productsIds = explode(',', $product_id))
			{
				JArrayHelper::toInteger($productsIds);

				$and .= "AND p.product_id IN (" . implode(',', $productsIds) . ") ";
			}
		}

		if ($attribute_set_id != 0)
		{
			$and .= "AND a.attribute_set_id=" . (int) $attribute_set_id . " ";
		}

		if ($published != 0)
		{
			$astpublished = " AND ast.published=" . (int) $published . " ";
		}

		if ($attribute_required != 0)
		{
			$and .= "AND a.attribute_required=" . (int) $attribute_required . " ";
		}

		if ($notAttributeId != 0)
		{
			// Secure notAttributeId
			if ($notAttributeIds = explode(',', $notAttributeId))
			{
				JArrayHelper::toInteger($notAttributeIds);

				$and .= "AND a.attribute_id NOT IN (" . implode(',', $notAttributeIds) . ") ";
			}
		}

		$query = "SELECT a.attribute_id AS value,a.attribute_name AS text,a.*,ast.attribute_set_name "
			. "FROM " . $this->_table_prefix . "product_attribute AS a "
			. "LEFT JOIN " . $this->_table_prefix . "attribute_set AS ast ON ast.attribute_set_id=a.attribute_set_id "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.attribute_set_id=a.attribute_set_id " . $astpublished
			. "WHERE a.attribute_name!='' "
			. $and
			. " and attribute_published=1 ORDER BY a.ordering ASC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * Method for get attribute set ID from specific product
	 *
	 * @param   integer  $pid  ID of product
	 *
	 * @return  integer        ID of Attribute set.
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 */
	public function getAttributeSetId($pid)
	{
		return RedshopHelperProduct::getAttributeSetId($pid);
	}

	/**
	 * Method for generate Attribute array base on specific data.
	 *
	 * @param   array    $data     Data
	 * @param   integer  $user_id
	 *
	 * @return  array
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function generateAttributeArray($data, $user_id = 0)
	{
		return RedshopHelperAttribute::generateAttributeArray($data, $user_id);
	}

	public function getSelectedCartAttributeArray($attArr = array())
	{
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$propArr = $attArr[$i]['attribute_childs'];

			for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
			{
				$selectedproperty[] = $propArr[$k]['property_id'];
				$subpropArr         = $propArr[$k]['property_childs'];

				for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
				{
					$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
				}
			}
		}

		$ret = array($selectedproperty, $selectedsubproperty);

		return $ret;
	}

	public function getSelectedCartAccessoryArray($attArr = array())
	{
		$selectedAccessory   = array();
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$selectedAccessory[] = $attArr[$i]['accessory_id'];
			$attchildArr         = $attArr[$i]['accessory_childs'];

			for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
			{
				$propArr = $attchildArr[$j]['attribute_childs'];

				for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
				{
					$selectedproperty[] = $propArr[$k]['property_id'];
					$subpropArr         = $propArr[$k]['property_childs'];

					for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
					{
						$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
					}
				}
			}
		}

		$ret = array($selectedAccessory, $selectedproperty, $selectedsubproperty);

		return $ret;
	}

	/**
	 * Method for generate Attribute data from order.
	 *
	 * @param int $orderItemId  ID of order item
	 * @param int $isAccessory  Is accessory?
	 * @param int $parentSectionId  Is accessory?
	 * @param int $quantity    Quantity
	 *
	 * @return array
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 */
	public function generateAttributeFromOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		return RedshopHelperOrder::generateAttributeFromOrder($order_item_id, $is_accessory, $parent_section_id, $quantity);
	}

	/**
	 * Method for generate Accessory data from order.
	 *
	 * @param  int  $orderItemId  ID of order item
	 * @param  int  $productId    ID of product
	 * @param  int  $quantity     Quantity
	 *
	 * @return array
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function generateAccessoryFromOrder($order_item_id = 0, $product_id = 0, $quantity = 1)
	{
		return RedshopHelperOrder::generateAccessoryFromOrder($order_item_id, $product_id, $quantity);
	}

	/**
	 * Discount calculator with product data.
	 *
	 * @param   object $productData Product data
	 * @param   array  $data        Data
	 *
	 * @return  array
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function discountCalculatorData($product_data, $data)
	{
		return RedshopHelperDiscount::discountCalculatorData($product_data, $data);
	}

	/**
	 * Discount calculator ajax function
	 *
	 * @param   array  $get  Data
	 *
	 * @return  array
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function discountCalculator($get)
	{
		return RedshopHelperDiscount::discountCalculator($get);
	}

	/**
	 * Function to get Discount calculation data
	 *
	 * @param   number  $area         default value is 0
	 * @param   number  $pid          default value can be null
	 * @param   number  $areabetween  default value is 0
	 *
	 * @return object
	 */
	public function getDiscountCalcData($area = 0, $pid = 0, $areabetween = 0)
	{
		$query = $this->_db->getQuery(true)
			->select("*")
			->from($this->_db->quoteName("#__redshop_product_discount_calc"))
			->where($this->_db->quoteName("product_id") . "=" . (int) $pid)
			->order("id ASC");

		if ($areabetween)
		{
			$query->where((floatval($area)) . " BETWEEN `area_start` AND `area_end` ");
		}

		if ($area)
		{
			$query->where($this->_db->quoteName("area_start_converted") . "<=" . floatval($area))
				->where($this->_db->quoteName("area_end_converted") . ">=" . floatval($area));
		}

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * @param string $pdcextraids
	 * @param int    $product_id
	 *
	 * @return mixed
	 */
	public function getDiscountCalcDataExtra($pdcextraids = "", $product_id = 0)
	{
		$db = JFactory::getDbo();

		$and = "";

		if ($product_id)
		{
			$and .= "AND product_id = " . (int) $product_id . " ";
		}

		if ($pdcextraids)
		{
			// Secure $pdcextraids
			if ($extraIds = explode(',', $pdcextraids))
			{
				JArrayHelper::toInteger($extraIds);
			}

			$and .= "AND pdcextra_id IN (" . implode(',', $extraIds) . ") ";
		}

		$query = "SELECT * FROM `" . $this->_table_prefix . "product_discount_calc_extra` "
			. "WHERE 1=1 "
			. $and
			. "ORDER BY option_name ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * Handle required attribute before add in to cart messages
	 *
	 * @param   array   $data                  cart data
	 * @param   string  $attributeTemplate     Attribute added data
	 * @param   array   $selectedAttrId        Selected attribute id for add to cart
	 * @param   array   $selectedPropId        Selected Property Id for Add to cart
	 * @param   array   $notselectedSubpropId  Not selected subproperty ids during add to cart
	 *
	 * @return  string  Error Message if found otherwise return null.
	 */
	public function handleRequiredSelectedAttributeCartMessage($data, $attributeTemplate, $selectedAttrId, $selectedPropId, $notselectedSubpropId)
	{
		if (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			return;
		}

		// Check if required attribute is filled or not ...
		$attribute_template = $this->_producthelper->getAttributeTemplate($attributeTemplate);

		if (count($attribute_template) > 0)
		{
			$selectedAttributId = 0;

			if (count($selectedAttrId) > 0)
			{
				$selectedAttributId = implode(",", $selectedAttrId);
			}

			$req_attribute = $this->_producthelper->getProductAttribute(
								$data['product_id'],
								0,
								0,
								0,
								1,
								$selectedAttributId
							);

			if (count($req_attribute) > 0)
			{
				$requied_attributeArr = array();

				for ($re = 0; $re < count($req_attribute); $re++)
				{
					$requied_attributeArr[$re] = urldecode($req_attribute[$re]->attribute_name);
				}

				$requied_attribute_name = implode(", ", $requied_attributeArr);

				// Error message if first attribute is required
				return $requied_attribute_name . " " . JText::_('COM_REDSHOP_IS_REQUIRED');
			}

			$selectedPropertyId = 0;

			if (count($selectedPropId) > 0)
			{
				$selectedPropertyId = implode(",", $selectedPropId);
			}

			$notselectedSubpropertyId = 0;

			if (count($notselectedSubpropId) > 0)
			{
				$notselectedSubpropertyId = implode(",", $notselectedSubpropId);
			}

			$req_property = $this->_producthelper->getAttibuteProperty(
								$selectedPropertyId,
								$selectedAttributId,
								$data['product_id'],
								0,
								1,
								$notselectedSubpropertyId
							);

			if (count($req_property) > 0)
			{
				$requied_subattributeArr = array();

				for ($re1 = 0; $re1 < count($req_property); $re1++)
				{
					$requied_subattributeArr[$re1] = urldecode($req_property[$re1]->property_name);
				}

				$requied_subattribute_name = implode(",", $requied_subattributeArr);

				// Give error as second attribute is required
				if ($data['reorder'] != 1)
				{
					return $requied_subattribute_name . " " . JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED');
				}
			}
		}

		return;
	}
}
