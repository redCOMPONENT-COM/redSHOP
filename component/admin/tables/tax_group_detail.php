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

class Tabletax_group_detail extends JTable
{
	var $tax_group_id = null;
	var $tax_group_name = null;
	var $published = null;


	function Tabletax_group_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'tax_group', 'tax_group_id', $db);
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