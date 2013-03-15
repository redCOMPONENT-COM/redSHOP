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

class Tablequotation_accessory_item extends JTable
{
	var $quotation_item_acc_id = null;
	var $quotation_item_id = null;
	var $accessory_id = null;
	var $accessory_item_sku = null;
	var $accessory_item_name = null;
	var $accessory_price = null;
	var $accessory_vat = null;
	var $accessory_quantity = null;
	var $accessory_item_price = null;
	var $accessory_final_price = null;
	var $accessory_attribute = null;


	function Tablequotation_accessory_item(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'quotation_accessory_item', 'quotation_item_acc_id', $db);
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