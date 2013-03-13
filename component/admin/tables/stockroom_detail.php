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

class Tablestockroom_detail extends JTable
{
	var $stockroom_id = null;
	var $stockroom_name = null;
	var $min_stock_amount = 0;
	var $stockroom_desc = null;
	var $creation_date = null;
	var $min_del_time = null;
	var $max_del_time = null;
	var $show_in_front = 0;
	var $delivery_time = 'Days';
	var $published = null;

	function Tablestockroom_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'stockroom', 'stockroom_id', $db);
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

}
