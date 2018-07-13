<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

class Product
{
	/**
	 * @param   integer $productId Product id
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getProductMedias($productId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__redshop_media')
			->where($db->quoteName('section_id') . ' = ' . (int) $productId)
			->where($db->quoteName('media_section') . ' = ' . $db->quote('product'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * @param   integer $pid Product id
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getProductCategories($pid)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $pid))
			->order($db->qn('c.name'));

		return $db->setQuery($query)->loadObjectlist();
	}
}
