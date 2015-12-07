<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelWrapper extends RedshopModel
{
	public $_productid = 0;

	public $_data = null;

	public $_total = null;

	public $_pagination = null;


	public $_context = null;

	public function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();

		$this->_context = 'wrapper_id';


		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$product_id = JRequest::getVar('product_id');
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
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();
		$showall = JRequest::getVar('showall', '0');
		$and = '';

		if ($showall && $this->_productid != 0)
		{
			$and = 'WHERE FIND_IN_SET(' . $this->_productid . ',w.product_id) OR wrapper_use_to_all = 1 ';

			$query = "SELECT * FROM #__redshop_product_category_xref "
				. "WHERE product_id = " . $this->_productid;
			$cat = $this->_getList($query);

			for ($i = 0; $i < count($cat); $i++)
			{
				$and .= " OR FIND_IN_SET(" . $cat[$i]->category_id . ",category_id) ";
			}
		}
		$query = 'SELECT distinct(w.wrapper_id), w.* FROM #__redshop_wrapper AS w '
			. $and;

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'w.wrapper_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$query .= ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $query;
	}
}
