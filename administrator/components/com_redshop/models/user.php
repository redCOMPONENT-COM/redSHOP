<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class userModeluser extends JModelLegacy
{
    public $_data = null;

    public $_id = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    public $_context = null;

    function __construct()
    {
        parent::__construct();

        global $mainframe;
        $this->_context      = 'user_info_id';
        $this->_table_prefix = '#__redshop_';
        $limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

        $filter       = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
        $spgrp_filter = $mainframe->getUserStateFromRequest($this->_context . 'spgrp_filter', 'spgrp_filter', 0);

        $approved_filter = $mainframe->getUserStateFromRequest($this->_context . 'approved_filter', 'approved_filter', 0);

        $tax_exempt_request_filter = $mainframe->getUserStateFromRequest($this->_context . 'tax_exempt_request_filter', 'tax_exempt_request_filter', 0);

        $array = JRequest::getVar('user_id', 0, '', 'array');

        $this->setId((int)$array[0]);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter', $filter);
        $this->setState('spgrp_filter', $spgrp_filter);
        $this->setState('approved_filter', $approved_filter);
        $this->setState('tax_exempt_request_filter', $tax_exempt_request_filter);
    }

    function setId($id)
    {
        $this->_id = $id;
    }

    function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
    }

    function getTotal()
    {
        if (empty($this->_total))
        {
            $query        = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    function getPagination()
    {
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    function _buildQuery()
    {
        $filter                    = $this->getState('filter');
        $spgrp_filter              = $this->getState('spgrp_filter');
        $approved_filter           = $this->getState('approved_filter');
        $tax_exempt_request_filter = $this->getState('tax_exempt_request_filter');

        $where = '';
        if ($filter)
        {
            $where .= "AND (u.username LIKE '%" . $filter . "%' ";
            $where .= "OR uf.firstname LIKE '%" . $filter . "%'  ";
            $where .= " OR uf.lastname LIKE '%" . $filter . "%' ";
            $where .= " OR sp.shopper_group_name LIKE '%" . $filter . "%' )";
        }
        if ($spgrp_filter)
        {
            $where .= "AND sp.shopper_group_id = '" . $spgrp_filter . "' ";
        }
        if ($approved_filter != 'select')
        {
            $where .= "AND uf.approved='" . $approved_filter . "' ";
        }
        if ($tax_exempt_request_filter != 'select')
        {
            $where .= "AND uf.tax_exempt='" . $tax_exempt_request_filter . "' " . "AND tax_exempt_approved=0 ";
        }

        $orderby = $this->_buildContentOrderBy();
        if ($this->_id != 0)
        {
            $query = 'SELECT * FROM  #__users AS u ' . 'LEFT JOIN ' . $this->_table_prefix . 'users_info AS uf ON u.id=uf.user_id ' . 'LEFT JOIN ' . $this->_table_prefix . 'shopper_group AS sp ON uf.shopper_group_id=sp.shopper_group_id ' . 'WHERE uf.address_type="ST" ' . 'AND uf.user_id="' . $this->_id . '" ' . $where . $orderby;
        }
        else
        {
            $query = 'SELECT uf.user_id, uf.*,u.username,u.name,sp.shopper_group_name ' . 'FROM ' . $this->_table_prefix . 'users_info AS uf ' . 'LEFT JOIN #__users AS u ON u.id = uf.user_id ' . 'LEFT JOIN ' . $this->_table_prefix . 'shopper_group AS sp ON sp.shopper_group_id = uf.shopper_group_id ' . 'WHERE uf.address_type="BT" ' . $where . $orderby;
        }
        return $query;
    }

    function _buildContentOrderBy()
    {
        global $mainframe;

        $filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'users_info_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
        $orderby          = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        return $orderby;
    }

    function customertotalsales($uid)
    {
        $query = 'SELECT SUM(order_total) FROM ' . $this->_table_prefix . 'orders WHERE user_id=' . $uid;
        $this->_db->setQuery($query);
        $re = $this->_db->loadResult();
        if (!$re)
        {
            $re = 0;
        }
        return $re;
    }
}

?>
