<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class Tableorder_acc_item extends JTable
{
	var $order_item_acc_id = null;
	var $order_item_id = null;
	var $product_id = null;
	var $order_acc_item_sku = null;
	var $order_acc_item_name = null;
	var $order_acc_price = null;
	var $order_acc_vat = null;
	var $product_quantity = null;
	var $product_acc_item_price = null;
	var $product_acc_final_price = null;
	var $product_attribute = null;


	function Tableorder_acc_item(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'order_acc_item', 'order_item_acc_id', $db);
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