<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class ratingsModelratings
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelRatings extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		$app = JFactory::getApplication();
		parent::__construct();
		$this->_table_prefix = '#__redshop_';

		$limit      = $app->getUserStateFromRequest('limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest('limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function _buildQuery()
	{
		$query = "SELECT distinct(p.product_id),p.product_name FROM  " . $this->_table_prefix . "product p"
			. ", " . $this->_table_prefix . "product_rating AS r "
			. "WHERE p.published=1 AND r.published=1 AND p.product_id=r.product_id ";

		return $query;
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query        = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			JLoader::import('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function getProductreviews($pid)
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('pr.*', 'uf.firstname', 'uf.lastname')
			->from($db->qn('#__redshop_product_rating', 'pr'))
			->leftJoin($db->qn('#__redshop_users_info', 'uf') . 'ON pr.userid = uf.user_id')
			->where(
				$db->qn('published') . ' = 1' .' AND ' .
				$db->qn('product_id') . ' = ' . $db->q($pid) . ' AND ' .
				$db->qn('uf.address_type') . ' LIKE ' . $db->q('BT')
			)
			->orWhere(
				$db->qn('product_id') . ' = ' . $db->q($pid) . ' AND ' .
				$db->qn('userid') . ' = ' . $db->q(0)
			);

		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObjectlist();

		return $this->_data;
	}
}
