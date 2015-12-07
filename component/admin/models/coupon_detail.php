<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelCoupon_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', 0, '', 'array');

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
		$db = JFactory::getDbo();

		if (empty($this->_data))
		{
			$query = 'SELECT * FROM #__redshop_coupons WHERE coupon_id=' . $this->_id;
			$db->setQuery($query);
			$this->_data = $db->loadObject();

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

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM #__redshop_coupons WHERE coupon_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getRemainingCouponAmount()
	{
		$db = JFactory::getDbo();
		$query = 'SELECT coupon_value FROM #__redshop_coupons_transaction WHERE coupon_id =' . $this->_id;
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function publish($cid = array(), $publish = 1)
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE #__redshop_coupons'
				. ' SET published = ' . intval($publish)
				. ' WHERE coupon_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getuserslist()
	{
		$db = JFactory::getDbo();
		$query = 'SELECT u.id as value,u.name as text FROM  #__users as u,#__redshop_users_info ru WHERE u.id=ru.user_id AND ru.address_type like "BT"';
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function getproducts()
	{
		$db = JFactory::getDbo();
		$product_id = JRequest::getVar('pid');

		if ($product_id)
		{
			$query = 'SELECT product_id,product_name FROM #__redshop_product WHERE product_id =' . $product_id;
			$db->setQuery($query);

			return $db->loadObject();
		}
	}

	public function getuserfullname2($uid)
	{
		$db = JFactory::getDbo();
		$query = "SELECT firstname,lastname,username FROM #__redshop_users_info as uf, #__users as u WHERE user_id="
			. $uid . " AND address_type like 'BT' AND uf.user_id=u.id";
		$db->setQuery($query);
		$this->_username = $db->loadObject();
		$fullname = '';

		if ($this->_username)
		{
			$fullname = $this->_username->firstname . " " . $this->_username->lastname . " (" . $this->_username->username . ")";
		}

		return $fullname;
	}

	public function checkduplicate($discount_code)
	{
		$db = JFactory::getDbo();
		$query = "SELECT count(*) as code from #__redshop_coupons"
			. " LEFT JOIN #__redshop_product_voucher ON coupon_code=voucher_code"
			. " where voucher_code='" . $discount_code . "' OR coupon_code='" . $discount_code . "'";

		$db->setQuery($query);

		return $db->loadResult();
	}
}
