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

require_once JPATH_COMPONENT . '/helpers/thumbnail.php';
jimport('joomla.client.helper');
JClientHelper::setCredentialsFromRequest('ftp');
jimport('joomla.filesystem.file');

/**
 * tax_group_detailModeltax_group_detail
 *
 * @package     RedSHOP
 * @subpackage  Model
 * @since       1.0
 */
class tax_group_detailModeltax_group_detail extends JModel
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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'tax_group WHERE tax_group_id = ' . $this->_id;
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
			$detail->tax_group_id = 0;
			$detail->tax_group_name = null;
			$detail->published = 0;
			$detail->tax_rate = null;

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

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
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

			$query = 'DELETE FROM ' . $this->_table_prefix . 'tax_group WHERE tax_group_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	/**
	 * publish
	 *
	 * @param $cid
	 *
	 */
	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'tax_group'
				. ' SET published = ' . intval($publish)
				. ' WHERE tax_group_id IN ( ' . $cids . ' )';

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
