<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablesubattribute_property extends JTable
{
	public $subattribute_color_id = null;

	public $subattribute_color_name = null;

	public $subattribute_color_title = null;

	public $subattribute_color_price = null;

	public $oprand = null;

	public $subattribute_color_image = null;

	public $subattribute_id = null;

	public $ordering = null;

	public $subattribute_color_number = null;

	public $setdefault_selected = 0;

	public $subattribute_color_main_image = null;

	public $subattribute_published = 1;

	public $extra_field = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_subattribute_color', 'subattribute_color_id', $db);
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
