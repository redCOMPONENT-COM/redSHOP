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
 * @since  1.5
 */
abstract class ModRedProducts3d
{
	/**
	 * getList function
	 * 
	 * @param   array  &$params  module params
	 * 
	 * @return  objectlist
	 */
	static function getList(&$params)
	{
		$category = $params->get('category', array());
		$count    = trim($params->get('count', 2));

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
				$db->qn(
					[
						'p.product_id', 'p.product_parent_id', 'p.supplier_id', 'p.product_on_sale',
						'p.product_special', 'p.product_download', 'p.product_template', 'p.product_name',
						'p.product_price', 'p.discount_price', 'p.discount_stratdate', 'p.discount_enddate',
						'p.product_number', 'p.product_type', 'p.product_s_desc', 'p.product_desc',
						'p.product_volume', 'p.product_tax_id', 'p.published', 'p.product_thumb_image',
						'p.product_full_image', 'p.publish_date', 'p.update_date', 'p.visited', 'p.metakey',
						'p.metadesc', 'p.metalanguage_setting', 'p.metarobot_info', 'p.pagetitle',
						'p.pageheading', 'p.sef_url', 'p.cat_in_sefurl', 'p.weight', 'p.expired',
						'p.not_for_sale', 'p.use_discount_calc', 'p.discount_calc_method', 'p.min_order_product_quantity',
						'p.attribute_set_id', 'p.product_length', 'p.product_height', 'p.product_width',
						'p.product_diameter', 'p.product_availability_date', 'p.use_range', 'p.product_tax_group_id',
						'p.product_download_days', 'p.product_download_limit', 'p.product_download_clock',
						'p.product_download_clock_min', 'p.accountgroup_id', 'p.canonical_url', 'p.minimum_per_product_total',
						'p.allow_decimal_piece', 'p.quantity_selectbox_value', 'p.checked_out', 'p.checked_out_time',
						'p.max_order_product_quantity', 'p.product_download_infinite', 'p.product_back_full_image',
						'p.product_back_thumb_image', 'p.product_preview_image', 'p.product_preview_back_image',
						'p.preorder', 'p.append_to_global_seo'
					]
				)
			)
			->from($db->qn('#__redshop_product', 'p'))
			->where($db->qn('p.published') . ' = ' . $db->q('1'));

		if (is_array($category) && count($category) > 0)
		{
			JArrayHelper::toInteger($category);
			$query->leftJoin(
				$db->qn('#__redshop_product_category_xref', 'cx')
				. ' ON ' . $db->qn('cx.product_id') . ' = ' . $db->qn('p.product_id')
			);

			$query->where($db->qn('cx.category_id') . ' IN (' . implode(',', $db->q($category)) . ')');
		}

		$db->setQuery($query, 0, (int) $count);
		$rows = $db->loadObjectList();

		return $rows;
	}
}
