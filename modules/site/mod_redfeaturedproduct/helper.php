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
abstract class ModRedFeaturedProductHelper
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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('p.product_id'))
			->from($db->qn('#__redshop_product', 'p'))
			->where($db->qn('p.published') . ' = 1')
			->where($db->qn('product_special') . ' = 1')
			->group($db->qn('p.product_id'));

		switch ($params->get('ScrollSortMethod', 'random'))
		{
			case 'random':
				$query->order('RAND()');
				break;
			case 'newest':
				$query->order($db->qn('publish_date') . ' DESC');
				break;
			case 'oldest':
				$query->order($db->qn('publish_date') . ' ASC');
				break;
			default:
				$query->order($db->qn('publish_date') . ' DESC');
				break;
		}

		$rows = array();

		if ($productIds = $db->setQuery($query, 0, (int) $params->get('NumberOfProducts', 5))->loadColumn())
		{
			// Third steep get all product relate info
			$query->clear()
				->where($db->qn('p.product_id') . ' IN (' . implode(',', $db->q($productIds)) . ')')
				->order('FIELD(' . $db->qn('p.product_id') . ', ' . implode(',', $db->q($productIds)) . ')');

			$user = JFactory::getUser();
			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', ' . $db->qn('p.product_id') . ', ' . $db->q($user->id) . ') AS ' . $db->qn('concat_id'));

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}

		return $rows;
	}
}
