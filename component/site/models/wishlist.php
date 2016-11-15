<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



/**
 * Class wishlistModelwishlist
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelWishlist extends RedshopModel
{
	public $_id = null;

	public $_name = null;

	// Product data
	public $_userid = null;

	public $_table_prefix = null;

	public $_comment = null;

	public $_cdate = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	public function getUserWishlist()
	{
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();

		$query = "SELECT * FROM " . $this->_table_prefix . "wishlist WHERE user_id=" . (int) $user->id;
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	/**
	 * getWishlistProduct description
	 * 
	 * @return  objectlist
	 */
	public function getWishlistProduct()
	{
		$user 		= JFactory::getUser();
		$db   		= JFactory::getDbo();
		$sess 		= JFactory::getSession();
		$numProd 	= $sess->get('no_of_prod', 0);

		if ($user->id)
		{
			$wishlists     = $this->getUserWishlist();
			$wish_products = array();

			for ($i = 0, $in = count($wishlists); $i < $in; $i++)
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
							. ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('wp.product_id')
						)
					->where($db->qn('wp.wishlist_id') . ' = ' . $db->q($wishlists[$i]->wishlist_id));
				$db->setQuery($query);
				$wish_products[$wishlists[$i]->wishlist_id] = $db->loadObjectList();
			}

			return $wish_products;
		}
		else
		{
			$productIds = array();
			$rows    = array();

			if (isset($numProd))
			{
				for ($i = 1; $i <= $numProd; $i++)
				{
					if (isset($sess->get('wish_' . $i)->product_id))
					{
						$productIds[] = (int) $sess->get('wish_' . $i)->product_id;
					}
				}

				if (count($productIds))
				{
					// Sanitize ids
					JArrayHelper::toInteger($productIds);

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
						->where($db->qn('p.product_id') . ' IN (' . implode(',', $db->q($productIds)) . ')');

					$db->setQuery($query);
					$rows = $db->loadObjectList();
				}
			}

			return $rows;
		}
	}

	/**
	 * getWishlistProductFromSession function
	 * 
	 * @return object list
	 */
	public function getWishlistProductFromSession()
	{
		$db      = JFactory::getDbo();
		$rows    = array();
		$sess 	 = JFactory::getSession();
		$numProd = $sess->get('no_of_prod', 0);

		$productIds = array();

		if (isset($numProd))
		{
			for ($i = 1; $i <= $numProd; $i++)
			{
				if (isset($sess->get('wish_' . $i)->product_id))
				{
					$productIds[] = (int) $sess->get('wish_' . $i)->product_id;
				}
			}

			if (count($productIds))
			{
				// Sanitize ids
				JArrayHelper::toInteger($productIds);

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
								'p.preorder', 'p.append_to_global_seo',
								'pcx.product_id'
							]
						)
					)
					->from($db->qn('#__redshop_product', 'p'))
					->leftJoin(
						$db->qn('#__redshop_product_category_xref', 'pcx')
						. ' ON ' . $db->qn('pcx.product_id') . ' = ' . $db->qn('p.product_id')
					)
					->where($db->qn('p.product_id') . ' IN (' . implode(',', $db->q($productIds)) . ')');
				$db->setQuery($query);
				$rows = $db->loadObjectList();
			}
		}

		return $rows;
	}

	public function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		else
		{
			$db         = JFactory::getDbo();
			$product_id = JRequest::getInt('product_id');

			if ($product_id)
			{
				$ins_query = "INSERT INTO " . $this->_table_prefix . "wishlist_product "
					. " SET wishlist_id=" . (int) $row->wishlist_id
					. ", product_id=" . (int) $product_id
					. ", cdate = " . $db->quote(time());
				$db->setQuery($ins_query);

				if ($db->execute())
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			elseif (!empty($_SESSION["no_of_prod"]))
			{
				ob_clean();
				$extraField = extraField::getInstance();
				$section    = 12;
				$row_data   = $extraField->getSectionFieldList($section);

				for ($si = 1; $si <= $_SESSION["no_of_prod"]; $si++)
				{
					for ($k = 0, $kn = count($row_data); $k < $kn; $k++)
					{
						$myfield = "productuserfield_" . $k;

						if ($_SESSION['wish_' . $si]->$myfield != '')
						{
							$myuserdata = $_SESSION['wish_' . $si]->$myfield;
							$ins_query  = "INSERT INTO #__redshop_wishlist_userfielddata SET "
								. " wishlist_id = " . (int) $row->wishlist_id
								. " , product_id = " . (int) $_SESSION['wish_' . $si]->product_id
								. ", userfielddata = " . $db->quote($myuserdata);

							$db->setQuery($ins_query);
							$db->execute();
						}
					}

					$ins_query = "INSERT INTO #__redshop_wishlist_product SET "
						. " wishlist_id = " . (int) $row->wishlist_id
						. ", product_id = " . (int) $_SESSION['wish_' . $si]->product_id
						. ", cdate = " . $db->quote($_SESSION['wish_' . $si]->cdate);
					$db->setQuery($ins_query);
					$db->execute();
					unset($_SESSION['wish_' . $si]);
				}

				unset($_SESSION["no_of_prod"]);
			}
		}

		return true;
	}

	public function savewishlist()
	{
		$cid        = JRequest::getVar('wishlist_id', '', 'request', 'array');
		$db         = JFactory::getDbo();
		$product_id = JRequest::getInt('product_id');

		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$query = "SELECT wishlist_product_id FROM " . $this->_table_prefix . "wishlist_product "
				. " WHERE wishlist_id=" . (int) $cid[$i] . " AND product_id=" . (int) $product_id;
			$db->setQuery($query);

			if (count($db->loadResult()) > 0)
			{
				continue;
			}

			$ins_query = "INSERT INTO " . $this->_table_prefix . "wishlist_product "
				. " SET wishlist_id=" . (int) $cid[$i]
				. ", product_id=" . (int) $product_id
				. ", cdate = " . $db->quote(time());
			$db->setQuery($ins_query);

			if ($db->execute())
			{
				continue;
			}
			else
			{
				return false;
			}
		}

		return true;
	}

	public function check_user_wishlist_authority($userid, $wishlist_id)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT wishlist_id FROM " . $this->_table_prefix . "wishlist "
			. " WHERE wishlist_id=" . (int) $wishlist_id . " AND user_id=" . (int) $userid;
		$db->setQuery($query);

		$rs = $db->loadResult();

		if ($rs)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function delwishlist($userid, $wishlist_id)
	{
		$db    = JFactory::getDbo();
		$query = "DELETE FROM " . $this->_table_prefix . "wishlist_product "
			. " WHERE wishlist_id=" . (int) $wishlist_id;
		$db->setQuery($query);

		$db->execute();
		$query = "DELETE FROM " . $this->_table_prefix . "wishlist_userfielddata "
			. " WHERE wishlist_id=" . (int) $wishlist_id;
		$db->setQuery($query);

		if ($db->execute())
		{
			$query = "DELETE FROM " . $this->_table_prefix . "wishlist "
				. " WHERE wishlist_id=" . (int) $wishlist_id . " AND user_id=" . (int) $userid;
			$db->setQuery($query);

			if ($db->execute())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function mysessdelwishlist($wishlist_id)
	{
		if (!empty($_SESSION["no_of_prod"]))
		{
			for ($k = 1; $k <= $_SESSION["no_of_prod"]; $k++)
			{
				if ($_SESSION['wish_' . $k]->product_id == $wishlist_id)
				{
					unset($_SESSION['wish_' . $k]);
				}
			}
		}
	}
}
