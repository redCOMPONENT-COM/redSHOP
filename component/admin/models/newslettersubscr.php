<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class newslettersubscrModelnewslettersubscr extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_table_prefix = null;
	var $_context = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe;
		$this->_context      = 'subscription_id';
		$this->_table_prefix = '#__redshop_';
		$limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$filter              = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$limitstart          = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);
	}

	function getData()
	{
		if (empty($this->_data))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query        = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	function _buildQuery()
	{
		$filter = $this->getState('filter');
		$where  = '';
		if ($filter)
		{
			$where = " AND (ns.name like '%" . $filter . "%' OR ns.email like '%" . $filter . "%') ";
		}

		$orderby = $this->_buildContentOrderBy();
		$query   = 'SELECT  distinct(ns.subscription_id),ns.*,n.name as n_name FROM ' . $this->_table_prefix . 'newsletter_subscription as ns '
			. ',' . $this->_table_prefix . 'newsletter as n '
			. 'WHERE ns.newsletter_id=n.newsletter_id '
			. $where
			. $orderby;

		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe;

		$filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'subscription_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	function getnewslettername($nid)
	{
		$query = 'SELECT name FROM ' . $this->_table_prefix . 'newsletter WHERE newsletter_id=' . $nid;
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	function getnewsletters()
	{
		$query = 'SELECT newsletter_id as value,name as text FROM ' . $this->_table_prefix . 'newsletter WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	function importdata($nid, $name, $email)
	{

		if (trim($nid) != null && (trim($name) != null) && (trim($email) != null))
		{
			$query = "INSERT INTO " . $this->_table_prefix . "newsletter_subscription (subscription_id,user_id,newsletter_id,name,email) VALUES ('','0','" . $nid . "','" . $name . "','" . $email . "' )";

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
			else
			{
				return true;
			}
		}
	}
}

?>