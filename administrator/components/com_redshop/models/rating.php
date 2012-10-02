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

class ratingModelrating extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'rating_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit          = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart     = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $comment_filter = $app->getUserStateFromRequest($this->_context . 'comment_filter', 'comment_filter', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('comment_filter', $comment_filter);
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
        $comment_filter = $this->getState('comment_filter');

        $where = '';

        if ($comment_filter)
        {
            $where = " WHERE username like '%" . $comment_filter . "%' ";
            $where .= " OR comment LIKE '%" . $comment_filter . "%' ";
            $where .= " OR product_name LIKE '%" . $comment_filter . "%' ";
        }

        $orderby = $this->_buildContentOrderBy();

        $query = ' SELECT p.product_name,u.username,r.* ' . ' FROM ' . $this->_table_prefix . 'product_rating r LEFT JOIN ' . $this->_table_prefix . 'product p ON p.product_id = r.product_id  LEFT JOIN #__users u ON u.id = r.userid ' . $where . $orderby;

        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'rating_id');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    public function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'product_rating WHERE rating_id IN ( ' . $cids . ' )';
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

            $query = 'UPDATE ' . $this->_table_prefix . 'product_rating' . ' SET published = ' . intval($publish) . ' WHERE rating_id IN ( ' . $cids . ' )';
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

