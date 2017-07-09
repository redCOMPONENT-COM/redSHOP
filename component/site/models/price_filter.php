<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class price_filterModelprice_filter
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelPrice_Filter extends RedshopModel
{
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.5
	 */
	public function getData()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT(p.product_id), p.*')
			->from($db->qn('#__redshop_product', 'p'))
			->where('p.published = 1')
			->order('p.product_price');

		if ($category = JFactory::getApplication()->input->getInt('category', 0))
		{
			$query->leftJoin($db->qn('#__redshop_product_category_xref', 'cx') . ' ON cx.product_id = p.product_id')
				->where('cx.category_id = ' . (int) $category);
		}

		return $db->setQuery($query)->loadObjectList();
	}
}
