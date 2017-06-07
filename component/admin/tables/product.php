<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Product table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Product
 * @since       2.0
 */
class RedshopTableProduct extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursor_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_product';

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $id = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $number = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $parent_id = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $manufacturer_id = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $supplier_id = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $template_id = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $tax_id = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $tax_group_id = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $attribute_set_id = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $account_group_id = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $featured = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $type = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $title = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $alias = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $description = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $short_description = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $images = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $product_full_image = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $product_thumb_image = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $product_back_full_image = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $product_back_thumb_image = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $product_preview_image = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $product_preview_back_image = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $created = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $created_by = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $created_by_alias = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $modified = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $modified_by = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $checked_out = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $checked_out_time = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $publish_up = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $publish_down = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $state = null;

	/**
	 * @var  int
	 * @since 2.0.5
	 */
	public $hits = null;

	/**
	 * @var  int
	 * @since 2.0.5
	 */
	public $on_sale = null;

	/**
	 * @var  int
	 * @since 2.0.5
	 */
	public $not_for_sale = null;
	
	/**
	 * @var  int
	 * @since 2.0.5
	 */
	public $is_expired = null;

	/**
	 * @var  int
	 * @since 2.0.5
	 */
	public $availability_date = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $cat_in_sefurl = null;
	
	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $sef_url = null;

	/**
	 * @var  float
	 * @since 2.0.5
	 */
	public $length = null;

	/**
	 * @var   float
	 * @since 2.0.5
	 */
	public $height = null;

	/**
	 * @var   float
	 * @since 2.0.5
	 */
	public $width = null;
	
	/**
	 * @var   float
	 * @since 2.0.5
	 */
	public $weight = null;

	/**
	 * @var   float
	 * @since 2.0.5
	 */
	public $diameter = null;

	/**
	 * @var  int
	 * @since 2.0.5
	 */
	public $download = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $download_days = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $download_limit = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $download_clock_min = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $download_infinite = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $volume = null;

	/**
	 * @var  int
	 * @since 2.0.5
	 */
	public $min_order_product_quantity = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $min_per_product_total = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $max_order_product_quantity = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $quantity_selectbox_value = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $allow_decimal_piece = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $discount_calc_method = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $use_discount_calc = null;

	/**
	 * @var   int
	 * @since 2.0.5
	 */
	public $use_range = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $metakey = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $metadesc = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $metalanguage_setting = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $metarobot_info = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $pagetitle = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $canonical_url = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $preorder = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $append_to_global_seo = null;

	/**
	 * @var   string
	 * @since 2.0.5
	 */
	public $params = null;

	/**
	 * Validate properties
	 *
	 * @return  boolean  True on success
	 */
	public function check()
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$table = JTable::getInstance('Product', 'RedshopTable');

		if (empty($this->number))
		{
			$this->setError(JText::_('COM_REDSHOP_TABLE_PRODUCT_MISSING_FIELD_NUMBER'));

			return false;
		}

		if ($table->load(array('number' => $db->quote($this->number))))
		{
			$this->setError(JText::_('COM_REDSHOP_TABLE_PRODUCT_NUMBER_ALREADY_EXISTS'));

			return false;
		}

		return parent::check();
	}
}
