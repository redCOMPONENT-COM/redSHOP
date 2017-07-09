<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableproduct_attribute_price_detail extends JTable
{
	public $price_id = null;

	public $section_id = 0;

	public $section = null;

	public $product_price = 0;

	public $product_currency = null;

	public $cdate = 0;

	public $shopper_group_id = 0;

	public $price_quantity_start = 0;

	public $price_quantity_end = 0;

	public $discount_price = 0;

	public $discount_start_date = 0;

	public $discount_end_date = 0;

	public function __construct(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_attribute_price', 'price_id', $db);
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
