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

class Tablefields_detail extends JTable
{
	var $field_id = null;
	var $field_title = null;
	var $wysiwyg = null;
	var $field_name = null;
	var $field_type = null;
	var $field_desc = null;
	var $field_class = null;
	var $field_section = null;
	var $field_maxlength = null;
	var $field_size = null;
	var $field_cols = null;
	var $field_rows = null;
	var $required = 0;
	var $ordering = null;
	var $field_show_in_front = null;
	var $display_in_product = null;
	var $display_in_checkout = null;

	var $published = null;

	function Tablefields_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'fields', 'field_id', $db);
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
