<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       [version> [<description>]
 */

class RedshopModelCountry extends RedshopModel
{
	public $id = null;

	public $data = null;

	public $tablePrefix = null;

	/**
	 * Construct class
	 * 
	 * @since   1.x
	 */

	public function __construct()
	{
		parent::__construct();

		$this->tablePrefix = '#__redshop_';

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int) $array[0]);
	}

	/**
	 * Function bind data
	 *
	 * @param   int  $id  country id
	 * 
	 * @return  void
	 * 
	 * @since   1.x
	 */

	public function setId($id)
	{
		$this->id = $id;
		$this->data = null;
	}

	/**
	 * Function get Country
	 * 
	 * @return  Object
	 * 
	 * @since   1.x
	 */

	public function &getData()
	{
		if ($this->loadData())
		{
		}
		else
		{
			$this->initData();
		}

		return $this->data;
	}

	/**
	 * Function load data
	 * 
	 * @return  boolean
	 * 
	 * @since   1.x
	 */

	public function loadData()
	{
		if (empty($this->data))
		{
			$query = 'SELECT * FROM ' . $this->tablePrefix . 'country WHERE id = ' . $this->id;
			$this->_db->setQuery($query);
			$this->data = $this->_db->loadObject();

			return (boolean) $this->data;
		}

		return true;
	}

	/**
	 * Function init data
	 * 
	 * @return  boolean
	 * 
	 * @since   1.x
	 */

	public function _initData()
	{
		if (empty($this->data))
		{
			$detail = new stdClass;

			$detail->id = 0;
			$detail->country_name = null;
			$detail->country_3_code = null;
			$detail->country_2_code = null;
			$detail->country_jtext = null;
			$this->data = $detail;

			return (boolean) $this->data;
		}

		return true;
	}

	/**
	 * Function store data
	 *
	 * @param   object  $data  Country instance
	 * 
	 * @return  boolean / object
	 * 
	 * @since   1.x
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
	 * Function bind data
	 *
	 * @param   array  $cid  array country ids
	 * 
	 * @return  boolean
	 * 
	 * @since   1.x
	 */

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->tablePrefix . 'country WHERE id IN ( ' . $cids . ' )';
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
