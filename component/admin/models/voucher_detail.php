<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelVoucher_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

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
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'product_voucher WHERE voucher_id = ' . $this->_id;
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
			$detail->voucher_id = 0;
			$detail->voucher_code = 0;
			$detail->amount = 0;
			$detail->voucher_type = null;
			$detail->start_date = null;
			$detail->end_date = null;
			$detail->free_shipping = 0;
			$detail->voucher_left = 0;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$voucher_id = $row->voucher_id;

		$sql = "delete from " . $this->_table_prefix . "product_voucher_xref where voucher_id='" . $voucher_id . "' ";
		$this->_db->setQuery($sql);
		$this->_db->execute();

		$products_list = explode(',', $data["container_product"]);

		if (count($products_list) > 0)
		{
			foreach ($products_list as $cp)
			{
				$sql = "insert into " . $this->_table_prefix . "product_voucher_xref (voucher_id,product_id) value ('" . $voucher_id . "','" . $cp . "')";
				$this->_db->setQuery($sql);
				$this->_db->execute();
			}
		}

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_voucher WHERE voucher_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'product_voucher'
				. ' SET published = ' . intval($publish)
				. ' WHERE voucher_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function voucher_products_sel($voucher_id)
	{
		$query = "SELECT cp.product_id as value,p.product_name as text FROM " . $this->_table_prefix . "product as p , "
			. $this->_table_prefix . "product_voucher_xref as cp  WHERE cp.voucher_id=" . $voucher_id . " and cp.product_id=p.product_id ";
		$this->_db->setQuery($query);
		$this->_productdata = $this->_db->loadObjectList();

		return $this->_productdata;
	}

	public function checkduplicate($discount_code)
	{
		$query = "SELECT count(*) as code from " . $this->_table_prefix . "coupons"
			. " LEFT JOIN " . $this->_table_prefix . "product_voucher ON coupon_code=voucher_code"
			. " where voucher_code='" . $discount_code . "' OR coupon_code='" . $discount_code . "'";

		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}
}
