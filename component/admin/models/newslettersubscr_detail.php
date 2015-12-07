<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelNewslettersubscr_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public function __construct()
	{
		parent::__construct();

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
		$db = JFactory::getDbo();

		if (empty($this->_data))
		{
			$query = 'SELECT ns.*,uf.firstname FROM #__redshop_newsletter_subscription as ns left join '
				. '#__redshop_users_info as uf on  ns.user_id = uf.user_id  WHERE ns.subscription_id = ' . $this->_id;
			$db->setQuery($query);
			$this->_data = $db->loadObject();

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

	public function delete($cid = array())
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM #__redshop_newsletter_subscription WHERE subscription_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		$db = JFactory::getDbo();

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE #__redshop_newsletter_subscription'
				. ' SET published = ' . intval($publish)
				. ' WHERE subscription_id IN ( ' . $cids . ' )';
			$db->setQuery($query);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getuserlist()
	{
		$db = JFactory::getDbo();

		$query = 'SELECT user_id as value,firstname as text FROM #__redshop_users_info as rdu, #__users as u WHERE  rdu.user_id=u.id AND rdu.address_type LIKE "BT"';
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function getnewsletters()
	{
		$db = JFactory::getDbo();

		$query = 'SELECT newsletter_id as value,name as text FROM #__redshop_newsletter WHERE published=1';
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function getuserfullname2($uid)
	{
		$db = JFactory::getDbo();
		$query = "SELECT firstname,lastname,username FROM #__redshop_users_info as uf LEFT JOIN #__users as u ON (uf.user_id=u.id) WHERE user_id='" . $uid . "' AND uf.address_type like 'BT'";
		$db->setQuery($query);
		$this->_username = $db->loadObject();

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
		$db = JFactory::getDbo();
		$where = "";

		if (count($subsc) > 0)
		{
			$sbscids = implode(",", $subsc);
			$where = " AND ns.subscription_id IN (" . $sbscids . ")";
		}

		$query = 'SELECT ns.*,ns.name as subscribername,n.name'
			. ' FROM #__redshop_newsletter_subscription as ns,#__redshop_newsletter as n WHERE ns.newsletter_id=n.newsletter_id '
			. $where;
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function getuserfullname($uid)
	{
		$db = JFactory::getDbo();
		$query = "SELECT uf.firstname,uf.lastname,IFNULL(u.email,uf.user_email)  as email FROM "
			. "#__redshop_users_info as uf LEFT JOIN #__users as u ON uf.user_id = u.id WHERE uf.user_id='"
			. $uid . "' and uf.address_type like 'BT'";

		$db->setQuery($query);

		return $db->loadObject();
	}

	public function getUserFromEmail($email)
	{
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__redshop_users_info AS uf "
			. "WHERE uf.address_type='BT' "
			. "AND uf.user_email='" . $email . "' ";
		$db->setQuery($query);
		$list = $db->loadObject();

		return $list;
	}
}
