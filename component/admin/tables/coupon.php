<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Coupon
 *
 * @package      RedSHOP.Backend
 * @subpackage  Table
 * @since        2.1.0
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
	 * @var  integer
	 */
	public $id;

	/**
	 * @var  string
	 */
	public $code;

	/**
	 * @var  integer
	 */
	public $type = 0;

	/**
	 * @var  float
	 */
	public $value = 0.00;

	/**
	 * @var  string
	 */
	public $start_date = '0000-00-00 00:00:00';

	/**
	 * @var  string
	 */
	public $end_date = '0000-00-00 00:00:00';

	/**
	 * @var  integer
	 */
	public $effect = 0;

	/**
	 * @var  integer
	 */
	public $userid;

	/**
	 * @var  integer
	 */
	public $amount_left;

	/**
	 * @var  integer
	 */
	public $published;

	/**
	 * @var  integer
	 */
	public $subtotal;

	/**
	 * @var  integer
	 */
	public $order_id;

	/**
	 * @var  integer
	 */
	public $free_shipping;

	/**
	 * @var  integer
	 */
	public $created_by;

	/**
	 * @var  string
	 */
	public $created_date = '0000-00-00 00:00:00';

	/**
	 * @var  integer
	 */
	public $checked_out;

	/**
	 * @var  string
	 */
	public $checked_out_time = '0000-00-00 00:00:00';

	/**
	 * @var  integer
	 */
	public $modified_by;

	/**
	 * @var  string
	 */
	public $modified_date = '0000-00-00 00:00:00';

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		if (!parent::doCheck())
		{
			return false;
		}

		if (empty($this->code))
		{
			return false;
		}

		if (empty($this->value))
		{
			return false;
		}

		$db = $this->getDbo();

		// Check duplicate.
		$code = $this->get('code');

		$voucherQuery = $db->getQuery(true)
			->select($db->qn('code'))
			->from($db->qn('#__redshop_voucher'));

		$couponQuery = $db->getQuery(true)
			->select($db->qn('code'))
			->from($db->qn('#__redshop_coupons'));

		if ($this->hasPrimaryKey())
		{
			$couponQuery->where($db->qn('id') . ' <> ' . $this->id);
		}

		$couponQuery->union($voucherQuery);

		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from('(' . $couponQuery . ') AS ' . $db->qn('data'))
			->where($db->qn('data.code') . ' = ' . $db->quote($code));

		if ($db->setQuery($query)->loadResult())
		{
			$this->setError(JText::_('COM_REDSHOP_COUPON_ERROR_CODE_ALREADY_EXIST'));

			return false;
		}

		return true;
	}
}
