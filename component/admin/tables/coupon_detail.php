<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablecoupon_detail extends JTable
{
	public $coupon_id = 0;

	public $coupon_code = 0;

	public $start_date = 0;

	public $end_date = 0;

	public $percent_or_total = 0;

	public $coupon_value = 0;

	public $coupon_type = 0;

	public $subtotal = 0;

	public $userid = 0;

	public $free_shipping = 0;

	public $coupon_left = 0;

	public $published = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'coupons', 'coupon_id', $db);
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

	/**
	 * Validate all fields
	 *
	 * @return  bool
	 *
	 * @since  2.0.2
	 */
	public function check ()
	{
		if (empty($this->coupon_code))
		{
			return false;
		}

		return parent::check();
	}
}
