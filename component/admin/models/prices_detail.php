<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

class RedshopModelPrices_detail extends RedshopModel
{
	public $_id = null;

	public $_prodid = null;

	public $_prodname = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->_prodid = JRequest::getVar('product_id', 0, '', 'int');

		$this->setId((int) $array[0]);
		$this->setProductName();
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function setProductName()
	{
		$query = ' SELECT prd.product_name '
			. ' FROM ' . $this->_table_prefix . 'product as prd '
			. ' WHERE prd.product_id = ' . $this->_prodid;
		$this->_db->setQuery($query);
		$this->_prodname = $this->_db->loadObject()->product_name;
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
			$query = ' SELECT p.*, '
				. ' g.shopper_group_name, prd.product_name '
				. ' FROM ' . $this->_table_prefix . 'product_price as p '
				. ' LEFT JOIN ' . $this->_table_prefix . 'shopper_group as g ON p.shopper_group_id = g.shopper_group_id '
				. ' LEFT JOIN ' . $this->_table_prefix . 'product as prd ON p.product_id = prd.product_id '
				. ' WHERE p.price_id = ' . $this->_id;
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
			$detail->price_id = 0;
			$detail->product_id = $this->_prodid;
			$detail->product_name = $this->_prodname;
			$detail->product_price = 0.00;
			$detail->product_currency = null;
			$detail->shopper_group_id = 0;
			$detail->price_quantity_start = 0;
			$detail->price_quantity_end = 0;
			$detail->shopper_group_name = null;
			$detail->discount_price = 0;
			$detail->discount_start_date = 0;
			$detail->discount_end_date = 0;
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

		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_price WHERE price_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
