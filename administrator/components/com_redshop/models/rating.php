<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class ratingModelrating extends JModelLegacy
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    public $_context = null;

    public function __construct()
    {
        parent::__construct();

        global $mainframe;
        $this->_context      = 'rating_id';
        $this->_table_prefix = '#__redshop_';
        $limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $comment_filter      = $mainframe->getUserStateFromRequest($this->_context . 'comment_filter', 'comment_filter', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('comment_filter', $comment_filter);
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
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    public function _buildQuery()
    {
        $comment_filter = $this->getState('comment_filter');

        $where = '';

        if ($comment_filter)
        {
            $where = " WHERE username like '%" . $comment_filter . "%' ";
            $where .= " OR comment LIKE '%" . $comment_filter . "%' ";
            $where .= " OR product_name LIKE '%" . $comment_filter . "%' ";
        }

        $orderby = $this->_buildContentOrderBy();

        $query = ' SELECT p.product_name,u.username,r.* ' . ' FROM ' . $this->_table_prefix . 'product_rating r LEFT JOIN ' . $this->_table_prefix . 'product p ON p.product_id = r.product_id  LEFT JOIN #__users u ON u.id = r.userid ' . $where . $orderby;

        return $query;
    }

    public function _buildContentOrderBy()
    {
        global $mainframe;

        $filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'rating_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }
}

