<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableproduct_subscribe_detail extends JTable
{
	public $product_subscribe_id = 0;

	public $order_id = 0;

	public $order_item_id = 0;

	public $product_id = 0;

	public $subscription_id = 0;

	public $user_id = 0;

	public $start_date = 0;

	public $end_date = 0;

	public function __construct(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_subscribe_detail', 'product_subscribe_id', $db);
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
