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

class RedshopModelStockroom extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'stockroom_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit      = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $filter     = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('filter', $filter);
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
        $filter  = $this->getState('filter');
        $orderby = $this->_buildContentOrderBy();
        $where   = '';
        if ($filter)
        {
            $where = " WHERE stockroom_name like '%" . $filter . "%' ";
        }

        $query = "SELECT distinct(s.stockroom_id),s.* FROM " . $this->_table_prefix . "stockroom s" . $where . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'stockroom_id');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    public function delete($cid = array())
    {

        if (count($cid))
        {
            $cids = implode(',', $cid);

            // delete stock of products
            $query_product = 'DELETE FROM ' . $this->_table_prefix . 'product_stockroom_xref WHERE stockroom_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query_product);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            // delete stock of products attribute stock
            $query_product_attr = 'DELETE FROM ' . $this->_table_prefix . 'product_attribute_stockroom_xref WHERE stockroom_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query_product_attr);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            // delete stockroom
            $query = 'DELETE FROM ' . $this->_table_prefix . 'stockroom WHERE stockroom_id IN ( ' . $cids . ' )';
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
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'stockroom' . ' SET published = ' . intval($publish) . ' WHERE stockroom_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    public function copy($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'stockroom WHERE stockroom_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $this->_copydata = $this->_db->loadObjectList();
        }
        foreach ($this->_copydata as $cdata)
        {
            $post['stockroom_id']   = 0;
            $post['stockroom_name'] = 'Copy Of ' . $cdata->stockroom_name;
            $post['stockroom_desc'] = $cdata->stockroom_desc;
            $post['min_del_time']   = $cdata->min_del_time;
            $post['max_del_time']   = $cdata->max_del_time;
            $post['delivery_time']  = $cdata->delivery_time;
            $post['show_in_front']  = $cdata->show_in_front;
            $post['creation_date']  = time();
            $post['published']      = $cdata->published;
            stockroom_detailModelstockroom_detail::store($post);
        }
        return true;
    }
}

