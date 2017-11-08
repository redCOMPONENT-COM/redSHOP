<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelStockroom_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_copydata = null;

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

		$stockroom_id = $row->stockroom_id;

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query_product = 'DELETE FROM ' . $this->_table_prefix . 'product_stockroom_xref WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query_product);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			// Delete stock of products attribute stock
			$query_product_attr = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_stockroom_xref WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query_product_attr);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			// Delete stockroom
			$query = 'DELETE FROM ' . $this->_table_prefix . 'stockroom WHERE stockroom_id IN ( ' . $cids . ' )';
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

			$query = 'UPDATE ' . $this->_table_prefix . 'stockroom'
				. ' SET published = ' . intval($publish)
				. ' WHERE stockroom_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
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

			if (!$this->_db->execute())
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
			$post['stockroom_name'] = $this->renameToUniqueValue('stockroom_name', $cdata->stockroom_name);
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

	public function getStockRoomList()
	{
		$query = 'SELECT s.stockroom_id AS value, s.stockroom_name AS text,s.* FROM ' . $this->_table_prefix . 'stockroom AS s ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}
}
