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
	 * @param   string  $template  Template
	 * @param   string  $beginTag  Begin tag
	 * @param   string  $closeTag  Close tag
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public static function isBlockTagExists($template, $beginTag, $closeTag)
	{
		return (strpos($template, $beginTag) !== false && strpos($template, $closeTag) !== false);
	}

	/**
	 * replace Conditional tag from Redshop tax
	 *
	 * @param   string  $template       Template
	 * @param   int     $amount         Amount
	 * @param   int     $discount       Discount
	 * @param   int     $check          Check
	 * @param   int     $quotationMode  Quotation mode
	 *
	 * @return  string
	 * @since   2.0.7
	 */
	public static function replaceTax($template = '', $amount = 0, $discount = 0, $check = 0, $quotationMode = 0)
	{
		if (!self::isBlockTagExists($template, '{if vat}', '{vat end if}'))
		{
			return $template;
		}

		$cart          = RedshopHelperCartSession::getCart();
		$productHelper = productHelper::getInstance();

		if ($amount <= 0)
		{
			$templateVatSdata = explode('{if vat}', $template);
			$templateVatEdata = explode('{vat end if}', $templateVatSdata[1]);
			$template         = $templateVatSdata[0] . $templateVatEdata[1];

			return $template;
		}

		if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
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
					$taxAfterDiscount = $discount;
				}
				else
				{
					if (!isset($cart['tax_after_discount']))
					{
						$taxAfterDiscount = RedshopHelperCart::calculateTaxAfterDiscount($amount, $discount);
					}
					else
					{
						$taxAfterDiscount = $cart['tax_after_discount'];
					}
				}

				if ($taxAfterDiscount > 0)
				{
					$template = str_replace("{tax_after_discount}", $productHelper->getProductFormattedPrice($taxAfterDiscount), $template);
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

		return $template;
	}


	/**
	 * @param   string  $template      Template
	 * @param   int     $discount      Discount
	 * @param   int     $subTotal      Subtotal
	 * @param   int     $quotationMode Quotation mode
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceDiscount($template = '', $discount = 0, $subTotal = 0, $quotationMode = 0)
	{
		if (!self::isBlockTagExists($template, '{if discount}', '{discount end if}'))
		{
			return $template;
		}

		$productHelper = productHelper::getInstance();
		$percentage = '';

		if ($discount <= 0)
		{
			$templateDiscountSdata = explode('{if discount}', $template);
			$templateDiscountEdata = explode('{discount end if}', $templateDiscountSdata[1]);
			$template              = $templateDiscountSdata[0] . $templateDiscountEdata[1];
		}
		else
		{
			$template = str_replace("{if discount}", '', $template);

			if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
			{
				$template = str_replace("{discount}", "", $template);
				$template = str_replace("{discount_in_percentage}", $percentage, $template);
			}
			else
			{
				$template = str_replace("{discount}", $productHelper->getProductFormattedPrice($discount, true), $template);
				$template = str_replace("{order_discount}", $productHelper->getProductFormattedPrice($discount, true), $template);

				if (!empty($subTotal) && $subTotal > 0)
				{
					$percentage = round(($discount * 100 / $subTotal), 2) . " %";
				}

				$template = str_replace("{discount_in_percentage}", $percentage, $template);
			}

			$template = str_replace("{discount_lbl}", JText::_('COM_REDSHOP_CHECKOUT_DISCOUNT_LBL'), $template);
			$template = str_replace("{discount end if}", '', $template);
		}

		return $template;
	}
}
