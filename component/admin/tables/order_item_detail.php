<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class Tableorder_item_detail extends JTable
{
	var $order_item_id = null;
	var $order_id = null;
	var $user_info_id = null;
	var $supplier_id = null;
	var $product_id = null;
	var $order_item_sku = null;
	var $order_item_name = null;
	var $product_quantity = null;
	var $product_item_price = null;
	var $product_item_old_price = null;
	var $product_item_price_excl_vat = null;
	var $product_final_price = null;
	var $order_item_currency = null;
	var $order_status = null;
	var $customer_note = null;
	var $cdate = null;
	var $mdate = null;
	var $product_attribute = null;
	var $discount_calc_data = null;
	var $product_accessory = null;
	var $delivery_time = null;
	var $container_id = null;
	var $stockroom_id = null;
	var $stockroom_quantity = null;
	var $wrapper_id = null;
	var $wrapper_price = null;
	var $is_giftcard = 0;
	var $product_purchase_price = 0;
	var $attribute_image = null;

	function Tableorder_item_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'order_item', 'order_item_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		return parent::bind($array, $ignore);
	}

}
?>
