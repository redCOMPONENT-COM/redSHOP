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
	 * @param   string  $data
	 * @param   int     $amount
	 * @param   int     $discount
	 * @param   int     $check
	 * @param   int     $quotation_mode
	 *
	 * @return  mixed|string
	 * @since   2.0.7
	 */
	public static function replaceTax($data = '', $amount = 0, $discount = 0, $check = 0, $quotation_mode = 0)
	{
		if (strpos($data, '{if vat}') !== false && strpos($data, '{vat end if}') !== false)
		{
			$cart          = RedshopHelperCartSession::getCart();
			$productHelper = productHelper::getInstance();

			if ($amount <= 0)
			{
				$template_vat_sdata = explode('{if vat}', $data);
				$template_vat_edata = explode('{vat end if}', $template_vat_sdata[1]);
				$data               = $template_vat_sdata[0] . $template_vat_edata[1];
			}
			else
			{
				if ($quotation_mode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
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
							$data = str_replace("{tax_after_discount}", $productHelper->getProductFormattedPrice($tax_after_discount), $data);
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