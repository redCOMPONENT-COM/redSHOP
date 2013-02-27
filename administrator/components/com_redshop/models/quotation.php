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

class RedshopModelQuotation extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'quotation_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit         = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart    = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $filter_status = $app->getUserStateFromRequest($this->_context . 'filter_status', 'filter_status', 0);
        $filter        = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter', $filter);
        $this->setState('filter_status', $filter_status);
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

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'quotation_cdate');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

        $orderby = " ORDER BY " . $filter_order . " " . $filter_order_Dir;

        return $orderby;
    }

    public function delete($cid = array())
    {
        $quotationHelper = new quotationHelper();
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $items = $quotationHelper->getQuotationProduct($cids);
            for ($i = 0; $i < count($items); $i++)
            {
                $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_accessory_item ' . 'WHERE quotation_item_id = ' . $items[$i]->quotation_item_id . ' ';
                $this->_db->setQuery($query);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }

                $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_attribute_item ' . 'WHERE quotation_item_id = ' . $items[$i]->quotation_item_id . ' ';
                $this->_db->setQuery($query);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }

                $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_fields_data ' . 'WHERE quotation_item_id = ' . $items[$i]->quotation_item_id . ' ';
                $this->_db->setQuery($query);
                if (!$this->_db->query())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }

            $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_item ' . 'WHERE quotation_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation WHERE quotation_id IN ( ' . $cids . ' )';
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

