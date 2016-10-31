<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Coupon
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       [version> [<description>]
 */

class RedshopModelCoupon extends RedshopModelForm
{
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.coupon.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.coupon', $data);

		return $data;
	}

	/**
	 * [getRemainingCouponAmount function]
	 * 
	 * @return array
	 */
	public function getRemainingCouponAmount()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('coupon_value'))
			->from($db->qn('#__redshop_coupons'))
			->where($db->qn('id') . ' = ' . $this->id);
		$db->setQuery($query);

		return $db->loadResult();
	}

	/*
	public function getuserslist()
	{
		$query = 'SELECT u.id as value,u.name as text FROM  #__users as u,' . $this->_table_prefix
			. 'users_info ru WHERE u.id=ru.user_id AND ru.address_type like "BT"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getproducts()
	{
		$product_id = JRequest::getVar('pid');

		if ($product_id)
		{
			$query = 'SELECT product_id,product_name FROM ' . $this->_table_prefix . 'product WHERE product_id =' . $product_id;
			$this->_db->setQuery($query);

			return $this->_db->loadObject();
		}
	}

	public function getuserfullname2($uid)
	{
		$query = "SELECT firstname,lastname,username FROM " . $this->_table_prefix . "users_info as uf, #__users as u WHERE user_id="
			. $uid . " AND address_type like 'BT' AND uf.user_id=u.id";
		$this->_db->setQuery($query);
		$this->_username = $this->_db->loadObject();
		$fullname = '';

		if ($this->_username)
		{
			$fullname = $this->_username->firstname . " " . $this->_username->lastname . " (" . $this->_username->username . ")";
		}

		return $fullname;
	}

	public function checkduplicate($discount_code)
	{
		$query = "SELECT count(*) as code from " . $this->_table_prefix . "coupons"
			. " LEFT JOIN " . $this->_table_prefix . "product_voucher ON coupon_code=voucher_code"
			. " where voucher_code='" . $discount_code . "' OR coupon_code='" . $discount_code . "'";

		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}
	*/
}
