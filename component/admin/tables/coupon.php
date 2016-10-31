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
 * Table Coupon
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
	 * Field name to publish/unpublish/trash table registers. Ex: state
	 *
	 * @var  string
	 */
	protected $_tableFieldState = 'published';

	/**
	 * Method to store a node in the database table.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean  True on success.
	 */
	public function doStore($updateNulls = false)
	{
		$startDate = new JDate($this->start_date);
		$this->start_date = $startDate->toUnix();

		$endDate = new JDate($this->end_date);
		$this->end_date = $endDate->toUnix();

		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		return true;
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

		if ($this->start_date > $this->end_date)
		{
			return false;
		}

		return parent::check();
	}
}
