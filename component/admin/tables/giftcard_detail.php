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

class Tablegiftcard_detail extends JTable
{
	var $giftcard_id = null;
	var $giftcard_name = null;
	var $giftcard_validity = null;
	var $giftcard_date = 0;
	var $giftcard_price = 0;
	var $giftcard_value = 0;
	var $giftcard_bgimage = null;
	var $giftcard_image = null;
	var $published = null;
	var $giftcard_desc = null;
	var $customer_amount = 0;
	var $accountgroup_id = 0;

	function Tablegiftcard_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'giftcard', 'giftcard_id', $db);
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
