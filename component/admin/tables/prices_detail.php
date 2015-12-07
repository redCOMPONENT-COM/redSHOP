<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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

		parent::__construct('#__redshop_product_price', 'price_id', $db);
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
		$db = JFactory::getDbo();

		$query = 'SELECT price_id FROM #__redshop_product_price WHERE shopper_group_id = "'
			. $this->shopper_group_id . '" AND product_id = ' . (int) $this->product_id
			. ' AND price_quantity_start <= ' . $db->quote($this->price_quantity_start)
			. ' AND price_quantity_end >= ' . $db->quote($this->price_quantity_start) . '';

		$db->setQuery($query);
		$xid = intval($db->loadResult());

		$query_end = 'SELECT price_id FROM #__redshop_product_price WHERE shopper_group_id = "'
			. $this->shopper_group_id . '" AND product_id = ' . (int) $this->product_id
			. ' AND price_quantity_start <= ' . $db->quote($this->price_quantity_end)
			. ' AND price_quantity_end >= ' . $db->quote($this->price_quantity_end) . '';

		$db->setQuery($query_end);
		$xid_end = intval($db->loadResult());

		if (($xid || $xid_end) && (($xid != intval($this->price_id) && $xid != 0) || ($xid_end != intval($this->price_id) && $xid_end != 0)))
		{
			$this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRICE_ALREADY_EXISTS'));

			return false;
		}

		return true;
	}
}
