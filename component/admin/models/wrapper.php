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
            $this->_pagination = new \JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    public function _buildQuery()
    {
        $db = \JFactory::getDbo();
        $app = \JFactory::getApplication();
        $query = $db->getQuery(true);
        $showAll = $app->input->get('showall', '0');
        $and = '';

        if ($showAll && $this->_productid != 0) {
            $and = 'AND FIND_IN_SET(' . $this->_productid . ',w.product_id) OR wrapper_use_to_all = 1 ';

            $query = "SELECT * FROM " . $this->_table_prefix . "product_category_xref "
                . "WHERE product_id = " . $this->_productid;
            $cat = $this->_getList($query);

            for ($i = 0, $in = count($cat); $i < $in; $i++) {
                $and .= " OR FIND_IN_SET(" . $cat[$i]->category_id . ",category_id) ";
            }
        }

        $filter = $this->getState('filter');

        if ($filter) {
            $and .= "w.wrapper_name LIKE '%" . $filter . "%' ";
        }

        $query->select('*')
            ->from($db->qn('#__redshop_wrapper', 'w'))
            ->where('1 = 1');

        if (\Joomla\String\StringHelper::strlen($and) > 0) {
            $query->where($and);
        }

        $filterOrder = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'wrapper_id');
        $filterOrderDir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $query->order($db->escape($db->qn($filterOrder) . ' ' . $filterOrderDir));

        echo $query;
        exit;

        return $query;
    }
}
