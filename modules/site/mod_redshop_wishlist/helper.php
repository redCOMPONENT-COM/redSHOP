<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JLoader::import('redshop.library');

/**
 * Helper for mod_articles_latest
 *
 * @since  1.5
 */
abstract class ModRedWishList
{
	/**
	 * Retrieve a list of product
	 *
	 * @param   JRegistry  &$params  module parameters
	 *
	 * @return  mixed
	 *
	 * @since   1.6.1
	 */
	public static function getList(&$params)
	{
		$user 	= JFactory::getUser();
		$db 	= JFactory::getDbo();
		$rows 	= array();
		$query 	= $db->getQuery(true);
		$sess 	= JFactory::getSession();
		$noProd = $sess->get('no_of_prod');
		$result = array();

		$query->select($db->qn(['wishlist_id', 'wishlist_name']))
			->from($db->qn('#__redshop_wishlist'))
			->where($db->qn('user_id') . ' = ' . $db->q($user->id));

		$db->setQuery($query);
		$wishlists = $db->loadObjectList();
		$result['wishlists'] = $wishlists;

		if (count($wishlists) > 0 && $user->id != 0)
		{
			for ($i = 0, $i = count($wishlists); $i < $i; $i++)
			{
				$query = $db->getQuery(true);
				$query->select(
					$db->qn(
							[
								'wp.wishlist_product_id', 'wp.wishlist_id', 'wp.product_id', 'wp.cdate',
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
					->innerJoin(
							$db->qn('#__redshop_wishlist_product', 'wp')
							. ' ON ' . $db->qn('wp.product_id') . ' = ' . $db->qn('p.product_id')
						)
					->where($db->qn('wp.wishlist_id') . ' = ' . $db->q($wishlists[$i]->wishlist_id));
				$db->setQuery($query);
				$wish_products[$wishlists[$i]->wishlist_id] = $db->loadObjectList();
			}
		}
		elseif (isset($noProd))
		{
			$prodIds = array();

			for ($i = 1; $i <= $noProd; $i++)
			{
				if (isset($sess->get('wish_' . $i)->product_id))
				{
					$prodIds[] = $sess->get('wish_' . $i)->product_id . ",";
				}
			}

			if (count($prodIds))
			{
				// Sanitize ids
				JArrayHelper::toInteger($prodIds);
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
					->where($db->qn('p.product_id') . ' IN (' . implode(',', $db->q($prodIds)) . ')');

				$db->setQuery($query);
				$rows = $db->loadObjectList();
			}
		}

		$result['rows'] = $rows;

		return $result;
	}
}
