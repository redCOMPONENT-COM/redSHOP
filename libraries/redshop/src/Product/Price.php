<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product;

defined('_JEXEC') or die;

/**
 * Price helper
 *
 * @since  2.1.0
 */
class Price
{
	/**
	 * Method for get price of product
	 *
	 * @param   integer $productId Product ID
	 * @param   boolean $withVAT   True for include VAT. False for not include VAT
	 * @param   integer $userId    User ID
	 *
	 * @return  float
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function getPrice($productId, $withVAT = true, $userId = 0)
	{
		$userId = !$userId ? \JFactory::getUser()->id : $userId;

		$row    = \RedshopHelperProduct::getProductById($productId);
		$result = \RedshopHelperProduct::getProductPrices($productId, $userId);

		if (!empty($result))
		{
			$tmpProductPrice    = $result->product_price;
			$row->product_price = $tmpProductPrice;
		}

		$discountId = \productHelper::getInstance()->getProductSpecialId($userId);
		$result     = \RedshopHelperProductPrice::getProductSpecialPrice($row->product_price, $discountId);

		if (!empty($result))
		{
			if ($result->discount_type == 0)
			{
				$discountAmount = $result->discount_amount;
			}
			else
			{
				$discountAmount = ($row->product_price * $result->discount_amount) / (100);
			}

			$row->product_price = $row->product_price - $discountAmount;

			if ($row->product_price < 0)
			{
				$row->product_price = 0;
			}
		}

		$taxAmount = 0.0;

		if ($withVAT && $row->product_price != 0)
		{
			$taxAmount = \RedshopHelperProduct::getProductTax($row->product_id, $row->product_price, $userId);
		}

		return $taxAmount + $row->product_price;
	}
}
