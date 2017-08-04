<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablequotation_accessory_item extends JTable
{
	public $quotation_item_acc_id = null;

	public $quotation_item_id = null;

	public $accessory_id = null;

	public $accessory_item_sku = null;

	public $accessory_item_name = null;

	public $accessory_price = null;

	public $accessory_vat = null;

	public $accessory_quantity = null;

	public $accessory_item_price = null;

	public $accessory_final_price = null;

	public $accessory_attribute = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'quotation_accessory_item', 'quotation_item_acc_id', $db);
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
