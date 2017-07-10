<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableshopper_group_detail extends JTable
{
	public $shopper_group_id = null;

	public $shopper_group_name = null;

	public $shopper_group_customer_type = null;

	public $shopper_group_portal = null;

	public $shopper_group_categories = null;

	public $shopper_group_url = null;

	public $shopper_group_logo = null;

	public $shopper_group_introtext = null;

	public $shopper_group_desc = null;

	public $parent_id = null;

	public $default_shipping = 0;

	public $default_shipping_rate = null;

	public $show_price_without_vat = 0;

	public $show_price = 'global';

	public $use_as_catalog = 'global';

	public $published = null;

	public $shopper_group_cart_checkout_itemid = 0;

	public $tax_group_id = 0;

	public $apply_product_price_vat = 0;

	public $shopper_group_cart_itemid = 0;

	public $shopper_group_quotation_mode = 0;

	public $shopper_group_manufactures = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'shopper_group', 'shopper_group_id', $db);
	}

	public function bind($array, $ignore = '')
	{
		if (array_key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
