<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelCoupon_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JFactory::getApplication()->input->get('cid', 0, 'array');

		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		if ($this->_loadData())
		{
		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'coupons WHERE coupon_id=' . $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->coupon_id = null;
			$detail->coupon_code = null;
			$detail->start_date = 0;
			$detail->end_date = 0;
			$detail->percent_or_total = null;
			$detail->free_shipping = 0;
			$detail->coupon_value = null;
			$detail->coupon_type = null;
			$detail->subtotal = null;
			$detail->userid = null;
			$detail->coupon_left = null;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	/**
	 * Store an coupon record
	 *
	 * @param   array  $data  Data
	 *
	 * @return  bool|object
	 *
	 * @since   2.0.2
	 */
	public function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->check())
		{
			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'coupons WHERE coupon_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getRemainingCouponAmount()
	{
		$query = 'SELECT coupon_value FROM ' . $this->_table_prefix . 'coupons_transaction WHERE coupon_id =' . $this->_id;
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'coupons'
				. ' SET published = ' . intval($publish)
				. ' WHERE coupon_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getuserslist()
	{
		$query = 'SELECT u.id as value,u.name as text FROM  #__users as u,' . $this->_table_prefix
			. 'users_info ru WHERE u.id=ru.user_id AND ru.address_type like "BT"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getproducts()
	{
		$product_id = JFactory::getApplication()->input->get('pid');

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
		$couponQuery = $db->getQuery(true)
			->select($db->qn('coupon_code', 'code'))
			->from($db->qn('#__redshop_coupons'));
		$couponQuery->union($voucherQuery);

		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from('(' . $couponQuery . ') AS ' . $db->qn('data'))
			->where($db->qn('data.code') . ' = ' . $db->quote($discountCode));

		return $db->setQuery($query)->loadResult();
	}
}
