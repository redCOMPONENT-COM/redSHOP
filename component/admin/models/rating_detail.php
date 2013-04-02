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

class rating_detailModelrating_detail extends JModel
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
			$query = 'SELECT p.*,u.name as username,pr.product_name FROM ' . $this->_table_prefix . 'product as pr, '
				. $this->_table_prefix . 'product_rating as p  left join #__users as u on u.id=p.userid WHERE p.rating_id = '
				. $this->_id . ' and p.product_id = pr.product_id';
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
			$detail->rating_id = null;
			$detail->product_id = null;
			$detail->title = null;
			$detail->comment = null;
			$detail->userid = null;
			$detail->time = null;
			$detail->user_rating = null;
			$detail->favoured = null;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}
		return true;
	}

	public function store($data)
	{
		$row =& $this->getTable();

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

			$query = 'DELETE FROM ' . $this->_table_prefix . 'product_rating WHERE rating_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
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

			$query = 'UPDATE ' . $this->_table_prefix . 'product_rating'
				. ' SET published = ' . intval($publish)
				. ' WHERE rating_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function favoured($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'product_rating'
				. ' SET favoured = ' . intval($publish)
				. ' WHERE rating_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getuserslist()
	{
		$query = 'SELECT u.id as value,u.name as text FROM  #__users as u,' . $this->_table_prefix .
			'users_info ru WHERE u.id=ru.user_id AND ru.address_type like "BT"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getproducts()
	{
		$product_id = JRequest::getVar('pid');

		if ($product_id)
		{
			$query = 'SELECT product_id,product_name FROM ' . $this->_table_prefix . 'product WHERE product_id =' . $product_id;
			$this->_db->setQuery($query);

			return $this->_db->loadObject();
		}
	}

	public function getuserfullname2($uid)
	{
		$query = "SELECT firstname,lastname,username FROM " . $this->_table_prefix . "users_info as uf, #__users as u WHERE user_id="
			. $uid . " AND address_type like 'BT' AND uf.user_id=u.id";
		$this->_db->setQuery($query);
		$this->_username = $this->_db->loadObject();
		$fullname = $this->_username->firstname . " " . $this->_username->lastname . " (" . $this->_username->username . ")";

		return $fullname;
	}
}
