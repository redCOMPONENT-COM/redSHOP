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

class Tableproduct_discount_calc extends JTable
{
	var $id = 0;
	var $product_id = 0;
	var $area_start = 0;
	var $area_end = 0;
	var $area_price = 0;
	var $discount_calc_unit = null;
	var $area_start_converted = 0;
	var $area_end_converted = 0;


	function Tableproduct_discount_calc(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_discount_calc', 'id', $db);
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

	/**
	 * Check for the product ID
	 */
	function check()
	{
		$producthelper = new producthelper();

		$unit = 1;
		$unit = $producthelper->getUnitConversation("m", $discount_calc_unit[$c]);

		# updating value
		$converted_area_start = $this->area_start * $unit * $unit;
		$converted_area_end = $this->area_end * $unit * $unit;
		# End

		/**** check for valid area *****/
		/*$query = 'SELECT id FROM '.$this->_table_prefix.'product_discount_calc '
				.' WHERE product_id = "'.$this->product_id.'" '
				.' AND area_end >= '.$this->area_start;*/

		$query = "SELECT *
					FROM `" . $this->_table_prefix . "product_discount_calc`
					WHERE product_id = " . $this->product_id . " AND (" . $converted_area_start . "
					BETWEEN `area_start_converted`
					AND `area_end_converted` || " . $converted_area_end . "
					BETWEEN `area_start_converted`
					AND `area_end_converted` )";

		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if ($xid)
		{
			$this->_error = JText::_('COM_REDSHOP_SAME_RANGE');
			return false;
		}
		return true;
	}
}