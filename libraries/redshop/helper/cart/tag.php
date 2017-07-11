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
	 * @param   string $data          Template
	 * @param   int    $amount        Amount
	 * @param   int    $discount      Discount
	 * @param   int    $check         Check
	 * @param   int    $quotationMode Quotation mode
	 *
	 * @return  mixed|string
	 * @since   2.0.7
	 */
	public static function replaceTax($data = '', $amount = 0, $discount = 0, $check = 0, $quotationMode = 0)
	{
		if (strpos($data, '{if vat}') !== false && strpos($data, '{vat end if}') !== false)
		{
			$cart          = RedshopHelperCartSession::getCart();
			$productHelper = productHelper::getInstance();

			if ($amount <= 0)
			{
				$templateVatSdata = explode('{if vat}', $data);
				$templateVatEdata = explode('{vat end if}', $templateVatSdata[1]);
				$data             = $templateVatSdata[0] . $templateVatEdata[1];
			}
			else
			{
				if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
				{
					$data = str_replace("{tax}", "", $data);
					$data = str_replace("{order_tax}", "", $data);
				}
				else
				{
					$data = str_replace("{tax}", $productHelper->getProductFormattedPrice($amount, true), $data);
					$data = str_replace("{order_tax}", $productHelper->getProductFormattedPrice($amount, true), $data);
				}

				if (strpos($data, '{tax_after_discount}') !== false)
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
							$data = str_replace("{tax_after_discount}", $productHelper->getProductFormattedPrice($taxAfterDiscount), $data);
						}
						else
						{
							$data = str_replace("{tax_after_discount}", $productHelper->getProductFormattedPrice($cart['tax']), $data);
						}
					}
					else
					{
						$data = str_replace("{tax_after_discount}", $productHelper->getProductFormattedPrice($cart['tax']), $data);
					}
				}

				$data = str_replace("{vat_lbl}", JText::_('COM_REDSHOP_CHECKOUT_VAT_LBL'), $data);
				$data = str_replace("{if vat}", '', $data);
				$data = str_replace("{vat end if}", '', $data);
			}
		}

		return $data;
	}
}
