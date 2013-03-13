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

class Tabletax_detail extends JTable
{
	var $tax_rate_id = null;
	var $tax_state = null;
	var $tax_country = null;
	var $mdate = null;
	var $tax_rate = null;
	var $tax_group_id = null;
	var $is_eu_country = 0;

	function Tabletax_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'tax_rate', 'tax_rate_id', $db);
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