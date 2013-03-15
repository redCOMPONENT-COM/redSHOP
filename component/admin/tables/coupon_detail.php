<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class Tablecoupon_detail extends JTable
{
	var $coupon_id = 0;
	var $coupon_code = 0;
	var $start_date = 0;
	var $end_date = 0;
	var $percent_or_total = 0;
	var $coupon_value = 0;
	var $coupon_type = 0;
	var $subtotal = 0;
	var $userid = 0;
	var $free_shipping = 0;
	var $coupon_left = 0;
	var $published = null;

	function Tablecoupon_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'coupons', 'coupon_id', $db);
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
