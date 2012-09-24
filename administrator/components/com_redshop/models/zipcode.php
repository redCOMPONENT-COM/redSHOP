<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class zipcodeModelzipcode extends JModelLegacy
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

        $this->_context = 'zipcode_id';

        $this->_table_prefix = '#__' . TABLE_PREFIX . '_';
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
            $query       = $this->_buildQuery(); //$this->_db->setQuery( $query ); echo $this->_db->getQuery();
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

        //$filter = $this->getState('filter');
        $orderby = $this->_buildContentOrderBy();

        $query = 'SELECT z . * , c.country_name, s.state_name ' . ' FROM `' . $this->_table_prefix . 'zipcode` AS z ' . 'LEFT JOIN ' . $this->_table_prefix . 'country AS c ON z.country_code = c.country_3_code ' . ' LEFT JOIN ' . $this->_table_prefix . 'state AS s ON z.state_code = s.state_2_code ' . ' AND c.country_id = s.country_id ' . ' WHERE 1 =1 ' . $orderby;

        return $query;
    }

    function _buildContentOrderBy()
    {
        global $mainframe;

        $filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'zipcode_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    function getCountryName($country_id)
    {
        $query = "SELECT  c.country_name from " . $this->_table_prefix . "country AS c where c.country_id=" . $country_id;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }
}
