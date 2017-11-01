<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableproduct_discount_calc extends JTable
{
	public $id = 0;

	public $product_id = 0;

	public $area_start = 0;

	public $area_end = 0;

	public $area_price = 0;

	public $discount_calc_unit = null;

	public $area_start_converted = 0;

	public $area_end_converted = 0;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_discount_calc', 'id', $db);
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

	/**
	 * Check for the product ID
	 */
	public function check()
	{
		$producthelper = productHelper::getInstance();

		// @todo  $discount_calc_unit is missing here
		$unit = $producthelper->getUnitConversation("m", $discount_calc_unit[$c]);

		$converted_area_start = $this->area_start * $unit * $unit;
		$converted_area_end = $this->area_end * $unit * $unit;

		$query = "SELECT *
					FROM `" . $this->_table_prefix . "product_discount_calc`
					WHERE product_id = " . (int) $this->product_id . " AND (" . (int) $converted_area_start . "
					BETWEEN `area_start_converted`
					AND `area_end_converted` || " . (int) $converted_area_end . "
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
