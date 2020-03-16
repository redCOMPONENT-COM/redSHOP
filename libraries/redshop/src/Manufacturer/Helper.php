<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Manufacturer;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Manufacturer helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Helper
{
	public static function getManufacturerCategory($mid, $manufacturer)
	{
		$plgManufacturer = \RedshopHelperOrder::getParameters('plg_manucaturer_excluding_category');
		$db = Factory::getDbo();

		$query = $db->getQuery(true)
			->select('DISTINCT(c.id)')
			->select($db->qn('c.name'))
			->select($db->qn('c.short_description'))
			->select($db->qn('c.description'))
			->select($db->qn('c.category_thumb_image'))
			->select($db->qn('c.category_full_image'))
			->from($db->qn('#__redshop_product') . ' AS p')
			->leftJoin($db->qn('#__redshop_product_category_xref') . ' AS pc' . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pc.product_id'))
			->leftJoin($db->qn('#__redshop_category') . ' AS c' . ' ON ' . $db->qn('pc.category_id') . ' = ' . $db->qn('c.id'))
			->where($db->qn('p.published') . ' = 1')
			->where($db->qn('p.manufacturer_id') . ' = ' . $db->q((int) $mid))
			->where($db->qn('p.expired') . ' = 0')
			->where($db->qn('p.product_parent_id') . ' = 0');

		if (!empty($plgManufacturer) && $plgManufacturer[0]->enabled && $manufacturer->excluding_category_list != '')
		{
			$excludingCategoryList = explode(',', $manufacturer->excluding_category_list);

			if (!empty($excludingCategoryList))
			{
				$excludingCategoryList = implode(',', \Joomla\Utilities\ArrayHelper::toInteger($excludingCategoryList));
				$query->where($db->qn('c.id') . ' NOT IN (' . $excludingCategoryList . ')');
			}
		}

		return $db->setQuery($query)->loadObjectlist();
	}
}