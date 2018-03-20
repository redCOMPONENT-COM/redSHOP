<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\User;

defined('_JEXEC') or die;

/**
 * Rating helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Product
{
	/**
	 * @var array
	 */
	protected static $productSpecialIds = array();

	/**
	 * Get Product Special Id
	 *
	 * @param   integer  $userId  User Id
	 *
	 * @return  string
	 * @throws  \Exception
	 */
	public static function getSpecials($userId)
	{
		if (array_key_exists($userId, self::$productSpecialIds))
		{
			return self::$productSpecialIds[$userId];
		}

		$db = \JFactory::getDbo();

		if ($userId)
		{
			$query = $db->getQuery(true)
				->select('ps.discount_product_id')
				->from($db->qn('#__redshop_discount_product_shoppers', 'ps'))
				->leftJoin($db->qn('#__redshop_users_info', 'ui') . ' ON ' . $db->qn('ui.shopper_group_id') . ' = ' . $db->qn('ps.shopper_group_id'))
				->where($db->qn('ui.user_id') . ' = ' . (int) $userId)
				->where($db->qn('ui.address_type') . ' = ' . $db->q('BT'));
		}
		else
		{
			$userArr = \JFactory::getSession()->get('rs_user');

			if (empty($userArr))
			{
				$userArr = \RedshopHelperUser::createUserSession($userId);
			}

			$shopperGroupId = isset($userArr['rs_user_shopperGroup']) ?
				$userArr['rs_user_shopperGroup'] : \RedshopHelperUser::getShopperGroup($userId);

			$query = $db->getQuery(true)
				->select($db->qn('dps.discount_product_id'))
				->from($db->qn('#__redshop_discount_product_shoppers', 'dps'))
				->where($db->qn('dps.shopper_group_id') . ' = ' . (int) $shopperGroupId);
		}

		$result = $db->setQuery($query)->loadColumn();

		self::$productSpecialIds[$userId] = '0';

		if (count($result) > 0)
		{
			self::$productSpecialIds[$userId] .= ',' . implode(',', $result);
		}

		return self::$productSpecialIds[$userId];
	}
}
