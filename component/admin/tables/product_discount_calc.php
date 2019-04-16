<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 *
	 * @return  boolean
	 */
	public function check()
	{
		$unit = \Redshop\Helper\Utility::getUnitConversation("m", $this->discount_calc_unit);

		$convertedAreaStart = $this->area_start * $unit * $unit;
		$convertedAreaEnd   = $this->area_end * $unit * $unit;

		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_product_discount_calc'))
			->where($db->qn('product_id') . ' = ' . (int) $this->product_id)
			->where(
				'('
				. $convertedAreaStart . ' BETWEEN ' . $db->qn('area_start_converted') . ' AND ' . $db->qn('area_end_converted')
				. ' || ' . $convertedAreaEnd . ' BETWEEN ' . $db->qn('area_start_converted') . ' AND ' . $db->qn('area_end_converted')
				. ')'
			);

		$xid = $db->setQuery($query)->loadResult();

		if ($xid)
		{
			$this->_error = JText::_('COM_REDSHOP_SAME_RANGE');

			return false;
		}

		return true;
	}
}
