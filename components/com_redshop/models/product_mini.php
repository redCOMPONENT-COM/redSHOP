<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class product_miniModelproduct_mini extends JModelLegacy
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    public function __construct()
    {
        parent::__construct();

        global $mainframe, $context;

        $context             = 'product_id';
        $this->_table_prefix = '#__redshop_';

        $limit      = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart = $mainframe->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }

    public function getTotal()
    {
        global $mainframe, $context;

        $orderby      = $this->_buildContentOrderBy();
        $search_field = $mainframe->getUserStateFromRequest($context . 'search_field', 'search_field', '');
        $keyword      = $mainframe->getUserStateFromRequest($context . 'keyword', 'keyword', '');
        $category_id  = $mainframe->getUserStateFromRequest($context . 'category_id', 'category_id', 0);

        $where = '';
        if (trim($keyword) != '')
        {
            $where .= " AND " . $search_field . " LIKE '%$keyword%'  ";
        }
        if ($category_id)
        {
            $where .= " AND c.category_id = '$category_id'  ";
        }
        if ($where != '')
        {
            $query = 'SELECT count(distinct(p.product_id)) ' . 'FROM ' . $this->_table_prefix . 'product p ' . 'LEFT JOIN ' . $this->_table_prefix . 'product_category_xref x ON x.product_id = p.product_id ' . 'LEFT JOIN ' . $this->_table_prefix . 'category c ON x.category_id = c.category_id ' . 'WHERE 1=1 ' . $where;
        }
        else
        {
            $query = 'SELECT count(*) FROM ' . $this->_table_prefix . 'product p ';
        }
        if (empty($this->_total))
        {
            $this->_db->setQuery($query);
            $this->_total = $this->_db->loadResult();
        }
        return $this->_total;
    }

    public function getPagination()
    {
        if (empty($this->_pagination))
        {

            $this->_pagination = new RedPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    public function _buildQuery()
    {
        global $mainframe, $context;

        $orderby      = $this->_buildContentOrderBy();
        $search_field = $mainframe->getUserStateFromRequest($context . 'search_field', 'search_field', '');
        $keyword      = $mainframe->getUserStateFromRequest($context . 'keyword', 'keyword', '');
        $category_id  = $mainframe->getUserStateFromRequest($context . 'category_id', 'category_id', 0);

        $where = '';
        if (trim($keyword) != '')
        {
            $where .= " AND " . $search_field . " LIKE '%$keyword%'  ";
        }
        if ($category_id)
        {
            $where .= " AND c.category_id = '$category_id'  ";
        }
        // change limit condition for all issue
        $limit = "";
        if ($this->getState('limit') > 0)
        {
            $limit = " LIMIT " . $this->getState('limitstart') . "," . $this->getState('limit');
        }
        if ($where == '')
        {
            $query = "SELECT distinct(p.product_id),p.* FROM " . $this->_table_prefix . "product AS p " . "WHERE 1=1 " . $orderby . $limit;
        }
        else
        {
            $query = 'SELECT distinct(p.product_id),p.*, x.ordering , x.category_id FROM ' . $this->_table_prefix . 'product p ' . 'LEFT JOIN ' . $this->_table_prefix . 'product_category_xref x ON x.product_id = p.product_id ' . 'LEFT JOIN ' . $this->_table_prefix . 'category c ON x.category_id = c.category_id ' . 'WHERE 1=1 ' . $where . ' ' . $orderby;
        }
        return $query;
    }

    public function _buildContentOrderBy()
    {
        global $mainframe, $context;

        $category_id      = $mainframe->getUserStateFromRequest($context . 'category_id', 'category_id', 0);
        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'product_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        return $orderby;
    }
}
