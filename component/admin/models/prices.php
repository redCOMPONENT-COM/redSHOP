<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelPrices extends RedshopModel
{
	public $_prodid = 0;

	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();

		$this->_context = 'price';

		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$pid = JRequest::getVar('product_id');
		$this->setProductId((int) $pid);
	}

	public function setProductId($id)
	{
		// Set employees_detail id and wipe data
		$this->_prodid = $id;
		$this->_data = null;
	}

	public function getProductId()
	{
		return $this->_prodid;
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
		$query = ' SELECT p.*, '
			. ' g.shopper_group_name, prd.product_name '
			. ' FROM ' . $this->_table_prefix . 'product_price as p '
			. ' LEFT JOIN ' . $this->_table_prefix . 'shopper_group as g ON p.shopper_group_id = g.shopper_group_id '
			. ' LEFT JOIN ' . $this->_table_prefix . 'product as prd ON p.product_id = prd.product_id '
			. 'WHERE p.product_id = \'' . $this->_prodid . '\' ';

		return $query;
	}
}
