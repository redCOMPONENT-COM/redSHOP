<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableorder_item_detail extends JTable
{
	public $order_item_id = null;

	public $order_id = null;

	public $user_info_id = null;

	public $supplier_id = null;

	public $product_id = null;

	public $order_item_sku = null;

	public $order_item_name = null;

	public $product_quantity = null;

	public $product_item_price = null;

	public $product_item_old_price = null;

	public $product_item_price_excl_vat = null;

	public $product_final_price = null;

	public $order_item_currency = null;

	public $order_status = null;

	public $customer_note = null;

	public $cdate = null;

	public $mdate = null;

	public $product_attribute = null;

	public $discount_calc_data = null;

	public $product_accessory = null;

	public $delivery_time = null;

	public $stockroom_id = null;

	public $stockroom_quantity = null;

	public $wrapper_id = null;

	public $wrapper_price = null;

	public $is_giftcard = 0;

	public $product_purchase_price = 0;

	public $attribute_image = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'order_item', 'order_item_id', $db);
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
