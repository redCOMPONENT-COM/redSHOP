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

class discountModeldiscount extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = null;

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $layout = JRequest::getVar('layout');

        if (isset($layout) && $layout == 'product')
        {
            $this->_context = 'discount_product_id';
        }
        else
        {
            $this->_context = 'discount_id';
        }

        $limit      = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
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
        $orderby = $this->_buildContentOrderBy();
        $where   = '';
        $layout  = JRequest::getVar('layout');
        if (isset($layout) && $layout == 'product')
        {
            $query = ' SELECT * FROM ' . $this->_table_prefix . 'discount_product ' . $orderby;
        }
        else
        {
            $query = ' SELECT * FROM ' . $this->_table_prefix . 'discount ' . $orderby;
        }
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $layout = JRequest::getVar('layout');

        if (isset($layout) && $layout == 'product')
        {
            $filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'discount_product_id');
        }
        else
        {
            $filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'discount_id');
        }

        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    public function delete($cid = array())
    {
        $layout = JRequest::getVar('layout');

        if (count($cid))
        {
            $cids = implode(',', $cid);
            if (isset($layout) && $layout == 'product')
            {
                $query = 'DELETE FROM ' . $this->_table_prefix . 'discount_product WHERE discount_product_id IN ( ' . $cids . ' )';
            }
            else
            {
                $query = 'DELETE FROM ' . $this->_table_prefix . 'discount WHERE discount_id IN ( ' . $cids . ' )';
            }

            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    public function publish($cid = array(), $publish = 1)
    {
        $layout = JRequest::getVar('layout');

        if (count($cid))
        {
            $cids = implode(',', $cid);

            if (isset($layout) && $layout == 'product')
            {
                $query = 'UPDATE ' . $this->_table_prefix . 'discount_product' . ' SET published = ' . intval($publish) . ' WHERE discount_product_id IN ( ' . $cids . ' )';
            }
            else
            {
                $query = 'UPDATE ' . $this->_table_prefix . 'discount' . ' SET published = ' . intval($publish) . ' WHERE discount_id IN ( ' . $cids . ' )';
            }

            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }
}
