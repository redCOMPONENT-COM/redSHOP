<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableorder_attribute_item extends JTable
{
	public $order_att_item_id = null;

	public $order_item_id = null;

	public $section_id = null;

	public $section = null;

	public $parent_section_id = null;

	public $section_name = null;

	public $section_vat = null;

	public $section_price = null;

	public $section_oprand = null;

	public $is_accessory_att = null;

	public $stockroom_id = null;

	public $stockroom_quantity = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'order_attribute_item', 'order_att_item_id', $db);
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
