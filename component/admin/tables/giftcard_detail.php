<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablegiftcard_detail extends JTable
{
	public $giftcard_id = null;

	public $giftcard_name = null;

	public $giftcard_validity = null;

	public $giftcard_date = 0;

	public $giftcard_price = 0;

	public $giftcard_value = 0;

	public $giftcard_bgimage = null;

	public $giftcard_image = null;

	public $published = null;

	public $giftcard_desc = null;

	public $customer_amount = 0;

	public $accountgroup_id = 0;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'giftcard', 'giftcard_id', $db);
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
