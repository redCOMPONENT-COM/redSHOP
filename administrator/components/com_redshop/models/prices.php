<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class pricesModelprices extends JModel
{
    var $_prodid = 0;

    var $_data = null;

    var $_total = null;

    var $_pagination = null;

    var $_table_prefix = null;

    var $_context = null;

    function __construct()
    {
        parent::__construct();
        global $mainframe;

        $this->_context = 'price';

        $this->_table_prefix = '#__' . TABLE_PREFIX . '_';
        $limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $pid = JRequest::getVar('product_id');
        $this->setProductId((int)$pid);
    }

    function setProductId($id)
    {
        // Set employees_detail id and wipe data
        $this->_prodid = $id;
        $this->_data   = null;
    }

    function getProductId()
    {
        return $this->_prodid;
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
        $query = ' SELECT p.*, ' . ' g.shopper_group_name, prd.product_name ' . ' FROM ' . $this->_table_prefix . 'product_price as p ' . ' LEFT JOIN ' . $this->_table_prefix . 'shopper_group as g ON p.shopper_group_id = g.shopper_group_id ' . ' LEFT JOIN ' . $this->_table_prefix . 'product as prd ON p.product_id = prd.product_id ' . 'WHERE p.product_id = \'' . $this->_prodid . '\' ';
        return $query;
    }
}

