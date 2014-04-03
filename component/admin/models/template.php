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

class templateModeltemplate extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

		$this->_context = 'template_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$template_section = $app->getUserStateFromRequest($this->_context . 'template_section', 'template_section', 0);
		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);

		$this->setState('filter', $filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('template_section', $template_section);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function _buildQuery()
	{
		$orderby          = $this->_buildContentOrderBy();
		$filter           = $this->getState('filter');
		$template_section = $this->getState('template_section');

		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('distinct(t.template_id)')
			->select('t.*')
			->from($db->qn('#__redshop_template', 't'))
			->order($orderby);

		if ($filter)
		{
			$query->where($db->qn('t.template_name') . 'LIKE ' . $db->q($filter . '%'));
		}

		if ($template_section)
		{
			$query->where($db->qn('t.template_section') . 'LIKE ' . $db->q($template_section));
		}

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=t.checked_out');

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'template_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $orderby;
	}
}
