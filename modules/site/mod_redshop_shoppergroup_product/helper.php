<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_product
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_redshop_shoppergroup_product
 *
 * @since  1.5
 */
class ModRedshopShopperGroupProduct
{
	/**
	 * Get a list of the products.
	 *
	 * @param   \Joomla\Registry\Registry  &$params  The module options.
	 *
	 * @return  array
	 */
	public static function getList(&$params)
	{
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();
		$shopperGroupId = RedshopHelperUser::getShopperGroup($user->id);
		$rows = array();

		$subQuery = $db->getQuery(true)
			->select('SUM(' . $db->qn('oi.product_quantity') . ') AS qty, oi.product_id, oi.order_id')
			->from($db->qn('#__redshop_order_item', 'oi'))
			->group('oi.product_id');

		$query = $db->getQuery(true)
			->select('p.product_id, orderItems.qty')
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin('(' . $subQuery . ') orderItems ON orderItems.product_id = p.product_id')
			->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON o.order_id = orderItems.order_id')
			->leftJoin($db->qn('#__redshop_users_info', 'ui') . ' ON ui.user_id = o.user_id')
			->where('ui.shopper_group_id = ' . (int) $shopperGroupId)
			->where('p.published = 1')
			->group('p.product_id')
			->order('orderItems.qty DESC');

		if ($productIds = $db->setQuery($query, 0, (int) $params->get('count', 5))->loadColumn())
		{
			// Third step: get all product related info
			$query->clear()
				->where('p.product_id IN (' . implode(',', $productIds) . ')')
				->order('FIELD(p.product_id, ' . implode(',', $productIds) . ')');

			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id');

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}

		return $rows;
	}
}
