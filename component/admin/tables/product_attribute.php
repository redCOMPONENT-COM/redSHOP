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

class Tableproduct_attribute extends JTable
{
	var $attribute_id = null;
	var $attribute_set_id = 0;
	var $attribute_name = null;
	var $attribute_required = null;
	var $allow_multiple_selection = 0;
	var $hide_attribute_price = 0;
	var $product_id = null;
	var $ordering = null;
	var $attribute_published = 1;
	var $display_type = null;

	function Tableproduct_attribute(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_attribute', 'attribute_id', $db);
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
