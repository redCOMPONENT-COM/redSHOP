<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelSample_detail extends RedshopModel
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
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM #__redshop_catalog_sample WHERE sample_id="' . $this->_id . '" ';
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

			$detail->sample_id = null;
			$detail->sample_name = null;
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

		else
		{
			$total_loop = count($data["colour_id"]);
			$sql = "DELETE FROM #__redshop_catalog_colour "
				. "WHERE sample_id='" . $row->sample_id . "' ";
			$this->_db->setQuery($sql);
			$this->_db->execute();

			if ($total_loop > 0)
			{
				$h = 0;

				foreach ($data["colour_id"] as $cp)
				{
					$sql = "INSERT INTO #__redshop_catalog_colour "
						. "(sample_id,code_image,is_image) "
						. "VALUE ('" . $row->sample_id . "','" . $data["code_image"][$h] . "','" . $data["is_image"][$h] . "') ";
					$this->_db->setQuery($sql);
					$this->_db->execute();
					$h++;
				}
			}
		}

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'DELETE FROM #__redshop_catalog_sample WHERE sample_id IN ( ' . $cids . ' )';
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
			$query = 'UPDATE #__redshop_catalog_sample'
				. ' SET published = ' . intval($publish)
				. ' WHERE sample_id IN ( ' . $cids . ' )';
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
		$query = 'SELECT * FROM #__redshop_catalog_colour '
			. 'WHERE sample_id="' . $sample_id . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}
}
