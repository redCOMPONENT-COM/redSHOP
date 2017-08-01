<?php
/**
 * @package     RedSHOP
 * @subpackage  Discount
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Discount class
 *
 * @since  2.0.3
 */
class RedshopHelperDiscount
{

	/**
	 * Method for get discount
	 *
	 * @param   int  $subTotal  Sub-total amount
	 * @param   int  $userId    User ID
	 *
	 * @return  mixed
	 *
	 * @since  2.0.3
	 */
	public static function getDiscount($subTotal = 0, $userId = 0)
	{
		$db   = JFactory::getDbo();
		$user = JFactory::getUser();

		if (!$userId)
		{
			$userId = $user->id;
		}

		$userData       = RedshopHelperUser::createUserSession($userId);
		$shopperGroupId = (int) $userData['rs_user_shopperGroup'];

		$shopperGroupDiscounts = RedshopEntityShopper_Group::getInstance($shopperGroupId)->getDiscounts();

		if ($shopperGroupDiscounts->isEmpty())
		{
			return false;
		}

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_discount'))
			->where($db->qn('published') . ' = 1')
			->where($db->qn('discount_id') . ' IN (' . implode(',', $shopperGroupDiscounts->ids()) . ')')
			->where($db->qn('start_date') . ' <= ' . time())
			->where($db->qn('end_date') . ' >= ' . time())
			->order($db->qn('amount') . ' DESC');

		$db->setQuery($query, 0, 1);

		if (!$subTotal)
		{
			return $db->setQuery($query)->loadObject();
		}

		$newQuery = clone $query;
		$newQuery->where($db->qn('condition') . ' = 2')
			->where($db->qn('amount') . ' = ' . $subTotal);

		$result = $db->setQuery($newQuery)->loadObject();

		if (!$result)
		{
			$newQuery = clone $query;
			$newQuery->where($db->qn('condition') . ' = 1')
				->where($db->qn('amount') . ' > ' . $subTotal);

			$result = $db->setQuery($newQuery)->loadObject();

			if (!$result)
			{
				$newQuery = clone $query;
				$newQuery->where($db->qn('condition') . ' = 3')
					->where($db->qn('amount') . ' < ' . $subTotal);

				$result = $db->setQuery($newQuery)->loadObject();
			}
		}

		return $result;
	}

	/**
	 * Get discount price from product with check discount date.
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return  float
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getDiscountPriceBaseDiscountDate($productId)
	{
		if ($productData = RedshopHelperProduct::getProductById($productId))
		{
			$today = time();

			// Convert discount_enddate to middle night
			$productData->discount_enddate = RedshopHelperDatetime::generateTimestamp($productData->discount_enddate);

			if (($productData->discount_enddate == '0' && $productData->discount_stratdate == '0')
				|| ((int) $productData->discount_enddate >= $today && (int) $productData->discount_stratdate <= $today)
				|| ($productData->discount_enddate == '0' && (int) $productData->discount_stratdate <= $today))
			{
				return (float) $productData->discount_price;
			}
		}

		return 0.0;
	}
}
