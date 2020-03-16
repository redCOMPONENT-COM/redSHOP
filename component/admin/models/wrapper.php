<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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

	/**
	 * RedshopModelWrapper constructor.
	 * @throws Exception
	 */
	public function __construct()
	{
		parent::__construct();
		$app = \JFactory::getApplication();
		$this->_context = 'wrapper_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', '');
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter', $filter);

		$productId = JFactory::getApplication()->input->get('product_id');
		$this->setProductId((int)$productId);
	}

	/**
	 * @param $id
	 */
	public function setProductId($id)
	{
		$this->_productid = $id;
		$this->_data = null;
	}

	/**
	 * @return mixed|object[]|null
	 */
	public function getData()
	{
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	/**
	 * @return int|null
	 */
	public function getTotal()
	{
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * @return JPagination|null
	 */
	public function getPagination()
	{
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new \JPagination(
				$this->getTotal(),
				$this->getState('limitstart'),
				$this->getState('limit')
			);
		}

		return $this->_pagination;
	}

	public function _buildQuery()
	{
		$db = \JFactory::getDbo();
		$app = \JFactory::getApplication();
		$showAll = $app->input->get('showall', '0');
		$subQuery = [];

		if ($showAll && $this->_productid != 0) {
			$subQuery[] = 'FIND_IN_SET(' . $db->q($this->_productid) . ',' . $db->qn('w.product_id') . ')';
			$subQuery[] = $db->qn('wrapper_use_to_all') . '=' . $db->q(1);

			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_product_category_xref'))
				->where($db->qn('product_id') . ' = ' . $db->q((int)$this->_productid));
			$db->setQuery($query);
			$cat = $db->loadObjectList();

			for ($i = 0, $in = count($cat); $i < $in; $i++) {
				$subQuery[] = 'FIND_IN_SET(' . $db->q($cat[$i]->category_id) . ',' . $db->qn('category_id') . ')';
			}
		}

		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->qn('#__redshop_wrapper', 'w'));

		if (!empty($subQuery)) {
			$query->where('(' . implode(' OR ', $subQuery) . ')');
		}

		$filter = $this->getState('filter');
		$filter = $db->escape(trim($filter));

		if ($filter) {
			$query->where($db->qn('w.wrapper_name') . " LIKE '%" . $filter . "%' ");
		}

		$filterOrder = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'wrapper_id');
		$filterOrderDir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$query->order($db->escape($db->qn($filterOrder) . ' ' . $filterOrderDir));

		return $query;
	}
}
