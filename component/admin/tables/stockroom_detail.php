<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablestockroom_detail extends JTable
{
	public $stockroom_id = null;

	public $stockroom_name = null;

	public $min_stock_amount = 0;

	public $stockroom_desc = null;

	public $creation_date = null;

	public $min_del_time = null;

	public $max_del_time = null;

	public $show_in_front = 0;

	public $delivery_time = 'Days';

	public $published = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'stockroom', 'stockroom_id', $db);
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
