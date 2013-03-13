<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
jimport('joomla.application.component.model');
class Tableprices_detail extends JTable
{
	var $price_id = 0;
	var $product_id = null;
	var $product_price = null;
	var $product_currency = null;
	var $shopper_group_id = null;
	var $price_quantity_start = 0;
	var $price_quantity_end = 0;
	var $cdate = null;
	var $discount_price = 0;
	var $discount_start_date = 0;
	var $discount_end_date = 0;

	function Tableprices_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix . 'product_price', 'price_id', $db);
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

	function check()
	{
		/**** check for valid name *****/
		$query = 'SELECT price_id FROM ' . $this->_table_prefix . 'product_price WHERE shopper_group_id = "' . $this->shopper_group_id . '" AND product_id = ' . $this->product_id . ' AND price_quantity_start <= ' . $this->price_quantity_start . ' AND price_quantity_end >= ' . $this->price_quantity_start . '';
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());

		$query_end = 'SELECT price_id FROM ' . $this->_table_prefix . 'product_price WHERE shopper_group_id = "' . $this->shopper_group_id . '" AND product_id = ' . $this->product_id . ' AND price_quantity_start <= ' . $this->price_quantity_end . ' AND price_quantity_end >= ' . $this->price_quantity_end . '';
		$this->_db->setQuery($query_end);
		$xid_end = intval($this->_db->loadResult());

		if (($xid || $xid_end) && ($xid != intval($this->price_id) || $xid_end != intval($this->price_id)))
		{
			$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRICE_ALREADY_EXISTS'));
			return false;
		}
		return true;
	}
}

?>