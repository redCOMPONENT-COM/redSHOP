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
 * Accessory helper
 *
 * @since  2.1.0
 */
class Accessory
{
	/**
	 * Method for calculate accessory price
	 *
	 * @param   integer  $productId           Product ID
	 * @param   float    $accessoryPrice      Accessory price
	 * @param   float    $accessoryMainPrice  Accessory main price
	 * @param   integer  $hasVAT              Is include VAT?
	 * @param   integer  $userId              User ID
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getPrice($productId = 0, $accessoryPrice = 0, $accessoryMainPrice = 0, $hasVAT = 0, $userId = 0)
	{
		$accessoryPrice     = empty($accessoryPrice) ? 0 : (float) $accessoryPrice;
		$accessoryMainPrice = empty($accessoryMainPrice) ? 0 : (float) $accessoryMainPrice;

		/*
		 * $hasVAT = 0 (add vat to accessory price)
		 * $hasVAT = 1 (Do not add vat to accessory price)
		 */
		if ($hasVAT != 1)
		{
			$accessoryPriceVAT     = 0;
			$accessoryMainPriceVAT = 0;

			// Get vat for accessory price
			if ($accessoryPrice > 0)
			{
				$accessoryPriceVAT = \RedshopHelperProduct::getProductTax($productId, $accessoryPrice, $userId);
			}

			if ($accessoryMainPrice > 0)
			{
				$accessoryMainPriceVAT = \RedshopHelperProduct::getProductTax($productId, $accessoryMainPrice, $userId);
			}

			// Add VAT to accessory prices
			$accessoryPrice     += $accessoryPriceVAT;
			$accessoryMainPrice += $accessoryMainPriceVAT;
		}

		$saved = $accessoryMainPrice - $accessoryPrice;
		$saved = $saved < 0 ? 0 : $saved;

		/**
		 * Return array
		 * 1. Accessory price
		 * 2. Accessory main price
		 * 3. Saved price
		 */
		return array($accessoryPrice, $accessoryMainPrice, $saved);
	}
}
