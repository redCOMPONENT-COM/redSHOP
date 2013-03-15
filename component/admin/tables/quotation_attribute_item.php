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

class Tablequotation_attribute_item extends JTable
{
	var $quotation_att_item_id = null;
	var $quotation_item_id = null;
	var $section_id = null;
	var $section = null;
	var $parent_section_id = null;
	var $section_name = null;
	var $section_vat = null;
	var $section_price = null;
	var $section_oprand = null;
	var $is_accessory_att = null;


	function Tablequotation_attribute_item(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'quotation_attribute_item', 'quotation_att_item_id', $db);
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