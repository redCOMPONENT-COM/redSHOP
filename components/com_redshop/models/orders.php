<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class ordersModelorders extends RedshopCoreModel
{
    public $_pagination = null;

    public $_template = null;

    public $_limitstart = null;

    public $_limit = null;

    public $_total = null;

    public function __construct()
    {
        parent::__construct();
        global $mainframe, $option;

        $this->_limitstart = JRequest::getVar('limitstart', 0);
        $this->_limit      = $mainframe->getUserStateFromRequest($option . 'limit', 'limit', 10, 'int');
    }

    public function _buildQuery()
    {
        $user  = JFactory::getUser();
        $query = "SELECT * FROM  " . $this->_table_prefix . "orders " . "WHERE user_id='" . $user->id . "' ";
        return $query;
    }

    public function getData()
    {
        $query       = $this->_buildQuery();
        $this->_data = $this->_getList($query, $this->_limitstart, $this->_limit);
        return $this->_data;
    }

    public function getPagination()
    {
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new redPagination($this->getTotal(), $this->_limitstart, $this->_limit);
        }
        return $this->_pagination;
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
}
