<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableaccountgroup_detail extends JTable
{
	public $accountgroup_id = null;

	public $accountgroup_name = null;

	public $economic_vat_account = null;

	public $economic_nonvat_account = null;

	public $economic_discount_vat_account = null;

	public $economic_discount_nonvat_account = null;

	public $economic_shipping_vat_account = null;

	public $economic_shipping_nonvat_account = null;

	public $economic_discount_product_number = null;

	public $published = 1;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'economic_accountgroup', 'accountgroup_id', $db);
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

