<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelCatalog_detail extends RedshopModel
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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'catalog WHERE catalog_id=' . $this->_id;

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

			$detail->catalog_id = null;
			$detail->catalog_name = null;

			$detail->published = 1;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row = $this->getTable('catalog');

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

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'catalog WHERE catalog_id IN ( ' . $cids . ' )';

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


			$query = 'UPDATE ' . $this->_table_prefix . 'catalog'
				. ' SET published = ' . intval($publish)
				. ' WHERE catalog_id IN ( ' . $cids . ' )';

			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function color_Data($sample_id)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'catalog_colour  WHERE sample_id=' . $sample_id;
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}
}
