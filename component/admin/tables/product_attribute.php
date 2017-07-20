<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableproduct_attribute extends JTable
{
	public $attribute_id = null;

	public $attribute_set_id = 0;

	public $attribute_name = null;

	public $attribute_description = null;

	public $attribute_required = null;

	public $allow_multiple_selection = 0;

	public $hide_attribute_price = 0;

	public $product_id = null;

	public $ordering = null;

	public $attribute_published = 1;

	public $display_type = null;

	public $extra_field = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_attribute', 'attribute_id', $db);
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
