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
}