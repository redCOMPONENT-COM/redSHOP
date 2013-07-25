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
	public $product_id = 0;

	public $product_parent_id = 0;

	public $product_number = null;

	public $product_price = null;

	public $discount_price = null;

	public $product_type = null;

	public $product_volume = null;

	public $manufacturer_id = null;

	public $supplier_id = null;

	public $product_on_sale = null;

	public $product_special = 0;

	public $product_download = 0;

	public $product_name = null;

	public $product_s_desc = null;

	public $product_desc = null;

	public $visited = 0;

	public $product_template = 0;

	public $publish_date = null;

	public $product_thumb_image = null;

	public $product_full_image = null;

	public $metakey = null;

	public $metadesc = null;

	public $metalanguage_setting = null;

	public $metarobot_info = null;

	public $append_to_global_seo = 'append';

	public $pagetitle = null;

	public $pageheading = null;

	public $sef_url = null;

	public $cat_in_sefurl = null;

	public $product_tax_id = null;

	public $product_tax_group_id = null;

	public $published = null;

	public $weight = 0;

	public $expired = 0;

	public $discount_stratdate = null;

	public $discount_enddate = null;

	public $not_for_sale = 0;

	public $use_discount_calc = 0;

	public $discount_calc_method = null;

	public $min_order_product_quantity = 0;

	public $max_order_product_quantity = 0;

	public $attribute_set_id = 0;

	public $product_length = 0;

	public $product_height = 0;

	public $product_width = 0;

	public $product_diameter = 0;

	public $product_availability_date = 0;

	public $use_range = 0;

	public $product_download_days = 0;

	public $product_download_limit = 0;

	public $product_download_clock = 0;

	public $product_download_clock_min = 0;

	public $product_download_infinite = 0;

	public $product_back_full_image = null;

	public $product_back_thumb_image = null;

	public $product_preview_image = null;

	public $product_preview_back_image = null;

	public $accountgroup_id = 0;

	public $preorder = null;

	public $quantity_selectbox_value = null;

	public $minimum_per_product_total = 0;

	public $canonical_url = null;
	/**
	 * @var boolean
	 */
	public $checked_out = 0;

	/**
	 * @var time
	 */
	public $checked_out_time = 0;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product', 'product_id', $db);
	}

	/**
	 * Check for the product ID
	 */
	public function check()
	{
		$db = JFactory::getDBO();
		$q = "SELECT product_id
			FROM " . $this->_table_prefix . "product
			WHERE product_number = " . $db->Quote($this->product_number);
		$db->setQuery($q);

		$xid = intval($db->loadResult());

		if ($xid && $xid != intval($this->product_id))
		{
			$this->setError(JText::_('COM_REDSHOP_PRODUCT_NUMBER_ALREADY_EXISTS'));

			return false;
		}

		return true;
	}
}
