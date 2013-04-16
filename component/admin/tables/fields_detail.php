<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablefields_detail extends JTable
{
	public $field_id = null;

	public $field_title = null;

	public $wysiwyg = null;

	public $field_name = null;

	public $field_type = null;

	public $field_desc = null;

	public $field_class = null;

	public $field_section = null;

	public $field_maxlength = null;

	public $field_size = null;

	public $field_cols = null;

	public $field_rows = null;

	public $required = 0;

	public $ordering = null;

	public $field_show_in_front = null;

	public $display_in_product = null;

	public $display_in_checkout = null;

	public $published = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'fields', 'field_id', $db);
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
