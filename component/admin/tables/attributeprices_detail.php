<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * redSHOP Product Attribute Price table
 *
 * @package     Redshop
 * @subpackage  Attribute Price Detail
 * @since       1.2
 */
class Tableattributeprices_detail extends JTable
{
	public $price_id = 0;

	public $section_id = null;

	public $section = null;

	public $product_price = null;

	public $product_currency = null;

	public $cdate = null;

	public $shopper_group_id = null;

	public $price_quantity_start = 0;

	public $price_quantity_end = 0;

	public $discount_price = 0;

	public $discount_start_date = 0;

	public $discount_end_date = 0;

	/**
	 * Object constructor to set table and key fields.
	 *
	 * @param JDatabase &$db JDatabase connector object.
	 */
	public function __construct(&$db)
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
		$query = 'SELECT price_id FROM ' . $this->_table_prefix . 'product_attribute_price WHERE shopper_group_id = "'
			. $this->shopper_group_id . '" AND section_id = ' . (int) $this->section_id
			. ' AND price_quantity_start <= ' . (int) $this->price_quantity_start
			. ' AND price_quantity_end >= ' . (int) $this->price_quantity_start;

		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());

		$query_end = 'SELECT price_id FROM ' . $this->_table_prefix . 'product_attribute_price WHERE shopper_group_id = "'
			. $this->shopper_group_id . '" AND section_id = ' . (int) $this->section_id
			. ' AND price_quantity_start <= ' . (int) $this->price_quantity_end
			. ' AND price_quantity_end >= ' . (int) $this->price_quantity_end;

		$this->_db->setQuery($query_end);
		$xid_end = intval($this->_db->loadResult());

		if (($xid || $xid_end)
			&& (
				($xid != intval($this->price_id)
				&& $xid != 0)
				|| (
					$xid_end != intval($this->price_id)
					&& $xid_end != 0
				)
			)
		)
		{
			$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRICE_ALREADY_EXISTS'));

			return false;
		}

		return true;
	}
}
