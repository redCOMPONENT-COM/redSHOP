<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Coupon
 *
 * @package      RedSHOP.Backend
 * @subpackage  Model
 * @since        2.1.0
 */
class RedshopModelCoupon extends RedshopModelForm
{
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 *
	 * @throws  Exception
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.coupon.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.coupon', $data);

		return $data;
	}

	/**
	 * Method for check duplicate code on voucher and coupon
	 *
	 * @param   string  $discountCode  Discount code.
	 *
	 * @return  integer
	 */
	public function checkDuplicate($discountCode)
	{
		$db = $this->getDbo();

		$voucherQuery = $db->getQuery(true)
			->select($db->qn('code'))
			->from($db->qn('#__redshop_voucher'));

		$couponQuery  = $db->getQuery(true)
			->select($db->qn('code'))
			->from($db->qn('#__redshop_coupons'));

		$couponQuery->union($voucherQuery);

		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from('(' . $couponQuery . ') AS ' . $db->qn('data'))
			->where($db->qn('data.code') . ' = ' . $db->quote($discountCode));

		return $db->setQuery($query)->loadResult();
	}
}
