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
 * sample_catalogModelsample_catalog
 *
 * @package     RedSHOP
 * @subpackage  Model
 * @since       1.0
 */
class sample_catalogModelsample_catalog extends JModel
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
	 * getsample
	 *
	 * @param $sample
	 *
	 */
	public function getsample($sample)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'catalog_colour as c left join ' . $this->_table_prefix .
			'catalog_sample as s on s.sample_id=c.sample_id WHERE colour_id in (' . $sample . ')';
		$this->_db->setQuery($query);
		$sample_data = $this->_db->loadObjectlist();

		return $sample_data;
	}

	/**
	 * _loadData
	 */
	public function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'sample_request WHERE request_id=' . $this->_id;
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
			$detail->sample_id = null;
			$detail->sample_name = null;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}
}
