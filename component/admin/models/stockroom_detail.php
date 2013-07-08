<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class stockroom_detailModelstockroom_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_copydata = null;

	public $_containerdata = null;

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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'stockroom WHERE stockroom_id = ' . $this->_id;
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
			$detail                   = new stdClass;
			$detail->stockroom_id     = 0;
			$detail->stockroom_name   = null;
			$detail->stockroom_desc   = null;
			$detail->creation_date    = null;
			$detail->min_del_time     = 0;
			$detail->max_del_time     = 0;
			$detail->min_stock_amount = 0;
			$detail->show_in_front    = 0;
			$detail->delivery_time    = 'Days';
			$detail->published        = 1;
			$this->_data              = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row =& $this->getTable();

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

		$stockroom_id = $row->stockroom_id;

		$sql = "delete from " . $this->_table_prefix . "stockroom_container_xref where stockroom_id='" . $stockroom_id . "' ";
		$this->_db->setQuery($sql);
		$this->_db->query();

		$stockroom_product = $data["container_product"];

		if (count($stockroom_product) > 0)
		{
			foreach ($stockroom_product as $cp)
			{
				$sql = "insert into " . $this->_table_prefix . "stockroom_container_xref (stockroom_id,container_id) value ('"
					. $stockroom_id . "','" . $cp . "')";
				$this->_db->setQuery($sql);
				$this->_db->query();
			}
		}

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query_product = 'DELETE FROM ' . $this->_table_prefix . 'product_stockroom_xref WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query_product);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			// Delete stock of products attribute stock
			$query_product_attr = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_stockroom_xref WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query_product_attr);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			// Delete stockroom
			$query = 'DELETE FROM ' . $this->_table_prefix . 'stockroom WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
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

			$query = 'UPDATE ' . $this->_table_prefix . 'stockroom'
				. ' SET published = ' . intval($publish)
				. ' WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function frontpublish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'stockroom'
				. ' SET `show_in_front` = ' . intval($publish)
				. ' WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function copy($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'SELECT * FROM ' . $this->_table_prefix . 'stockroom WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);
			$this->_copydata = $this->_db->loadObjectList();
		}

		foreach ($this->_copydata as $cdata)
		{
			$post['stockroom_id'] = 0;
			$post['stockroom_name'] = 'Copy Of ' . $cdata->stockroom_name;
			$post['stockroom_desc'] = $cdata->stockroom_desc;
			$post['min_del_time'] = $cdata->min_del_time;
			$post['max_del_time'] = $cdata->max_del_time;
			$post['delivery_time'] = $cdata->delivery_time;
			$post['show_in_front'] = $cdata->show_in_front;
			$post['creation_date'] = time();
			$post['published'] = $cdata->published;
			$this->store($post);
		}

		return true;
	}

	public function stock_product_data($stockroom_id)
	{
		$query = "SELECT cp.container_id as value,p.container_name as text FROM " . $this->_table_prefix . "container as p , "
			. $this->_table_prefix . "stockroom_container_xref as cp  WHERE cp.stockroom_id=$stockroom_id and cp.container_id=p.container_id ";
		$this->_db->setQuery($query);
		$this->_productdata = $this->_db->loadObjectList();

		return $this->_productdata;
	}

	public function stock_product($container_id)
	{
		$query = "SELECT DISTINCT p.product_id as pid,p.product_name,p.product_number,p.product_volume,cp.quantity "
			. "FROM " . $this->_table_prefix . "container as c , " . $this->_table_prefix . "stockroom_container_xref as sc,
	 		" . $this->_table_prefix . "container_product_xref as cp ," . $this->_table_prefix . "product as p
	 		WHERE cp.product_id=p.product_id and cp.container_id=c.container_id and  c.container_id=$container_id and sc.container_id=c.container_id ";
		$this->_db->setQuery($query);
		$this->_productdata = $this->_db->loadObjectList();

		return $this->_productdata;
	}

	public function stock_container($stockroom_id)
	{
		if ($stockroom_id != 0)
		{
			$query = "SELECT DISTINCT c.container_id ,c.container_name FROM " . $this->_table_prefix . "container AS c "
				. ", " . $this->_table_prefix . "stockroom_container_xref AS sc "
				. "WHERE sc.stockroom_id='" . $stockroom_id . "' "
				. "AND sc.container_id=c.container_id ";
		}
		else
		{
			$query = "SELECT DISTINCT c.container_id,c.container_name,s.stockroom_name FROM " . $this->_table_prefix . "container AS c "
				. ", " . $this->_table_prefix . "stockroom_container_xref AS sc "
				. "," . $this->_table_prefix . "stockroom AS s "
				. "WHERE sc.container_id=c.container_id "
				. "AND s.stockroom_id = sc.stockroom_id";
		}

		$this->_db->setQuery($query);
		$this->_containerdata = $this->_db->loadObjectList();

		return $this->_containerdata;
	}

	public function getStockRoomList()
	{
		$query = 'SELECT s.stockroom_id AS value, s.stockroom_name AS text,s.* FROM ' . $this->_table_prefix . 'stockroom AS s ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}
}
