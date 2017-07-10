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

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Cart - Tag replacer
 *
 * @since  2.0.7
 */
class RedshopHelperCartTag
{
	/**
	 * replace Conditional tag from Redshop tax
	 *
	 * @param   string  $template
	 * @param   int     $amount
	 * @param   int     $discount
	 * @param   int     $check
	 * @param   int     $quotation_mode
	 *
	 * @return  mixed|string
	 * @since   2.0.7
	 */
	public static function replaceTax($template = '', $amount = 0, $discount = 0, $check = 0, $quotation_mode = 0)
	{
		if (strpos($template, '{if vat}') !== false && strpos($template, '{vat end if}') !== false)
		{
			$cart          = RedshopHelperCartSession::getCart();
			$productHelper = productHelper::getInstance();

			if ($amount <= 0)
			{
				$template_vat_sdata = explode('{if vat}', $template);
				$template_vat_edata = explode('{vat end if}', $template_vat_sdata[1]);
				$template           = $template_vat_sdata[0] . $template_vat_edata[1];
			}
			else
			{
				if ($quotation_mode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
				{
					$template = str_replace("{tax}", "", $template);
					$template = str_replace("{order_tax}", "", $template);
				}
				else
				{
					$template = str_replace("{tax}", $productHelper->getProductFormattedPrice($amount, true), $template);
					$template = str_replace("{order_tax}", $productHelper->getProductFormattedPrice($amount, true), $template);
				}

				if (strpos($template, '{tax_after_discount}') !== false)
				{
					if (Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') && (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
					{
						if ($check)
						{
							$tax_after_discount = $discount;
						}
						else
						{
							if (!isset($cart['tax_after_discount']))
							{
								$tax_after_discount = RedshopHelperCart::calculateTaxAfterDiscount($amount, $discount);
							}
							else
							{
								$tax_after_discount = $cart['tax_after_discount'];
							}
						}

						if ($tax_after_discount > 0)
						{
							$template = str_replace("{tax_after_discount}", $productHelper->getProductFormattedPrice($tax_after_discount), $template);
						}
						else
						{
							$template = str_replace("{tax_after_discount}", $productHelper->getProductFormattedPrice($cart['tax']), $template);
						}
					}
					else
					{
						$template = str_replace("{tax_after_discount}", $productHelper->getProductFormattedPrice($cart['tax']), $template);
					}
				}

				$template = str_replace("{vat_lbl}", JText::_('COM_REDSHOP_CHECKOUT_VAT_LBL'), $template);
				$template = str_replace("{if vat}", '', $template);
				$template = str_replace("{vat end if}", '', $template);
			}
		}

		return $template;
	}

	/**
	 * Replace Conditional tag from Redshop Discount
	 *
	 * @param   string  $template
	 * @param   int     $discount
	 * @param   int     $subtotal
	 * @param   int     $quotation_mode
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceDiscount($template = '', $discount = 0, $subtotal = 0, $quotation_mode = 0)
	{
		if (strpos($template, '{if discount}') !== false && strpos($template, '{discount end if}') !== false)
		{
			$productHelper = productHelper::getInstance();
			$percentage = '';

			if ($discount <= 0)
			{
				$template_discount_sdata = explode('{if discount}', $template);
				$template_discount_edata = explode('{discount end if}', $template_discount_sdata[1]);
				$template                = $template_discount_sdata[0] . $template_discount_edata[1];
			}
			else
			{
				$template = str_replace("{if discount}", '', $template);

				if ($quotation_mode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
				{
					$template = str_replace("{discount}", "", $template);
					$template = str_replace("{discount_in_percentage}", $percentage, $template);

				}
				else
				{
					$template = str_replace("{discount}", $productHelper->getProductFormattedPrice($discount, true), $template);
					$template = str_replace("{order_discount}", $productHelper->getProductFormattedPrice($discount, true), $template);

					if (!empty($subtotal) && $subtotal > 0)
					{
						$percentage = round(($discount * 100 / $subtotal), 2) . " %";
					}

					$template = str_replace("{discount_in_percentage}", $percentage, $template);
				}

				$template = str_replace("{discount_lbl}", JText::_('COM_REDSHOP_CHECKOUT_DISCOUNT_LBL'), $template);
				$template = str_replace("{discount end if}", '', $template);
			}
		}

		return $template;
	}

	/**
	 * Replace Conditional tag from Redshop payment Discount/charges
	 *
	 * @param   string  $template
	 * @param   int     $amount
	 * @param   int     $cart
	 * @param   string  $payment_oprand
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replacePayment($template = '', $amount = 0, $cart = 0, $payment_oprand = '-')
	{
		if (strpos($template, '{if payment_discount}') !== false && strpos($template, '{payment_discount end if}') !== false)
		{
			if ($cart == 1 || $amount == 0)
			{
				$template_pdiscount_sdata = explode('{if payment_discount}', $template);
				$template_pdiscount_edata = explode('{payment_discount end if}', $template_pdiscount_sdata[1]);
				$template                 = $template_pdiscount_sdata[0] . $template_pdiscount_edata[1];

				return $template;
			}

			if ($amount <= 0)
			{
				$template_pd_sdata = explode('{if payment_discount}', $template);
				$template_pd_edata = explode('{payment_discount end if}', $template_pd_sdata[1]);
				$template          = $template_pd_sdata[0] . $template_pd_edata[1];
			}
			else
			{
				$template = str_replace("{payment_order_discount}", productHelper::getInstance()->getProductFormattedPrice($amount), $template);
				$payText  = ($payment_oprand == '+') ? JText::_('COM_REDSHOP_PAYMENT_CHARGES_LBL') : JText::_('COM_REDSHOP_PAYMENT_DISCOUNT_LBL');
				$template = str_replace("{payment_discount_lbl}", $payText, $template);
				$template = str_replace("{payment_discount end if}", '', $template);
				$template = str_replace("{if payment_discount}", '', $template);
			}
		}

		return $template;
	}

	/**
	 * Replace Billing Address
	 *
	 * @param   string   $template
	 * @param   string   $billingaddresses
	 * @param   boolean  $sendmail
	 *
	 * @return   string
	 *
	 * @since   2.0.7
	 */
	public static function replaceBillingAddress($template, $billingaddresses, $sendmail = false)
	{
		if (strpos($template, '{billing_address_start}') !== false && strpos($template, '{billing_address_end}') !== false)
		{
			$user           = JFactory::getUser();
			$template_sdata = explode('{billing_address_start}', $template);
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

				$cname = RedshopHelperOrder::getCountryName($billingaddresses->country_code);

				if ($cname != "")
				{
					$billingdata = str_replace("{country}", JText::_($cname), $billingdata);
					$billingdata = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $billingdata);
				}

				$sname = RedshopHelperOrder::getStateName($billingaddresses->state_code, $billingaddresses->country_code);

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

				$billing_extrafield = RedshopHelperExtrafields::listAllFieldDisplay($extra_section, $billingaddresses->users_info_id, 1);
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

			$template = $template_sdata[0] . $billingdata . $template_edata[1];
		}
		elseif (strpos($template, '{billing_address}') !== false)
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

				if (strpos($template, '{quotation_custom_field_list}') !== false)
				{
					$template = str_replace('{quotation_custom_field_list}', '', $template);

					if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
					{
						$billadd .= RedshopHelperExtrafields::listAllField(16, $billingaddresses->users_info_id, '', '');
					}
				}
				elseif (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
				{
					$template = RedshopHelperExtrafields::listAllField(16, $billingaddresses->users_info_id, '', '', $template);
				}
			}

			$template = str_replace("{billing_address}", $billadd, $template);
		}

		$template = str_replace("{billing_address}", "", $template);
		$template = str_replace("{billing_address_information_lbl}", JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'), $template);

		return $template;
	}

	/**
	 * Replace Shipping Address
	 *
	 * @param   string  $template
	 * @param   string  $shippingaddresses
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceShippingAddress($template, $shippingaddresses, $sendmail = false)
	{
		if (strpos($template, '{shipping_address_start}') !== false && strpos($template, '{shipping_address_end}') !== false)
		{
			$template_sdata = explode('{shipping_address_start}', $template);
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

				$cname = RedshopHelperOrder::getCountryName($shippingaddresses->country_code);

				if ($cname != "")
				{
					$shippingdata = str_replace("{country}", JText::_($cname), $shippingdata);
					$shippingdata = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $shippingdata);
				}

				$sname = RedshopHelperOrder::getStateName($shippingaddresses->state_code, $shippingaddresses->country_code);

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
				$shippingdata = extraField::getInstance()>extra_field_display($extra_section, $shippingaddresses->users_info_id, "", $shippingdata);

				$shipping_extrafield = RedshopHelperExtrafields::listAllFieldDisplay($extra_section, $shippingaddresses->users_info_id, 1);
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

			$template = $template_sdata[0] . $shippingdata . $template_edata[1];
		}
		elseif (strpos($template, '{shipping_address}') !== false)
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
					$template = RedshopHelperExtrafields::extraFieldDisplay(15, $shippingaddresses->users_info_id, "", $template);
				}
				else
				{
					// Additional functionality - more flexible way
					$template = RedshopHelperExtrafields::extraFieldDisplay(14, $shippingaddresses->users_info_id, "", $template);
				}
			}

			$template = str_replace("{shipping_address}", $shipadd, $template);
		}

		$shippingtext = (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) ? JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL') : '';
		$template     = str_replace("{shipping_address}", "", $template);
		$template     = str_replace("{shipping_address_information_lbl}", $shippingtext, $template);

		return $template;
	}

	/**
	 * Replace Shipping methods
	 *
	 * @param   string  $template
	 * @param   array   $row
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceShippingMethod($template = "", $row = array())
	{
		$productHelper = productHelper::getInstance();
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
			$replace[]    = $productHelper->getProductFormattedPrice($row->order_shipping);
			$replace[]    = $productHelper->getProductFormattedPrice($row->order_shipping - $row->order_shipping_tax);
			$replace[]    = JText::_($shipping_rate_name);
			$replace[]    = $productHelper->getProductFormattedPrice($row->order_shipping);
			$replace[]    = $productHelper->getProductFormattedPrice($row->order_shipping_tax);

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
			$template = str_replace($search, $replace, $template);
		}
		else
		{
			$template = str_replace($search, array("", "", "", ""), $template);
		}

		return $template;
	}

	/**
	 * @param   string   $template
	 * @param   array    $cart
	 * @param   boolean  $replace_button
	 * @param   int      $quotationMode
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceCartItem($template, $cart = array(), $replace_button = false, $quotationMode = 0)
	{
		$input = JFactory::getApplication()->input;
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = JEventDispatcher::getInstance();
		$Itemid     = RedshopHelperUtility::getCheckoutItemId();
		$mainview   = $input->get('view');

		$productHelper = productHelper::getInstance();

		if ($Itemid == 0)
		{
			$Itemid = $input->getInt('Itemid');
		}

		$cart_tr = '';

		$idx        = $cart['idx'];
		$fieldArray = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

		if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_DELETE')))
		{
			$delete_img = Redshop::getConfig()->get('ADDTOCART_DELETE');
		}
		else
		{
			$delete_img = "defaultcross.png";
		}

		for ($i = 0; $i < $idx; $i++)
		{
			$cart_mdata = $template;

			// Plugin support:  Process the product plugin for cart item
			$dispatcher->trigger('onCartItemDisplay', array(&$cart_mdata, $cart, $i));

			$quantity = $cart[$i]['quantity'];

			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
			{
				$giftcard_id  = $cart[$i]['giftcard_id'];
				$giftcardData = $productHelper->getGiftcardData($giftcard_id);
				$link         = JRoute::_('index.php?option=com_redshop&view=giftcard&gid=' . $giftcard_id . '&Itemid=' . $Itemid);
				$reciverInfo = '<div class="reciverInfo">' . JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL') . ': ' . $cart[$i]['reciver_name']
					. '<br />' . JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL') . ': ' . $cart[$i]['reciver_email'] . '</div>';

				$product_name = "<div  class='product_name'><a href='" . $link . "'>" . $giftcardData->giftcard_name . "</a></div>" . $reciverInfo;

				if (strpos($cart_mdata, "{product_name_nolink}") !== false)
				{
					$product_name_nolink = "<div  class=\"product_name\">" . $giftcardData->giftcard_name . "</div><" . $reciverInfo;
					$cart_mdata          = str_replace("{product_name_nolink}", $product_name_nolink, $cart_mdata);

					if (strpos($cart_mdata, "{product_name}") !== false)
						$cart_mdata = str_replace("{product_name}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_name}", $product_name, $cart_mdata);
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

				if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
				{
					$cart_mdata = str_replace("{product_total_price}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_price}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_price}", $productHelper->getProductFormattedPrice($cart[$i]['product_price']), $cart_mdata);
					$cart_mdata = str_replace("{product_total_price}", $productHelper->getProductFormattedPrice($cart[$i]['product_price'] * $cart[$i]['quantity'], true), $cart_mdata);
				}

				$cart_mdata     = str_replace("{if product_on_sale}", '', $cart_mdata);
				$cart_mdata     = str_replace("{product_on_sale end if}", '', $cart_mdata);

				$thumbUrl = RedshopHelperMedia::getImagePath(
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
				$user_fields    = $productHelper->GetProdcutUserfield($i, 13);
				$cart_mdata     = str_replace("{product_userfields}", $user_fields, $cart_mdata);
				$cart_mdata     = str_replace("{product_price_excl_vat}", $productHelper->getProductFormattedPrice($cart[$i]['product_price']), $cart_mdata);
				$cart_mdata     = str_replace("{product_total_price_excl_vat}", $productHelper->getProductFormattedPrice($cart[$i]['product_price'] * $cart[$i]['quantity']), $cart_mdata);
				$cart_mdata     = str_replace("{attribute_change}", '', $cart_mdata);
				$cart_mdata     = str_replace("{product_attribute_price}", "", $cart_mdata);
				$cart_mdata     = str_replace("{product_attribute_number}", "", $cart_mdata);
				$cart_mdata     = str_replace("{product_tax}", "", $cart_mdata);

				// ProductFinderDatepicker Extra Field
				$cart_mdata = $productHelper->getProductFinderDatepickerValue($cart_mdata, $giftcard_id, $fieldArray, $giftcard = 1);

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
				$product        = RedshopHelperProduct::getProductById($product_id);
				$retAttArr      = $productHelper->makeAttributeCart($cart [$i] ['cart_attribute'], $product_id, 0, 0, $quantity, $cart_mdata);
				$cart_attribute = $retAttArr[0];

				$retAccArr      = $productHelper->makeAccessoryCart($cart [$i] ['cart_accessory'], $product_id, $cart_mdata);
				$cart_accessory = $retAccArr[0];

				$ItemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $product_id);

				if (count($ItemData) > 0)
				{
					$Itemid = (int) $ItemData->id;
				}
				else
				{
					$Itemid = (int) RedshopHelperUtility::getItemId($product_id);
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

				if ($product_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $product_image))
				{
					$val        = explode("/", $product_image);
					$prd_image  = $val[1];
					$type       = $val[0];
				}
				elseif ($product->product_full_image && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
				{
					$prd_image = $product->product_full_image;
					$type      = 'product';
				}
				elseif (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
				{
					$prd_image = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
					$type      = 'product';
				}

				$isAttributeImage = false;

				if (isset($cart[$i]['attributeImage']))
				{
					$isAttributeImage = JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $cart[$i]['attributeImage']);
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

						$product_image = "<div  class='product_image'><a href='" . $link . "'><img src='" . $product_cart_img . "'></a></div>";
					}
					else
					{
						$thumbUrl = RedshopHelperMedia::getImagePath(
							$prd_image,
							'',
							'thumb',
							$type,
							Redshop::getConfig()->get('CART_THUMB_WIDTH'),
							Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);

						$product_image = "<div  class='product_image'><a href='" . $link . "'><img src='" . $thumbUrl . "'></a></div>";
					}
				}
				else
				{
					$product_image = "<div  class='product_image'></div>";
				}

				// Trigger to change product image.
				$dispatcher->trigger('OnSetCartOrderItemImage', array(&$cart, &$product_image, $product, $i));

				$chktag              = $productHelper->getApplyVatOrNot($template);
				$product_total_price = "<div class='product_price'>";

				if (!$quotationMode || ($quotationMode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					if (!$chktag)
					{
						$product_total_price .= $productHelper->getProductFormattedPrice($cart[$i]['product_price_excl_vat'] * $quantity);
					}
					else
					{
						$product_total_price .= $productHelper->getProductFormattedPrice($cart[$i]['product_price'] * $quantity);
					}
				}

				$product_total_price .= "</div>";

				$product_old_price = "";
				$product_price     = "<div class='product_price'>";

				if (!$quotationMode || ($quotationMode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					if (!$chktag)
					{
						$product_price .= $productHelper->getProductFormattedPrice($cart[$i]['product_price_excl_vat'], true);
					}
					else
					{
						$product_price .= $productHelper->getProductFormattedPrice($cart[$i]['product_price'], true);
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

						$product_old_price = $productHelper->getProductFormattedPrice($product_old_price, true);
					}
				}

				$product_price .= "</div>";

				$wrapper_name = "";

				if ((array_key_exists('wrapper_id', $cart[$i])) && $cart[$i]['wrapper_id'])
				{
					$wrapper = $productHelper->getWrapper($product_id, $cart[$i]['wrapper_id']);

					if (count($wrapper) > 0)
					{
						$wrapper_name = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper[0]->wrapper_name;

						if (!$quotationMode || ($quotationMode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
						{
							$wrapper_name .= "(" . $productHelper->getProductFormattedPrice($cart[$i]['wrapper_price'], true) . ")";
						}
					}
				}

				if (strpos($cart_mdata, "{product_name_nolink}") !== false)
				{
					$product_name_nolink = "";
					$product_name_nolink = "<div  class='product_name'>$product->product_name</a></div>";
					$cart_mdata          = str_replace("{product_name_nolink}", $product_name_nolink, $cart_mdata);

					if (strpos($cart_mdata, "{product_name}") !== false)
						$cart_mdata = str_replace("{product_name}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_name}", $product_name, $cart_mdata);
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

								$product_attribute_value_price = $productHelper->getProductFormattedPrice($product_attribute_value_price);
							}

							$productAttributeCalculatedPrice = $productHelper->getProductFormattedPrice(
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
				$user_fields          = $productHelper->GetProdcutUserfield($i);
				$cart_mdata           = str_replace("{product_userfields}", $user_fields, $cart_mdata);
				$user_custom_fields   = $productHelper->GetProdcutfield($i);
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
				$cart_mdata           = $productHelper->getProductOnSaleComment($product, $cart_mdata, $product_old_price);
				$cart_mdata           = str_replace("{product_old_price}", $product_old_price, $cart_mdata);
				$cart_mdata           = str_replace("{product_wrapper}", $wrapper_name, $cart_mdata);
				$cart_mdata           = str_replace("{product_thumb_image}", $product_image, $cart_mdata);
				$cart_mdata           = str_replace("{attribute_price_without_vat}", '', $cart_mdata);
				$cart_mdata           = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

				// ProductFinderDatepicker Extra Field Start
				$cart_mdata = $productHelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray);

				$product_price_excl_vat = $cart[$i]['product_price_excl_vat'];

				if (!$quotationMode || ($quotationMode && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
				{
					$cart_mdata = str_replace("{product_price_excl_vat}", $productHelper->getProductFormattedPrice($product_price_excl_vat), $cart_mdata);
					$cart_mdata = str_replace("{product_total_price_excl_vat}", $productHelper->getProductFormattedPrice($product_price_excl_vat * $quantity), $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_price_excl_vat}", "", $cart_mdata);
					$cart_mdata = str_replace("{product_total_price_excl_vat}", "", $cart_mdata);
				}

				// $cart[$i]['product_price_excl_vat'] = $product_price_excl_vat;
				RedshopHelperCartSession::setCart($cart);

				if ($product->product_type == 'subscription')
				{
					$subscription_detail   = $productHelper->getProductSubscriptionDetail($product->product_id, $cart[$i]['subscription_id']);
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
				$cart_mdata = $productHelper->replaceVatinfo($cart_mdata);
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

					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_UPDATE')))
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

				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('ADDTOCART_DELETE')))
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

			$cart_tr .= $cart_mdata;
		}

		return $cart_tr;
	}
}
