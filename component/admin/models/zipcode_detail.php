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


class zipcode_detailModelzipcode_detail extends JModel
{
	public $_id = null;
	public $_data = null;
	public $_table_prefix = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int) $array[0]);

	}

	function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	function &getData()
	{
		if ($this->_loadData())
		{

		}
		else  $this->_initData();

		return $this->_data;
	}

	function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'zipcode WHERE zipcode_id = ' . $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}


	function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass();

			$detail->zipcode_id = 0;
			$detail->city_name = null;
			$detail->state_code = null;
			$detail->country_code = null;
			$detail->zipcode = null;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	function store($data)
	{

		$row =& $this->getTable();


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

	function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'zipcode WHERE zipcode_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);
			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	function getcountry()
	{
		require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'helper.php');
		$redhelper = new redhelper();
		$q = "SELECT  country_3_code as value,country_name as text,country_jtext from #__" . TABLE_PREFIX . "_country ORDER BY country_name ASC";
		$this->_db->setQuery($q);
		$countries = $this->_db->loadObjectList();
		$countries = $redhelper->convertLanguageString($countries);
		return $countries;
	}

}

?>
