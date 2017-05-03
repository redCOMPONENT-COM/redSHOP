<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for mod_redmanufacturer
 *
 * @since  1.5.3
 */
abstract class ModRedshopCategoryScrollerHelper
{
	/**
	 * Retrieve a list of article
	 *
	 * @param   \Joomla\Registry\Registry  &$params  Module parameters
	 *
	 * @return  mixed
	 */
	public static function getList(&$params)
	{
		$scrollSortMethod  = trim($params->get('ScrollSortMethod', 'random'));
		$cid = JFactory::getApplication()->input->getInt('cid', 0);
		$NumberOfProducts  = trim($params->get('NumberOfProducts', 5));

		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('p.product_id'))
			->from($db->qn('#__redshop_product', 'p'))
			->where($db->qn('p.published') . ' = ' . $db->q('1'))
			->group($db->qn('p.product_id'));

		switch ($scrollSortMethod)
		{
			case 'random':
				$query->order("RAND()");
				break;
			case 'newest':
				$query->order($db->qn('p.publish_date') . ' DESC');
				break;
			case 'oldest':
				$query->order($db->qn('p.publish_date') . ' ASC');
				break;
			case 'mostsold':
				$query->order($db->qn('orderItems.qty') . ' DESC');

				$subQuery = $db->getQuery(true)
					->select(
						[
							'SUM(' . $db->qn('oi.product_quantity') . ') AS ' . $db->qn('qty'),
							$db->qn('oi.product_id')
						]
					)
					->from($db->qn('#__redshop_order_item', 'oi'))
					->group($db->qn('oi.product_id'));

				$query->select($db->qn('orderItems.qty'))
					->leftJoin('(' . $subQuery . ') ' . $db->qn('orderItems') . ' ON ' . $db->qn('orderItems.product_id') . ' = ' . $db->qn('p.product_id'));
				break;
			default:
				$query->order($db->qn('p.product_id'));
				break;
		}

		if ($cid)
		{
			$query->leftJoin($db->qn('#__redshop_product_category_xref', 'cx') . ' ON ' . $db->qn('cx.product_id') . ' = ' . $db->qn('p.product_id'))
				->where($db->qn('cx.category_id') . ' = ' . $db->q((int) $cid));
		}

		$query->setLimit($NumberOfProducts);

		$db->setQuery($query);

		if ($productIds = $db->setQuery($query)->loadColumn())
		{
			// Third steep get all product relate info
			$query->clear()
				->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')')
				->order('FIELD(' . $db->qn('p.product_id') . ', ' . implode(',', $productIds) . ')');

			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', ' . $db->qn('p.product_id') . ', ' . (int) $user->id . ') AS ' . $db->qn('concat_id'));

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}

		return $rows;
	}
}
