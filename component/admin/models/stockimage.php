<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelStockimage extends RedshopModel
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

		$this->_context = 'stock_amount_id';
		$this->_table_prefix = '#__redshop_';

		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', '');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('filter', $filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
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
		$filter = $this->getState('filter');
		$orderby = $this->_buildOrderBy();

		$where = '';

		if ($filter)
		{
			$where = " WHERE si.stock_amount_image_tooltip LIKE '%" . $filter . "%' ";
		}
		$query = "SELECT * FROM " . $this->_table_prefix . "stockroom_amount_image AS si "
			. "LEFT JOIN " . $this->_table_prefix . "stockroom AS s ON s.stockroom_id=si.stockroom_id "
			. $where
			. $orderby;

		return $query;
	}

	public function _buildOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'stock_amount_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $orderby;
	}

	public function getStockAmountOption($select = 0)
	{
		$option = array();
		$option[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$option[] = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_HIGHER_THAN'));
		$option[] = JHTML::_('select.option', 2, JText::_('COM_REDSHOP_EQUAL'));
		$option[] = JHTML::_('select.option', 3, JText::_('COM_REDSHOP_LOWER_THAN'));

		if ($select != 0)
		{
			$option = $option[$select]->text;
		}
		return $option;
	}
}
