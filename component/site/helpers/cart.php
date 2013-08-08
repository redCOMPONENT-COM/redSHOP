<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/extra_field.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/order.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/shipping.php';

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

	public function __construct()
	{
		$this->_table_prefix    = '#__' . TABLE_PREFIX . '_';
		$this->_db              = Jfactory::getDBO();
		$this->_session         = JFactory::getSession();
		$this->_order_functions = new order_functions;
		$this->_extra_field     = new extra_field;
		$this->_extraFieldFront = new extraField;
		$this->_redhelper       = new redhelper;
		$this->_producthelper   = new producthelper;
		$this->_shippinghelper  = new shipping;
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
	 */
	public function replaceTax($data = '', $amount = 0, $discount = 0, $check = 0, $quotation_mode = 0)
	{
		if (strstr($data, '{if vat}') && strstr($data, '{vat end if}'))
		{
			$cart = $this->_session->get('cart');

			if ($amount <= 0)
			{
				$template_vat_sdata = explode('{if vat}', $data);
				$template_vat_edata = explode('{vat end if}', $template_vat_sdata[1]);
				$data               = $template_vat_sdata[0] . $template_vat_edata[1];
			}
			else
			{
				if ($quotation_mode && !SHOW_QUOTATION_PRICE)
				{
					$data = str_replace("{tax}", "", $data);
					$data = str_replace("{order_tax}", "", $data);
				}
				else
				{
					$data = str_replace("{tax}", $this->_producthelper->getProductFormattedPrice($amount, true), $data);
					$data = str_replace("{order_tax}", $this->_producthelper->getProductFormattedPrice($amount, true), $data);
				}

				if (strstr($data, '{tax_after_discount}'))
				{
					if (APPLY_VAT_ON_DISCOUNT && VAT_RATE_AFTER_DISCOUNT)
					{
						if ($check)
						{
							$tax_after_discount = $discount;
						}
						else
						{
							if (!isset($cart['tax_after_discount']))
							{
								$tax_after_discount = $this->calculateTaxafterDiscount($amount, $discount);
							}
							else
							{
								$tax_after_discount = $cart['tax_after_discount'];
							}
						}

						if ($tax_after_discount > 0)
						{
							$data = str_replace("{tax_after_discount}", $this->_producthelper->getProductFormattedPrice($tax_after_discount), $data);
						}
						else
						{
							$data = str_replace("{tax_after_discount}", $this->_producthelper->getProductFormattedPrice($cart['tax']), $data);
						}
					}
					else
					{
						$data = str_replace("{tax_after_discount}", $this->_producthelper->getProductFormattedPrice($cart['tax']), $data);
					}
				}

				$data = str_replace("{vat_lbl}", JText::_('COM_REDSHOP_CHECKOUT_VAT_LBL'), $data);
				$data = str_replace("{if vat}", '', $data);
				$data = str_replace("{vat end if}", '', $data);
			}
		}

		return $data;
	}

	/*
	 * Calculate tax after Discount is apply
	 */

	public function calculateTaxafterDiscount($tax = 0, $discount = 0)
	{
		$tax_after_discount = 0;
		$cart               = $this->_session->get('cart');

		if (APPLY_VAT_ON_DISCOUNT && VAT_RATE_AFTER_DISCOUNT)
		{
			if ($discount > 0)
			{
				$tmptax             = VAT_RATE_AFTER_DISCOUNT * $discount;
				$tax_after_discount = $tax - $tmptax;
			}
		}

		$cart['tax_after_discount'] = $tax_after_discount;
		$this->_session->set('cart', $cart);

		return $tax_after_discount;
	}

	/*
	 * replace Conditional tag from Redshop Discount
	 */

	public function replaceDiscount($data = '', $discount = 0, $subtotal = 0, $quotation_mode = 0)
	{
		if (strstr($data, '{if discount}') && strstr($data, '{discount end if}'))
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

				if ($quotation_mode && !SHOW_QUOTATION_PRICE)
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
		if (strstr($data, '{if payment_discount}') && strstr($data, '{payment_discount end if}'))
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
	 * @param int $total
	 * @param     $paymentinfo
	 * @param     $finalAmount
	 *
	 * @return array
	 */
	public function calculatePayment($total = 0, $paymentinfo, $finalAmount)
	{
		$payment_discount = 0;
		$payment          = array();

		if ($paymentinfo->payment_discount_is_percent == 0)
		{
			$payment_discount = $paymentinfo->payment_price;
		}
		else
		{
			if ($paymentinfo->payment_price > 0)
			{
				$payment_discount = $total * $paymentinfo->payment_price / 100;
			}
		}

		if ($payment_discount)
		{
			$payment_discount = round($payment_discount, 2);
		}

		if ($payment_discount > 0)
		{
			if ($total < $payment_discount)
			{
				$payment_discount = $total;
			}

			if ($paymentinfo->payment_oprand == '+')
			{
				$finalAmount = $finalAmount + $payment_discount;
			}
			else
			{
				$finalAmount = $finalAmount - $payment_discount;
			}
		}

		$payment[0] = $finalAmount;
		$payment[1] = $payment_discount;

		return $payment;
	}

	/**
	 * replace Billing Address
	 *
	 * @param $data
	 * @param $billingaddresses
	 *
	 * @return mixed
	 */
	public function replaceBillingAddress($data, $billingaddresses)
	{
		if (strstr($data, '{billing_address_start}') && strstr($data, '{billing_address_end}'))
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

					if (SHOW_TAX_EXEMPT_INFRONT)
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
		elseif (strstr($data, '{billing_address}'))
		{
			$billadd = '';

			if (isset($billingaddresses))
			{
				$extra_section = ($billingaddresses->is_company == 1) ? 8 : 7;

				if ($billingaddresses->is_company == 1 && $billingaddresses->company_name != "")
				{
					$billadd .= JText::_('COM_REDSHOP_COMPANY_NAME') . ' : ' . $billingaddresses->company_name . '<br />';
				}

				if ($billingaddresses->firstname != "")
				{
					$billadd .= JText::_("COM_REDSHOP_FIRSTNAME") . ' : ' . $billingaddresses->firstname . '<br />';
				}

				if ($billingaddresses->lastname != "")
				{
					$billadd .= JText::_("COM_REDSHOP_LASTNAME") . ' : ' . $billingaddresses->lastname . '<br />';
				}

				if ($billingaddresses->address != "")
				{
					$billadd .= JText::_("COM_REDSHOP_ADDRESS") . ' : ' . $billingaddresses->address . '<br /> ';
				}

				if ($billingaddresses->zipcode != "")
				{
					$billadd .= JText::_("COM_REDSHOP_ZIP") . ' : ' . $billingaddresses->zipcode . '<br />';
				}

				if ($billingaddresses->city != "")
				{
					$billadd .= JText::_("COM_REDSHOP_CITY") . ' : ' . $billingaddresses->city . '<br /> ';
				}

				$cname = $this->_order_functions->getCountryName($billingaddresses->country_code);

				if ($cname != "")
				{
					$billadd .= JText::_("COM_REDSHOP_COUNTRY") . ' : ' . JText::_($cname) . '<br />';
				}

				$sname = $this->_order_functions->getStateName($billingaddresses->state_code, $billingaddresses->country_code);

				if ($sname != "")
				{
					$billadd .= JText::_("COM_REDSHOP_STATE") . ' : ' . $sname . '<br />';
				}

				if ($billingaddresses->phone != "")
				{
					$billadd .= JText::_("COM_REDSHOP_PHONE") . ' : ' . $billingaddresses->phone . '<br/>';
				}

				if ($billingaddresses->user_email != "")
				{
					$billadd .= JText::_("COM_REDSHOP_EMAIL") . ' : ' . $billingaddresses->user_email . '<br />';
				}
				elseif ($user->email != '')
				{
					$billadd .= JText::_("COM_REDSHOP_EMAIL") . ' : ' . $user->email . '<br />';
				}

				if ($billingaddresses->is_company == 1)
				{
					if ($billingaddresses->vat_number != "")
					{
						$billadd .= JText::_("COM_REDSHOP_VAT_NUMBER") . ' : ' . $billingaddresses->vat_number . '<br />';
					}

					if ($billingaddresses->ean_number != "")
					{
						$billadd .= JText::_("COM_REDSHOP_EAN_NUMBER") . ' : ' . $billingaddresses->ean_number . '<br />';
					}

					if (SHOW_TAX_EXEMPT_INFRONT)
					{
						$billadd .= JText::_("COM_REDSHOP_TAX_EXEMPT") . ' : ';

						if ($billingaddresses->tax_exempt == 1)
						{
							$taxexe = JText::_("COM_REDSHOP_YES");
						}
						else
						{
							$taxexe = JText::_("COM_REDSHOP_NO");
						}

						$billadd .= $taxexe . '<br />';

						$billadd .= JText::_("COM_REDSHOP_USER_TAX_EXEMPT_REQUEST_LBL") . ' : ';

						if ($billingaddresses->requesting_tax_exempt == 1)
						{
							$taxexereq = JText::_("COM_REDSHOP_YES");
						}
						else
						{
							$taxexereq = JText::_("COM_REDSHOP_NO");
						}

						$billadd .= $taxexereq . '<br />';
					}
				}

				$billadd .= $this->_extra_field->list_all_field_display($extra_section, $billingaddresses->users_info_id, 1);

				if (DEFAULT_QUOTATION_MODE)
				{
					if (strstr($data, "{quotation_custom_field_list}"))
					{
						$billing .= $this->_extra_field->list_all_field(16, $billingaddresses->users_info_id, "", "");
						$data = str_replace("{quotation_custom_field_list}", "", $data);
					}
					else
					{
						$data = $this->_extra_field->list_all_field(16, $billingaddresses->users_info_id, "", "", $data);
					}
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
	public function replaceShippingAddress($data, $shippingaddresses)
	{
		if (strstr($data, '{shipping_address_start}') && strstr($data, '{shipping_address_end}'))
		{
			$template_sdata = explode('{shipping_address_start}', $data);
			$template_edata = explode('{shipping_address_end}', $template_sdata[1]);
			$shippingdata   = (SHIPPING_METHOD_ENABLE) ? $template_edata[0] : '';

			$shipping_extrafield = '';

			if (isset($shippingaddresses) && SHIPPING_METHOD_ENABLE)
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
		elseif (strstr($data, '{shipping_address}'))
		{
			$shipadd = '';

			if (isset($shippingaddresses) && SHIPPING_METHOD_ENABLE)
			{
				if ($shippingaddresses->is_company == 1 && $shippingaddresses->company_name != "")
				{
					$shipadd .= JText::_('COM_REDSHOP_COMPANY_NAME') . ' : ' . $shippingaddresses->company_name . '<br />';
				}

				if ($shippingaddresses->firstname != "")
				{
					$shipadd .= JText::_("COM_REDSHOP_FIRSTNAME") . ' : ' . $shippingaddresses->firstname . '<br />';
				}

				if ($shippingaddresses->lastname != "")
				{
					$shipadd .= JText::_("COM_REDSHOP_LASTNAME") . ' : ' . $shippingaddresses->lastname . '<br />';
				}

				if ($shippingaddresses->address != "")
				{
					$shipadd .= JText::_("COM_REDSHOP_ADDRESS") . ' : ' . $shippingaddresses->address . '<br />';
				}

				if ($shippingaddresses->zipcode != "")
				{
					$shipadd .= JText::_("COM_REDSHOP_ZIP") . ' : ' . $shippingaddresses->zipcode . '<br />';
				}

				if ($shippingaddresses->city != "")
				{
					$shipadd .= JText::_("COM_REDSHOP_CITY") . ' : ' . $shippingaddresses->city . '<br />';
				}

				$cname = $this->_order_functions->getCountryName($shippingaddresses->country_code);

				if ($cname != "")
				{
					$shipadd .= JText::_("COM_REDSHOP_COUNTRY") . ' : ' . JText::_($cname) . '<br />';
				}

				$sname = $this->_order_functions->getStateName($shippingaddresses->state_code, $shippingaddresses->country_code);

				if ($sname != "")
				{
					$shipadd .= JText::_("COM_REDSHOP_STATE") . ' : ' . $sname . '<br />';
				}

				if ($shippingaddresses->phone != "")
				{
					$shipadd .= JText::_("COM_REDSHOP_PHONE") . ' : ' . $shippingaddresses->phone . '<br />';
				}

				if ($shippingaddresses->is_company == 1)
				{
					// Additional functionality - more flexible way
					$data = $this->_extraFieldFront->extra_field_display(15, $shippingaddresses->users_info_id, "", $data);

					$shipadd .= $this->_extra_field->list_all_field_display(15, $shippingaddresses->users_info_id, 1);
				}
				else
				{
					// Additional functionality - more flexible way
					$data = $this->_extraFieldFront->extra_field_display(14, $shippingaddresses->users_info_id, "", $data);

					$shipadd .= $this->_extra_field->list_all_field_display(14, $shippingaddresses->users_info_id, 1);
				}
			}

			$data = str_replace("{shipping_address}", $shipadd, $data);
		}

		$shippingtext = (SHIPPING_METHOD_ENABLE) ? JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL') : '';
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
		$search[] = "{shipping_method}";
		$search[] = "{order_shipping}";
		$search[] = "{shipping_excl_vat}";
		$search[] = "{shipping_rate_name}";
		$search[] = "{shipping}";
		$search[] = "{vat_shipping}";
		$search[] = "{order_shipping_shop_location}";

		if (SHIPPING_METHOD_ENABLE)
		{
			$details = explode("|", $this->_shippinghelper->decryptShipping(str_replace(" ", "+", $row->ship_method_id)));

			if (count($details) <= 1)
			{
				$details = explode("|", $row->ship_method_id);
			}

			$shipping_method    = "";
			$shipping_rate_name = "";

			if (count($details) > 0)
			{
				if (array_key_exists(1, $details))
				{
					$shipping_method = $details[1];
				}

				if (array_key_exists(2, $details))
				{
					$shipping_rate_name = $details[2];
				}
			}

			// $shopLocation = $this->_shippinghelper->decryptShipping( str_replace(" ","+",$row->shop_id) );
			$shopLocation = $row->shop_id;
			$replace[]    = $shipping_method;
			$replace[]    = $this->_producthelper->getProductFormattedPrice($row->order_shipping);
			$replace[]    = $this->_producthelper->getProductFormattedPrice($row->order_shipping - $row->order_shipping_tax);
			$replace[]    = $shipping_rate_name;
			$replace[]    = $this->_producthelper->getProductFormattedPrice($row->order_shipping);
			$replace[]    = $this->_producthelper->getProductFormattedPrice($row->order_shipping_tax);

			if ($details[0] != 'plgredshop_shippingdefault_shipping_GLS')
			{
				$shopLocation = '';
			}

			$mobilearr = array();

			if ($shopLocation)
			{
				$mobilearr          = explode('###', $shopLocation);
				$arrLocationDetails = explode('|', $shopLocation);
				$shopLocation       = "<b>" . $arrLocationDetails[0] . $arrLocationDetails[1] . '</b><br>';
				$shopLocation .= $arrLocationDetails[2] . '<br>';
				$shopLocation .= $arrLocationDetails[3] . $arrLocationDetails[4] . '<br>';
				$shopLocation .= $arrLocationDetails[5] . '<br>';
				$arrLocationTime = explode('  ', $arrLocationDetails[6]);

				for ($t = 0; $t < count($arrLocationTime); $t++)
				{
					$shopLocation .= $arrLocationTime[$t] . '<br>';
				}
			}

			if (isset($mobilearr[1]) === true)
			{
				$replace[] = $shopLocation . ' ' . $mobilearr[1];
			}
			else
			{
				$replace[] = $shopLocation;
			}

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
		$dispatcher = JDispatcher::getInstance();
		$prdItemid  = JRequest::getInt('Itemid');
		$option     = JRequest::getVar('option', 'com_redshop');
		$Itemid     = $this->_redhelper->getCheckoutItemid();
		$url        = JURI::base(true);
		$mainview   = JRequest::getVar('view');

		if ($Itemid == 0)
		{
			$Itemid = JRequest::getInt('Itemid');
		}

		$cart_tr = '';
		$i       = 0;

		$idx        = $cart['idx'];
		$fieldArray = $this->_extraFieldFront->getSectionFieldList(17, 0, 0);

		if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . ADDTOCART_DELETE))
		{
			$delete_img = ADDTOCART_DELETE;
		}
		else
		{
			$delete_img = "defaultcross.jpg";
		}

		for ($i = 0; $i < $idx; $i++)
		{
			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
			{
				$giftcard_id  = $cart[$i]['giftcard_id'];
				$giftcardData = $this->_producthelper->getGiftcardData($giftcard_id);
				$link         = JRoute::_('index.php?option=' . $option . '&view=giftcard&gid=' . $giftcard_id . '&Itemid=' . $Itemid);

				$product_name = "<div  class='product_name'><a href='" . $link . "'>" . $giftcardData->giftcard_name . "</a></div>";

				if (strstr($data, "{product_name_nolink}"))
				{
					$product_name_nolink = "<div  class='product_name'>$giftcardData->giftcard_name</a></div>";
					$cart_mdata          = str_replace("{product_name_nolink}", $product_name_nolink, $data);

					if (strstr($data, "{product_name}"))
						$cart_mdata = str_replace("{product_name}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_name}", $product_name, $data);
				}

				// $cart_mdata=str_replace("{product_name}",$product_name,$data);
				$cart_mdata = str_replace("{product_attribute}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_accessory}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_wrapper}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_old_price}", '', $cart_mdata);
				$cart_mdata = str_replace("{vat_info}", '', $cart_mdata);
				$cart_mdata = str_replace("{update_cart}", '', $cart_mdata);
				$cart_mdata = str_replace("{product_number}", '', $cart_mdata);
				$cart_mdata = str_replace("{attribute_price_without_vat}", '', $cart_mdata);
				$cart_mdata = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

				if ($quotation_mode && !SHOW_QUOTATION_PRICE)
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
				$giftcard_image = "<div  class='giftcard_image'><img src='"
					. $url . "/components/com_redshop/helpers/thumb.php?filename=giftcard/"
					. $giftcardData->giftcard_image
					. "&newxsize=" . CART_THUMB_WIDTH
					. "&newysize=" . CART_THUMB_HEIGHT
					. "&swap=" . USE_IMAGE_SIZE_SWAPPING
					. "'></div>";
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
				$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray, $giftcard = 1);

				$remove_product = '<form style="" class="rs_hiddenupdatecart" name="delete_cart' . $i . '" method="POST" >
				<input type="hidden" name="giftcard_id" value="' . $cart[$i]['giftcard_id'] . '">
				<input type="hidden" name="cart_index" value="' . $i . '">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="Itemid" value="' . $Itemid . '">
				<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $delete_img
					. '" title="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL')
					. '" alt="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL')
					. '" onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';

				if (QUANTITY_TEXT_DISPLAY)
				{
					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{remove_product}", $remove_product, $cart_mdata);
				}
			}
			else
			{
				$product_id     = $cart[$i]['product_id'];
				$product        = $this->_producthelper->getProductById($product_id);
				$quantity       = $cart[$i]['quantity'];
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
					$Itemid = $this->_redhelper->getItemid($product_id);
				}

				$link = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $product_id . '&Itemid=' . $Itemid);

				$pname        = $product->product_name;
				$product_name = "<div  class='product_name'><a href='" . $link . "'>" . $pname . "</a></div>";

				$product_image      = "";
				$product_image_path = "";

				if (WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART && isset($cart[$i]['hidden_attribute_cartimage']))
				{
					$image_path    = REDSHOP_FRONT_IMAGES_ABSPATH;
					$product_image = str_replace($image_path, '', $cart[$i]['hidden_attribute_cartimage']);
				}

				if ($product_image && is_file(REDSHOP_FRONT_IMAGES_RELPATH . $product_image))
				{
					$product_image_path = JURI::base() . "/components/com_redshop/helpers/thumb.php?filename=" . $product_image;
					$val                = explode("/", $product_image);
					$prd_image          = $val[1];
					$type               = $val[0];

				}
				elseif ($product->product_full_image && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
				{
					$product_image_path = $url . "/components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_full_image;
					$prd_image          = $product->product_full_image;
					$type               = 'product';
				}
				elseif (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE))
				{
					$product_image_path = $url . "/components/com_redshop/helpers/thumb.php?filename=product/" . PRODUCT_DEFAULT_IMAGE;
					$prd_image          = PRODUCT_DEFAULT_IMAGE;
					$type               = 'product';
				}

				$isAttributeImage = false;

				if (isset($cart[$i]['attributeImage']))
				{
					$isAttributeImage = is_file(REDSHOP_FRONT_IMAGES_RELPATH . "mergeImages/" . $cart[$i]['attributeImage']);
				}

				if ($isAttributeImage)
				{
					$product_image_path = $url . "/components/com_redshop/helpers/thumb.php?filename=mergeImages/" . $cart[$i]['attributeImage'];
					$prd_image          = $cart[$i]['attributeImage'];
					$type               = 'mergeImages';
				}

				if ($product_image_path)
				{
					$redhelper = new redhelper;

					if (WATERMARK_CART_THUMB_IMAGE && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . WATERMARK_IMAGE))
					{
						$product_cart_img = $redhelper->watermark($type, $prd_image, CART_THUMB_WIDTH, CART_THUMB_HEIGHT, WATERMARK_CART_THUMB_IMAGE, '0');

						$product_image = "<div  class='product_image'><img src='" . $product_cart_img . "'></div>";
					}
					else
					{
						$product_image = "<div  class='product_image'><img src='" . $product_image_path
							. "&newxsize=" . CART_THUMB_WIDTH
							. "&newysize=" . CART_THUMB_HEIGHT
							. "&swap=" . USE_IMAGE_SIZE_SWAPPING
							. "'></div>";
					}
				}
				else
				{
					$product_image = "<div  class='product_image'></div>";
				}

				$chktag              = $this->_producthelper->getApplyVatOrNot($data);
				$product_total_price = "<div class='product_price'>";

				if (!$quotation_mode || ($quotation_mode && SHOW_QUOTATION_PRICE))
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

				if (!$quotation_mode || ($quotation_mode && SHOW_QUOTATION_PRICE))
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

						if (!$quotation_mode || ($quotation_mode && SHOW_QUOTATION_PRICE))
						{
							$wrapper_name .= "(" . $this->_producthelper->getProductFormattedPrice($cart[$i]['wrapper_price'], true) . ")";
						}
					}
				}

				$cart_mdata = '';

				if (strstr($data, "{product_name_nolink}"))
				{
					$product_name_nolink = "";
					$product_name_nolink = "<div  class='product_name'>$product->product_name</a></div>";
					$cart_mdata          = str_replace("{product_name_nolink}", $product_name_nolink, $data);

					if (strstr($data, "{product_name}"))
						$cart_mdata = str_replace("{product_name}", "", $cart_mdata);
				}
				else
				{
					$cart_mdata = str_replace("{product_name}", $product_name, $data);
				}

				$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);

				// Replace Attribute data
				if (strstr($cart_mdata, "{product_attribute_loop_start}") && strstr($cart_mdata, "{product_attribute_loop_end}"))
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

								if (count($temp_tpi[$tpi]['attribute_childs'][0]['property_childs']) > 0)
								{
									$product_attribute_value_price = $product_attribute_value_price + $temp_tpi[$tpi]['attribute_childs'][0]['property_childs'][0]['subproperty_price'];
								}

								$product_attribute_value_price = $this->_producthelper->getProductFormattedPrice($product_attribute_value_price);
							}

							$data_add_pro = $templateattibute_middle;
							$data_add_pro = str_replace("{product_attribute_name}", $product_attribute_name, $data_add_pro);
							$data_add_pro = str_replace("{product_attribute_value}", $product_attribute_value, $data_add_pro);
							$data_add_pro = str_replace("{product_attribute_value_price}", $product_attribute_value_price, $data_add_pro);
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
				$cart_mdata           = str_replace("{product_attribute}", $discount_calc_output . $cart_attribute, $cart_mdata);
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

				if (!$quotation_mode || ($quotation_mode && SHOW_QUOTATION_PRICE))
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

					$update_cart_none = '<label>' . $quantity . '</label>';

					$update_img = '';

					if ($mainview == 'checkout')
					{
						$update_cart = $quantity;
					}
					else
					{
						$update_cart = '<form style="padding:0px;margin:0px;" name="update_cart' . $i . '" method="POST" >';
						$update_cart .= '<input class="inputbox" type="text" value="' . $quantity . '" name="quantity" id="quantitybox' . $i . '" size="' . DEFAULT_QUANTITY . '" maxlength="' . DEFAULT_QUANTITY . '" onchange="validateInputNumber(this.id);">';
						$update_cart .= '<input type="hidden" name="product_id" value="' . $product_id . '">
								<input type="hidden" name="cart_index" value="' . $i . '">
								<input type="hidden" name="Itemid" value="' . $Itemid . '">
								<input type="hidden" name="task" value="">';

						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . ADDTOCART_UPDATE))
						{
							$update_img = ADDTOCART_UPDATE;
						}
						else
						{
							$update_img = "defaultupdate.jpg";
						}

						$update_cart .= '<img class="update_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $update_img . '" title="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" onclick="document.update_cart' . $i . '.task.value=\'update\';document.update_cart' . $i . '.submit();">';

						$update_cart .= '</form>';

					}

					$update_cart_minus_plus = '<form name="update_cart' . $i . '" method="POST">';

					$update_cart_minus_plus .= '<input type="text" id="quantitybox' . $i . '" name="quantity"  size="1"  value="' . $quantity . '" /><input type="button" id="minus" value="-"
									    onClick="quantity.value = (quantity.value) ; var qty1 = quantity.value; if( !isNaN( qty1 ) &amp;&amp; qty1 > 1 ) quantity.value--;return false;">';

					$update_cart_minus_plus .= '<input type="button" value="+"
									    onClick="quantity.value = (+quantity.value+1)"><input type="hidden" name="product_id" value="' . $product_id . '">
																	<input type="hidden" name="cart_index" value="' . $i . '">
																	<input type="hidden" name="Itemid" value="' . $Itemid . '">
																	<input type="hidden" name="task" value=""><img class="update_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $update_img . '" title="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_UPDATE_PRODUCT_FROM_CART_LBL') . '" onclick="document.update_cart' . $i . '.task.value=\'update\';document.update_cart' . $i . '.submit();">
									</form>
									';

					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . ADDTOCART_DELETE))
					{
						$delete_img = ADDTOCART_DELETE;
					}
					else
					{
						$delete_img = "defaultcross.jpg";
					}

					$empty_cart = '<form style="padding:0px;margin:0px;" name="delete_cart' . $i . '" method="POST" >
								<input type="hidden" name="product_id" value="' . $product_id . '">
								<input type="hidden" name="task" value="">
								<input type="hidden" name="Itemid" value="' . $Itemid . '">
								<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $delete_img . '"  onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';

					if ($mainview == 'checkout')
					{
						$remove_product = '';

					}
					else
					{
						$remove_product = '<form style="padding:0px;margin:0px;" name="delete_cart' . $i . '" method="POST" >
								<input type="hidden" name="product_id" value="' . $product_id . '">
								<input type="hidden" name="cart_index" value="' . $i . '">
								<input type="hidden" name="task" value="">
								<input type="hidden" name="Itemid" value="' . $Itemid . '">
								<img class="delete_cart" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $delete_img . '" title="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL') . '" alt="' . JText::_('COM_REDSHOP_DELETE_PRODUCT_FROM_CART_LBL') . '" onclick="document.delete_cart' . $i . '.task.value=\'delete\';document.delete_cart' . $i . '.submit();"></form>';

					}

					if (QUANTITY_TEXT_DISPLAY)
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
					$cart_mdata = str_replace("{attribute_change}", '', $cart_mdata);
				}

				$cart_mdata = $this->_producthelper->replaceVatinfo($cart_mdata);
				$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);
				$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);
			}

			// Plugin support:  Process the product plugin for cart item
			JPluginHelper::importPlugin('redshop_product');
			$results = $dispatcher->trigger('onCartItemDisplay', array(& $cart_mdata, $cart, $i));

			$cart_tr .= $cart_mdata;
		}

		return $cart_tr;
	}

	public function repalceOrderItems($data, $rowitem = array())
	{
		$dispatcher = JDispatcher::getInstance();
		$mainview   = JRequest::getVar('view');
		$fieldArray = $this->_extraFieldFront->getSectionFieldList(17, 0, 0);

		$subtotal_excl_vat = 0;
		$cart              = '';
		$url               = JURI::root();
		$returnArr         = array();

		$wrapper_name = "";

		$OrdersDetail = $this->_order_functions->getOrderDetails($rowitem [0]->order_id);

		for ($i = 0; $i < count($rowitem); $i++)
		{
			$product_id = $rowitem [$i]->product_id;
			$quantity   = $rowitem [$i]->product_quantity;

			if ($rowitem [$i]->is_giftcard)
			{
				$giftcardData      = $this->_producthelper->getGiftcardData($product_id);
				$product_name      = $giftcardData->giftcard_name;
				$userfield_section = 13;
			}
			else
			{
				$product           = $this->_producthelper->getProductById($product_id);
				$product_name      = $product->product_name;
				$userfield_section = 12;
			}

			$dirname = JPATH_COMPONENT_SITE . "/assets/images/orderMergeImages/" . $rowitem [$i]->attribute_image;

			if (is_file($dirname))
			{
				$attribute_image_path = $url . "components/com_redshop/helpers/thumb.php?filename=orderMergeImages/" . $rowitem [$i]->attribute_image . "&newxsize=" . CART_THUMB_WIDTH . "&newysize=" . CART_THUMB_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
				$attrib_img           = "<img src='" . $attribute_image_path . "'>";
			}
			else
			{
				if (is_file(JPATH_COMPONENT_SITE . "/assets/images/product_attributes/" . $rowitem [$i]->attribute_image))
				{
					$attribute_image_path = $url . "components/com_redshop/helpers/thumb.php?filename=product_attributes/" . $rowitem [$i]->attribute_image . "&newxsize=" . CART_THUMB_WIDTH . "&newysize=" . CART_THUMB_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
					$attrib_img           = "<img src='" . $attribute_image_path . "'>";
				}
				else
				{
					if ($product->product_full_image)
					{
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
						{
							$attribute_image_path = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $product->product_full_image;
							$attrib_img           = "<img src='" . $attribute_image_path . "'>";
						}
						else
						{
							if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE))
							{
								$attribute_image_path = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . PRODUCT_DEFAULT_IMAGE;
								$attrib_img           = "<img src='" . $attribute_image_path . "'>";
							}
						}
					}
					else
					{
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE))
						{
							$attribute_image_path = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . PRODUCT_DEFAULT_IMAGE;
							$attrib_img           = "<img src='" . $attribute_image_path . "'>";
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
				$cname = $res->category_name;
				$clink = JRoute::_($url . 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $catId);

			}

			$category_path = "<a href='" . $clink . "'>" . $cname . "</a>";
			$cart_mdata    = str_replace("{category_name}", $category_path, $cart_mdata);

			$cart_mdata = $this->_producthelper->replaceVatinfo($cart_mdata);

			$product_note = "<div class='product_note'>" . $wrapper_name . "</div>";

			$cart_mdata = str_replace("{product_wrapper}", $product_note, $cart_mdata);

			// Make attribute order template output
			$attribute_data = $this->_producthelper->makeAttributeOrder($rowitem[$i]->order_item_id, 0, $product_id, 0, 0, $data);

			// Assign template output into {product_attribute} tag
			$cart_mdata = str_replace("{product_attribute}", $attribute_data->product_attribute, $cart_mdata);

			// Assign template output into {attribute_middle_template} tag
			$cart_mdata = str_replace($attribute_data->attribute_middle_template_core, $attribute_data->attribute_middle_template, $cart_mdata);

			if (strstr($cart_mdata, '{remove_product_attribute_title}'))
			{
				$cart_mdata = str_replace("{remove_product_attribute_title}", "", $cart_mdata);
			}

			if (strstr($cart_mdata, '{remove_product_subattribute_title}'))
			{
				$cart_mdata = str_replace("{remove_product_subattribute_title}", "", $cart_mdata);
			}

			if (strstr($cart_mdata, '{product_attribute_number}'))
			{
				$cart_mdata = str_replace("{product_attribute_number}", "", $cart_mdata);
			}

			$cart_mdata = str_replace("{product_accessory}", $this->_producthelper->makeAccessoryOrder($rowitem [$i]->order_item_id), $cart_mdata);

			$product_userfields = $this->_producthelper->getuserfield($rowitem [$i]->order_item_id, $userfield_section);

			$cart_mdata = str_replace("{product_userfields}", $product_userfields, $cart_mdata);

			$user_custom_fields = $this->_producthelper->GetProdcutfield_order($rowitem [$i]->order_item_id);
			$cart_mdata         = str_replace("{product_customfields}", $user_custom_fields, $cart_mdata);
			$cart_mdata         = str_replace("{product_customfields_lbl}", JText::_("COM_REDSHOP_PRODUCT_CUSTOM_FIELD"), $cart_mdata);

			$cart_mdata = str_replace("{product_sku}", $product->product_number, $cart_mdata);

			$cart_mdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cart_mdata);

			$cart_mdata = str_replace("{product_number}", $product->product_number, $cart_mdata);

			$product_vat = ($rowitem [$i]->product_item_price - $rowitem [$i]->product_item_price_excl_vat) * $rowitem [$i]->product_quantity;

			$cart_mdata = str_replace("{product_vat}", $product_vat, $cart_mdata);

			$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);

			$cart_mdata = $this->_producthelper->getProductOnSaleComment($product, $cart_mdata);

			$cart_mdata = str_replace("{attribute_price_without_vat}", '', $cart_mdata);

			$cart_mdata = str_replace("{attribute_price_with_vat}", '', $cart_mdata);

			// ProductFinderDatepicker Extra Field Start
			$cart_mdata = $this->_producthelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray);

			$cart_mdata = str_replace("{product_thumb_image}", "<div  class='product_image'>" . $attrib_img . "</div>", $cart_mdata);
			$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);

			$cart_mdata = str_replace("{product_old_price}", $product_old_price, $cart_mdata);

			$cart_mdata = str_replace("{product_quantity}", $quantity, $cart_mdata);

			$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);

			$cart_mdata = str_replace("{product_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat), $cart_mdata);

			$cart_mdata = str_replace("{product_total_price_excl_vat}", $this->_producthelper->getProductFormattedPrice($rowitem [$i]->product_item_price_excl_vat * $quantity), $cart_mdata);

			$subtotal_excl_vat += $rowitem [$i]->product_item_price_excl_vat * $quantity;

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

			if ($mainview == "order_detail")
			{
				$Itemid     = JRequest::getVar('Itemid');
				$Itemid     = $this->_redhelper->getCartItemid($Itemid);
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
					$mailtoken    = "<a href='" . JUri::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $download_id . "'>" . $file_name . "</a>";
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

					$mailtoken = "<a href='" . JUri::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid="
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
			JPluginHelper::importPlugin('redshop_product');
			$results = $dispatcher->trigger('onOrderItemDisplay', array(& $cart_mdata, &$rowitem, $i));

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

		if (strstr($data, '{cart_lbl}'))
		{
			$search[]  = "{cart_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_LBL');
		}

		if (strstr($data, '{copy_orderitem_lbl}'))
		{
			$search[]  = "{copy_orderitem_lbl}";
			$replace[] = JText::_('COM_REDSHOP_COPY_ORDERITEM_LBL');
		}

		if (strstr($data, '{totalpurchase_lbl}'))
		{
			$search[]  = "{totalpurchase_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_TOTAL_PURCHASE_TBL');
		}

		if (strstr($data, '{subtotal_excl_vat_lbl}'))
		{
			$search[]  = "{subtotal_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SUBTOTAL_EXCL_VAT_LBL');
		}

		if (strstr($data, '{product_name_lbl}'))
		{
			$search[]  = "{product_name_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME_LBL');
		}

		if (strstr($data, '{price_lbl}'))
		{
			$search[]  = "{price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');
		}

		if (strstr($data, '{quantity_lbl}'))
		{
			$search[]  = "{quantity_lbl}";
			$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');
		}

		if (strstr($data, '{total_price_lbl}'))
		{
			$search[]  = "{total_price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
		}

		if (strstr($data, '{total_price_exe_lbl}'))
		{
			$search[]  = "{total_price_exe_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_EXEL_LBL');
		}

		if (strstr($data, '{order_id_lbl}'))
		{
			$search[]  = "{order_id_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_ID_LBL');
		}

		if (strstr($data, '{order_number_lbl}'))
		{
			$search[]  = "{order_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_NUMBER_LBL');
		}

		if (strstr($data, '{order_date_lbl}'))
		{
			$search[]  = "{order_date_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DATE_LBL');
		}

		if (strstr($data, '{order_status_lbl}'))
		{
			$search[]  = "{order_status_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_STAUS_LBL');
		}

		if (strstr($data, '{order_status_order_only_lbl}'))
		{
			$search[]  = "{order_status_order_only_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_STAUS_LBL');
		}

		if (strstr($data, '{order_status_payment_only_lbl}'))
		{
			$search[]  = "{order_status_payment_only_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_STAUS_LBL');
		}

		if (SHIPPING_METHOD_ENABLE)
		{
			if (strstr($data, '{shipping_lbl}'))
			{
				$search[]  = "{shipping_lbl}";
				$replace[] = JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
			}

			if (strstr($data, '{tax_with_shipping_lbl}'))
			{
				$search[]  = "{tax_with_shipping_lbl}";
				$replace[] = JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
			}
		}
		else
		{
			if (strstr($data, '{shipping_lbl}'))
			{
				$search[]  = "{shipping_lbl}";
				$replace[] = "";
			}

			if (strstr($data, '{tax_with_shipping_lbl}'))
			{
				$search[]  = "{tax_with_shipping_lbl}";
				$replace[] = "";
			}
		}

		if (strstr($data, '{order_information_lbl}'))
		{
			$search[]  = "{order_information_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_INFORMATION_LBL');
		}

		if (strstr($data, '{order_detail_lbl}'))
		{
			$search[]  = "{order_detail_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LBL');
		}

		if (strstr($data, '{product_name_lbl}'))
		{
			$search[]  = "{product_name_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NAME_LBL');
		}

		if (strstr($data, '{note_lbl}'))
		{
			$search[]  = "{note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_NOTE_LBL');
		}

		if (strstr($data, '{price_lbl}'))
		{
			$search[]  = "{price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRICE_LBL');
		}

		if (strstr($data, '{quantity_lbl}'))
		{
			$search[]  = "{quantity_lbl}";
			$replace[] = JText::_('COM_REDSHOP_QUANTITY_LBL');
		}

		if (strstr($data, '{total_price_lbl}'))
		{
			$search[]  = "{total_price_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
		}

		if (strstr($data, '{order_subtotal_lbl}'))
		{
			$search[]  = "{order_subtotal_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_SUBTOTAL_LBL');
		}

		if (strstr($data, '{total_lbl}'))
		{
			$search[]  = "{total_lbl}";
			$replace[] = JText::_('COM_REDSHOP_TOTAL_LBL');
		}

		if (strstr($data, '{discount_type_lbl}'))
		{
			$search[]  = "{discount_type_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CART_DISCOUNT_CODE_TBL');
		}

		if (strstr($data, '{payment_lbl}'))
		{
			$search[]  = "{payment_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PAYMENT_METHOD');
		}

		if (strstr($data, '{customer_note_lbl}'))
		{
			$search [] = "{customer_note_lbl}";
			$replace[] = JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL');
		}

		if (SHIPPING_METHOD_ENABLE)
		{
			if (strstr($data, '{shipping_method_lbl}'))
			{
				$search[]  = "{shipping_method_lbl}";
				$replace[] = JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL');
			}
		}
		else
		{
			if (strstr($data, '{shipping_method_lbl}'))
			{
				$search[]  = "{shipping_method_lbl}";
				$replace[] = '';
			}
		}

		if (strstr($data, '{product_number_lbl}'))
		{
			$search[]  = "{product_number_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_NUMBER');
		}

		if (strstr($data, '{shopname}'))
		{
			$search []  = "{shopname}";
			$replace [] = SHOP_NAME;
		}

		if (strstr($data, '{quotation_id_lbl}'))
		{
			$search []  = "{quotation_id_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_ID');
		}

		if (strstr($data, '{quotation_number_lbl}'))
		{
			$search []  = "{quotation_number_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_NUMBER');
		}

		if (strstr($data, '{quotation_date_lbl}'))
		{
			$search []  = "{quotation_date_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DATE');
		}

		if (strstr($data, '{quotation_status_lbl}'))
		{
			$search []  = "{quotation_status_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_STATUS');
		}

		if (strstr($data, '{quotation_note_lbl}'))
		{
			$search []  = "{quotation_note_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_NOTE');
		}

		if (strstr($data, '{quotation_information_lbl}'))
		{
			$search []  = "{quotation_information_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_INFORMATION');
		}

		if (strstr($data, '{account_information_lbl}'))
		{
			$search []  = "{account_information_lbl}";
			$replace [] = JText::_('COM_REDSHOP_ACCOUNT_INFORMATION');
		}

		if (strstr($data, '{quotation_detail_lbl}'))
		{
			$search []  = "{quotation_detail_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DETAILS');
		}

		if (strstr($data, '{quotation_subtotal_lbl}'))
		{
			$search []  = "{quotation_subtotal_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
		}

		if (strstr($data, '{quotation_discount_lbl}'))
		{
			$search []  = "{quotation_discount_lbl}";
			$replace [] = JText::_('COM_REDSHOP_QUOTATION_DISCOUNT_LBL');
		}

		if (strstr($data, '{thirdparty_email_lbl}'))
		{
			$search [] = "{thirdparty_email_lbl}";
			$replace[] = JText::_('COM_REDSHOP_THIRDPARTY_EMAIL_LBL');
		}

		$data = str_replace($search, $replace, $data);

		return $data;
	}

	// Add cart
	public function calculation($cart, $shipping = 0, $user_id = 0)
	{
		$Idx               = $cart['idx'];
		$total             = 0;
		$vat               = 0;
		$subtotal          = 0;
		$subtotal_excl_vat = 0;
		$shipping          = 0;
		$discount          = 0;
		$user_info_id      = 0;
		$total_discount    = 0;
		$redArray          = array();

		for ($i = 0; $i < $Idx; $i++)
		{
			$quantity = $cart[$i]['quantity'];
			$subtotal += $quantity * $cart[$i]['product_price'];
			$subtotal_excl_vat += $quantity * $cart[$i]['product_price_excl_vat'];
			$vat += $quantity * $cart[$i]['product_vat'];
		}

		$tmparr             = array();
		$tmparr['subtotal'] = $subtotal;

		$tmparr['tax'] = $vat;
		$shippingVat   = 0;

		// If SHOW_SHIPPING_IN_CART set to no, make shipping Zero
		if (SHOW_SHIPPING_IN_CART && SHIPPING_METHOD_ENABLE)
		{
			if (!$user_id)
			{
				$user          = JFactory::getUser();
				$user_id       = $user->id;
				$shippingArray = $this->_order_functions->getShippingAddress($user_id);

				if (!empty($shippingArray[0]))
				{
					$user_info_id = $shippingArray[0]->users_info_id;
				}
			}

			$noOFGIFTCARD = 0;

			for ($i = 0; $i < $Idx; $i++)
			{
				if (isset($cart [$i] ['giftcard_id']) === true)
				{
					if (!is_null($cart [$i] ['giftcard_id']) && $cart [$i] ['giftcard_id'] != 0)
					{
						$noOFGIFTCARD++;
					}
				}
			}

			if ($noOFGIFTCARD == $Idx)
			{
				$cart['free_shipping'] = 1;
			}
			elseif ($cart['free_shipping'] != 1)
			{
				$cart['free_shipping'] = 0;
			}

			if (isset($cart ['free_shipping']) && $cart ['free_shipping'] > 0)
			{
				$shipping = 0;
			}
			else
			{
				$total_discount      = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
				$d['order_subtotal'] = (SHIPPING_AFTER == 'total') ? $subtotal - $total_discount : $subtotal;
				$d['users_info_id']  = $user_info_id;
				$shippingArr         = $this->_shippinghelper->getDefaultShipping($d);
				$shipping            = $shippingArr['shipping_rate'];
				$shippingVat         = $shippingArr['shipping_vat'];
			}
		}

		$view = JRequest::getVar('view');

		if (key_exists('shipping', $cart) && $view != 'cart')
		{
			$shipping = $cart['shipping'];

			if (!isset($cart['shipping_vat']))
			{
				$cart['shipping_vat'] = 0;
			}

			$shippingVat = $cart['shipping_vat'];
		}

		if (VAT_RATE_AFTER_DISCOUNT)
		{
			$Discountvat = (VAT_RATE_AFTER_DISCOUNT * $total_discount) / (1 + VAT_RATE_AFTER_DISCOUNT);
			$vat         = $vat - $Discountvat;
		}

		$total      = $subtotal + $shipping;
		$redArray[] = $total;
		$redArray[] = $subtotal;
		$redArray[] = $subtotal_excl_vat;
		$redArray[] = $shipping;

		if (isset($cart['discount']) === false)
		{
			$cart['discount'] = 0;
		}

		$redArray[] = $cart['discount'];

		$redArray[] = $vat;
		$redArray[] = $shippingVat;

		return $redArray;
	}

	public function GetCartModuleCalc($redArray)
	{
		$cartParamArr       = array();
		$cartParamArr       = $this->GetCartParameters();
		$cart_output        = 0;
		$show_with_shipping = 1;
		$show_with_discount = 1;
		$show_with_vat      = 1;

		if (array_key_exists('cart_output', $cartParamArr))
		{
			$cart_output = $cartParamArr['cart_output'];
		}

		if (array_key_exists('show_with_shipping', $cartParamArr))
		{
			$show_with_shipping = $cartParamArr['show_with_shipping'];
		}

		if (array_key_exists('show_with_discount', $cartParamArr))
		{
			$show_with_discount = $cartParamArr['show_with_discount'];
		}

		if (array_key_exists('show_with_vat', $cartParamArr))
		{
			$show_with_vat = $cartParamArr['show_with_vat'];
		}

		if (!$show_with_vat)
		{
			$total = $redArray['product_subtotal_excl_vat'];
		}
		else
		{
			$total = $redArray['product_subtotal'];
		}

		$shipping       = $redArray['shipping'];
		$discount_total = $redArray['coupon_discount'] + $redArray['voucher_discount'] + $redArray['cart_discount'];

		if ($show_with_shipping == 1 && $show_with_discount == 1)
		{
			$mod_cart_total = $total + $shipping - $discount_total;
		}
		elseif ($show_with_shipping == 0 && $show_with_discount == 1)
		{
			$mod_cart_total = $total - $discount_total;
		}
		elseif ($show_with_shipping == 1 && $show_with_discount == 0)
		{
			$mod_cart_total = $total + $shipping;
		}
		else
		{
			$mod_cart_total = $total;
		}

		$this->_show_with_vat = $show_with_vat;
		$layout               = JRequest::getVar('layout');
		$view                 = JRequest::getVar('view');

		if (array_key_exists('payment_amount', $redArray) && $view == 'checkout' && $layout != 'default')
		{
			if ($redArray['payment_oprand'] == '+')
			{
				$mod_cart_total += $redArray['payment_amount'];
			}
			else
			{
				$mod_cart_total -= $redArray['payment_amount'];
			}
		}

		return $mod_cart_total;
	}

	public function replaceTemplate($cart, $cart_data, $checkout = 1)
	{
		$cart_data = $this->replaceLabel($cart_data);

		if (strstr($cart_data, "{product_loop_start}") && strstr($cart_data, "{product_loop_end}"))
		{
			$template_sdata  = explode('{product_loop_start}', $cart_data);
			$template_start  = $template_sdata[0];
			$template_edata  = explode('{product_loop_end}', $template_sdata[1]);
			$template_end    = $template_edata[1];
			$template_middle = $template_edata[0];
			$template_middle = $this->replaceCartItem($template_middle, $cart, 1, DEFAULT_QUOTATION_MODE);
			$cart_data       = $template_start . $template_middle . $template_end;
		}

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

		if (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
		{
			if (strstr($cart_data, '{product_subtotal_lbl}'))
			{
				$cart_data = str_replace("{product_subtotal_lbl}", JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL'), $cart_data);
			}

			if (strstr($cart_data, '{product_subtotal_excl_vat_lbl}'))
			{
				$cart_data = str_replace("{product_subtotal_excl_vat_lbl}", JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL'), $cart_data);
			}

			if (strstr($cart_data, '{shipping_with_vat_lbl}'))
			{
				$cart_data = str_replace("{shipping_with_vat_lbl}", JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL'), $cart_data);
			}

			if (strstr($cart_data, '{shipping_excl_vat_lbl}'))
			{
				$cart_data = str_replace("{shipping_excl_vat_lbl}", JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL'), $cart_data);
			}

			if (strstr($cart_data, '{product_price_excl_lbl}'))
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

			if ((strstr($cart_data, "{discount_denotation}") || strstr($cart_data, "{shipping_denotation}")) && ($discount_total != 0 || $shipping != 0))
			{
				$cart_data = str_replace("{denotation_label}", JText::_('COM_REDSHOP_DENOTATION_TXT'), $cart_data);
			}
			else
			{
				$cart_data = str_replace("{denotation_label}", "", $cart_data);
			}

			if (strstr($cart_data, "{discount_excl_vat}"))
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
				if (!SHOW_SHIPPING_IN_CART || !SHIPPING_METHOD_ENABLE)
				{
					$rep = false;
				}
			}
			else
			{
				if (!SHIPPING_METHOD_ENABLE)
				{
					$rep = false;
				}
			}

			if (!empty($rep))
			{
				if (strstr($cart_data, "{shipping_excl_vat}"))
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

		if (!APPLY_VAT_ON_DISCOUNT)
		{
			$total_for_discount = $subtotal_excl_vat;
		}
		else
		{
			$total_for_discount = $subtotal;
		}

		$cart_data = $this->replaceDiscount($cart_data, $discount_amount + $tmp_discount, $total_for_discount, DEFAULT_QUOTATION_MODE);

		if ($checkout)
		{
			$cart_data = $this->replacePayment($cart_data, $cart['payment_amount'], 0);
		}
		else
		{
			$cart_data = $this->replacePayment($cart_data, 0, 1);
		}

		$cart_data = $this->replaceTax($cart_data, $tax + $shippingVat, $discount_amount + $tmp_discount, 0, DEFAULT_QUOTATION_MODE);

		return $cart_data;
	}

	public function replaceOrderTemplate($row, $ReceiptTemplate)
	{
		$url       = JURI::base();
		$redconfig = new Redconfiguration;
		$order_id  = $row->order_id;
		$session   = JFactory::getSession();
		$orderitem = $this->_order_functions->getOrderItemDetail($order_id);

		if (strstr($ReceiptTemplate, "{product_loop_start}") && strstr($ReceiptTemplate, "{product_loop_end}"))
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
		$paymentmethod_detail = $this->_order_functions->getPaymentMethodInfo($paymentmethod->payment_method_class);
		$paymentmethod_detail = $paymentmethod_detail [0];
		$OrderStatus          = $this->_order_functions->getOrderStatusTitle($row->order_status);

		$product_name      = "";
		$product_price     = "";
		$subtotal_excl_vat = $cartArr[1];
		$barcode_code      = $row->barcode;
		$img_url           = REDSHOP_FRONT_IMAGES_ABSPATH . "barcode/" . $barcode_code . ".png";
		$bar_replace       = '<img alt="" src="' . $img_url . '">';

		$total_excl_vat = $subtotal_excl_vat + ($row->order_shipping - $row->order_shipping_tax) - ($row->order_discount - $row->order_discount_vat);
		$sub_total_vat  = $row->order_tax + $row->order_shipping_tax;

		if (isset($row->voucher_discount) === false)
		{
			$row->voucher_discount = 0;
		}

		$Total_discount = $row->coupon_discount + $row->order_discount + $row->special_discount + $row->tax_after_discount + $row->voucher_discount;

		// For Payment and Shipping Extra Fields
		if (strstr($ReceiptTemplate, '{payment_extrafields}'))
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

		if (strstr($ReceiptTemplate, '{shipping_extrafields}'))
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

		if (!APPLY_VAT_ON_DISCOUNT)
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
		$search[]   = "{order_detail_link}";
		$replace[]  = "<a href='" . $orderdetailurl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		$dpData = "";

		if (count($downloadProducts) > 0)
		{
			$dpData .= "<table>";

			for ($d = 0; $d < count($downloadProducts); $d++)
			{
				$g                = $d + 1;
				$downloadProduct  = $downloadProducts[$d];
				$downloadfilename = substr(basename($downloadProduct->file_name), 11);
				$downloadToken    = $downloadProduct->download_id;
				$product_name     = $downloadProduct->product_name;
				$mailtoken        = $product_name . ": <a href='" . JUri::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $downloadToken . "'>" . $downloadfilename . "</a>";

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

		if ((strstr($ReceiptTemplate, "{discount_denotation}") || strstr($ReceiptTemplate, "{shipping_denotation}")) && ($Total_discount != 0 || $row->order_shipping != 0))
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

		if (strstr($ReceiptTemplate, "{discount_excl_vat}"))
		{
			$replace [] = "*";
		}
		else
		{
			$replace [] = "";
		}

		$search  [] = "{shipping_denotation}";

		if (strstr($ReceiptTemplate, "{shipping_excl_vat}"))
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

		$search  [] = "{payment_method}";
		$replace [] = JText::_($paymentmethod->order_payment_name);

		$txtextra_info = '';

		if ($paymentmethod_detail->element == "rs_payment_banktransfer" || $paymentmethod_detail->element == "rs_payment_banktransfer_discount" || $paymentmethod_detail->element == "rs_payment_banktransfer2" || $paymentmethod_detail->element == "rs_payment_banktransfer3" || $paymentmethod_detail->element == "rs_payment_banktransfer4" || $paymentmethod_detail->element == "rs_payment_banktransfer5")
		{
			$paymentpath   = JPATH_SITE . '/plugins/redshop_payment/'
				. $paymentmethod_detail->element . '/' . $paymentmethod_detail->element . '.xml';
			$paymentparams = new JRegistry($paymentmethod_detail->params);
			$txtextra_info = $paymentparams->get('txtextra_info', '');
		}

		$search  [] = "{payment_extrainfo}";
		$replace [] = $txtextra_info;

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

		if (strstr($ReceiptTemplate, '{order_detail_link_lbl}'))
		{
			$search [] = "{order_detail_link_lbl}";
			$replace[] = JText::_('COM_REDSHOP_ORDER_DETAIL_LINK_LBL');
		}

		if (strstr($ReceiptTemplate, '{product_subtotal_lbl}'))
		{
			$search [] = "{product_subtotal_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL');
		}

		if (strstr($ReceiptTemplate, '{product_subtotal_excl_vat_lbl}'))
		{
			$search [] = "{product_subtotal_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL');
		}

		if (strstr($ReceiptTemplate, '{shipping_with_vat_lbl}'))
		{
			$search [] = "{shipping_with_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL');
		}

		if (strstr($ReceiptTemplate, '{shipping_excl_vat_lbl}'))
		{
			$search [] = "{shipping_excl_vat_lbl}";
			$replace[] = JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL');
		}

		if (strstr($ReceiptTemplate, '{product_price_excl_lbl}'))
		{
			$search [] = "{product_price_excl_lbl}";
			$replace[] = JText::_('COM_REDSHOP_PRODUCT_PRICE_EXCL_LBL');
		}

		$billingaddresses  = $this->_order_functions->getOrderBillingUserInfo($order_id);
		$shippingaddresses = $this->_order_functions->getOrderShippingUserInfo($order_id);

		$search [] = "{requisition_number}";
		$replace[] = ($row->requisition_number) ? $row->requisition_number : "N/A";

		$search [] = "{requisition_number_lbl}";
		$replace[] = JText::_('COM_REDSHOP_REQUISITION_NUMBER');

		if (strstr($ReceiptTemplate, '{redcrm_debitornumber_lbl}'))
		{
			if ($session->get('isredcrmuser_debitor'))
			{
				$search [] = "{redcrm_debitornumber_lbl}";
				$replace[] = JText::_('COM_REDSHOP_DEBITOR_NUMBER');
			}
			else
			{
				$search [] = "{redcrm_debitornumber_lbl}";
				$replace[] = "";
			}
		}

		if (strstr($ReceiptTemplate, '{redcrm_debitornumber}'))
		{
			if ($session->get('isredcrmuser_debitor'))
			{
				$search [] = "{redcrm_debitornumber}";
				$replace[] = $row->user_info_id;
			}
			else
			{
				$search [] = "{redcrm_debitornumber}";
				$replace[] = "";
			}
		}

		$ReceiptTemplate = $this->replaceBillingAddress($ReceiptTemplate, $billingaddresses);
		$ReceiptTemplate = $this->replaceShippingAddress($ReceiptTemplate, $shippingaddresses);

		$message = str_replace($search, $replace, $ReceiptTemplate);
		$message = $this->replacePayment($message, $row->payment_discount, 0, $row->payment_oprand);
		$message = $this->replaceDiscount($message, $row->order_discount, $total_for_discount);
		$message = $this->replaceTax($message, $row->order_tax + $row->order_shipping_tax, $row->tax_after_discount, 1);

		return $message;
	}

	public function makeCart_output($cart)
	{
		$outputArr          = array();
		$totalQuntity       = 0;
		$idx                = $cart['idx'];
		$output             = '';
		$show_with_vat      = 0;
		$cart_output        = 'simple';
		$cartParamArr       = array();
		$show_shipping_line = 0;
		$cartParamArr       = $this->GetCartParameters();

		if (array_key_exists('cart_output', $cartParamArr))
		{
			$cart_output = $cartParamArr['cart_output'];
		}

		if (array_key_exists('show_shipping_line', $cartParamArr))
		{
			$show_shipping_line = $cartParamArr['show_shipping_line'];
		}

		for ($i = 0; $i < $idx; $i++)
		{
			$totalQuntity += $cart [$i] ['quantity'];

			if ($this->rs_multi_array_key_exists('giftcard_id', $cart [$i]) && $cart [$i] ['giftcard_id'])
			{
				$giftcardData = $this->_producthelper->getGiftcardData($cart [$i] ['giftcard_id']);
				$name         = $giftcardData->giftcard_name;
			}
			else
			{
				$product_detail = $this->_producthelper->getProductById($cart [$i] ['product_id']);
				$name           = $product_detail->product_name;
			}

			if ($i != 0)
			{
				$output .= '<br>';
			}

			$output .= $cart [$i] ['quantity'] . " x " . $name . "<br />";

			if (array_key_exists('show_with_vat', $cartParamArr))
			{
				$show_with_vat = $cartParamArr['show_with_vat'];
			}

			if (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
			{
				if ($show_with_vat)
					$output .= JText::_('COM_REDSHOP_PRICE_CART_LBL') . " " . $this->_producthelper->getProductFormattedPrice($cart [$i] ['product_price'], true);
				else
					$output .= JText::_('COM_REDSHOP_PRICE_CART_LBL') . " " . $this->_producthelper->getProductFormattedPrice($cart [$i] ['product_price_excl_vat'], true);
			}
		}

		$output = '<div class="mod_cart_products" id="mod_cart_products">' . $output . '</div>';

		if ($cart_output == 'simple')
		{
			$output = '<div class="mod_cart_extend_total_pro_value" id="mod_cart_total_txt_product" >';
			$output .= JText::_('COM_REDSHOP_TOTAL_PRODUCT') . ':' . ' ' . $totalQuntity . ' ' . JText::_('COM_REDSHOP_PRODUCTS_IN_CART');
			$output .= '</div>';
		}

		if (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
		{
			$output .= '<div class="mod_cart_total_txt" id="mod_cart_total_txt_ajax" >' . JText::_('COM_REDSHOP_TOTAL') . ':' . '</div>';
			$output .= '<div class="mod_cart_total_value" id="mod_cart_total_value_ajax">' . $this->_producthelper->getProductFormattedPrice($cart['mod_cart_total']) . '</div>';
			$shippingvalue = $cart['shipping'];

			if (!$show_with_vat)
			{
				$shippingvalue = $cart['shipping'] - $cart['shipping_tax'];
			}

			if ($show_shipping_line)
			{
				$output .= '<div class="mod_cart_shipping_txt" id="mod_cart_shipping_txt_ajax" >' . JText::_('COM_REDSHOP_SHIPPING_LBL') . ':' . '</div>';
				$output .= '<div class="mod_cart_shipping_value" id="mod_cart_shipping_value_ajax">' . $this->_producthelper->getProductFormattedPrice($shippingvalue) . '</div>';
			}
		}

		$outputArr[] = $output;
		$outputArr[] = $totalQuntity;

		return $outputArr;
	}

	public function GetCartParameters()
	{
		$sel = 'SELECT params  from #__modules where module = "mod_redshop_cart" and published =1';
		$this->_db->setQuery($sel);
		$params = $this->_db->loadResult();

		$cartparamArr = array();
		$params       = substr($params, 1);
		$params       = substr_replace($params, " ", -1);
		$params       = str_replace('"', ' ', $params);
		$allparams    = explode(",", $params);

		for ($i = 0; $i < count($allparams); $i++)
		{
			$cart_param = explode(':', $allparams[$i]);

			if (!empty($cart_param))
			{
				if (strstr($cart_param[0], 'cart_output') || strstr($cart_param[0], 'show_with_shipping') || strstr($cart_param[0], 'show_with_discount') || strstr($cart_param[0], 'show_with_vat') || strstr($cart_param[0], 'show_shipping_line'))
				{
					$cartparamArr[trim($cart_param[0])] = trim($cart_param[1]);
				}
			}
		}

		return $cartparamArr;
	}

	public function modifyCart($cartArr, $user_id)
	{
		$cartArr['user_id'] = $user_id;
		$idx                = (int) ($cartArr['idx']);
		$getacctax          = 0;
		$taxtotal           = 0;
		$subtotal_excl_vat  = 0;

		for ($i = 0; $i < $idx; $i++)
		{
			if (!isset($cartArr[$i]['giftcard_id']) || (isset($cartArr[$i]['giftcard_id']) && $cartArr[$i]['giftcard_id'] <= 0))
			{
				$product_id = $cartArr[$i]['product_id'];
				$quantity   = $cartArr[$i]['quantity'];
				$product    = $this->_producthelper->getProductById($product_id);

				// Attribute price
				$price = 0;

				if (!isset($cartArr['quotation']))
				{
					$cartArr['quotation'] = 0;
				}

				if (DEFAULT_QUOTATION_MODE || $cartArr['quotation'] == 1)
				{
					$price = $cartArr[$i]['product_price_excl_vat'];
				}

				if ($product->use_discount_calc)
				{
					$price = $cartArr[$i]['discount_calc_price'];
				}

				$retAttArr = $this->_producthelper->makeAttributeCart($cartArr [$i] ['cart_attribute'], $product->product_id, $user_id, $price, $quantity);

				// Product + attribute (price)
				$getproprice = $retAttArr[1];

				// Product + attribute (VAT)
				$getprotax                  = $retAttArr[2];
				$product_old_price_excl_vat = $retAttArr[5];

				// Accessory calculation
				$retAccArr = $this->_producthelper->makeAccessoryCart($cartArr [$i] ['cart_accessory'], $product->product_id, $user_id);

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
						$wrapperArr    = $this->getWrapperPriceArr(array('product_id' => $cartArr[$i]['product_id'], 'wrapper_id' => $cartArr[$i]['wrapper_id']));
						$wrapper_vat   = $wrapperArr['wrapper_vat'];
						$wrapper_price = $wrapperArr['wrapper_price'];
						$product_old_price_excl_vat += $wrapper_price;
					}
				}

				// END WRAPPER PRICE

				$product_price          = $getaccprice + $getproprice + $getprotax + $getacctax + $wrapper_price + $wrapper_vat;
				$product_vat            = ($getprotax + $getacctax + $wrapper_vat);
				$product_price_excl_vat = ($getproprice + $getaccprice + $wrapper_price);

				$product_type = $product->product_type;

				if ($product_type == 'subscription')
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
			}
		}

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

			for ($i = 0; $i < count($shippingBoxes); $i++)
			{
				$shipping_box_id = $shippingBoxes[$i]->shipping_box_id;

				// Previous priority
				if ($i > 0)
				{
					$shipping_box_priority_pre = $shippingBoxes[$i - 1]->shipping_box_priority;
				}

				// Current priority
				$shipping_box_priority = $shippingBoxes[$i]->shipping_box_priority;
				$checked               = ($shipping_box_post_id == $shipping_box_id) ? "checked" : "";

				if ($i == 0 || ($shipping_box_priority == $shipping_box_priority_pre))
				{
					$shipping_box_list .= "<input " . $checked . " type='radio' id='shipping_box_id" . $shipping_box_id . "' name='shipping_box_id'  onclick='javascript:onestepCheckoutProcess(this.name,\'\');' value='" . $shipping_box_id . "'>";
					$shipping_box_list .= "<label for='shipping_box_id" . $shipping_box_id . "'>" . $shippingBoxes[$i]->shipping_box_name . "</label><br/>";
				}
			}
		}

		$box_template_desc = str_replace("{shipping_box_list}", $shipping_box_list, $box_template_desc);
		$style             = 'none';

		$shippingmethod = $this->_order_functions->getShippingMethodInfo();

		for ($s = 0; $s < count($shippingmethod); $s++)
		{
			if ($shippingmethod[$s]->element == 'australiapost' || $shippingmethod[$s]->element == 'bring' || $shippingmethod[$s]->element == 'ups' || $shippingmethod[$s]->element == 'uspsv4')
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

	public function getGLSLocation($users_info_id, $classname, $shop_id = 0)
	{
		$output = '';
		$sql    = "SELECT  enabled FROM #__extensions WHERE element ='default_shipping_GLS'";
		$this->_db->setQuery($sql);
		$isEnabled = $this->_db->loadResult();

		if ($isEnabled && $classname == 'default_shipping_GLS')
		{
			JPluginHelper::importPlugin('rs_labels_GLS');
			$dispatcher = JDispatcher::getInstance();
			$sql        = "SELECT  * FROM #__" . TABLE_PREFIX . "_users_info WHERE users_info_id='" . $users_info_id . "'";
			$this->_db->setQuery($sql);
			$values = $this->_db->loadObject();

			$ShopResponses = $dispatcher->trigger('GetNearstParcelShops', array($values));
			$ShopRespons   = $ShopResponses[0];

			$shopList = array();

			for ($i = 0; $i < count($ShopRespons); $i++)
			{
				$shopList[] = JHTML::_('select.option', $ShopRespons[$i]->shop_id, $ShopRespons[$i]->CompanyName . ", " . $ShopRespons[$i]->Streetname . ", " . $ShopRespons[$i]->ZipCode . ", " . $ShopRespons[$i]->CityName);
			}

			if ($shop_id)
			{
				$selected_shop_id = $shop_id;

				$shop_id = explode("###", $shop_id);
				$output .= JText::_('COM_REDSHOP_SHIPPING_LOCATION') . " : ";
				$output .= $shop_id = str_replace("|", "<br>", $shop_id[0]) . "<br/>";
			}

			$output .= JText::_('COM_REDSHOP_PROVIDE_ZIPCODE_TO_PICKUP_PARCEL') . " : ";
			$output .= "<input type='text' id='gls_zipcode' name='gls_zipcode' value='" . $values->zipcode . "' onblur='javascript:updateGLSLocation(this.value);' ><input type='button' id='update' value='OPDATER' name='update'><br/>";
			$output .= JText::_('COM_REDSHOP_SELECT_GLS_LOCATION') . " : ";
			$output .= "<span id='rs_locationdropdown'>";
			$output .= $lists['shopList'] = JHTML::_('select.genericlist', $shopList, 'shop_id', 'class="inputbox" ', 'value', 'text', $selected_shop_id);
			$output .= "</span><br>";
			$output .= JText::_('COM_REDSHOP_ENTER_GLS_MOBILE') . " : ";
			$output .= "<input type='text' id='gls_mobile' name='gls_mobile' /><br/>";
		}

		return $output;
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

		if (strstr($template_desc, "{shipping_method_loop_start}") && strstr($template_desc, "{shipping_method_loop_end}"))
		{
			$template1       = explode("{shipping_method_loop_start}", $template_desc);
			$template1       = explode("{shipping_method_loop_end}", $template1[1]);
			$template_middle = $template1[0];

			$template_rate_middle = "";

			if (strstr($template_middle, "{shipping_rate_loop_start}") && strstr($template_middle, "{shipping_rate_loop_end}"))
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

				for ($s = 0; $s < count($shippingmethod); $s++)
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
						$rate_data = str_replace("{shipping_method_title}", $rs->name, $rate_data);

						if ($template_rate_middle != "")
						{
							$data         = "";
							$mainlocation = "";

							for ($i = 0; $i < count($rate); $i++)
							{
								$glsLocation = '';
								$data .= $template_rate_middle;

								$displayrate = (trim($rate[$i]->rate) > 0) ? " (" . $this->_producthelper->getProductFormattedPrice(trim($rate[$i]->rate)) . " )" : "";

								$checked = ($rateExist == 0 || $shipping_rate_id == $rate[$i]->value) ? "checked" : "";

								if ($checked == "checked")
								{
									$shipping_rate_id = $rate[$i]->value;
								}

								if ($classname == 'default_shipping_GLS')
								{
									$glsLocation = $this->getGLSLocation($users_info_id, $classname);
									$style       = ($checked != "checked") ? "style='display:none;'" : "style='display:block;'";

									if ($glsLocation)
									{
										$glsLocation = "<div $style id='rs_glslocationId'>" . $glsLocation . "</div>";
									}
								}

								$shipping_rate_name = '<input type="radio" id="shipping_rate_id_'.$shippingmethod[$s]->extension_id.'_'.$i.'" name="shipping_rate_id" value="'
									. $rate[$i]->value . '" '
									. $checked
									. ' onclick="javascript:onestepCheckoutProcess(this.name,\'' . $classname . '\');">'
									. '<label for="shipping_rate_id_'.$shippingmethod[$s]->extension_id.'_'.$i.'">' . html_entity_decode($rate[$i]->text) . '</label>';

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

								if (strstr($data, "{shipping_location}"))
								{
									$shippinglocation = $this->_order_functions->getshippinglocationinfo($rate[$i]->text);

									for ($k = 0; $k < count($shippinglocation); $k++)
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
				}
			}

			$template_desc = str_replace("{shipping_method_loop_start}", "", $template_desc);
			$template_desc = str_replace("{shipping_method_loop_end}", "", $template_desc);
			$template_desc = str_replace($template_middle, $rate_data, $template_desc);
		}

		if (strstr($template_desc, "{shipping_extrafields}"))
		{
			$extraField         = new extraField;
			$paymentparams_new  = new JRegistry($shippingmethod[0]->params);
			$extrafield_payment = $paymentparams_new->get('extrafield_shipping');
			$extrafield_total   = "";
			$extrafield_hidden  = "";

			if (count($extrafield_payment) > 0)
			{
				for ($ui = 0; $ui < count($extrafield_payment); $ui++)
				{
					$product_userfileds = $extraField->list_all_user_fields($extrafield_payment[$ui], 19, '', 0, 0, 0);
					$extrafield_total .= $product_userfileds[0] . " " . $product_userfileds[1] . "<br>";
					$extrafield_hidden .= "<input type='hidden' name='extrafields[]' value='" . $extrafield_payment[$ui] . "'>";
				}

				$template_desc = str_replace("{shipping_extrafields}", "<div id='extrafield_shipping'>" . $extrafield_total . $extrafield_hidden . "</div>", $template_desc);
			}
			else
			{
				$template_desc = str_replace("{shipping_extrafields}", "<div id='extrafield_shipping'></div>", $template_desc);
			}
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
		elseif ($rateExist == 1 && $extrafield_total == "")
		{
			$template_desc = "<div style='display:none;'>" . $template_desc . "</div>";
		}

		$returnarr = array("template_desc" => $template_desc, "shipping_rate_id" => $shipping_rate_id);

		return $returnarr;
	}

	public function replaceCreditCardInformation($payment_method_id = 0)
	{
		$ccdata = $this->_session->get('ccdata');

		$url                      = JURI::base(true);
		$cc_list                  = array();
		$cc_list['VISA']->img     = 'visa.jpg';
		$cc_list['MC']->img       = 'master.jpg';
		$cc_list['amex']->img     = 'blue.jpg';
		$cc_list['maestro']->img  = 'mastero.jpg';
		$cc_list['jcb']->img      = 'jcb.jpg';
		$cc_list['diners']->img   = 'dinnersclub.jpg';
		$cc_list['discover']->img = 'discover.jpg';

		$montharr   = array();
		$montharr[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_MONTH'));
		$montharr[] = JHTML::_('select.option', '01', 1);
		$montharr[] = JHTML::_('select.option', '02', 2);
		$montharr[] = JHTML::_('select.option', '03', 3);
		$montharr[] = JHTML::_('select.option', '04', 4);
		$montharr[] = JHTML::_('select.option', '05', 5);
		$montharr[] = JHTML::_('select.option', '06', 6);
		$montharr[] = JHTML::_('select.option', '07', 7);
		$montharr[] = JHTML::_('select.option', '08', 8);
		$montharr[] = JHTML::_('select.option', '09', 9);
		$montharr[] = JHTML::_('select.option', '10', 10);
		$montharr[] = JHTML::_('select.option', '11', 11);
		$montharr[] = JHTML::_('select.option', '12', 12);

		if (!empty($payment_method_id))
		{
			$paymentmethod = $this->_order_functions->getPaymentMethodInfo($payment_method_id);
			$paymentmethod = $paymentmethod[0];
		}
		else
		{
			$paymentmethod = $this->_redhelper->getPlugins('redshop_payment');
			$paymentmethod = $paymentmethod[0];
		}

		$cardinfo = "";

		if (file_exists(JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod->element . '/' . $paymentmethod->element . '.php'))
		{
			$paymentparams = new JRegistry($paymentmethod->params);
			$is_creditcard = $paymentparams->get('is_creditcard', 0);

			if ($is_creditcard)
			{
				$credict_card          = array();
				$accepted_credict_card = $paymentparams->get("accepted_credict_card");

				if ($accepted_credict_card != "")
				{
					$credict_card = $accepted_credict_card;
				}

				$cardinfo .= '<fieldset class="adminform"><legend>' . JText::_('COM_REDSHOP_CARD_INFORMATION') . '</legend>';
				$cardinfo .= '<table class="admintable">';
				$cardinfo .= '<tr><td colspan="2" align="right" nowrap="nowrap">';
				$cardinfo .= '<table width="100%" border="0" cellspacing="2" cellpadding="2">';
				$cardinfo .= '<tr>';

				for ($ic = 0; $ic < count($credict_card); $ic++)
				{
					$cardinfo .= '<td align="center"><img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'checkout/' . $cc_list[$credict_card[$ic]]->img . '" alt="" border="0" /></td>';
				}

				$cardinfo .= '</tr>';
				$cardinfo .= '<tr>';

				for ($ic = 0; $ic < count($credict_card); $ic++)
				{
					$value   = $credict_card[$ic];
					$checked = "";

					if (!isset($ccdata['creditcard_code']) && $ic == 0)
					{
						$checked = "checked";
					}
					elseif (isset($ccdata['creditcard_code']))
					{
						$checked = ($ccdata['creditcard_code'] == $value) ? "checked" : "";
					}

					$cardinfo .= '<td align="center"><input type="radio" name="creditcard_code" value="' . $value . '" ' . $checked . ' /></td>';
				}

				$cardinfo .= '</tr></table></td></tr>';
				$cardinfo .= '<tr valign="top">';
				$cardinfo .= '<td align="right" nowrap="nowrap" width="10%"><label for="order_payment_name">' . JText::_('COM_REDSHOP_NAME_ON_CARD') . '</label></td>';
				$order_payment_name = (!empty($ccdata['order_payment_name'])) ? $ccdata['order_payment_name'] : "";
				$cardinfo .= '<td><input class="inputbox" id="order_payment_name" name="order_payment_name" value="' . $order_payment_name . '" autocomplete="off" type="text"></td>';
				$cardinfo .= '</tr>';
				$cardinfo .= '<tr valign="top">';
				$cardinfo .= '<td align="right" nowrap="nowrap" width="10%"><label for="order_payment_number">' . JText::_('COM_REDSHOP_CARD_NUM') . '</label></td>';
				$order_payment_number = (!empty($ccdata['order_payment_number'])) ? $ccdata['order_payment_number'] : "";
				$cardinfo .= '<td><input class="inputbox" id="order_payment_number" name="order_payment_number" value="' . $order_payment_number . '" autocomplete="off" type="text"></td>';
				$cardinfo .= '</tr>';
				$cardinfo .= '<tr><td align="right" nowrap="nowrap" width="10%">' . JText::_('COM_REDSHOP_EXPIRY_DATE') . '</td>';
				$cardinfo .= '<td>';

				$value = isset($ccdata['order_payment_expire_month']) ? $ccdata['order_payment_expire_month'] : date('m');
				$cardinfo .= JHTML::_('select.genericlist', $montharr, 'order_payment_expire_month', 'size="1" class="inputbox" ', 'value', 'text', $value);

				$thisyear = date('Y');
				$cardinfo .= '/<select class="inputbox" name="order_payment_expire_year" size="1">';

				for ($y = $thisyear; $y < ($thisyear + 10); $y++)
				{
					$selected = (!empty($ccdata['order_payment_expire_year']) && $ccdata['order_payment_expire_year'] == $y) ? "selected" : "";
					$cardinfo .= '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
				}

				$cardinfo .= '</select></td></tr>';
				$cardinfo .= '<tr valign="top"><td align="right" nowrap="nowrap" width="10%"><label for="credit_card_code">' . JText::_('COM_REDSHOP_CARD_SECURITY_CODE') . '</label></td>';

				$credit_card_code = (!empty($ccdata['credit_card_code'])) ? $ccdata['credit_card_code'] : "";
				$cardinfo .= '<td><input class="inputbox" id="credit_card_code" name="credit_card_code" value="' . $credit_card_code . '" autocomplete="off" type="password"></td></tr>';

				$cardinfo .= '</table></fieldset>';
			}
		}

		return $cardinfo;
	}

	public function replacePaymentTemplate($template_desc = "", $payment_method_id = 0, $is_company = 0, $ean_number = 0)
	{
		$ccdata = $this->_session->get('ccdata');

		$rsUserhelper = new rsUserhelper;
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

		$paymentmethod = JPluginHelper::getPlugin('redshop_payment');

		$template_desc = str_replace("{payment_heading}", JText::_('COM_REDSHOP_PAYMENT_METHOD'), $template_desc);

		if (strstr($template_desc, "{split_payment}"))
		{
			if (SPLITABLE_PAYMENT == 1)
			{
				$splitpayment  = '<input type="checkbox" name="issplit" value="1">' . JText::_('COM_REDSHOP_SPLIT_PAYMENT') . '?';
				$template_desc = str_replace("{split_payment}", $splitpayment, $template_desc);
			}
			else
			{
				$template_desc = str_replace("{split_payment}", "", $template_desc);
			}
		}

		if (strstr($template_desc, "{payment_loop_start}") && strstr($template_desc, "{payment_loop_end}"))
		{
			$template1       = explode("{payment_loop_start}", $template_desc);
			$template1       = explode("{payment_loop_end}", $template1[1]);
			$template_middle = $template1[0];
			$shopperGroupId  = $rsUserhelper->getShopperGroup($user_id);
			$payment_display = "";
			$flag            = false;

			for ($p = 0; $p < count($paymentmethod); $p++)
			{
				$cardinfo        = "";
				$display_payment = "";
				$paymentFilePath = JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod[$p]->name . '/' . $paymentmethod[$p]->name . '.php';

				if (file_exists($paymentFilePath))
				{
					$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod[$p]->name . '/' . $paymentmethod[$p]->name . '.php';

					include_once $paymentpath;

					$paymentparams  = new JRegistry($paymentmethod[$p]->params);
					$private_person = $paymentparams->get('private_person', '');
					$business       = $paymentparams->get('business', '');
					$is_creditcard  = $paymentparams->get('is_creditcard', 0);
					$shopper_group  = $paymentparams->get('shopper_group_id');

					if (!is_array($shopper_group))
					{
						$shopper_groupArr    = array();
						$shopper_groupArr[0] = $shopper_group;

						if ($shopper_group == '')
						{
							$shopper_groupArr[0] = 0;
						}
					}
					else
					{
						$shopper_groupArr = $shopper_group;
					}

					if (in_array($shopperGroupId, $shopper_groupArr) || $shopper_groupArr[0] == 0)
					{
						if ($flag == false)
						{
							if (count($paymentmethod) > 0)
							{
								$payment_method_id = $paymentmethod[$p]->name;
							}
						}

						$checked = '';

						if ($payment_method_id === $paymentmethod[$p]->name)
						{
							$checked = "checked";
						}

						$payment_chcked_class = '';

						if ($payment_method_id == $paymentmethod[$p]->name)
						{
							$payment_chcked_class = "paymentgtwchecked";
						}

						$payment_radio_output = '<div id="' . $paymentmethod[$p]->name . '" class="' . $payment_chcked_class . '"><input  type="radio" name="payment_method_id" id="' . $paymentmethod[$p]->name . $p . '" value="' . $paymentmethod[$p]->name . '" ' . $checked . ' onclick="javascript:onestepCheckoutProcess(this.name,\'\');" /><label for="' . $paymentmethod[$p]->name . $p . '">' . JText::_('PLG_' . strtoupper($paymentmethod[$p]->name)) . '</label></div>';

						$is_subscription = false;

						if ($paymentmethod[$p]->name == 'rs_payment_eantransfer' || $paymentmethod[$p]->name == 'rs_payment_cashtransfer' || $paymentmethod[$p]->name == 'rs_payment_banktransfer' || $paymentmethod[$p]->name == "rs_payment_banktransfer2" || $paymentmethod[$p]->name == "rs_payment_banktransfer3" || $paymentmethod[$p]->name == "rs_payment_banktransfer4" || $paymentmethod[$p]->name == "rs_payment_banktransfer5")
						{
							if ($is_company == 0 && $private_person == 1)
							{
								$display_payment = $payment_radio_output;
								$flag = true;
							}
							else
							{
								if ($is_company == 1 && $business == 1 && ($paymentmethod[$p]->name != 'rs_payment_eantransfer' || ($paymentmethod[$p]->name == 'rs_payment_eantransfer' && $ean_number == 1)))
								{
									$display_payment = $payment_radio_output;
									$flag = true;
								}
							}
						}
						elseif ($is_subscription)
						{
							$display_payment = '<input id="' . $paymentmethod[$p]->name . $p . '" type="radio" name="payment_method_id" value="'
								. $paymentmethod[$p]->name . '" '
								. $checked . ' onclick="javascript:onestepCheckoutProcess(this.name);" />'
								. '<label for="' . $paymentmethod[$p]->name . $p . '">' . JText::_($paymentmethod[$p]->name) . '</label><br>';
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
							$cardinfo = '<div id="divcardinfo_' . $paymentmethod[$p]->name . '">';

							if ($checked != "" && ONESTEP_CHECKOUT_ENABLE)
							{
								$cardinfo .= $this->replaceCreditCardInformation($paymentmethod[$p]->name);
							}

							$cardinfo .= '</div>';
						}

						$flag = true;
					}
				}

				$payment_display .= $template_middle;
				$payment_display = str_replace("{payment_method_name}", $display_payment, $payment_display);
				$payment_display = str_replace("{creditcard_information}", $cardinfo, $payment_display);
			}

			$template_desc = str_replace("{payment_loop_start}", "", $template_desc);
			$template_desc = str_replace("{payment_loop_end}", "", $template_desc);
			$template_desc = str_replace($template_middle, $payment_display, $template_desc);
		}

		$extrafield_total   = '';

		if (strstr($template_desc, "{payment_extrafields}"))
		{
			$extraField         = new extraField;
			$paymentparams_new  = new JRegistry($paymentmethod[0]->params);
			$extrafield_payment = $paymentparams_new->get('extrafield_payment');
			$extrafield_total   = '';
			$extrafield_hidden  = '';

			if (count($extrafield_payment) > 0)
			{
				for ($ui = 0; $ui < count($extrafield_payment); $ui++)
				{
					$product_userfileds = $extraField->list_all_user_fields($extrafield_payment[$ui], 18, '', 0, 0, 0);
					$extrafield_total .= $product_userfileds[0] . " " . $product_userfileds[1] . "<br>";
					$extrafield_hidden .= "<input type='hidden' name='extrafields[]' value='" . $extrafield_payment[$ui] . "'>";
				}

				$template_desc = str_replace("{payment_extrafields}", "<div id='extrafield_payment'>" . $extrafield_total . $extrafield_hidden . "</div>", $template_desc);
			}
			else
			{
				$template_desc = str_replace("{payment_extrafields}", "<div id='extrafield_payment'></div>", $template_desc);
			}
		}

		if (count($paymentmethod) == 1 && $is_creditcard == "0" && $extrafield_total == "")
		{
			$template_desc = "<div style='display:none;'>" . $template_desc . "</div>";
		}

		return $template_desc;
	}

	public function replaceTermsConditions($template_desc = "", $Itemid = 1)
	{
		if (strstr($template_desc, "{terms_and_conditions"))
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

			if (strstr($template_desc, "{terms_and_conditions:") && strstr($template_desc, "}"))
			{
				$terms_left_one   = explode("{terms_and_conditions:", $template_desc);
				$terms_left_two   = explode("}", $terms_left_one[1]);
				$terms_left_three = explode(":", $terms_left_two[0]);
				$terms_left_final = $terms_left_three[0];
			}

			$finaltag       = ($terms_left_final != "") ? "{terms_and_conditions:$terms_left_final}" : "{terms_and_conditions}";
			$termscondition = '';

			if (SHOW_TERMS_AND_CONDITIONS == 0 || (SHOW_TERMS_AND_CONDITIONS == 1 && ((count($list) > 0 && $list->accept_terms_conditions == 0) || count($list) == 0)))
			{
				$finalwidth  = "500";
				$finalheight = "450";

				if ($terms_left_final != "")
				{
					$dimension = explode(" ", $terms_left_final);

					if (count($dimension) > 0)
					{
						if (strstr($dimension[0], "width"))
						{
							$width      = explode("width=", $dimension[0]);
							$finalwidth = (isset($width[1])) ? $width[1] : "500";
						}
						else
						{
							$height      = explode("height=", $dimension[0]);
							$finalheight = (isset($height[1])) ? $height[0] : "450";
						}

						if (strstr($dimension[1], "height"))
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
				$article_link   = $url . "index.php?option=com_content&amp;view=article&amp;id=" . TERMS_ARTICLE_ID . "&Itemid=" . $Itemid . "&tmpl=component&for=true";
				$termscondition = '<input type="checkbox" id="termscondition" name="termscondition" value="1" />';
				$termscondition .= JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL');
				$termscondition .= ' <a class="modal" href="' . $article_link . '" rel="{handler: \'iframe\', size: {x: ' . $finalwidth . ', y: ' . $finalheight . '}}">' . JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_FOR_LBL') . '</a>';
			}

			$template_desc = str_replace($finaltag, $termscondition, $template_desc);
		}

		return $template_desc;
	}

	public function replaceNewsletterSubscription($template_desc = "", $onchange = 0)
	{
		if (strstr($template_desc, "{newsletter_signup_chk}"))
		{
			$Itemid               = JRequest::getVar('Itemid');
			$newslettersignup     = "";
			$newslettersignup_lbl = "";
			$link                 = "";

			if (DEFAULT_NEWSLETTER != 0)
			{
				$user  = JFactory::getUser();
				$query = "SELECT subscription_id FROM " . $this->_table_prefix . "newsletter_subscription "
					. "WHERE user_id='" . $user->id . "' AND email='" . $user->email . "'";
				$this->_db->setQuery($query);
				$subscribe = $this->_db->loadResult();

				if ($subscribe == 0)
				{
					if ($onchange)
					{
						$link = " onchange='window.location.href=\"" . JUri::root() . "index.php?option=com_redshop&view=account&task=newsletterSubscribe&tmpl=component&Itemid=" . $Itemid . "\"";

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

	public function getCartProductPrice($product_id, $cart, $voucher_left)
	{
		$productArr             = array();
		$affected_product_idArr = array();
		$idx                    = $cart['idx'];
		$product_price          = 0;
		$product_price_excl_vat = 0;
		$quantity               = 0;
		$flag                   = false;
		$product_idArr          = explode(',', $product_id);

		for ($v = 0; ($v < $idx) && ($voucher_left > 0); $v++)
		{
			if ($voucher_left < $cart[$v]['quantity'] && $voucher_left)
			{
				$cart[$v]['quantity'] = $voucher_left;
			}

			if (in_array($cart[$v]['product_id'], $product_idArr) || $this->_globalvoucher)
			{
				if (DISCOUNT_TYPE > 3)
				{
					$p_quantity = $cart[$v]['quantity'];
				}
				else
				{
					$p_quantity = 1;
				}

				$product_price += ($cart[$v]['product_price'] * $p_quantity);
				$product_price_excl_vat += $cart[$v]['product_price_excl_vat'] * $p_quantity;
				$affected_product_idArr[] = $cart[$v]['product_id'];
				$voucher_left             = $voucher_left - $p_quantity;
				$quantity += $p_quantity;
			}
		}

		$productArr['product_ids']            = implode(',', $affected_product_idArr);
		$productArr['product_price']          = $product_price;
		$productArr['product_price_excl_vat'] = $product_price_excl_vat;
		$productArr['product_quantity']       = $p_quantity;

		return $productArr;
	}

	public function coupon($c_data = array())
	{
		$coupon_code = JRequest::getVar('discount_code', '');
		$view        = JRequest::getVar('view', '');
		$user        = JFactory::getUser();
		$return      = false;

		$cart = (count($c_data) <= 0) ? $this->_session->get('cart') : $c_data;

		if ($coupon_code != "")
		{
			$coupon = $this->getcouponData($coupon_code, $cart['product_subtotal']);

			if (count($coupon) > 0)
			{
				$dis_type    = $coupon->percent_or_total;
				$coupon_id   = $coupon->coupon_id;
				$coupon_type = $coupon->coupon_type;
				$userid      = $coupon->userid;
				$userType    = false;
				$return      = true;

				if ($coupon_type == 1)
				{
					if ($user->id)
					{
						$sel = "SELECT SUM(coupon_value) AS usertotal FROM " . $this->_table_prefix . "coupons_transaction "
							. "WHERE userid='" . $user->id . "' "
							. "GROUP BY userid ";
						$this->_db->setQuery($sel);
						$userData = $this->_db->loadResult();

						if (!empty($userData))
						{
							if ($userid != $userData->userid)
							{
								$userType = true;
							}
							else
							{
								$userType = false;
							}
						}
						else
						{
							if ($userid != $user->id)
							{
								return false;
							}
							else
							{
								$return = false;
							}
						}
					}
					else
					{
						return false;
					}
				}

				if (!$userType)
					$return = true;

				$pSubtotal   = $cart['product_subtotal'];
				$tmpsubtotal = $pSubtotal;

				if ($view == 'cart')
				{
					$tmpsubtotal = $pSubtotal - $cart['voucher_discount'] - $cart['cart_discount'];
				}

				if ($dis_type == 0)
				{
					$couponValue = $coupon->coupon_value;
				}
				else
				{
					$couponValue = ($tmpsubtotal * $coupon->coupon_value) / (100);
				}

				$key = $this->rs_multi_array_key_exists('coupon', $cart);

				if (!$key)
				{
					$couponArr    = array();
					$oldarr       = array();
					$coupon_index = 0;
				}
				else
				{
					$oldarr       = $cart['coupon'];
					$coupon_index = count($oldarr) + 1;
				}

				if ($couponValue < 0)
				{
					return;
				}

				$remaining_coupon_discount = 0;

				if ($couponValue > $tmpsubtotal)
				{
					$remaining_coupon_discount = $couponValue - $tmpsubtotal;
					$couponValue               = $tmpsubtotal;
				}

				if (!is_null($cart['total']) && $cart['total'] == 0 && $view = !'cart')
				{
					$couponValue = 0;
				}

				$valueExist = 0;

				if (is_array($cart['coupon']))
					$valueExist = $this->rs_recursiveArraySearch($cart['coupon'], $coupon_code);

				switch (DISCOUNT_TYPE)
				{
					case 4:
						if ($valueExist)
						{
							$return = true;
						}
						break;

					case 3:
						if ($valueExist && $key)
						{
							$return = false;

						}
						break;

					case 2:
						$voucherKey = $this->rs_multi_array_key_exists('voucher', $cart);

						if ($valueExist || $voucherKey)
						{
							$return = false;
						}
						break;

					default:
						$couponArr = array();
						$oldarr    = array();
						unset($cart['voucher']);
						unset($cart['coupon']);
						$cart['cart_discount']    = 0;
						$cart['voucher_discount'] = 0;
						$return                   = true;
						break;
				}

				if ($return)
				{
					$transaction_coupon_id = 0;

					if ($this->rs_multi_array_key_exists('transaction_coupon_id', $coupon))
						$transaction_coupon_id = $coupon->transaction_coupon_id;

					$couponArr['coupon'][$coupon_index]['coupon_code']               = $coupon_code;
					$couponArr['coupon'][$coupon_index]['coupon_id']                 = $coupon_id;
					$couponArr['coupon'][$coupon_index]['used_coupon']               = 1;
					$couponArr['coupon'][$coupon_index]['coupon_value']              = $couponValue;
					$couponArr['coupon'][$coupon_index]['remaining_coupon_discount'] = $remaining_coupon_discount;
					$couponArr['coupon'][$coupon_index]['transaction_coupon_id']     = $transaction_coupon_id;

					$couponArr['coupon']   = array_merge($couponArr['coupon'], $oldarr);
					$cart                  = array_merge($cart, $couponArr);
					$cart['free_shipping'] = $coupon->free_shipping;
					$this->_session->set('cart', $cart);
				}
			}
			elseif (VOUCHERS_ENABLE)
			{
				$return = $this->voucher();
			}
		}

		if (!empty($c_data))
		{
			return $cart;
		}
		else
		{
			return $return;
		}
	}

	public function voucher($v_data = array())
	{
		$voucher_code = JRequest::getVar('discount_code', '');
		$return       = false;

		if (count($v_data) <= 0)
		{
			$cart = $this->_session->get('cart');
		}
		else
		{
			$cart = $v_data;
		}

		if ($voucher_code != "")
		{
			$voucher = $this->getVoucherData($voucher_code);

			if (count($voucher) > 0)
			{
				$return     = true;
				$type       = $voucher->voucher_type;
				$voucher_id = $voucher->voucher_id;

				if ($type == 'Percentage')
				{
					$dis_type = 1;
				}
				else
				{
					$dis_type = 0;
				}

				$productArr = array();
				$product_id = $voucher->nproduct;
				$productArr = $this->getCartProductPrice($product_id, $cart, $voucher->voucher_left);

				if ($productArr['product_ids'] == '')
				{
					$return = false;
				}

				$product_price = $productArr['product_price'];

				$p_quantity  = $productArr['product_quantity'];
				$product_ids = $productArr['product_ids'];

				if ($p_quantity > $voucher->voucher_left)
				{
					$p_quantity = $voucher->voucher_left;
				}

				if ($dis_type == 0)
				{
					$voucher->total *= $p_quantity;
					$voucherValue = $voucher->total;
				}
				else
				{
					$voucherValue = ($product_price * $voucher->total) / (100);
				}

				$key = $this->rs_multi_array_key_exists('voucher', $cart);

				if (!$key)
				{
					$voucherArr    = array();
					$oldarr        = array();
					$voucher_index = 0;
				}
				else
				{
					$oldarr        = $cart['voucher'];
					$voucher_index = count($oldarr) + 1;
				}

				$remaining_voucher_discount = 0;

				$totalDiscount = $cart['voucher_discount'] + $cart['cart_discount'] + $cart['coupon_discount'];
				$tmpsubtotal   = $product_price - $cart['coupon_discount'] - $cart['cart_discount'];

				if ($product_price < $voucherValue)
				{
					$remaining_voucher_discount = $voucherValue - $product_price;
					$voucherValue               = $product_price;
				}
				elseif ($totalDiscount > $tmpsubtotal)
				{
					$remaining_voucher_discount = $voucherValue;
					$voucherValue               = 0;
				}

				$valueExist = 0;

				if (is_array($cart['voucher']))
					$valueExist = $this->rs_recursiveArraySearch($cart['voucher'], $voucher_code);

				switch (DISCOUNT_TYPE)
				{
					case 4:
						if ($valueExist)
						{
							$return = true;
						}
						break;
					case 3:
						if ($valueExist && $key)
						{
							$return = false;
						}
						break;
					case 2:
						$couponKey = $this->rs_multi_array_key_exists('coupon', $cart);

						if ($valueExist || $couponKey)
						{
							$return = false;
						}
						break;
					case 1:
					default:
						$voucherArr = array();
						$oldarr     = array();
						unset($cart['coupon']);
						$cart['cart_discount']    = 0;
						$cart['coupon_discount']  = 0;
						$cart['voucher_discount'] = 0;
						$return                   = true;
						break;
				}

				$transaction_voucher_id = 0;

				if ($this->rs_multi_array_key_exists('transaction_voucher_id', $voucher))
				{
					$transaction_voucher_id = $voucher->transaction_voucher_id;
				}

				if ($return)
				{
					$voucherArr['voucher'][$voucher_index]['voucher_code']               = $voucher_code;
					$voucherArr['voucher'][$voucher_index]['voucher_id']                 = $voucher_id;
					$voucherArr['voucher'][$voucher_index]['product_id']                 = $product_ids;
					$voucherArr['voucher'][$voucher_index]['used_voucher']               = $p_quantity;
					$voucherArr['voucher'][$voucher_index]['voucher_value']              = $voucherValue;
					$voucherArr['voucher'][$voucher_index]['remaining_voucher_discount'] = $remaining_voucher_discount;
					$voucherArr['voucher'][$voucher_index]['transaction_voucher_id']     = $transaction_voucher_id;
					$voucherArr['voucher']                                               = array_merge($voucherArr['voucher'], $oldarr);
					$cart                                                                = array_merge($cart, $voucherArr);
					$cart['free_shipping']                                               = $voucher->free_shipping;
					$this->_session->set('cart', $cart);
				}
			}
		}

		if (!empty($v_data))
		{
			return $cart;
		}
		else
		{
			return $return;
		}
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

	public function calculateDiscount($type, $typeArr)
	{
		$value        = $type == 'voucher' ? 'voucher_value' : 'coupon_value';
		$codediscount = 0;

		if (!empty($typeArr))
		{
			$idx = count($typeArr);

			for ($i = 0; $i < $idx; $i++)
			{
				$codediscount += $typeArr[$i][$value];
			}
		}

		return $codediscount;
	}

	public function getVoucherData($voucher_code, $product_id = 0)
	{
		$user         = JFactory::getUser();
		$voucher      = array();
		$current_time = time();

		$gbvoucher = $this->globalvoucher($voucher_code);

		if ($this->_globalvoucher != 1)
		{
			if ($user->id)
			{
				$query = "SELECT vt.transaction_voucher_id,vt.amount as total,vt.product_id from " . $this->_table_prefix . "product_voucher_xref as pv "
					. " left join " . $this->_table_prefix . "product_voucher as v on v.voucher_id = pv.voucher_id where voucher_code='" . $voucher_code . "' ) as nproduct , v.* FROM " . $this->_table_prefix . "product_voucher as v "
					. " left join " . $this->_table_prefix . "product_voucher_transaction as vt on vt.voucher_id = v.voucher_id "
					. "\nWHERE vt.amount > 0 AND v.voucher_type = 'Total' AND v.published = 1 and vt.voucher_code='" . $voucher_code . "' AND ((start_date<='" . $current_time . "' and end_date>='" . $current_time . "') OR ( start_date =0 AND end_date = 0) ) AND vt.user_id='" . $user->id . "' ORDER BY transaction_voucher_id DESC limit 0,1";
				$this->_db->setQuery($query);
				$voucher = $this->_db->loadObject();

				if (count($voucher) > 0)
					$this->_r_voucher = 1;
			}

			if ((count($voucher)) <= 0)
			{
				$query = "SELECT (select GROUP_CONCAT(DISTINCT CAST(product_id AS CHAR)  SEPARATOR ', ') as product_id from " . $this->_table_prefix . "product_voucher_xref as pv "
					. " left join " . $this->_table_prefix . "product_voucher as v on v.voucher_id = pv.voucher_id where voucher_code='" . $voucher_code . "') as nproduct,"
					. " amount as total ,voucher_type,free_shipping,voucher_id,voucher_code,voucher_left  FROM " . $this->_table_prefix . "product_voucher as v  "
					. "\nWHERE published = 1 and voucher_code='" . $voucher_code . "' and ((start_date<='" . $current_time . "' and end_date>='" . $current_time . "') OR ( start_date =0 AND end_date = 0) ) and voucher_left>0 limit 0,1";
				$this->_db->setQuery($query);
				$voucher = $this->_db->loadObject();
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
		$current_time = time();
		$query        = "SELECT product_id,v.* from " . $this->_table_prefix . "product_voucher_xref as pv  "
			. "left join " . $this->_table_prefix . "product_voucher as v on v.voucher_id = pv.voucher_id "
			. " \nWHERE v.published = 1"
			. " and v.voucher_code='" . $voucher_code . "' "
			. " and ((v.start_date<='" . $current_time . "' and v.end_date>='" . $current_time . "') OR ( v.start_date =0 AND v.end_date = 0) ) and v.voucher_left>0 limit 0,1";
		$this->_db->setQuery($query);
		$voucher = $this->_db->loadObject();

		if (count($voucher) <= 0)
		{
			$this->_globalvoucher = 1;
			$query                = "SELECT v.*,v.amount as total from " . $this->_table_prefix . "product_voucher as v "
				. "WHERE v.published = 1 "
				. "AND v.voucher_code='" . $voucher_code . "' "
				. "AND ((v.start_date<='" . $current_time . "' AND v.end_date>='" . $current_time . "') OR ( v.start_date =0 AND v.end_date = 0) ) "
				. "AND v.voucher_left>0 LIMIT 0,1 ";
			$this->_db->setQuery($query);
			$voucher = $this->_db->loadObject();
		}

		return $voucher;
	}

	public function getcouponData($coupon_code)
	{
		$current_time = time();
		$cart         = $this->_session->get('cart');
		$user         = JFactory::getUser();
		$coupon       = array();

		if ($user->id)
		{
			$query = "SELECT ct.coupon_value as coupon_value,c.free_shipping, c.coupon_id,c.coupon_code,c.percent_or_total,ct.userid,ct.transaction_coupon_id FROM " . $this->_table_prefix . "coupons as c "
				. "left join " . $this->_table_prefix . "coupons_transaction as ct on ct.coupon_id = c.coupon_id "
				. "WHERE ct.coupon_value > 0 AND c.published = 1 and ct.coupon_code='" . $coupon_code . "' AND (c.start_date<='" . $current_time . "' AND c.end_date>='" . $current_time . "' ) AND ct.userid='" . $user->id . "' ORDER BY transaction_coupon_id DESC limit 0,1";
			$this->_db->setQuery($query);
			$coupon = $this->_db->loadObject();

			if (count($coupon) > 0)
			{
				$this->_c_remain = 1;
			}
		}

		if (count($coupon) <= 0)
		{
			$query = "SELECT * FROM " . $this->_table_prefix . "coupons   "
				. "WHERE published = 1 and coupon_code='" . $coupon_code . "' and (start_date<='" . $current_time . "' and end_date>='" . $current_time . "' ) AND coupon_left > 0 AND ( '" . $subtotal . "' >= subtotal OR subtotal = 0 OR subtotal = '' ) limit 0,1";
			$this->_db->setQuery($query);
			$coupon = $this->_db->loadObject();
		}

		return $coupon;
	}

	public function modifyDiscount($cart)
	{
		$calArr                            = $this->calculation($cart);
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

		if (DISCOUNT_ENABLE == 1)
		{
			$discount_amount = $this->_producthelper->getDiscountAmount($cart);
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
			$cart         = $this->voucher($cart);
		}

		if (array_key_exists('voucher', $cart))
		{
			$voucherDiscount = $this->calculateDiscount('voucher', $cart['voucher']);
		}

		$cart['voucher_discount'] = $voucherDiscount;

		for ($c = 0; $c < $c_index; $c++)
		{
			$coupon_code = $cart['coupon'][$c]['coupon_code'];
			unset($cart['coupon'][$c]);
			$coupon_code = JRequest::setVar('discount_code', $coupon_code);
			$cart        = $this->coupon($cart);
		}

		if (array_key_exists('coupon', $cart))
		{
			$couponDiscount = $this->calculateDiscount('coupon', $cart['coupon']);
		}

		$cart['coupon_discount'] = $couponDiscount;
		$codeDsicount            = $voucherDiscount + $couponDiscount;
		$totaldiscount           = $cart['cart_discount'] + $codeDsicount;

		$calArr = $this->calculation($cart);

		if (!APPLY_VAT_ON_DISCOUNT)
		{
			$vatData = $this->_producthelper->getVatRates();
			$vatrate = 0;

			if (isset($vatData->tax_rate))
			{
				$vatrate = $vatData->tax_rate;
			}

			$discount_excl_vat = round($totaldiscount, 2);
			$discount_excl_vat = $discount_excl_vat / (1 + ($vatrate));
		}

		$tax         = $calArr[5];
		$Discountvat = 0;
		$chktag      = $this->_producthelper->taxexempt_addtocart();

		if (VAT_RATE_AFTER_DISCOUNT && !empty($chktag))
		{
			$Discountvat = (VAT_RATE_AFTER_DISCOUNT * $totaldiscount) / (1 + VAT_RATE_AFTER_DISCOUNT);
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
		$cart['mod_cart_total']            = $this->GetCartModuleCalc($cart);

		$this->_session->set('cart', $cart);

		if (JModuleHelper::isEnabled('redshop_cart'))
		{
			JLoader::import('joomla.html.parameter');
			$cart_param        = JModuleHelper::getModule('redshop_cart');
			$cart_param_main   = new JRegistry($cart_param->params);
			$use_cookies_value = $cart_param_main->get('use_cookies_value', '');

			if ($use_cookies_value == 1)
			{
				setcookie("redSHOPcart", serialize($cart), time() + (60 * 60 * 24 * 365));
			}
		}

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

	public function checkQuantityInStock($data = array(), $newquantity = 1, $minQuantity = 0)
	{
		$main_quantity   = $newquantity;
		$stockroomhelper = new rsstockroomhelper;

		$productData      = $this->_producthelper->getProductById($data['product_id']);
		$product_preorder = $productData->preorder;

		if ($productData->min_order_product_quantity > 0 && $productData->min_order_product_quantity > $newquantity)
		{
			$msg = $productData->product_name . " " . JText::_('COM_REDSHOP_WARNING_MSG_MINIMUM_QUANTITY');
			$msg = sprintf($msg, $productData->min_order_product_quantity);
			JError::raiseWarning('', $msg);
			$newquantity = $productData->min_order_product_quantity;
		}

		if (USE_STOCKROOM == 1)
		{
			if (($product_preorder == "global" && !ALLOW_PRE_ORDER) || ($product_preorder == "no") || ($product_preorder == "" && !ALLOW_PRE_ORDER))
			{
				$currentStock = $stockroomhelper->getStockroomTotalAmount($data['product_id']);
			}

			if (($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes") || ($product_preorder == "" && ALLOW_PRE_ORDER))
			{
				$regular_currentStock  = $stockroomhelper->getStockroomTotalAmount($data['product_id']);
				$preorder_currentStock = $stockroomhelper->getPreorderStockroomTotalAmount($data['product_id']);
				$currentStock          = $regular_currentStock + $preorder_currentStock;
			}

			$attArr = $data['cart_attribute'];

			if (count($attArr) <= 0)
			{
				$ownreserveStock = $stockroomhelper->getCurrentUserReservedStock($data['product_id']);

				if ($currentStock >= 0)
				{
					if ($newquantity > $ownreserveStock && $currentStock < ($newquantity - $ownreserveStock))
					{
						$newquantity = $currentStock + $ownreserveStock;
					}
				}
				else
				{
					$newquantity = $currentStock + $ownreserveStock;
				}
			}

			if ($newquantity >= 0)
			{
				if ($newquantity == 0)
				{
					$data['quantity'] = $main_quantity;
				}
				else
				{
					$data['quantity'] = $newquantity;
				}

				$newquantity = $this->checkAttributeStockRoom($data, $productData);
			}
		}

		if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
		{
			$msg = $productData->product_name . " " . JText::_('COM_REDSHOP_WARNING_MSG_MAXIMUM_QUANTITY');
			$msg = sprintf($msg, $productData->max_order_product_quantity);
			JError::raiseWarning('', $msg);
			$newquantity = $productData->max_order_product_quantity;
		}

		$stockroomhelper->addReservedStock($data['product_id'], $newquantity);

		return $newquantity;
	}

	public function checkAttributeStockRoom($data = array(), $productData = array())
	{
		$stockroomhelper  = new rsstockroomhelper;
		$newquantity      = $data['quantity'];
		$attArr           = $data['cart_attribute'];
		$product_preorder = $productData->preorder;

		for ($i = 0; $i < count($attArr); $i++)
		{
			$propArr = $attArr[$i]['attribute_childs'];

			for ($k = 0; $k < count($propArr); $k++)
			{
				if (USE_STOCKROOM == 1)
				{
					if (($product_preorder == "global" && !ALLOW_PRE_ORDER) || ($product_preorder == "no") || ($product_preorder == "" && !ALLOW_PRE_ORDER))
					{
						$property_stock = $stockroomhelper->getStockroomTotalAmount($propArr[$k]['property_id'], "property");
					}

					if (($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes") || ($product_preorder == "" && ALLOW_PRE_ORDER))
					{
						$regular_property_stock  = $stockroomhelper->getStockroomTotalAmount($propArr[$k]['property_id'], "property");
						$Preorder_property_stock = $stockroomhelper->getPreorderStockroomTotalAmount($propArr[$k]['property_id'], "property");
						$property_stock          = $regular_property_stock + $Preorder_property_stock;
					}

					$ownreserveStock = $stockroomhelper->getCurrentUserReservedStock($propArr[$k]['property_id'], "property");

					if ($property_stock >= 0)
					{
						if ($newquantity > $ownreserveStock && $property_stock < ($newquantity - $ownreserveStock))
						{
							$newquantity = $property_stock + $ownreserveStock;
						}
					}
					else
					{
						$newquantity = $property_stock + $ownreserveStock;
					}
				}

				$subpropArr = $propArr[$k]['property_childs'];

				for ($l = 0; $l < count($subpropArr); $l++)
				{
					if (USE_STOCKROOM == 1)
					{
						if (($product_preorder == "global" && !ALLOW_PRE_ORDER) || ($product_preorder == "no") || ($product_preorder == "" && !ALLOW_PRE_ORDER))
						{
							$subproperty_stock = $stockroomhelper->getStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
						}

						if (($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes") || ($product_preorder == "" && ALLOW_PRE_ORDER))
						{
							$regular_subproperty_stock  = $stockroomhelper->getStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
							$preorder_subproperty_stock = $stockroomhelper->getPreorderStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
							$subproperty_stock          = $regular_subproperty_stock + $preorder_subproperty_stock;
						}

						$ownreserveStock = $stockroomhelper->getCurrentUserReservedStock($propArr[$k]['property_id'], "property");

						if ($subproperty_stock >= 0)
						{
							if ($newquantity > $ownreserveStock && $subproperty_stock < ($newquantity - $ownreserveStock))
							{
								$newquantity = $subproperty_stock + $ownreserveStock;
							}
						}
						else
						{
							$newquantity = $subproperty_stock + $ownreserveStock;
						}
					}
				}
			}
		}

		if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
		{
			$newquantity = $productData->max_order_product_quantity;
		}

		for ($i = 0; $i < count($attArr); $i++)
		{
			$propArr = $attArr[$i]['attribute_childs'];

			for ($k = 0; $k < count($propArr); $k++)
			{
				$stockroomhelper->addReservedStock($propArr[$k]['property_id'], $newquantity, "property");
				$subpropArr = $propArr[$k]['property_childs'];

				for ($l = 0; $l < count($subpropArr); $l++)
				{
					$stockroomhelper->addReservedStock($subpropArr[$l]['subproperty_id'], $newquantity, "subproperty");
				}
			}
		}

		return $newquantity;
	}

	public function cartFinalCalculation($callmodify = true)
	{
		$ajax = JRequest::getVar('ajax_cart_box');
		$cart = $this->_session->get('cart');

		if ($callmodify == true)
		{
			$cart = $this->modifyDiscount($cart);
		}

		$cartoutputArray = array();
		$cartArray       = $this->makeCart_output($cart);

		$text = $this->_shippinghelper->getfreeshippingRate();

		$cartoutputArray['cart_output']    = $cartArray[0];
		$cartoutputArray['total_quantity'] = $cartArray[1];

		if (AJAX_CART_BOX == 1 && $ajax == 1)
		{
			echo "`" . $cartArray[0] . "`" . $text;
			exit;
		}

		return $cartoutputArray;
	}

	public function carttodb($cart = array())
	{
		if (count($cart) <= 0)
		{
			$cart = $this->_session->get('cart');
		}

		$idx  = $cart['idx'];
		$user = JFactory::getUser();

		// If user is not logged in don't save in db
		if ($user->id <= 0)
			return false;

		$query = "SELECT cart_id FROM " . $this->_table_prefix . "usercart WHERE user_id='" . $user->id . "'";
		$this->_db->setQuery($query);
		$cart_id = $this->_db->loadResult();

		if (!$cart_id)
		{
			$row          = JTable::getInstance('usercart', 'Table');
			$row->user_id = $user->id;
			$row->cdate   = time();
			$row->mdate   = time();

			if (!$row->store())
			{
				return JError::raiseWarning('', $row->getError());
			}

			$cart_id = $row->cart_id;
		}

		$this->removecartfromdb($cart_id, $user->id);

		for ($i = 0; $i < $idx; $i++)
		{
			$rowItem = JTable::getInstance('usercart_item', 'Table');

			$rowItem->cart_idx                = $i;
			$rowItem->cart_id                 = $cart_id;
			$rowItem->product_id              = $cart[$i]['product_id'];

			if (isset($cart[$i]['giftcard_id']) === false)
			{
				$cart[$i]['giftcard_id'] = 0;
			}

			$rowItem->giftcard_id             = $cart[$i]['giftcard_id'];
			$rowItem->product_quantity        = $cart[$i]['quantity'];
			$rowItem->product_wrapper_id      = $cart[$i]['wrapper_id'];

			if (isset($cart[$i]['subscription_id']) === false)
			{
				$cart[$i]['subscription_id'] = 0;
			}

			$rowItem->product_subscription_id = $cart[$i]['subscription_id'];

			if (!$rowItem->store())
			{
				return JError::raiseWarning('', $rowItem->getError());
			}

			$cart_item_id = $rowItem->cart_item_id;

			$cart_attribute = $cart[$i]['cart_attribute'];
			/* store attribute in db */
			$this->attributetodb($cart_attribute, $cart_item_id, $rowItem->product_id);

			$cart_accessory = $cart[$i]['cart_accessory'];

			for ($j = 0; $j < count($cart_accessory); $j++)
			{
				$rowAcc               = JTable::getInstance('usercart_accessory_item', 'Table');
				$rowAcc->accessory_id = $cart_accessory[$j]['accessory_id'];

				// Store product quantity as accessory quantity.
				$rowAcc->accessory_quantity = $cart[$i]['quantity'];

				if (!$rowAcc->store())
				{
					return JError::raiseWarning('', $rowAcc->getError());
				}

				$accessory_childs = $cart_accessory[$j]['accessory_childs'];
				$this->attributetodb($accessory_childs, $cart_item_id, $rowAcc->accessory_id, true);
			}
		}
	}

	public function attributetodb($attribute = array(), $cart_item_id = 0, $product_id = 0, $isAccessary = false)
	{
		if ($cart_item_id == 0)
		{
			return false;
		}

		for ($j = 0; $j < count($attribute); $j++)
		{
			$rowAtt = JTable::getInstance('usercart_attribute_item', 'Table');

			$rowAtt->section_id        = $attribute[$j]['attribute_id'];
			$rowAtt->section           = 'attribute';
			$rowAtt->parent_section_id = $product_id;
			$rowAtt->is_accessory_att  = $isAccessary;

			if (!$rowAtt->store())
			{
				return JError::raiseWarning('', $rowAtt->getError());
			}

			$attribute_childs = $attribute[$j]['attribute_childs'];

			for ($k = 0; $k < count($attribute_childs); $k++)
			{
				$rowProp = JTable::getInstance('usercart_attribute_item', 'Table');

				$rowProp->section_id        = $attribute_childs[$k]['property_id'];
				$rowProp->section           = 'property';
				$rowProp->parent_section_id = $attribute[$j]['attribute_id'];
				$rowProp->is_accessory_att  = $isAccessary;

				if (!$rowProp->store())
				{
					return JError::raiseWarning('', $rowProp->getError());
				}

				$property_childs = $attribute_childs[$k]['property_childs'];

				if (count($property_childs) > 0)
				{
					for ($i = 0; $i < count($property_childs); $i++)
					{
						$rowProp = JTable::getInstance('usercart_attribute_item', 'Table');

						$rowProp->section_id        = $property_childs[$i]['subproperty_id'];
						$rowProp->section           = 'subproperty';
						$rowProp->parent_section_id = $attribute_childs[$k]['property_id'];
						$rowProp->is_accessory_att  = $isAccessary;

						if (!$rowProp->store())
						{
							return JError::raiseWarning('', $rowProp->getError());
						}
					}
				}
			}
		}
	}

	/**
	 * Remove cart entry from table
	 *
	 * @param   int  $cart_id   #__redshop_usercart table key id
	 * @param   int  $userid    user information id - joomla #__users table key id
	 * @param   bool $delCart   remove cart from #__redshop_usercart table
	 *
	 * @return bool
	 */
	public function removecartfromdb($cart_id = 0, $userid = 0, $delCart = false)
	{
		/*if($cart_id==0)
		{
			return false;
		}*/

		if ($userid == 0)
		{
			$user   = JFactory::getUser();
			$userid = $user->id;
		}

		if ($cart_id == 0)
		{
			$query = "SELECT cart_id FROM " . $this->_table_prefix . "usercart WHERE user_id='" . $userid . "'";
			$this->_db->setQuery($query);
			$cart_id = $this->_db->loadResult();
		}

		$query = "SELECT cart_item_id FROM " . $this->_table_prefix . "usercart_item WHERE cart_id='" . $cart_id . "'";
		$this->_db->setQuery($query);
		$cart_item_id = $this->_db->loadResult();

		$query = "DELETE FROM " . $this->_table_prefix . "usercart_accessory_item WHERE cart_item_id='" . $cart_item_id . "'";
		$this->_db->setQuery($query);
		$this->_db->Query();

		$query = "DELETE FROM " . $this->_table_prefix . "usercart_attribute_item WHERE cart_item_id='" . $cart_item_id . "'";
		$this->_db->setQuery($query);
		$this->_db->Query();

		$query = "DELETE FROM " . $this->_table_prefix . "usercart_item WHERE cart_id='" . $cart_id . "'";
		$this->_db->setQuery($query);
		$this->_db->Query();

		if ($delCart)
		{
			$query = "DELETE FROM " . $this->_table_prefix . "usercart WHERE cart_id='" . $cart_id . "'";
			$this->_db->setQuery($query);
			$this->_db->Query();
		}

		return true;
	}

	public function dbtocart($userId = 0)
	{
		$rsUserhelper = new rsUserhelper;

		if ($userId == 0)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		$query = "SELECT ci.* FROM " . $this->_table_prefix . "usercart AS c," . $this->_table_prefix . "usercart_item AS ci
				  WHERE c.cart_id = ci.cart_id AND user_id='" . $userId . "' ORDER BY cart_idx";
		$this->_db->setQuery($query);
		$cart_items = $this->_db->loadObjectlist();

		if (count($cart_items) > 0)
		{
			$cart = array();
			$idx  = 0;

			for ($i = 0; $i < count($cart_items); $i++)
			{
				$setCartItem = true;
				$section     = 12;

				if ($cart_items[$i]->giftcard_id != 0)
				{
					$section = 13;
				}

				$cart_item_id  = $cart_items[$i]->cart_item_id;
				$product_id    = $cart_items[$i]->product_id;
				$quantity      = $cart_items[$i]->product_quantity;
				$product_price = 0;
				$product_data  = $this->_producthelper->getProductById($product_id);

				$calc_output       = "";
				$calc_output_array = array();

				// Attribute price added
				$generateAttributeCart = $this->generateAttributeFromCart($cart_item_id, 0, $product_id, $quantity);
				$retAttArr             = $this->_producthelper->makeAttributeCart($generateAttributeCart, $product_id, 0, $product_price, $quantity);

				$product_price_excl_vat     = $retAttArr[1];
				$product_vat_price          = $retAttArr[2];
				$selectedAttrId             = $retAttArr[3];
				$isStock                    = $retAttArr[4];
				$product_old_price          = $retAttArr[5] + $retAttArr[6];
				$product_old_price_excl_vat = $retAttArr[5];
				$product_price              = $product_price_excl_vat + $product_vat_price;

				if (!$isStock)
				{
					$setCartItem = false;
					$msg         = JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');
				}

				$subscription_id = 0;

				if ($product_data->product_type == 'subscription')
				{
					$productSubscription = $this->_producthelper->getProductSubscriptionDetail($product_id, $cart_items[$i]->product_subscription_id);

					if ($productSubscription->subscription_id != "")
					{
						$subscription_id    = $productSubscription->subscription_id;
						$subscription_price = $productSubscription->subscription_price;
						$subscription_vat   = 0;

						if ($subscription_price)
						{
							$subscription_vat = $this->_producthelper->getProductTax($product_id, $subscription_price);
						}

						$product_vat_price += $subscription_vat;
						$product_price += $subscription_price + $subscription_vat;
						$product_old_price = $product_old_price + $subscription_price + $subscription_vat;
						$product_old_price_excl_vat += $subscription_price;
						$product_price_excl_vat += $subscription_price;

					}
					else
					{
						$setCartItem = false;
						$msg         = JText::_('COM_REDSHOP_SELECT_PRODUCT_SUBSCRIPTION');
					}
				}

				// Accessory price
				$generateAccessoryCart = $this->generateAccessoryFromCart($cart_item_id, $product_id, $quantity);
				$retAccArr             = $this->_producthelper->makeAccessoryCart($generateAccessoryCart, $product_id);
				$accessory_total_price = $retAccArr[1];
				$accessory_vat_price   = $retAccArr[2];

				$product_price_excl_vat += $accessory_total_price;
				$product_price += $accessory_total_price + $accessory_vat_price;
				$product_old_price += $accessory_total_price + $accessory_vat_price;
				$product_old_price_excl_vat += $accessory_total_price;
				$product_vat_price = $product_vat_price + $accessory_vat_price;

				// Check if required attribute is filled or not
				$selectedAttributId = 0;

				if (count($selectedAttrId) > 0)
				{
					$selectedAttributId = implode(",", $selectedAttrId);
				}

				$req_attribute = $this->_producthelper->getProductAttribute($product_id, 0, 0, 0, 1, $selectedAttributId);

				if (count($req_attribute) > 0)
				{
					$requied_attributeArr = array();

					for ($re = 0; $re < count($req_attribute); $re++)
					{
						$requied_attributeArr[$re] = $req_attribute[0]->attribute_name;
					}

					$requied_attribute_name = implode(", ", $requied_attributeArr);

					// Throw an error as first attribute is required
					$msg         = $requied_attribute_name . " " . JText::_('COM_REDSHOP_IS_REQUIRED');
					$setCartItem = false;
				}

				// ADD WRAPPER PRICE
				$wrapper_price = 0;
				$wrapper_vat   = 0;

				if ($cart_items[$i]->product_wrapper_id)
				{
					$wrapperArr    = $this->getWrapperPriceArr(array('product_id' => $product_id, 'wrapper_id' => $cart_items[$i]->product_wrapper_id));
					$wrapper_vat   = $wrapperArr['wrapper_vat'];
					$wrapper_price = $wrapperArr['wrapper_price'];
				}

				$product_vat_price += $wrapper_vat;
				$product_price += $wrapper_price + $wrapper_vat;
				$product_old_price += $wrapper_price + $wrapper_vat;
				$product_old_price_excl_vat += $wrapper_price;
				$product_price_excl_vat += $wrapper_price;

				// END WRAPPER PRICE

				if ($setCartItem)
				{
					if ($product_price < 0)
					{
						$product_price = 0;
					}

					$cart[$idx]['giftcard_id']                = '';
					$cart[$idx]['product_id']                 = $product_id;
					$cart[$idx]['discount_calc_output']       = $calc_output;
					$cart[$idx]['discount_calc']              = $calc_output_array;
					$cart[$idx]['product_price']              = $product_price;
					$cart[$idx]['product_price_excl_vat']     = $product_price_excl_vat;
					$cart[$idx]['product_vat']                = $product_vat_price;
					$cart[$idx]['product_old_price']          = $product_old_price;
					$cart[$idx]['product_old_price_excl_vat'] = $product_old_price_excl_vat;
					$cart[$idx]['cart_attribute']             = $generateAttributeCart;
					$cart[$idx]['cart_accessory']             = $generateAccessoryCart;
					$cart[$idx]['subscription_id']            = $subscription_id;
					$cart[$idx]['category_id']                = 0;
					$cart[$idx]['wrapper_id']                 = $cart_items[$i]->product_wrapper_id;
					$cart[$idx]['wrapper_price']              = $wrapper_price;
					$cart[$idx]['quantity']                   = 0;

					$newQuantity            = $quantity;
					$cart[$idx]['quantity'] = $this->checkQuantityInStock($cart[$idx], $newQuantity);

					if ($cart[$idx]['quantity'] <= 0)
					{
						$msg = JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

						if (CART_RESERVATION_MESSAGE != '')
						{
							$msg = CART_RESERVATION_MESSAGE;
						}
					}
					else
					{
						$idx++;
					}
				}
			}

			$cart['idx']                   = $idx;
			$cart['discount_type']         = 0;
			$cart['discount']              = 0;
			$shoppergroup                  = $rsUserhelper->getShopperGroup($user->id);
			$cart['user_shopper_group_id'] = $shoppergroup;

			// Set 0 as default..
			$cart['free_shipping']    = 0;
			$cart['voucher_discount'] = 0;
			$cart['coupon_discount']  = 0;
			$cart['cart_discount']    = 0;
			$this->_session->set('cart', $cart);

			$this->cartFinalCalculation();
		}
	}

	public function generateAttributeFromCart($cart_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		$generateAttributeCart = array();

		$cart_itemsAttdata = $this->getCartItemAttributeDetail($cart_item_id, $is_accessory, "attribute", $parent_section_id);

		for ($i = 0; $i < count($cart_itemsAttdata); $i++)
		{
			$accPropertyCart                             = array();
			$generateAttributeCart[$i]['attribute_id']   = $cart_itemsAttdata[$i]->section_id;
			$generateAttributeCart[$i]['attribute_name'] = $cart_itemsAttdata[$i]->section_name;

			$cartPropdata = $this->getCartItemAttributeDetail($cart_item_id, $is_accessory, "property", $cart_itemsAttdata[$i]->section_id);

			for ($p = 0; $p < count($cartPropdata); $p++)
			{
				$accSubpropertyCart = array();
				$property_price     = 0;
				$property           = $this->_producthelper->getAttibuteProperty($cartPropdata[$p]->section_id);
				$pricelist          = $this->_producthelper->getPropertyPrice($cartPropdata[$p]->section_id, $quantity, 'property');

				if (count($pricelist) > 0)
				{
					$property_price = $pricelist->product_price;
				}
				else
				{
					$property_price = $property[0]->property_price;
				}

				$accPropertyCart[$p]['property_id']     = $cartPropdata[$p]->section_id;
				$accPropertyCart[$p]['property_name']   = $property[0]->text;
				$accPropertyCart[$p]['property_oprand'] = $property[0]->oprand;
				$accPropertyCart[$p]['property_price']  = $property_price;

				$cartSubpropdata = $this->getCartItemAttributeDetail($cart_item_id, $is_accessory, "subproperty", $cartPropdata[$p]->section_id);

				for ($sp = 0; $sp < count($cartSubpropdata); $sp++)
				{
					$subproperty_price = 0;
					$subproperty       = $this->_producthelper->getAttibuteSubProperty($cartSubpropdata[$sp]->section_id);
					$pricelist         = $this->_producthelper->getPropertyPrice($cartSubpropdata[$sp]->section_id, $quantity, 'subproperty');

					if (count($pricelist) > 0)
					{
						$subproperty_price = $pricelist->product_price;
					}
					else
					{
						$subproperty_price = $subproperty[0]->subattribute_color_price;
					}

					$accSubpropertyCart[$sp]['subproperty_id']     = $cartSubpropdata[$sp]->section_id;
					$accSubpropertyCart[$sp]['subproperty_name']   = $subproperty[0]->text;
					$accSubpropertyCart[$sp]['subproperty_oprand'] = $subproperty[0]->oprand;
					$accSubpropertyCart[$sp]['subproperty_price']  = $subproperty_price;
				}

				$accPropertyCart[$p]['property_childs'] = $accSubpropertyCart;
			}

			$generateAttributeCart[$i]['attribute_childs'] = $accPropertyCart;
		}

		return $generateAttributeCart;
	}

	public function generateAccessoryFromCart($cart_item_id = 0, $product_id = 0, $quantity = 1)
	{
		$generateAccessoryCart = array();

		$cartItemdata = $this->getCartItemAccessoryDetail($cart_item_id);

		for ($i = 0; $i < count($cartItemdata); $i++)
		{
			$accessory          = $this->_producthelper->getProductAccessory($cartItemdata[$i]->product_id);
			$accessorypricelist = $this->_producthelper->getAccessoryPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
			$accessory_price    = $accessorypricelist[0];

			$generateAccessoryCart[$i]['accessory_id']     = $cartItemdata[$i]->product_id;
			$generateAccessoryCart[$i]['accessory_name']   = $accessory[0]->product_name;
			$generateAccessoryCart[$i]['accessory_oprand'] = $accessory[0]->oprand;
			$generateAccessoryCart[$i]['accessory_price']  = $accessory_price;
			$generateAccessoryCart[$i]['accessory_childs'] = $this->generateAttributeFromCart($cart_item_id, 1, $cartItemdata[$i]->product_id, $quantity);
		}

		return $generateAccessoryCart;
	}

	public function getCartItemAccessoryDetail($cart_item_id = 0)
	{
		$and = "";

		if ($cart_item_id != 0)
		{
			$and .= " AND cart_item_id='" . $cart_item_id . "' ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "usercart_accessory_item "
			. "WHERE 1=1 "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getCartItemAttributeDetail($cart_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		$and = "";

		if ($cart_item_id != 0)
		{
			$and .= " AND cart_item_id='" . $cart_item_id . "' ";
		}

		if ($parent_section_id != 0)
		{
			$and .= " AND parent_section_id='" . $parent_section_id . "' ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "usercart_attribute_item "
			. "WHERE is_accessory_att='" . $is_accessory . "' "
			. "AND section='" . $section . "' "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function addProductToCart($data = array())
	{
		$dispatcher   = JDispatcher::getInstance();
		$rsUserhelper = new rsUserhelper;
		$redTemplate  = new Redtemplate;
		$user         = JFactory::getUser();
		$cart         = $this->_session->get('cart');

		if (!$cart || !array_key_exists("idx", $cart) || array_key_exists("quotation_id", $cart))
		{
			$cart        = array();
			$cart['idx'] = 0;
		}

		$idx = (int) ($cart['idx']);

		$section = (isset($data['giftcard_id']) && $data['giftcard_id'] != 0) ? 13 : 12;

		$row_data = $this->_extraFieldFront->getSectionFieldList($section);

		// Set session for giftcard
		if (isset($data['giftcard_id']) && $data['giftcard_id'])
		{
			$cart[$idx]['reciver_email']   = $data['reciver_email'];
			$cart[$idx]['reciver_name']    = $data['reciver_name'];
			$cart[$idx]['customer_amount'] = $data['customer_amount'];

			for ($g = 0; $g < count($idx); $g++)
			{
				if ($cart[$g]['giftcard_id'] == $data['giftcard_id'])
				{
					$cart[$idx]['quantity'] += 1;
					$this->_session->set('cart', $cart);

					return true;
				}
			}

			$cart[$idx]['quantity'] = 1;
			$giftcardData           = $this->_producthelper->getGiftcardData($data['giftcard_id']);

			if ($giftcardData->customer_amount)
			{
				$giftcard_price = $data['customer_amount'];
			}
			else
			{
				$giftcard_price = $giftcardData->giftcard_price;
			}

			$cart[$idx]['product_price']          = $giftcard_price;
			$cart[$idx]['product_price_excl_vat'] = $giftcard_price;
			$cart[$idx]['product_vat']            = 0;
			$cart[$idx]['product_id']             = '';

			if (!$cart['discount_type'])
			{
				$cart['discount_type'] = 0;
			}

			if (!$cart['discount'])
			{
				$cart['discount'] = 0;
			}

			$cart[$idx]['giftcard_id'] = $data['giftcard_id'];

			for ($i = 0; $i < count($row_data); $i++)
			{
				$data_txt = (isset($data[$row_data[$i]->field_name])) ? $data[$row_data[$i]->field_name] : '';
				$tmpstr   = strpbrk($data_txt, '`');

				if ($tmpstr)
				{
					$tmparray = explode('`', $data_txt);
					$tmp      = new stdClass;
					$tmp      = @array_merge($tmp, $tmparray);

					if (is_array($tmparray))
					{
						$data_txt = implode(",", $tmparray);
					}
				}

				$cart[$idx][$row_data[$i]->field_name] = $data_txt;
			}

			$cart['idx'] = $idx + 1;
			$this->_session->set('cart', $cart);

			return true;
		}

		if (isset($data['hidden_attribute_cartimage']))
		{
			$cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage'];
		}

		$product_id   = $data['product_id'];
		$quantity     = $data['quantity'];
		$product_data = $this->_producthelper->getProductById($product_id);

		if (isset($data['parent_accessory_product_id']) && $data['parent_accessory_product_id'] != 0)
		{
			$tempdata           = $this->_producthelper->getProductById($data['parent_accessory_product_id']);
			$producttemplate    = $redTemplate->getTemplate("product", $tempdata->product_template);
			$accessory_template = $this->_producthelper->getAccessoryTemplate($producttemplate[0]->template_desc);
			$data_add           = $accessory_template->template_desc;
		}
		else
		{
			$producttemplate = $redTemplate->getTemplate("product", $product_data->product_template);
			$data_add        = $producttemplate[0]->template_desc;
		}

		/*
		 * Check if required userfield are filled or not if not than redirect to product detail page...
		 * get product userfield from selected product template...
		 */
		if (!AJAX_CART_BOX)
		{
			$fieldreq = $this->userfieldValidation($data, $data_add, $section);

			if ($fieldreq != "")
			{
				return $fieldreq;
			}
		}

		// Get product price
		$data['product_price'] = 0;

		// Attribute price added
		$generateAttributeCart = isset($data['cart_attribute']) ? $data['cart_attribute'] : $this->generateAttributeArray($data);

		$retAttArr                          = $this->_producthelper->makeAttributeCart($generateAttributeCart, $product_data->product_id, 0, $data['product_price'], $quantity);
		$selectProp                         = $this->_producthelper->getSelectedAttributeArray($data);
		$data['product_old_price']          = $retAttArr[5] + $retAttArr[6];
		$data['product_old_price_excl_vat'] = $retAttArr[5];

		$data['product_price'] = $retAttArr[1];

		$product_vat_price                    = $retAttArr[2];
		$cart[$idx]['product_price_excl_vat'] = $retAttArr[1];
		$data['product_price'] += $product_vat_price;

		if (!empty($selectProp[0]))
		{
			$attributeImage = $product_id;

			if (count($selectProp[0]) == 1)
			{
				$attributeImage .= '_p' . $selectProp[0][0];
			}
			else
			{
				$pattributeImage = implode('_p', $selectProp[0]);
				$attributeImage .= '_p' . $pattributeImage;
			}

			if (count($selectProp[1]) == 1)
			{
				$attributeImage .= '_sp' . $selectProp[1][0];
			}
			else
			{
				$sattributeImage = implode('_sp', $selectProp[1]);

				if ($sattributeImage)
				{
					$attributeImage .= '_sp' . $sattributeImage;
				}
			}

			$cart[$idx]['attributeImage'] = $attributeImage . '.png';
		}

		if ($data['reorder'] && $data['attributeImage'])
		{
			$cart[$idx]['attributeImage'] = $data['attributeImage'];
		}

		$selectedAttrId       = $retAttArr[3];
		$isStock              = $retAttArr[4];
		$selectedPropId       = $selectProp[0];
		$notselectedSubpropId = $retAttArr[8];
		$product_preorder     = $product_data->preorder;
		$isPreorderStock      = $retAttArr[7];

		if (!$isStock)
		{
			if (($product_preorder == "global" && !ALLOW_PRE_ORDER) || ($product_preorder == "no") || ($product_preorder == "" && !ALLOW_PRE_ORDER))
			{
				$msg = urldecode(JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));

				return $msg;
			}
			elseif (!$isPreorderStock)
			{
				$msg = urldecode(JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE'));

				return $msg;
			}
		}

		// Attribute End

		// Discount calculator procedure start
		$discountArr = array();
		$discountArr = $this->discountCalculatorData($product_data, $data);

		$calc_output       = "";
		$calc_output_array = array();
		$product_price_tax = 0;

		if (!empty($discountArr))
		{
			$calc_output       = $discountArr[0];
			$calc_output_array = $discountArr[1];

			// Calculate price without VAT
			$data['product_price'] += $discountArr[2] + $discountArr[3];

			$cart[$idx]['product_price_excl_vat'] += $discountArr[2];
			$product_vat_price += $discountArr[3];
			$cart[$idx]['discount_calc_price'] = $discountArr[2];
		}

		$cart[$idx]['subscription_id'] = 0;

		if ($product_data->product_type == 'subscription')
		{
			if (isset($data['subscription_id']) && $data['subscription_id'] != "")
			{
				$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($data['product_id'], $data['subscription_id']);
				$subscription_price  = $subscription_detail->subscription_price;

				if ($subscription_price)
				{
					$subscription_vat = $this->_producthelper->getProductTax($data['product_id'], $subscription_price);
				}

				$product_vat_price += $subscription_vat;
				$data['product_price']     = $data['product_price'] + $subscription_price + $subscription_vat;
				$data['product_old_price'] = $data['product_old_price'] + $subscription_price + $subscription_vat;
				$data['product_old_price_excl_vat'] += $subscription_price;
				$cart[$idx]['product_price_excl_vat'] += $subscription_price;
				$cart[$idx]['subscription_id'] = $data['subscription_id'];
			}
			else
			{
				$msg = urldecode(JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR_SUBSCRIPTION_PLAN'));

				return $msg;
			}
		}

		// Accessory price
		if (ACCESSORY_AS_PRODUCT_IN_CART_ENABLE)
		{
			if (isset($data['accessory_data']))
			{
				$cart['AccessoryAsProduct'] = array($data['accessory_data'], $data['acc_quantity_data'], $data['acc_attribute_data'], $data['acc_property_data'], $data['acc_subproperty_data']);
			}

			$generateAccessoryCart        = array();
			$data['accessory_data']       = "";
			$data['acc_quantity_data']    = "";
			$data['acc_attribute_data']   = "";
			$data['acc_property_data']    = "";
			$data['acc_subproperty_data'] = "";
		}
		else
		{
			$generateAccessoryCart = isset($data['cart_accessory']) ? $data['cart_accessory'] : $this->generateAccessoryArray($data);

			if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0))
			{
				if (!$generateAccessoryCart)
				{
					$document = JFactory::getDocument();

					return $document->getError();
				}
			}
		}

		$retAccArr             = $this->_producthelper->makeAccessoryCart($generateAccessoryCart, $product_data->product_id);
		$accessory_total_price = $retAccArr[1];
		$accessory_vat_price   = $retAccArr[2];

		$cart[$idx]['product_price_excl_vat'] += $accessory_total_price;
		$data['product_price'] += $accessory_total_price + $accessory_vat_price;
		$data['product_old_price'] += $accessory_total_price + $accessory_vat_price;
		$data['product_old_price_excl_vat'] += $accessory_total_price;
		$cart[$idx]['product_vat'] = $product_vat_price + $accessory_vat_price;

		if (!INDIVIDUAL_ADD_TO_CART_ENABLE)
		{
			/*
			 * Check if required attribute is filled or not ...
			 */
			$attribute_template = $this->_producthelper->getAttributeTemplate($data_add);

			if (count($attribute_template) > 0)
			{
				$selectedAttributId = 0;

				if (count($selectedAttrId) > 0)
				{
					$selectedAttributId = implode(",", $selectedAttrId);
				}

				$req_attribute = $this->_producthelper->getProductAttribute($data['product_id'], 0, 0, 0, 1, $selectedAttributId);

				if (count($req_attribute) > 0)
				{
					$requied_attributeArr = array();

					for ($re = 0; $re < count($req_attribute); $re++)
					{
						$requied_attributeArr[$re] = urldecode($req_attribute[$re]->attribute_name);
					}

					$requied_attribute_name = implode(", ", $requied_attributeArr);

					// Throw an error as first attribute is required
					$msg = urldecode($requied_attribute_name) . " " . JText::_('COM_REDSHOP_IS_REQUIRED');

					return $msg;
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

				$req_property = $this->_producthelper->getAttibuteProperty($selectedPropertyId, $selectedAttributId, $data['product_id'], 0, 1, $notselectedSubpropertyId);

				if (count($req_property) > 0)
				{
					$requied_subattributeArr = array();

					for ($re1 = 0; $re1 < count($req_property); $re1++)
					{
						$requied_subattributeArr[$re1] = urldecode($req_property[$re1]->property_name);
					}

					$requied_subattribute_name = implode(",", $requied_subattributeArr);

					// Give error as second attribute is required
					$msg = urldecode($requied_subattribute_name) . " " . JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED');

					if ($data['reorder'] != 1)
						return $msg;
				}
			}
		}

		// ADD WRAPPER PRICE
		$wrapper_price = 0;
		$wrapper_vat   = 0;

		if (isset($data['sel_wrapper_id']) && $data['sel_wrapper_id'])
		{
			$wrapperArr    = $this->getWrapperPriceArr(array('product_id' => $data['product_id'], 'wrapper_id' => $data['sel_wrapper_id']));
			$wrapper_vat   = $wrapperArr['wrapper_vat'];
			$wrapper_price = $wrapperArr['wrapper_price'];
		}

		$cart[$idx]['product_vat'] += $wrapper_vat;
		$data['product_price'] += $wrapper_price + $wrapper_vat;
		$data['product_old_price'] += $wrapper_price + $wrapper_vat;
		$data['product_old_price_excl_vat'] += $wrapper_price;
		$cart[$idx]['product_price_excl_vat'] += $wrapper_price;

		// END WRAPPER PRICE

		$att_id_total     = false;
		$att_acc_total    = false;
		$prodcut_id_total = false;

		// Checking For same Product and update Quantity
		$selectAcc = $this->_producthelper->getSelectedAccessoryArray($data);
		$selectAtt = $this->_producthelper->getSelectedAttributeArray($data);

		$sameProduct = false;

		for ($i = 0; $i < $idx; $i++)
		{
			if ($cart[$i]['product_id'] == $data['product_id'])
			{
				$sameProduct = true;

				if (isset($data['subscription_id']) && $cart[$i]['subscription_id'] != $data['subscription_id'])
				{
					$sameProduct = false;
				}

				if ($cart[$i]['wrapper_id'] != $data['sel_wrapper_id'])
				{
					$sameProduct = false;
				}

				$prevSelectAtt = $this->getSelectedCartAttributeArray($cart[$i]['cart_attribute']);

				$newdiff1 = array_diff($prevSelectAtt[0], $selectAtt[0]);
				$newdiff2 = array_diff($selectAtt[0], $prevSelectAtt[0]);

				if (count($newdiff1) > 0 || count($newdiff2) > 0)
				{
					$sameProduct = false;
				}

				$newdiff1 = array_diff($prevSelectAtt[1], $selectAtt[1]);
				$newdiff2 = array_diff($selectAtt[1], $prevSelectAtt[1]);

				if (count($newdiff1) > 0 || count($newdiff2) > 0)
				{
					$sameProduct = false;
				}

				$prevSelectAcc = $this->getSelectedCartAccessoryArray($cart[$i]['cart_accessory']);

				$newdiff1 = array_diff($prevSelectAcc[0], $selectAcc[0]);
				$newdiff2 = array_diff($selectAcc[0], $prevSelectAcc[0]);

				if (count($newdiff1) > 0 || count($newdiff2) > 0)
				{
					$sameProduct = false;
				}

				$newdiff1 = array_diff($prevSelectAcc[1], $selectAcc[1]);
				$newdiff2 = array_diff($selectAcc[1], $prevSelectAcc[1]);

				if (count($newdiff1) > 0 || count($newdiff2) > 0)
				{
					$sameProduct = false;
				}

				$newdiff1 = array_diff($prevSelectAcc[2], $selectAcc[2]);
				$newdiff2 = array_diff($selectAcc[2], $prevSelectAcc[2]);

				if (count($newdiff1) > 0 || count($newdiff2) > 0)
				{
					$sameProduct = false;
				}

				// Discount calculator
				$array_diff_calc = array_diff_assoc($cart[$i]['discount_calc'], $calc_output_array);

				if (count($array_diff_calc) > 0)
				{
					$sameProduct = false;
				}

				/*
				 * Process the prepare Product plugins
				 *
				 *  For future enhancement - not working anymore
				 */
				JPluginHelper::importPlugin('redshop_product');
				$results = $dispatcher->trigger('checkSameCartProduct', array(& $cart, $data));

				// Product userfiled
				if (!empty($row_data))
				{
					$puf = 1;

					for ($r = 0; $r < count($row_data); $r++)
					{
						$produser_field  = $row_data[$r]->field_name;
						$added_userfield = $data[$produser_field];

						if (isset($cart[$i][$produser_field]) && $added_userfield != $cart[$i][$produser_field])
						{
							$puf = 0;
						}
					}

					if ($puf != 1)
					{
						$sameProduct = false;
					}
				}

				if ($sameProduct)
				{
					$newQuantity     = $cart[$i]['quantity'] + $data['quantity'];
					$newcartquantity = $this->checkQuantityInStock($cart[$i], $newQuantity);

					if ($newQuantity > $newcartquantity)
					{
						$cart['notice_message'] = $newcartquantity . " " . JTEXT::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
					}
					else
					{
						$cart['notice_message'] = "";
					}

					if ($newcartquantity != $cart[$i]['quantity'])
					{
						$cart[$i]['quantity'] = $newcartquantity;

						/*
						 * trigger the event of redSHOP product plugin support on Same product is going to add into cart
						 *
						 * Usually redSHOP update quantity
						 */
						JPluginHelper::importPlugin('redshop_product');
						$results = $dispatcher->trigger('onSameCartProduct', array(& $cart, $data, $i));

						$this->_session->set('cart', $cart);
						$data['cart_index'] = $i;
						$data['quantity']   = $newcartquantity;
						$this->update($data);

						return true;
					}
					else
					{
						$msg = (CART_RESERVATION_MESSAGE != '' && IS_PRODUCT_RESERVE) ? CART_RESERVATION_MESSAGE : urldecode(JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'));

						return $msg;
					}
				}
			}
		}

		// Set product price
		if ($data['product_price'] < 0)
		{
			$data['product_price'] = 0;
		}

		$per_product_total = $product_data->minimum_per_product_total;

		if ($data['product_price'] < $per_product_total)
		{
			$msg = JText::_('COM_REDSHOP_PER_PRODUCT_TOTAL') . " " . $per_product_total;

			return $msg;
		}

		if (!$sameProduct)
		{
			// SET VALVUES INTO SESSION CART
			$cart[$idx]['giftcard_id']                = '';
			$cart[$idx]['product_id']                 = $data['product_id'];
			$cart[$idx]['discount_calc_output']       = $calc_output;
			$cart[$idx]['discount_calc']              = $calc_output_array;
			$cart[$idx]['product_price']              = $data['product_price'];
			$cart[$idx]['product_old_price']          = $data['product_old_price'];
			$cart[$idx]['product_old_price_excl_vat'] = $data['product_old_price_excl_vat'];
			$cart[$idx]['cart_attribute']             = $generateAttributeCart;

			$cart[$idx]['cart_accessory'] = $generateAccessoryCart;

			if (isset($data['hidden_attribute_cartimage']))
			{
				$cart[$idx]['hidden_attribute_cartimage'] = $data['hidden_attribute_cartimage'];
			}

			$cart[$idx]['quantity'] = 0;

			$newQuantity            = $data['quantity'];
			$cart[$idx]['quantity'] = $this->checkQuantityInStock($cart[$idx], $newQuantity);

			if ($newQuantity > $cart[$idx]['quantity'])
			{
				$cart['notice_message'] = $cart[$idx]['quantity'] . " " . JTEXT::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
			}
			else
			{
				$cart['notice_message'] = "";
			}

			if ($cart[$idx]['quantity'] <= 0)
			{
				$msg = (CART_RESERVATION_MESSAGE != '' && IS_PRODUCT_RESERVE) ? CART_RESERVATION_MESSAGE : JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

				return $msg;
			}

			$cart[$idx]['category_id']   = $data['category_id'];
			$cart[$idx]['wrapper_id']    = $data['sel_wrapper_id'];
			$cart[$idx]['wrapper_price'] = $wrapper_price + $wrapper_vat;

			/**
			 * Implement new plugin support before session update
			 * trigger the event of redSHOP product plugin support on Before cart session is set - on prepare cart session
			 */
			JPluginHelper::importPlugin('redshop_product');
			$results = $dispatcher->trigger('onBeforeSetCartSession', array(& $cart, $data));

			$cart['idx'] = $idx + 1;

			for ($i = 0; $i < count($row_data); $i++)
			{
				$field_name = $row_data[$i]->field_name;
				$data_txt   = (isset($data[$field_name])) ? $data[$field_name] : '';
				$tmpstr     = strpbrk($data_txt, '`');

				if ($tmpstr)
				{
					$tmparray = explode('`', $data_txt);
					$tmp      = new stdClass;
					$tmp      = @array_merge($tmp, $tmparray);

					if (is_array($tmparray))
					{
						$data_txt = implode(",", $tmparray);
					}
				}

				$cart[$idx][$field_name] = $data_txt;
			}
		}

		if (!$cart['discount_type'])
		{
			$cart['discount_type'] = 0;
		}

		if (!$cart['discount'])
		{
			$cart['discount'] = 0;
		}

		if (!$cart['cart_discount'])
		{
			$cart['cart_discount'] = 0;
		}

		if (!isset($cart['user_shopper_group_id']) || (isset($cart['user_shopper_group_id']) && $cart['user_shopper_group_id'] == 0))
		{
			$cart['user_shopper_group_id'] = $rsUserhelper->getShopperGroup($user->id);
		}

		$cart['free_shipping'] = 0;

		$this->_session->set('cart', $cart);

		return true;
	}

	public function update($data)
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$user    = JFactory::getUser();

		$cartElement = $data['cart_index'];

		$newQuantity = intval(abs($data['quantity']) > 0 ? $data['quantity'] : 1);
		$oldQuantity = intval($cart[$cartElement]['quantity']);

		if ($newQuantity <= 0)
		{
			$newQuantity = 1;
		}

		// Discount calculator
		if (!empty($cart[$cartElement]['discount_calc']))
		{
			$calcdata               = $cart[$cartElement]['discount_calc'];
			$calcdata['product_id'] = $cart[$cartElement]['product_id'];

			$discount_cal = $this->discountCalculator($calcdata);

			$calculator_price  = $discount_cal['product_price'];
			$product_price_tax = $discount_cal['product_price_tax'];
		}

		// Attribute price
		$retAttArr                  = $this->_producthelper->makeAttributeCart($cart[$cartElement]['cart_attribute'], $cart[$cartElement]['product_id'], $user->id, $calculator_price, $cart[$cartElement]['quantity']);
		$product_price              = $retAttArr[1];
		$product_vat_price          = $retAttArr[2];
		$product_old_price          = $retAttArr[5] + $retAttArr[6];
		$product_old_price_excl_vat = $retAttArr[5];

		// Accessory price
		$retAccArr             = $this->_producthelper->makeAccessoryCart($cart[$cartElement]['cart_accessory'], $cart[$cartElement]['product_id']);
		$accessory_total_price = $retAccArr[1];
		$accessory_vat_price   = $retAccArr[2];


		if ($cart[$cartElement]['wrapper_id'])
		{
			$wrapperArr    = $this->getWrapperPriceArr(array('product_id' => $cart[$cartElement]['product_id'], 'wrapper_id' => $cart[$cartElement]['wrapper_id']));
			$wrapper_vat   = $wrapperArr['wrapper_vat'];
			$wrapper_price = $wrapperArr['wrapper_price'];
		}

		if (isset($cart[$cartElement]['subscription_id']) && $cart[$cartElement]['subscription_id'] != "")
		{
			$subscription_vat    = 0;
			$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($product_id, $cart[$cartElement]['subscription_id']);
			$subscription_price  = $subscription_detail->subscription_price;

			if ($subscription_price)
			{
				$subscription_vat = $this->_producthelper->getProductTax($product_id, $subscription_price);
			}

			$product_vat_price += $subscription_vat;
			$product_price = $product_price + $subscription_price;
			$product_old_price_excl_vat += $subscription_price;
		}

		$cart[$cartElement]['product_price']              = $product_price + $product_vat_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
		$cart[$cartElement]['product_old_price']          = $product_old_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
		$cart[$cartElement]['product_old_price_excl_vat'] = $product_old_price_excl_vat + $accessory_total_price + $wrapper_price;
		$cart[$cartElement]['product_price_excl_vat']     = $product_price + $accessory_total_price + $wrapper_price;
		$cart[$cartElement]['product_vat']                = $product_vat_price + $accessory_vat_price + $wrapper_vat;

		$session->set('cart', $cart);
	}

	public function userfieldValidation($data, $data_add, $section = 12)
	{
		$returnArr    = $this->_producthelper->getProductUserfieldFromTemplate($data_add);
		$userfieldArr = $returnArr[1];

		$msg = "";

		if (count($userfieldArr) > 0)
		{
			$req_fields = $this->_extraFieldFront->getSectionFieldList($section, 1, 1, 1);

			for ($i = 0; $i < count($req_fields); $i++)
			{
				if (in_array($req_fields[$i]->field_name, $userfieldArr))
				{
					if (!isset($data[$req_fields[$i]->field_name]) || (isset($data[$req_fields[$i]->field_name]) && $data[$req_fields[$i]->field_name] == ""))
					{
						$msg .= $req_fields[$i]->field_title . " " . JText::_('COM_REDSHOP_IS_REQUIRED') . "<br/>";
					}
				}
			}
		}

		return $msg;
	}

	public function generateAccessoryArray($data, $user_id = 0)
	{
		$generateAccessoryCart = array();
		$accessory_total_price = 0;

		if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0))
		{
			$accessory_data    = explode("@@", $data['accessory_data']);
			$acc_quantity_data = explode("@@", $data['acc_quantity_data']);

			for ($i = 0; $i < count($accessory_data); $i++)
			{
				$accessory          = $this->_producthelper->getProductAccessory($accessory_data[$i]);
				$accessorypricelist = $this->_producthelper->getAccessoryPrice($data['product_id'], $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1, $user_id);
				$accessory_price    = $accessorypricelist[0];
				$acc_quantity       = (isset($acc_quantity_data[$i]) && $acc_quantity_data[$i]) ? $acc_quantity_data[$i] : $data['quantity'];

				$generateAccessoryCart[$i]['accessory_id']       = $accessory_data[$i];
				$generateAccessoryCart[$i]['accessory_name']     = $accessory[0]->product_name;
				$generateAccessoryCart[$i]['accessory_oprand']   = $accessory[0]->oprand;
				$generateAccessoryCart[$i]['accessory_price']    = $accessory_price * $acc_quantity;
				$generateAccessoryCart[$i]['accessory_quantity'] = $acc_quantity;

				$accAttributeCart = array();

				if ($data['acc_attribute_data'] != "" && $data['acc_attribute_data'] != 0)
				{
					$acc_attribute_data = explode('@@', $data['acc_attribute_data']);

					if ($acc_attribute_data[$i] != "")
					{
						$acc_attribute_data = explode('##', $acc_attribute_data[$i]);

						for ($ia = 0; $ia < count($acc_attribute_data); $ia++)
						{
							$accPropertyCart                         = array();
							$attribute                               = $this->_producthelper->getProductAttribute(0, 0, $acc_attribute_data[$ia]);
							$accAttributeCart[$ia]['attribute_id']   = $acc_attribute_data[$ia];
							$accAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

							if ($attribute[0]->text != "" && $data['acc_property_data'] != "" && $data['acc_property_data'] != 0)
							{
								$acc_property_data = explode('@@', $data['acc_property_data']);
								$acc_property_data = explode('##', $acc_property_data[$i]);

								if (isset($acc_property_data[$ia]) && $acc_property_data[$ia] != "")
								{
									$acc_property_data = explode(',,', $acc_property_data[$ia]);

									for ($ip = 0; $ip < count($acc_property_data); $ip++)
									{
										$accSubpropertyCart = array();
										$property_price     = 0;
										$property           = $this->_producthelper->getAttibuteProperty($acc_property_data[$ip]);
										$pricelist          = $this->_producthelper->getPropertyPrice($acc_property_data[$ip], $data['quantity'], 'property', $user_id);

										if (count($pricelist) > 0)
										{
											$property_price = $pricelist->product_price;
										}
										else
										{
											$property_price = $property[0]->property_price;
										}

										$accPropertyCart[$ip]['property_id']     = $acc_property_data[$ip];
										$accPropertyCart[$ip]['property_name']   = $property[0]->text;
										$accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
										$accPropertyCart[$ip]['property_price']  = $property_price;

										if ($data['acc_subproperty_data'] != "" && $data['acc_subproperty_data'] != 0)
										{
											$acc_subproperty_data = explode('@@', $data['acc_subproperty_data']);
											$acc_subproperty_data = @explode('##', $acc_subproperty_data[$i]);
											$acc_subproperty_data = @explode(',,', $acc_subproperty_data[$ia]);


											if (isset($acc_subproperty_data[$ip]) && $acc_subproperty_data[$ip] != "")
											{
												$acc_subproperty_data = explode('::', $acc_subproperty_data[$ip]);

												for ($isp = 0; $isp < count($acc_subproperty_data); $isp++)
												{
													$subproperty_price = 0;
													$subproperty       = $this->_producthelper->getAttibuteSubProperty($acc_subproperty_data[$isp]);
													$pricelist         = $this->_producthelper->getPropertyPrice($acc_subproperty_data[$isp], $data['quantity'], 'subproperty', $user_id);

													if (count($pricelist) > 0)
													{
														$subproperty_price = $pricelist->product_price;
													}
													else
													{
														$subproperty_price = $subproperty[0]->subattribute_color_price;
													}

													$accSubpropertyCart[$isp]['subproperty_id']     = $acc_subproperty_data[$isp];
													$accSubpropertyCart[$isp]['subproperty_name']   = $subproperty[0]->text;
													$accSubpropertyCart[$isp]['subproperty_oprand'] = $subproperty[0]->oprand;
													$accSubpropertyCart[$isp]['subproperty_price']  = $subproperty_price;
												}
											}
										}

										$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
									}
								}
							}

							$accAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
						}
					}
				}
				else
				{
					$attribute_set_id   = $this->getAttributeSetId($accessory[0]->child_product_id);
					$attributes_acc_set = array();

					if ($attribute_set_id > 0)
					{
						$attributes_acc_set = $this->getProductAccAttribute($accessory[0]->child_product_id, $attribute_set_id, 0, 0, 1);
					}

					$req_attribute = $this->_producthelper->getProductAttribute($accessory[0]->child_product_id, 0, 0, 0, 1);
					$req_attribute = array_merge($req_attribute, $attributes_acc_set);

					if (count($req_attribute) > 0)
					{
						$requied_attributeArr = array();

						for ($re = 0; $re < count($req_attribute); $re++)
						{
							$requied_attributeArr[$re] = urldecode($req_attribute[$re]->attribute_name);
						}

						$requied_attribute_name = implode(", ", $requied_attributeArr);

						// Throw an error as first attribute is required
						$msg      = urldecode($requied_attribute_name) . " " . JText::_('IS_REQUIRED');
						$document = JFactory::getDocument();
						$document->setError($msg);

						return false;
					}
				}

				$generateAccessoryCart[$i]['accessory_childs'] = $accAttributeCart;
			}
		}

		return $generateAccessoryCart;
	}

	public function getProductAccAttribute($product_id = 0, $attribute_set_id = 0, $attribute_id = 0, $published = 0, $attribute_required = 0, $notAttributeId = 0)
	{
		$and          = "";
		$astpublished = "";

		if ($product_id != 0)
		{
			$and .= "AND p.product_id IN (" . $product_id . ") ";
		}

		if ($attribute_set_id != 0)
		{
			$and .= "AND a.attribute_set_id='" . $attribute_set_id . "' ";
		}

		if ($published != 0)
		{
			$astpublished = " AND ast.published='" . $published . "' ";
		}

		if ($attribute_required != 0)
		{
			$and .= "AND a.attribute_required='" . $attribute_required . "' ";
		}

		if ($notAttributeId != 0)
		{
			$and .= "AND a.attribute_id NOT IN (" . $notAttributeId . ") ";
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

	public function getAttributeSetId($pid)
	{
		$query = "SELECT attribute_set_id FROM " . $this->_table_prefix . "product"
			. " WHERE product_id=" . $pid;

		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function generateAttributeArray($data, $user_id = 0)
	{
		$generateAttributeCart = array();
		$attribute_total_price = 0;
		$setPropEqual          = true;
		$setSubpropEqual       = true;

		if ($data['attribute_data'] != "" && $data['attribute_data'] != 0)
		{
			$attribute_data = explode('##', $data['attribute_data']);

			for ($ia = 0; $ia < count($attribute_data); $ia++)
			{
				$prooprand                                    = array();
				$proprice                                     = array();
				$accPropertyCart                              = array();
				$attribute                                    = $this->_producthelper->getProductAttribute(0, 0, $attribute_data[$ia]);
				$generateAttributeCart[$ia]['attribute_id']   = $attribute_data[$ia];
				$generateAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

				if ($attribute[0]->text != "" && ($data['property_data'] != "" || $data['property_data'] != 0))
				{
					$acc_property_data = explode('##', $data['property_data']);

					if (isset($acc_property_data[$ia]) && $acc_property_data[$ia] != "")
					{
						$acc_property_data = explode(',,', $acc_property_data[$ia]);

						for ($ip = 0; $ip < count($acc_property_data); $ip++)
						{
							$accSubpropertyCart = array();
							$property_price     = 0;
							$property           = $this->_producthelper->getAttibuteProperty($acc_property_data[$ip]);
							$pricelist          = $this->_producthelper->getPropertyPrice($acc_property_data[$ip], $data['quantity'], 'property', $user_id);

							if (count($pricelist) > 0)
							{
								$property_price = $pricelist->product_price;
							}
							else
							{
								$property_price = $property[0]->property_price;
							}

							$accPropertyCart[$ip]['property_id']     = $acc_property_data[$ip];
							$accPropertyCart[$ip]['property_name']   = $property[0]->text;
							$accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
							$accPropertyCart[$ip]['property_price']  = $property_price;
							$prooprand[$ip]                          = $property[0]->oprand;
							$proprice[$ip]                           = $property_price;

							if ($data['subproperty_data'] != "")
							{
								$acc_subproperty_data = @explode('##', $data['subproperty_data']);
								$acc_subproperty_data = @explode(',,', $acc_subproperty_data[$ia]);

								if (isset($acc_subproperty_data[$ip]) && $acc_subproperty_data[$ip] != "")
								{
									$acc_subproperty_data = explode('::', $acc_subproperty_data[$ip]);

									for ($isp = 0; $isp < count($acc_subproperty_data); $isp++)
									{
										$subproperty_price = 0;
										$subproperty       = $this->_producthelper->getAttibuteSubProperty($acc_subproperty_data[$isp]);

										$pricelist = $this->_producthelper->getPropertyPrice($acc_subproperty_data[$isp], $data['quantity'], 'subproperty', $user_id);

										if (count($pricelist) > 0)
										{
											$subproperty_price = $pricelist->product_price;
										}
										else
										{
											$subproperty_price = $subproperty[0]->subattribute_color_price;
										}

										$accSubpropertyCart[$isp]['subproperty_id']           = $acc_subproperty_data[$isp];
										$accSubpropertyCart[$isp]['subproperty_name']         = $subproperty[0]->text;
										$accSubpropertyCart[$isp]['subproperty_oprand']       = $subproperty[0]->oprand;
										$accSubpropertyCart[$isp]['subattribute_color_title'] = $subproperty[0]->subattribute_color_title;
										$accSubpropertyCart[$isp]['subproperty_price']        = $subproperty_price;
									}
								}
							}

							$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
						}
					}
				}

				$generateAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
			}
		}

		return $generateAttributeCart;
	}

	public function getSelectedCartAttributeArray($attArr = array())
	{
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0; $i < count($attArr); $i++)
		{
			$propArr = $attArr[$i]['attribute_childs'];

			for ($k = 0; $k < count($propArr); $k++)
			{
				$selectedproperty[] = $propArr[$k]['property_id'];
				$subpropArr         = $propArr[$k]['property_childs'];

				for ($l = 0; $l < count($subpropArr); $l++)
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

		for ($i = 0; $i < count($attArr); $i++)
		{
			$selectedAccessory[] = $attArr[$i]['accessory_id'];
			$attchildArr         = $attArr[$i]['accessory_childs'];

			for ($j = 0; $j < count($attchildArr); $j++)
			{
				$propArr = $attchildArr[$j]['attribute_childs'];

				for ($k = 0; $k < count($propArr); $k++)
				{
					$selectedproperty[] = $propArr[$k]['property_id'];
					$subpropArr         = $propArr[$k]['property_childs'];

					for ($l = 0; $l < count($subpropArr); $l++)
					{
						$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
					}
				}
			}
		}

		$ret = array($selectedAccessory, $selectedproperty, $selectedsubproperty);

		return $ret;
	}

	public function generateAttributeFromOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		$generateAttributeCart = array();

		$orderItemAttdata = $this->_order_functions->getOrderItemAttributeDetail($order_item_id, $is_accessory, "attribute", $parent_section_id);

		for ($i = 0; $i < count($orderItemAttdata); $i++)
		{
			$accPropertyCart                             = array();
			$generateAttributeCart[$i]['attribute_id']   = $orderItemAttdata[$i]->section_id;
			$generateAttributeCart[$i]['attribute_name'] = $orderItemAttdata[$i]->section_name;

			$orderPropdata = $this->_order_functions->getOrderItemAttributeDetail($order_item_id, $is_accessory, "property", $orderItemAttdata[$i]->section_id);

			for ($p = 0; $p < count($orderPropdata); $p++)
			{
				$accSubpropertyCart = array();
				$property_price     = 0;
				$property           = $this->_producthelper->getAttibuteProperty($orderPropdata[$p]->section_id);
				$pricelist          = $this->_producthelper->getPropertyPrice($orderPropdata[$p]->section_id, $quantity, 'property');

				if (count($pricelist) > 0)
				{
					$property_price = $pricelist->product_price;
				}
				else
				{
					$property_price = $property[0]->property_price;
				}

				$accPropertyCart[$p]['property_id']     = $orderPropdata[$p]->section_id;
				$accPropertyCart[$p]['property_name']   = $property[0]->text;
				$accPropertyCart[$p]['property_oprand'] = $property[0]->oprand;
				$accPropertyCart[$p]['property_price']  = $property_price;

				$orderSubpropdata = $this->_order_functions->getOrderItemAttributeDetail($order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

				for ($sp = 0; $sp < count($orderSubpropdata); $sp++)
				{
					$subproperty_price = 0;
					$subproperty       = $this->_producthelper->getAttibuteSubProperty($orderSubpropdata[$sp]->section_id);
					$pricelist         = $this->_producthelper->getPropertyPrice($orderSubpropdata[$sp]->section_id, $quantity, 'subproperty');

					if (count($pricelist) > 0)
					{
						$subproperty_price = $pricelist->product_price;
					}
					else
					{
						$subproperty_price = $subproperty[0]->subattribute_color_price;
					}

					$accSubpropertyCart[$sp]['subproperty_id']     = $orderSubpropdata[$sp]->section_id;
					$accSubpropertyCart[$sp]['subproperty_name']   = $subproperty[0]->text;
					$accSubpropertyCart[$sp]['subproperty_oprand'] = $subproperty[0]->oprand;
					$accSubpropertyCart[$sp]['subproperty_price']  = $subproperty_price;
				}

				$accPropertyCart[$p]['property_childs'] = $accSubpropertyCart;
			}

			$generateAttributeCart[$i]['attribute_childs'] = $accPropertyCart;
		}

		return $generateAttributeCart;
	}

	public function generateAccessoryFromOrder($order_item_id = 0, $product_id = 0, $quantity = 1)
	{
		$generateAccessoryCart = array();

		$orderItemdata = $this->_order_functions->getOrderItemAccessoryDetail($order_item_id);

		for ($i = 0; $i < count($orderItemdata); $i++)
		{
			$accessory          = $this->_producthelper->getProductAccessory($orderItemdata[$i]->product_id);
			$accessorypricelist = $this->_producthelper->getAccessoryPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
			$accessory_price    = $accessorypricelist[0];

			$generateAccessoryCart[$i]['accessory_id']       = $orderItemdata[$i]->product_id;
			$generateAccessoryCart[$i]['accessory_name']     = $accessory[0]->product_name;
			$generateAccessoryCart[$i]['accessory_oprand']   = $accessory[0]->oprand;
			$generateAccessoryCart[$i]['accessory_price']    = $accessory_price;
			$generateAccessoryCart[$i]['accessory_quantity'] = $orderItemdata[$i]->product_quantity;
			$generateAccessoryCart[$i]['accessory_childs']   = $this->generateAttributeFromOrder($order_item_id, 1, $orderItemdata[$i]->product_id, $quantity);
		}

		return $generateAccessoryCart;
	}

	public function discountCalculatorData($product_data, $data)
	{
		$use_discount_calculator = $product_data->use_discount_calc;
		$discount_calc_method    = $product_data->discount_calc_method;
		$use_range               = $product_data->use_range;
		$calc_output             = "";
		$calc_output_array       = array();

		if ($use_discount_calculator)
		{
			$discount_cal = $this->discountCalculator($data);

			$calculator_price  = $discount_cal['product_price'];
			$product_price_tax = $discount_cal['product_price_tax'];

			if ($calculator_price)
			{
				$calc_output               = "Type : " . $discount_calc_method . "<br />";
				$calc_output_array['type'] = $discount_calc_method;

				if ($use_range)
				{
					$calcHeight        = @$data['calcHeight'];
					$calcWidth         = @$data['calcWidth'];
					$calcDepth         = @$data['calcDepth'];
					$calcRadius        = @$data['calcRadius'];
					$calcPricePerPiece = "";
					$totalPiece        = "";
				}
				else
				{
					$calcHeight        = @$product_data->product_height;
					$calcWidth         = @$product_data->product_width;
					$calcDepth         = @$product_data->product_length;
					$calcRadius        = @$data['calcRadius'];
					$calcPricePerPiece = @$discount_cal['price_per_piece'];
					$totalPiece        = @$discount_cal['total_piece'];
				}

				switch ($discount_calc_method)
				{
					case "volume":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_HEIGHT') . " " . $calcHeight . "<br />";
						$calc_output_array['calcHeight'] = $calcHeight;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_WIDTH') . " " . $calcWidth . "<br />";
						$calc_output_array['calcWidth'] = $calcWidth;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_LENGTH') . " " . $calcDepth . "<br />";
						$calc_output_array['calcDepth'] = $calcDepth;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}

						break;

					case "area":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_DEPTH') . " " . $calcDepth . "<br />";
						$calc_output_array['calcDepth'] = $calcDepth;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_WIDTH') . " " . $calcWidth . "<br />";
						$calc_output_array['calcWidth'] = $calcWidth;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}

						break;

					case "circumference":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_RADIUS') . " " . $calcRadius . "<br />";
						$calc_output_array['calcRadius'] = $calcRadius;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}
						break;
				}

				$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_UNIT') . " " . $data['calcUnit'];
				$calc_output_array['calcUnit'] = $data['calcUnit'];

				// Extra selected value data
				$calc_output .= "<br />" . $discount_cal['pdcextra_data'];

				// Extra selected value ids
				$calc_output_array['calcextra_ids'] = $discount_cal['pdcextra_ids'];

				$discountArr[] = $calc_output;
				$discountArr[] = $calc_output_array;
				$discountArr[] = $calculator_price;
				$discountArr[] = $product_price_tax;

				return $discountArr;
			}
			else
			{
				return array();
			}
		}
	}

	/*
	 * discount calculaor Ajax Function
	 *
	 * @return: ajax responce
	 */
	public function discountCalculator($get)
	{
		$product_id = $get['product_id'];

		$discount_cal = array();

		$productprice = $this->_producthelper->getProductNetPrice($product_id);

		$product_price = $productprice['product_price_novat'];

		$data = $this->_producthelper->getProductById($product_id);

		// Default calculation method
		$calcMethod = $data->discount_calc_method;

		// Default calculation unit
		$globalUnit = "m";

		// Use range or not
		$use_range = $data->use_range;

		$calcHeight = $get['calcHeight'];
		$calcWidth  = $get['calcWidth'];
		$calcLength = $get['calcDepth'];
		$calcRadius = $get['calcRadius'];
		$calcUnit   = trim($get['calcUnit']);

		$calcHeight = str_replace(",", ".", $calcHeight);
		$calcWidth  = str_replace(",", ".", $calcWidth);
		$calcLength = str_replace(",", ".", $calcLength);
		$calcRadius = $cart_mdata = str_replace(",", ".", $calcRadius);
		$calcUnit   = $cart_mdata = str_replace(",", ".", $calcUnit);

		// Convert unit using helper function
		$unit = 1;
		$unit = $this->_producthelper->getUnitConversation($globalUnit, $calcUnit);

		$calcHeight *= $unit;
		$calcWidth *= $unit;
		$calcLength *= $unit;
		$calcRadius *= $unit;

		$product_unit = 1;

		if (!$use_range)
		{
			$product_unit = $this->_producthelper->getUnitConversation($globalUnit, DEFAULT_VOLUME_UNIT);

			$product_height   = $data->product_height * $product_unit;
			$product_width    = $data->product_width * $product_unit;
			$product_length   = $data->product_length * $product_unit;
			$product_diameter = $data->product_diameter * $product_unit;
		}

		$finalArea = 0;
		$Area      = 0;

		switch ($calcMethod)
		{
			case "volume":

				$Area = $calcHeight * $calcWidth * $calcLength;

				if (!$use_range)
					$product_area = $product_height * $product_width * $product_length;
				break;

			case "area":
				$Area = $calcLength * $calcWidth;

				if (!$use_range)
					$product_area = $product_length * $product_width;
				break;

			case "circumference":

				$Area = 2 * PI * $calcRadius;

				if (!$use_range)
					$product_area = PI * $product_diameter;
				break;
		}

		$finalArea = $Area;

		if ($use_range)
		{
			$finalArea = number_format($finalArea, 8, '.', '');

			// Calculation prices as per various area
			$discount_calc_data = $this->getDiscountCalcData($finalArea, $product_id);

		}
		else
		{
			// Shandard size of product
			$final_product_Area = $product_area;

			// Total sheet calculation
			if ($final_product_Area <= 0)
				$final_product_Area = 1;
			$total_sheet = $finalArea / $final_product_Area;

			// Returns the next highest integer value by rounding up value if necessary.
			$total_sheet = ceil($total_sheet);

			// If sheet is less than 0 or equal to 0 than
			if ($total_sheet <= 0)
				$total_sheet = 1;

			// Product price of all sheets
			$product_price_total = $total_sheet * $product_price;

			// Generating array
			$discount_calc_data[0]->area_price         = $product_price;
			$discount_calc_data[0]->discount_calc_unit = $product_unit;
			$discount_calc_data[0]->price_per_piece    = $product_price_total;
		}

		$area_price          = 0;
		$price_per_piece     = 0;
		$price_per_piece_tax = 0;
		$conversation_unit   = "m";

		if (count($discount_calc_data))
		{
			$area_price = $discount_calc_data[0]->area_price;

			// Discount calculator extra price enhancement
			$pdcextraid = $get['pdcextraid'];
			$pdcstring  = $pdcids = array();

			if (trim($pdcextraid) != "")
			{
				$pdcextradatas = $this->getDiscountCalcDataExtra($pdcextraid);

				for ($pdc = 0; $pdc < count($pdcextradatas); $pdc++)
				{
					$pdcextradata = $pdcextradatas[$pdc];
					$option_name  = $pdcextradata->option_name;
					$pdcprice     = $pdcextradata->price;
					$pdcoprand    = $pdcextradata->oprand;
					$pdcextra_id  = $pdcextradata->pdcextra_id;

					$pdcstring[] = $option_name . ' (' . $pdcoprand . ' ' . $pdcprice . ' )';
					$pdcids[]    = $pdcextra_id;

					switch ($pdcoprand)
					{
						case "+":
							$area_price += $pdcprice;
							break;
						case "-":
							$area_price -= $pdcprice;
							break;
						case "%":
							$area_price *= 1 + ($pdcprice / 100);
							break;
					}
				}
			}


			$conversation_unit = $discount_calc_data[0]->discount_calc_unit;

			if ($use_range)
			{
				$display_final_area = $finalArea / ($unit * $unit);

				$price_per_piece = $area_price * $finalArea;

				$price_per_piece = $area_price;

				$formatted_price_per_area = $this->_producthelper->getProductFormattedPrice($area_price);

				// Applying TAX
				$chktag              = $this->_producthelper->getApplyattributeVatOrNot();
				$price_per_piece_tax = $this->_producthelper->getProductTax($product_id, $price_per_piece, 0, 1);

				echo $display_final_area . "\n";

				echo $area_price . "\n";

				echo $price_per_piece . "\n";

				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

				echo $price_per_piece_tax . "\n";
				echo $chktag . "\n";
			}
			else
			{
				$price_per_piece = $discount_calc_data[0]->price_per_piece;

				$price_per_piece_tax = $this->_producthelper->getProductTax($product_id, $price_per_piece, 0, 1);

				echo $Area . "<br />" . JText::_('COM_REDSHOP_TOTAL_PIECE') . $total_sheet . "\n";

				echo $area_price . "\n";

				echo $price_per_piece . "\n";

				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_OF_ALL_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

				echo $price_per_piece_tax . "\n";
				echo $chktag . "\n";
			}
		}
		else
		{
			$price_per_piece = false;
			echo "fail";
		}

		$discount_cal['product_price']     = $price_per_piece;
		$discount_cal['product_price_tax'] = $price_per_piece_tax;
		$discount_cal['pdcextra_data']     = (count($pdcstring) > 0) ? implode("<br />", $pdcstring) : '';
		$discount_cal['pdcextra_ids']      = (count($pdcids) > 0) ? implode(",", $pdcids) : '';
		$discount_cal['total_piece']       = $total_sheet;
		$discount_cal['price_per_piece']   = $area_price;

		return $discount_cal;
	}

	/**
	 * Function to get Discount calculation data
	 *
	 * @param int $area
	 * @param     $pid
	 * @param int $areabetween
	 *
	 * @return mixed
	 */
	public function getDiscountCalcData($area = 0, $pid, $areabetween = 0)
	{
		$and = "";

		if ($areabetween)
		{
			$and .= "AND " . $area . " BETWEEN `area_start` AND `area_end` ";
		}

		if ($area)
		{
			$and .= " AND (" . $area . " >=`area_start_converted` AND " . $area . " <=`area_end_converted`) ";
		}

		$query = "SELECT * FROM `" . $this->_table_prefix . "product_discount_calc` "
			. "WHERE `product_id`='" . $pid . "' "
			. $and
			. "ORDER BY id ASC ";
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
		$and = "";

		if ($product_id)
		{
			$and .= "AND product_id = '" . $product_id . "' ";
		}

		if ($pdcextraids)
		{
			$and .= "AND pdcextra_id IN (" . $pdcextraids . ") ";
		}

		$query = "SELECT * FROM `" . $this->_table_prefix . "product_discount_calc_extra` "
			. "WHERE 1=1 "
			. $and
			. "ORDER BY option_name ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}
}
