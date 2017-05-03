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
			->select('SUM(' . $db->qn('oi.product_quantity') . ') AS ' . $db->qn('qty') . ', ' . $db->qn('oi.product_id') . ', ' . $db->qn('oi.order_id'))
			->from($db->qn('#__redshop_order_item', 'oi'))
			->group($db->qn('oi.product_id'));

		$query = $db->getQuery(true)
			->select($db->qn(['p.product_id', 'orderItems.qty']))
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin('(' . $subQuery . ') ' . $db->qn('orderItems') . ' ON ' . $db->qn('orderItems.product_id') . ' = ' . $db->qn('p.product_id'))
			->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('orderItems.order_id'))
			->leftJoin($db->qn('#__redshop_users_info', 'ui') . ' ON ui.user_id = o.user_id')
			->where($db->qn('ui.shopper_group_id') . ' = ' . $db->q((int) $shopperGroupId))
			->where($db->qn('p.published') . ' = ' . $db->q('1'))
			->group($db->qn('p.product_id'))
			->order($db->qn('orderItems.qty') . ' DESC');

		if ($productIds = $db->setQuery($query, 0, (int) $params->get('count', 5))->loadColumn())
		{
			// Third step: get all product related info
			$query->clear()
				->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')')
				->order('FIELD(' . $db->qn('p.product_id') . ', ' . implode(',', $productIds) . ')');

			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', ' . $db->qn('p.product_id') . ', ' . $db->q((int) $user->id) . ') AS ' . $db->qn('concat_id'));

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}

		return $rows;
	}
}
