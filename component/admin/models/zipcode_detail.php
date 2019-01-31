<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelZipcode_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JFactory::getApplication()->input->get('cid', 0, 'array');
		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id   = $id;
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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'zipcode WHERE zipcode_id = ' . $this->_id;
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

			$detail->zipcode_id   = 0;
			$detail->city_name    = null;
			$detail->state_code   = null;
			$detail->country_code = null;
			$detail->zipcode      = null;

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		if (empty($data['country_code']) || empty($data['state_code']))
		{
			return false;
		}

		$data['country_code'] = implode(',', $data['country_code']);
		$data['state_code']   = implode(',', $data['state_code']);

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

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'zipcode WHERE zipcode_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getcountry()
	{
		$q = "SELECT  country_3_code as value,country_name as text,country_jtext from #__redshop_country ORDER BY country_name ASC";
		$this->_db->setQuery($q);
		$countries = $this->_db->loadObjectList();

		return RedshopHelperUtility::convertLanguageString($countries);
	}

	/**
	 * Get list state of country
	 *
	 * @param   string $countryCodes Country Codes
	 *
	 * @return  array|boolean
	 *
	 * @since   2.1.2
	 */
	public function getStateList($countryCodes)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select(array($db->quoteName('s.state_name', 'text'), $db->quoteName('s.state_2_code', 'value')))
			->from($db->quoteName('#__redshop_state', 's'))
			->join('LEFT', $db->quoteName('#__redshop_country', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('s.country_id'))
			->where('FIND_IN_SET (' . $db->quoteName('c.country_3_code') . ', ' . $db->quote($countryCodes) . ')')
			->order($db->quoteName('s.state_name') . ' ASC');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Get list state of country
	 *
	 * @param   array $data Data
	 *
	 * @return  array|boolean
	 *
	 * @since   2.1.2
	 */
	public function getStateDropdown($data)
	{
		if (empty($data['country_codes']))
		{
			return array();
		}

		return $this->getStateList($data['country_codes']);
	}
}
