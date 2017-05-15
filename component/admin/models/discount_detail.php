<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelDiscount_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_shoppers = null;

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
		$layout = JRequest::getVar('layout');

		if (empty($this->_data))
		{
			if (isset($layout) && $layout == 'product')
			{
				$query = 'SELECT * FROM ' . $this->_table_prefix . 'discount_product WHERE discount_product_id = ' . $this->_id;
			}
			else
			{
				$query = 'SELECT * FROM ' . $this->_table_prefix . 'discount WHERE discount_id = ' . $this->_id;
			}

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

			$detail->discount_id = 0;
			$detail->discount_product_id = 0;
			$detail->name = null;
			$detail->condition = 0;
			$detail->shopper_group_id = 0;
			$detail->amount = 0;
			$detail->discount_amount = 0;
			$detail->discount_type = 'no';
			$detail->category_ids = null;
			$detail->start_date = time();
			$detail->end_date = time();
			$detail->published = 1;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row = $this->getTable('discount_detail');

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

		// Remove Relation With Shoppers
		$sdel = "DELETE FROM " . $this->_table_prefix . "discount_shoppers WHERE discount_id = " . $row->discount_id;
		$this->_db->setQuery($sdel);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function delete($cid = array())
	{
		$layout = JRequest::getVar('layout');

		if (count($cid))
		{
			$cids = implode(',', $cid);

			if (isset($layout) && $layout == 'product')
			{
				$query = 'DELETE FROM ' . $this->_table_prefix . 'discount_product WHERE discount_product_id IN ( ' . $cids . ' )';
			}
			else
			{
				$query = 'DELETE FROM ' . $this->_table_prefix . 'discount WHERE discount_id IN ( ' . $cids . ' )';
			}

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
		$layout = JRequest::getVar('layout');

		if (count($cid))
		{
			$cids = implode(',', $cid);

			if (isset($layout) && $layout == 'product')
			{
				$query = 'UPDATE ' . $this->_table_prefix . 'discount_product'
					. ' SET published = ' . intval($publish)
					. ' WHERE discount_product_id IN ( ' . $cids . ' )';
			}
			else
			{
				$query = 'UPDATE ' . $this->_table_prefix . 'discount'
					. ' SET published = ' . intval($publish)
					. ' WHERE discount_id IN ( ' . $cids . ' )';
			}

			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function &getShoppers()
	{
		$query = 'SELECT shopper_group_id as value,shopper_group_name as text FROM ' . $this->_table_prefix . 'shopper_group WHERE published = 1';
		$this->_db->setQuery($query);
		$this->_shoppers = $this->_db->loadObjectList();

		return $this->_shoppers;
	}

	public function selectedShoppers()
	{
		$fieldName = 'discount_id';
		$tableName = 'discount_shoppers';

		if ('product' == JFactory::getApplication()->input->getCmd('layout'))
		{
			$fieldName = 'discount_product_id';
			$tableName = 'discount_product_shoppers';
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('s.shopper_group_id')
					->from($db->qn('#__redshop_' . $tableName, 'ds'))
					->leftjoin(
							$db->qn('#__redshop_shopper_group', 's')
							. ' ON ' . $db->qn('s.shopper_group_id') . ' = ' . $db->qn('ds.shopper_group_id')
						)
					->where($db->qn('ds.' . $fieldName) . ' = ' . (int) $this->_id);

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$result = $db->loadColumn();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $result;
	}

	public function saveShoppers($did, $sids)
	{
		$layout = JRequest::getVar('layout');

		foreach ($sids as $sid)
		{
			if (isset($layout) && $layout == 'product')
			{
				$query = "INSERT INTO #__redshop_discount_product_shoppers VALUES('" . $did . "','" . $sid . "')";
			}
			else
			{
				$query = "INSERT INTO #__redshop_discount_shoppers VALUES('" . $did . "','" . $sid . "')";
			}

			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				return false;
			}
		}

		return true;
	}

	public function storeDiscountProduct($data)
	{
		$dprow = $this->getTable('discount_product');

		if (!$dprow->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$dprow->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// 	Remove Relation With Shoppers
		$del = "DELETE FROM " . $this->_table_prefix . "discount_product_shoppers WHERE discount_product_id = " . $dprow->discount_product_id;
		$this->_db->setQuery($del);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $dprow;
	}
}
