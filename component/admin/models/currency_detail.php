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

/**
 * currency_detailModelcurrency_detail
 *
 * @package     RedSHOP
 * @subpackage  Model
 * @since       1.0
 */
class currency_detailModelcurrency_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	/**
	 * __construct
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int) $array[0]);
	}

	/**
	 * setId
	 *
	 * @param $id
	 *
	 */
	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	/**
	 * getData
	 */
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

	/**
	 * _loadData
	 */
	public function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'currency WHERE currency_id = ' . $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}

	/**
	 * _initData
	 */
	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;

			$detail->currency_id = 0;
			$detail->currency_name = null;
			$detail->currency_code = null;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	/**
	 * store
	 *
	 * @param $data
	 *
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

	/**
	 * delete
	 *
	 * @param $cid
	 *
	 */
	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'currency WHERE currency_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
