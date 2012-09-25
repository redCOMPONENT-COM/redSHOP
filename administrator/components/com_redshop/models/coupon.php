<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class couponModelcoupon extends JModelLegacy
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    public $_context = null;

    function __construct()
    {
        parent::__construct();

        global $mainframe;
        $this->_context      = 'coupon_id';
        $this->_table_prefix = '#__redshop_';
        $limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $filter              = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
        $limitstart          = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter', $filter);
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
        $filter = $this->getState('filter');
        $where  = '';
        if ($filter)
        {
            if ($filter == "Percentage" || $filter == "percentage")
            {
                $percentage = 1;
            }
            if ($filter == "Total" || $filter == "total")
            {
                $percentage = 0;
            }

            if ($filter == "User Specific" || $filter == "user specific")
            {
                $coupon_type = 1;
            }
            if ($filter == "Global" || $filter == "global")
            {
                $coupon_type = 0;
            }

            $where = " WHERE coupon_code like '%" . $filter . "%' ";
            if (isset($percentage))
            {
                $where .= " OR percent_or_total='" . $percentage . "'";
            }
            if (isset($coupon_type))
            {
                $where .= " OR coupon_type='" . $coupon_type . "'";
            }
        }
        $orderby = $this->_buildContentOrderBy();
        $query   = "SELECT distinct(c.coupon_id),c.* FROM " . $this->_table_prefix . "coupons c " . $where . $orderby;
        return $query;
    }

    function _buildContentOrderBy()
    {
        global $mainframe;

        $filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'coupon_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }
}

?>
