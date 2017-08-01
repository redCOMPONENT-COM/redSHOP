<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


class RedshopModelRating_detail extends RedshopModelForm
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
		$query = $this->_db->getQuery(true);

		if (empty($this->_data))
		{
			$query->select(array('p.*', 'IFNULL(u.name, p.username) AS username', 'pr.product_name'))
				->from(array($this->_db->qn('#__redshop_product', 'pr'), $this->_db->qn('#__redshop_product_rating', 'p')))
				->join('LEFT', $this->_db->qn('#__users', 'u') . ' ON (' . $this->_db->qn('u.id') . ' = ' . $this->_db->qn('p.userid') . ')')
				->where($this->_db->qn('p.rating_id') . ' = ' . $this->_db->q($this->_id))
				->where($this->_db->qn('p.product_id') . ' = ' . $this->_db->qn('pr.product_id'));

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
		// Set email for existing joomla user
		if (isset($data['userid']) && $data['userid'] > 0)
		{
			$user = JFactory::getUser($data['userid']);
			$data['email']    = $user->email;
			$data['username'] = $user->username;
		}

		$row = $this->getTable();

		// Check if this rate is rated before
		$rtn = $row->load(array('userid' => $data['userid'], 'product_id' => $data['product_id']));

		// This one is not rated before
		if ($rtn === false)
		{
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
		}
		else
		{
			return false;
		}

		return $row;
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   1.6
	 */
	public function delete(&$pks)
	{
		$pks = (array) $pks;

		if (!empty($pks))
		{
			$db = $this->_db;
			$query = $db->getQuery(true)
				->delete($db->qn('#__redshop_product_rating'))
				->where($db->qn('rating_id') . ' IN (' . implode(',', $pks) . ')');

			if (!$db->setQuery($query)->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish(&$pks, $value = 1)
	{
		if (count($pks))
		{
			$cids = implode(',', $pks);

			$query = 'UPDATE ' . $this->_table_prefix . 'product_rating'
				. ' SET published = ' . intval($value)
				. ' WHERE rating_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
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

			if (!$this->_db->execute())
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
