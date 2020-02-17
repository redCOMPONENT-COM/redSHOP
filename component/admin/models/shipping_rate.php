<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelShipping_rate extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

    /**
     * RedshopModelShipping_rate constructor.
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
	public function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();

		$this->_table_prefix = '#__redshop_';
		$this->_context      = 'shipping_rate_id';
		$limit               = $app->getUserStateFromRequest($this->_context . 'limit', 'limit',
            \JFactory::getConfig('list_limit'), 0);
		$limitstart          = $app->getUserStateFromRequest($this->_context . 'limitstart',
            'limitstart', 0);

		$id = $app->getUserStateFromRequest($this->_context . 'extension_id', 'extension_id', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('id', $id);
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
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'),
                $this->getState('limit'));
		}

		return $this->_pagination;
	}

    /**
     * @return JDatabaseQuery
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
	public function _buildQuery()
	{
		$id = $this->getState('id');
		$db = \JFactory::getDbo();
		$app = \JFactory::getApplication();
		$query = $db->getQuery(true);
		$query->select(
		    'r.*',
            $db->qn('p.extension_id'),
            $db->qn('p.element'),
            $db->qn('p.folder')
        )->from($db->qn('#__redshop_shipping_rate', 'r'))
        ->leftJoin($db->qn('#__extensions', 'p')
            . ' ON '
            . 'CONVERT(' . $db->qn('p.element') . ' USING utf8)'
            . ' = ' .
            'CONVERT(' . $db->qn('r.shipping_class') . ' USING utf8)'
        )->where($db->qn('p.extension_id') . ' = ' . $db->q((int) $id));

        $filterOrder    = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order',
            'shipping_rate_id');
        $filterOrderDir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir',
            'filter_order_Dir', '');

        $query->order($db->qn($filterOrder) . ' ' . $filterOrderDir);

		return $query;
	}
}
