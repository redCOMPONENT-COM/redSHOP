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

class Tablemass_discount_detail extends JTable
{
	var $mass_discount_id = 0;
	var $discount_name = null;
	var $discount_product = null;
	var $category_id = null;
	var $discount_type = null;
	var $discount_amount = null;
	var $discount_startdate = null;
	var $discount_enddate = null;
	var $manufacturer_id = null;


	function Tablemass_discount_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'mass_discount', 'mass_discount_id', $db);
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