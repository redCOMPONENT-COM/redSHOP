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

class Tablenotifystock_user extends JTable
{
	var $id = null;
	var $product_id = null;
	var $property_id = null;
	var $subproperty_id = null;
	var $user_id = null;
	var $notification_status = null;

	function Tablenotifystock_user(& $db)
	{
		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix . 'notifystock_users', 'id', $db);
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
