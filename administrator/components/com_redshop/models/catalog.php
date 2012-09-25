<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class catalogModelcatalog extends JModelLegacy
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
        $this->_context      = 'sample_id';
        $this->_table_prefix = '#__redshop_';
        $limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $limitstart          = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
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
        $orderby = $this->_buildContentOrderBy();
        $query   = 'SELECT  distinct(c.catalog_id),c.* FROM ' . $this->_table_prefix . 'catalog c ' . $orderby;
        return $query;
    }

    function _buildContentOrderBy()
    {
        global $mainframe;
        $layout       = JRequest::getVar('layout', 'default');
        $filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'catalog_id');

        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    function MediaDetail($pid)
    {
        $query = 'SELECT * FROM ' . $this->_table_prefix . 'media  WHERE section_id =' . $pid . ' AND media_section = "catalog"';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
    }
}
