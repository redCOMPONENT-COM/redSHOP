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
 * Rating helper
 *
 * @since  2.1.0
 */
class Rating
{
	/**
	 * Get Product Rating
	 *
	 * @param   integer $productId Product id
	 *
	 * @return  string
	 * @since   2.1.0
	 */
	public static function getRating($productId)
	{
		$productData = \RedshopHelperProduct::getProductById($productId);

		if (empty($productData))
		{
			return '';
		}

		$avgRating = 0;

		if ($productData->count_rating > 0)
		{
			$avgRating = round($productData->sum_rating / $productData->count_rating);
		}

		if (!$avgRating)
		{
			return '';
		}

		return \RedshopLayoutHelper::render(
			'product.rating',
			array(
				'avgRating'   => $avgRating,
				'countRating' => $productData->count_rating
			)
		);
	}
}
