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

class state_detailModelstate_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';

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
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'state WHERE state_id = ' . $this->_id;
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

			$detail->state_id = 0;
			$detail->state_name = null;
			$detail->state_3_code = null;
			$detail->country_id = null;
			$detail->state_2_code = null;
			$detail->show_state = 2;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{

		$row =& $this->getTable('state_detail');

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

			$query = 'DELETE FROM ' . $this->_table_prefix . 'state WHERE state_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getcountry()
	{
		require_once JPATH_COMPONENT_SITE . '/helpers/helper.php';
		$redhelper = new redhelper;
		$q = "SELECT  country_3_code as value,country_name as text,country_jtext from #__" . TABLE_PREFIX . "_country ORDER BY 					    	country_name ASC";
		$this->_db->setQuery($q);
		$countries = $this->_db->loadObjectList();
		$countries = $redhelper->convertLanguageString($countries);

		return $countries;
	}

	/**
	 * Method to checkout/lock the state_detail
	 *
	 *  @param   int  $uid  User ID of the user checking the helloworl detail out.
	 *
	 *  @return   boolean True on success.
	 */
	public function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid))
			{
				$user = JFactory::getUser();
				$uid = (int) $user->get('id');
			}

			// Lets get to it and checkout the thing...
			$state_detail = $this->getTable('state_detail');

			if (!$state_detail->checkout($uid, $this->_id))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * Method to checkin/unlock the state_detail
	 *
	 * @access    public
	 * @return    boolean    True on success
	 * @since    1.5
	 */
	public function checkin()
	{
		if ($this->_id)
		{
			$state_detail = & $this->getTable('state_detail');

			if (!$state_detail->checkin($this->_id))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return false;
	}

	/**
	 * Tests if state_detail is checked out
	 *
	 * @access    public
	 *
	 * @param    int    A user id
	 *
	 * @return    boolean    True if checked out
	 * @since    1.5
	 */
	public function isCheckedOut($uid = 0)
	{
		if ($this->_loadData())
		{
			if ($uid)
			{
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			}
			else
			{
				return $this->_data->checked_out;
			}
		}
	}
}
