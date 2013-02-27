<?php
/**
 * @package redSHOP
 * @subpackage Tables
 *
 * @copyright Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license GNU General Public License version 2 or later, see LICENSE.
 */
defined('JPATH_PLATFORM') or die;

/**
 * redSHOP Product Attribute Price table
 *
 * @package redSHOP
 * @subpackage Attribute Price Detail
 */
class Tableattributeprices_detail extends JTable
{
	var $price_id = 0;
	var $section_id = null;
	var $section = null;
	var $product_price = null;
	var $product_currency = null;
	var $cdate = null;
	var $shopper_group_id = null;
	var $price_quantity_start = 0;
	var $price_quantity_end = 0;
	var $discount_price = 0;
	var $discount_start_date = 0;
	var $discount_end_date = 0;

	/**
	 * Object constructor to set table and key fields.
	 *
	 * @param JDatabase &$db JDatabase connector object.
	 */
	function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix . 'product_attribute_price', 'price_id', $db);
	}

	/**
	 * Method to check user entered valid quantity start and end for shopper group based price.
	 *
	 * @return boolean True on success.
	 */
	public function check()
	{
		$query = 'SELECT price_id FROM ' . $this->_table_prefix . 'product_attribute_price WHERE shopper_group_id = "' . $this->shopper_group_id . '" AND section_id = ' . $this->section_id . ' AND price_quantity_start <= ' . $this->price_quantity_start . ' AND price_quantity_end >= ' . $this->price_quantity_start . '';
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());

		$query_end = 'SELECT price_id FROM ' . $this->_table_prefix . 'product_attribute_price WHERE shopper_group_id = "' . $this->shopper_group_id . '" AND section_id = ' . $this->section_id . ' AND price_quantity_start <= ' . $this->price_quantity_end . ' AND price_quantity_end >= ' . $this->price_quantity_end . '';
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
