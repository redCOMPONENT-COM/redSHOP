<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.0.4
 */

class RedshopTableCoupon extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */

	protected $_tableName = 'redshop_coupons';

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
