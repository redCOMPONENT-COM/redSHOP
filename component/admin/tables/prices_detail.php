<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableprices_detail extends JTable
{
	public $price_id = 0;

	public $product_id = null;

	public $product_price = null;

	public $product_currency = null;

	public $shopper_group_id = null;

	public $price_quantity_start = 0;

	public $price_quantity_end = 0;

	public $cdate = null;

	public $discount_price = 0;

	public $discount_start_date = 0;

	public $discount_end_date = 0;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix . 'product_price', 'price_id', $db);
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

	public function check()
	{
		/**** check for valid name *****/
		$query = 'SELECT price_id FROM ' . $this->_table_prefix . 'product_price WHERE shopper_group_id = "'
			. $this->shopper_group_id . '" AND product_id = ' . (int) $this->product_id
			. ' AND price_quantity_start <= ' . $this->_db->quote($this->price_quantity_start)
			. ' AND price_quantity_end >= ' . $this->_db->quote($this->price_quantity_start) . '';

		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());

		$query_end = 'SELECT price_id FROM ' . $this->_table_prefix . 'product_price WHERE shopper_group_id = "'
			. $this->shopper_group_id . '" AND product_id = ' . (int) $this->product_id
			. ' AND price_quantity_start <= ' . $this->_db->quote($this->price_quantity_end)
			. ' AND price_quantity_end >= ' . $this->_db->quote($this->price_quantity_end) . '';

		$this->_db->setQuery($query_end);
		$xid_end = intval($this->_db->loadResult());

		if (($xid || $xid_end) && (($xid != intval($this->price_id) && $xid != 0) || ($xid_end != intval($this->price_id) && $xid_end != 0)))
		{
			$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRICE_ALREADY_EXISTS'));

			return false;
		}

		return true;
	}
}
