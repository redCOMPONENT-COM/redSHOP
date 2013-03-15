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

class Tableproduct_attribute_price_detail extends JTable
{
	var $price_id = null;
	var $section_id = 0;
	var $section = null;
	var $product_price = 0;
	var $product_currency = null;
	var $cdate = 0;
	var $shopper_group_id = 0;
	var $price_quantity_start = 0;
	var $price_quantity_end = 0;
	var $discount_price = 0;
	var $discount_start_date = 0;
	var $discount_end_date = 0;

	function Tableproduct_attribute_price_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_attribute_price_detail', 'price_id', $db);
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
