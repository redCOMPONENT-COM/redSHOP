<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class templateModeltemplate extends JModelLegacy
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

        $this->_context      = 'template_id';
        $this->_table_prefix = '#__redshop_';
        $limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $template_section    = $mainframe->getUserStateFromRequest($this->_context . 'template_section', 'template_section', 0);
        $filter              = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);

        $this->setState('filter', $filter);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('template_section', $template_section);
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
        $orderby          = $this->_buildContentOrderBy();
        $filter           = $this->getState('filter');
        $template_section = $this->getState('template_section');

        $where = '';
        if ($filter)
        {
            $where .= "AND t.template_name LIKE '" . $filter . "%' ";
        }
        if ($template_section)
        {
            $where .= "AND t.template_section='" . $template_section . "' ";
        }
        $query = 'SELECT distinct(t.template_id),t.* FROM ' . $this->_table_prefix . 'template AS t ' . 'WHERE 1=1 ' . $where . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        global $mainframe;

        $filter_order = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'template_id');

        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }
}

