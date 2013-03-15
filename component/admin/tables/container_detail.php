<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class Tablecontainer_detail extends JTable
{
	var $container_id = null;
	var $container_name = null;
	var $creation_date = null;
	var $container_desc = null;
	var $min_del_time = null;
	var $max_del_time = null;
	var $container_volume = null;
	var $stockroom_id = null;
	var $manufacture_id = null;
	var $supplier_id = null;
	var $published = null;

	function Tablecontainer_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'container', 'container_id', $db);
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
?>
