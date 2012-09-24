<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class quotationModelquotation extends JModelLegacy
{
    var $_data = null;

    var $_total = null;

    var $_pagination = null;

    var $_table_prefix = null;

    var $_context = null;

    function __construct()
    {
        parent::__construct();

        global $mainframe;
        $this->_context = 'quotation_id';

        $this->_table_prefix = '#__redshop_';
        $limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

        $filter_status = $mainframe->getUserStateFromRequest($this->_context . 'filter_status', 'filter_status', 0);
        $filter        = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
        //		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter', $filter);
        $this->setState('filter_status', $filter_status);
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

        $where = "";
        //	    $order_id = array();
        //
        $filter        = $this->getState('filter');
        $filter_status = $this->getState('filter_status');
        //		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
        //		$order_id = implode(',',$cid);
        //
        //		$where[] = "1=1";
        //		if ( $filter_status ) {
        //
        //			$where[] = "o.order_status like '%".$filter_status."%'";
        //
        //		}
        if ($filter)
        {
            $where .= " AND (uf.firstname LIKE '%" . $filter . "%' OR uf.lastname LIKE '%" . $filter . "%')";
        }
        if ($filter_status != 0)
        {
            $where .= " AND q.quotation_status ='" . $filter_status . "' ";
        }
        $orderby = $this->_buildContentOrderBy();

        $query = "SELECT q.* FROM " . $this->_table_prefix . "quotation AS q " . "LEFT JOIN " . $this->_table_prefix . "users_info AS uf ON q.user_id=uf.user_id " . "WHERE uf.address_type Like 'BT' " . $where . "UNION SELECT q.* FROM " . $this->_table_prefix . "quotation AS q WHERE q.user_id=0 " . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        global $mainframe;

        $filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'quotation_cdate');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

        $orderby = " ORDER BY " . $filter_order . " " . $filter_order_Dir;

        return $orderby;
    }
}

