<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablemass_discount_detail extends JTable
{
	public $mass_discount_id = 0;

	public $discount_name = null;

	public $discount_product = null;

	public $category_id = null;

	public $discount_type = null;

	public $discount_amount = null;

	public $discount_startdate = null;

	public $discount_enddate = null;

	public $manufacturer_id = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'mass_discount', 'mass_discount_id', $db);
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
}
