<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelNewslettersubscr_detail extends RedshopModel
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
			$query = 'SELECT ns.*,uf.firstname FROM ' . $this->_table_prefix . 'newsletter_subscription as ns left join '
				. $this->_table_prefix . 'users_info as uf on  ns.user_id = uf.user_id  WHERE ns.subscription_id = ' . $this->_id;
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
			$detail->subscription_id = 0;
			$detail->user_id = 0;
			$detail->date = null;
			$detail->newsletter_id = null;
			$detail->name = null;
			$detail->email = null;
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

		return $row;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'newsletter_subscription WHERE subscription_id IN ( ' . $cids . ' )';
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

			$query = 'UPDATE ' . $this->_table_prefix . 'newsletter_subscription'
				. ' SET published = ' . intval($publish)
				. ' WHERE subscription_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getuserlist()
	{
		$query = 'SELECT user_id as value,firstname as text FROM ' . $this->_table_prefix
			. 'users_info as rdu, #__users as u WHERE  rdu.user_id=u.id AND rdu.address_type LIKE "BT"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getnewsletters()
	{
		$query = 'SELECT newsletter_id as value,name as text FROM ' . $this->_table_prefix . 'newsletter WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getuserfullname2($uid)
	{
		$query = "SELECT firstname,lastname,username FROM " . $this->_table_prefix
			. "users_info as uf LEFT JOIN #__users as u ON (uf.user_id=u.id) WHERE user_id='" . $uid . "' AND uf.address_type like 'BT'";
		$this->_db->setQuery($query);
		$this->_username = $this->_db->loadObject();

		if (count($this->_username) > 0)
		{
			$fullname = $this->_username->firstname . " " . $this->_username->lastname . ($this->_username->username != "" ?
				" (" . $this->_username->username . ")" : ""
			);
		}
		else
		{
			$fullname = "";
		}

		return $fullname;
	}

	public function getnewslettersbsc($subsc = array())
	{
		$where = "";

		if (count($subsc) > 0)
		{
			$sbscids = implode(",", $subsc);
			$where = " AND ns.subscription_id IN (" . $sbscids . ")";
		}

		$query = 'SELECT ns.*,ns.name as subscribername,n.name'
			. ' FROM ' . $this->_table_prefix . 'newsletter_subscription as ns,' . $this->_table_prefix
			. 'newsletter as n WHERE ns.newsletter_id=n.newsletter_id '
			. $where;
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getuserfullname($uid)
	{
		$query = "SELECT uf.firstname,uf.lastname,IFNULL(u.email,uf.user_email)  as email FROM "
			. $this->_table_prefix . "users_info as uf LEFT JOIN #__users as u ON uf.user_id = u.id WHERE uf.user_id='"
			. $uid . "' and uf.address_type like 'BT'";

		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function getUserFromEmail($email)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "users_info AS uf "
			. "WHERE uf.address_type='BT' "
			. "AND uf.user_email='" . $email . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}
}
