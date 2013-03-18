<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablepayment_detail extends JTable
{
	public $payment_method_id = null;

	public $payment_method_name = null;

	public $payment_class = null;

	public $payment_method_code = null;

	public $published = null;

	public $is_creditcard = null;

	public $accepted_credict_card = null;

	public $payment_extrainfo = null;

	public $payment_price = null;

	public $payment_discount_is_percent = null;

	public $payment_passkey = null;

	public $params = null;

	public $plugin = null;

	public $ordering = null;

	public $shopper_group = null;

	public $payment_oprand = '+';

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'payment_method', 'payment_method_id', $db);
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
