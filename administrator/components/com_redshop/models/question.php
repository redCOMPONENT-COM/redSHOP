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

class RedshopModelQuestion extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'question_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit      = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $filter     = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
        $product_id = $app->getUserStateFromRequest($this->_context . 'product_id', 'product_id', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter', $filter);
        $this->setState('product_id', $product_id);
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

    public function getProduct()
    {
        $query = "SELECT * FROM " . $this->_table_prefix . "product ";
        $list  = $this->_data = $this->_getList($query);
        return $list;
    }

    public function _buildQuery()
    {
        $where      = "";
        $filter     = $this->getState('filter');
        $product_id = $this->getState('product_id');
        if ($filter)
        {
            $where .= " AND q.question LIKE '%" . $filter . "%' ";
        }
        if ($product_id != 0)
        {
            $where .= " AND q.product_id ='" . $product_id . "' ";
        }
        $orderby = $this->_buildContentOrderBy();

        $query = "SELECT q.* FROM " . $this->_table_prefix . "customer_question AS q " . "WHERE q.parent_id=0 " . $where . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'question_date');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

        $orderby = " ORDER BY " . $filter_order . " " . $filter_order_Dir;
        return $orderby;
    }

    /**
     * Method to save order
     *
     * @access public
     * @return boolean
     */
    public function saveorder($cid = array(), $order)
    {
        $row       = $this->getTable('customer_question');
        $order     = JRequest::getVar('order', array(0), 'post', 'array');
        $groupings = array();

        // update ordering values
        for ($i = 0; $i < count($cid); $i++)
        {
            $row->load((int)$cid[$i]);
            // track categories
            $groupings[] = $row->question_id;

            if ($row->ordering != $order[$i])
            {
                $row->ordering = $order[$i];
                if (!$row->store())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }
        // execute updateOrder for each parent group
        $groupings = array_unique($groupings);
        foreach ($groupings as $group)
        {
            $row->reorder((int)$group);
        }
        return true;
    }

    /**
     * Method to up order
     *
     * @access public
     * @return boolean
     */
    public function orderup()
    {
        $row = $this->getTable('customer_question');
        $row->load($this->_id);
        $row->move(-1);
        $row->store();
        return true;
    }

    /**
     * Method to down the order
     *
     * @access public
     * @return boolean
     */
    public function orderdown()
    {
        $row = $this->getTable('customer_question');
        $row->load($this->_id);
        $row->move(1);
        $row->store();
        return true;
    }

    /**
     * Method to publish the records
     *
     * @access public
     * @return boolean
     */
    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'customer_question ' . ' SET published = ' . intval($publish) . ' WHERE question_id IN ( ' . $cids . ' )';
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
