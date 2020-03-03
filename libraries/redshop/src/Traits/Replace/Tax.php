<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Traits\Replace;

defined('_JEXEC') || die;

/**
 * For classes extends class RedshopTagsAbstract
 *
 * @since  3.0
 */
trait Tax
{
	/**
	 * replace Conditional tag from Redshop tax
	 *
	 * @param   string $template      Template
	 * @param   int    $amount        Amount
	 * @param   int    $discount      Discount
	 * @param   int    $check         Check
	 * @param   int    $quotationMode Quotation mode
	 *
	 * @return  string
	 * @since   3.0
	 */
	public function replaceTax($template = '', $amount = 0, $discount = 0, $check = 0, $quotationMode = 0)
	{

		if (!\RedshopHelperCartTag::isBlockTagExists($template, '{if vat}', '{vat end if}'))
		{
			return $template;
		}

		$cart = \Redshop\Cart\Helper::getCart();

		if ($amount <= 0)
		{
			$templateData = $this->getTemplateBetweenLoop('{if vat}', '{vat end if}', $template);

			$template = $templateData['begin'] . $templateData['end'];

			return $template;
		}

		$replacement = [];

		if ($quotationMode && !\Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
		{
			$replacement['{tax}'] = '';
			$replacement['{order_tax}'] = '';
		}
		else
		{
			$replacement['{tax}'] = \RedshopHelperProductPrice::formattedPrice($amount, true);
			$replacement['{order_tax}'] = \RedshopHelperProductPrice::formattedPrice($amount, true);
		}

		if (strpos($template, '{tax_after_discount}') !== false)
		{
			if (\Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') && (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
			{
				if ($check)
				{
					$taxAfterDiscount = $discount;
				}
				else
				{
					if (!isset($cart['tax_after_discount']))
					{
						$taxAfterDiscount = \RedshopHelperCart::calculateTaxAfterDiscount($amount, $discount);
					}
					else
					{
						$taxAfterDiscount = $cart['tax_after_discount'];
					}
				}

				if ($taxAfterDiscount > 0)
				{
					$replacement['{tax_after_discount}'] = \RedshopHelperProductPrice::formattedPrice($taxAfterDiscount);
				}
				else
				{
					$replacement['{tax_after_discount}'] = \RedshopHelperProductPrice::formattedPrice($cart['tax']);
				}
			}
			else
			{
				$replacement['{tax_after_discount}'] = \RedshopHelperProductPrice::formattedPrice($cart['tax']);
			}
		}

		$template = str_replace("{vat_lbl}", \JText::_('COM_REDSHOP_CHECKOUT_VAT_LBL'), $template);
		$replacement['{vat_lbl}'] = \JText::_('COM_REDSHOP_CHECKOUT_VAT_LBL');
		$replacement['{if vat}'] = '';
		$replacement['{vat end if}'] = '';

		return $this->strReplace($replacement, $template);
	}
}