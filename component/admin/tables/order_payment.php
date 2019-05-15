<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableorder_payment extends JTable
{
	public $payment_order_id = null;

	public $order_id = null;

	public $payment_method_id = null;

	public $order_payment_code = null;

	public $order_payment_cardname = null;

	public $order_payment_number = null;

	public $order_payment_ccv = null;

	public $order_payment_expire = null;

	public $order_payment_name = null;

	public $order_payment_trans_id = null;

	public $order_payment_amount = null;

	public $authorize_status = null;

	public $payment_method_class;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'order_payment', 'payment_order_id', $db);
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
