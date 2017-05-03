<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_articles_latest
 *
 * @since  1.5
 */
abstract class ModRedshopWhoBoughtProductHelper
{
	/**
	 * Retrieve a list of product
	 *
	 * @param   JRegistry  &$params  module parameters
	 *
	 * @return  mixed
	 *
	 * @since   1.5
	 */
	public static function getList(&$params)
	{
		$number_of_items        = trim($params->get('number_of_items', 5));

		$category = $params->get('category', '');

		if (is_array($category))
		{
			$category = implode(',', $category);
		}
		else
		{
			$category = trim($category);
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('p.product_id')
			->from($db->qn('#__redshop_product', 'p'))
			->where($db->qn('p.published') . ' = 1')
			->group($db->qn('p.product_id'));

		if ($category != "")
		{
			$query->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON ' . $db->qn('pc.product_id') . ' = ' . $db->qn('p.product_id'))
				->where($db->qn('pc.category_id') . ' IN (' . $db->q($category) . ')');
		}

		$rows = array();

		if ($productIds = $db->setQuery($query, 0, $number_of_items)->loadColumn())
		{
			$query->clear()
				->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')')
				->order('FIELD(' . $db->qn('p.product_id') . ', ' . implode(',', $productIds) . ')');

			$user = JFactory::getUser();
			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', ' . $db->qn('p.product_id') . ', ' . $db->q((int) $user->id) . ') AS ' . $db->qn('concat_id'));

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}
	}
}
