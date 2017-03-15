<?php
/**
 * @package     RedSHOP
 * @subpackage  Discount
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
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

		$query = $db->getQuery(true)
			->select($db->qn('ds.discount_id'))
			->from($db->qn('#__redshop_discount_shoppers', 'ds'))
			->where($db->qn('ds.shopper_group_id') . ' = ' . $shopperGroupId);

		$result = $db->setQuery($query)->loadColumn();

		if (empty($result))
		{
			return;
		}

		$result = array_merge(array(0 => '0'), $result);

		// Secure ids
		$result = ArrayHelper::toInteger($result);

		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_discount'))
			->where($db->qn('published') . ' = 1')
			->where($db->qn('discount_id') . ' IN (' . implode(',', $result) . ')')
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
}
