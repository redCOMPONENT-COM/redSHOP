<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

class Tableproduct_detail extends JTable
{
	var $product_id = 0;
	var $product_parent_id = 0;
	var $product_number = null;
	var $product_price = null;
	var $discount_price = null;
	var $product_type = null;
	var $product_volume = null;
	var $manufacturer_id = null;
	var $supplier_id = null;
	var $product_on_sale = null;
	var $product_special = 0;
	var $product_download = 0;
	var $product_name = null;
	var $product_s_desc = null;
	var $product_desc = null;
	var $visited = 0;
	var $product_template = 0;
	var $publish_date = null;
	var $product_thumb_image = null;
	var $product_full_image = null;
	var $metakey = null;
	var $metadesc = null;
	var $metalanguage_setting = null;
	var $metarobot_info = null;
	var $append_to_global_seo = 'append';
	var $pagetitle = null;
	var $pageheading = null;
	var $sef_url = null;
	var $cat_in_sefurl = null;
	var $product_tax_id = null;
	var $product_tax_group_id = null;
	var $published = null;
	var $weight = 0;
	var $expired = 0;
	var $discount_stratdate = null;
	var $discount_enddate = null;
	var $not_for_sale = 0;
	var $use_discount_calc = 0;
	var $discount_calc_method = null;
	var $min_order_product_quantity = 0;
	var $max_order_product_quantity = 0;
	var $attribute_set_id = 0;
	var $product_length = 0;
	var $product_height = 0;
	var $product_width = 0;
	var $product_diameter = 0;
	var $product_availability_date = 0;
	var $use_range = 0;
	var $product_download_days = 0;
	var $product_download_limit = 0;
	var $product_download_clock = 0;
	var $product_download_clock_min = 0;
	var $product_download_infinite = 0;
	var $product_back_full_image = null;
	var $product_back_thumb_image = null;
	var $product_preview_image = null;
	var $product_preview_back_image = null;
	var $accountgroup_id = 0;
	var $preorder = null;
	var $quantity_selectbox_value = null;
	var $canonical_url = null;
	/**
	 * @var boolean
	 */
	var $checked_out = 0;

	/**
	 * @var time
	 */
	var $checked_out_time = 0;

	function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product', 'product_id', $db);
	}

	/**
	 * Check for the product ID
	 */
	function check()
	{

		$db = JFactory::getDBO();
		$q  = "SELECT product_id
			FROM " . $this->_table_prefix . "product
			WHERE product_number = " . $db->Quote($this->product_number);
		$db->setQuery($q);

		$xid = intval($db->loadResult());

		if ($xid && $xid != intval($this->product_id))
		{

			$this->setError(JText::_('COM_REDSHOP_PRODUCT_NUMBER_ALREADY_EXISTS'));

			//$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRODUCT_NUMBER_ALREADY_EXISTS'));
			return false;
		}

		return true;
	}
}