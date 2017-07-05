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

namespace Redshop\Helper\Cart;

defined('_JEXEC') or die;

class Tag
{
	/**
	 * @param   string  $data           Input template content
	 * @param   int     $discount       Discount value
	 * @param   int     $subtotal       Sub total value
	 * @param   int     $quotationMode  Is quotation mode
	 *
	 * @return   string
	 *
	 * @since    2.0.6
	 */
	public static function replaceDiscount($data = '', $discount = 0, $subtotal = 0, $quotationMode = 0)
	{
		$productHelper = productHelper::getInstance();

		if (strpos($data, '{if discount}') !== false && strpos($data, '{discount end if}') !== false)
		{
			$percentage = '';

			if ($discount <= 0)
			{
				$templateDiscountSdata = explode('{if discount}', $data);
				$templateDiscountEdata = explode('{discount end if}', $templateDiscountSdata[1]);
				$data                    = $templateDiscountSdata[0] . $templateDiscountEdata[1];
			}
			else
			{
				$data = str_replace("{if discount}", '', $data);

				if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
				{
					$data = str_replace("{discount}", "", $data);
					$data = str_replace("{discount_in_percentage}", $percentage, $data);
				}
				else
				{
					$data = str_replace("{discount}", $productHelper->getProductFormattedPrice($discount, true), $data);
					$data = str_replace("{order_discount}", $productHelper->getProductFormattedPrice($discount, true), $data);

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
}
