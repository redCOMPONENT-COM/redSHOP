<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelWrapper extends RedshopModel
{
	public $_productid = 0;

	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();

		$this->_context = 'wrapper_id';

		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', '');
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);

		$product_id = JFactory::getApplication()->input->get('product_id');
		$this->setProductId((int) $product_id);
	}

	public function setProductId($id)
	{
		$this->_productid = $id;
		$this->_data = null;
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
		$db      = JFactory::getDbo();
		$app     = JFactory::getApplication();
		$showall = $app->input->get('showall', '0');
		$and     = '';

		if ($showall && $this->_productid != 0)
		{
			$and = 'AND FIND_IN_SET(' . $this->_productid . ',w.product_id) OR wrapper_use_to_all = 1 ';

			$query = "SELECT * FROM " . $this->_table_prefix . "product_category_xref "
				. "WHERE product_id = " . $this->_productid;
			$cat = $this->_getList($query);

			for ($i = 0, $in = count($cat); $i < $in; $i++)
			{
				$and .= " OR FIND_IN_SET(" . $cat[$i]->category_id . ",category_id) ";
			}
		}

		$filter = $this->getState('filter');

		if ($filter)
		{
			$and .= " AND w.wrapper_name LIKE '%" . $filter . "%' ";
		}

		$query = 'SELECT distinct(w.wrapper_id), w.* FROM ' . $this->_table_prefix . 'wrapper AS w WHERE 1=1 '
			. $and;

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'wrapper_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$query .= ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $query;
	}
}
