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

class RedshopModelFields extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'field_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit         = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart    = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $filter        = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
        $filtertype    = $app->getUserStateFromRequest($this->_context . 'filtertypes', 'filtertypes', 0);
        $filtersection = $app->getUserStateFromRequest($this->_context . 'filtersection', 'filtersection', 0);
        $limitstart    = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('filter', $filter);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filtertype', $filtertype);
        $this->setState('filtersection', $filtersection);
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
        $orderby       = $this->_buildContentOrderBy();
        $filter        = $this->getState('filter');
        $filtertype    = $this->getState('filtertype');
        $filtersection = $this->getState('filtersection');

        $where = '';
        if ($filter)
        {
            $where .= " AND f.field_title like '%" . $filter . "%' ";
        }
        if ($filtertype)
        {
            $where .= " AND f.field_type='" . $filtertype . "' ";
        }
        if ($filtersection)
        {
            $where .= " AND f.field_section='" . $filtersection . "' ";
        }
        $query = "SELECT * FROM " . $this->_table_prefix . "fields AS f " . "WHERE 1=1 " . $where . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'ordering');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        if ($filter_order == 'ordering')
        {
            $orderby = ' ORDER BY field_section, ordering ' . $filter_order_Dir;
        }
        else
        {
            $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . ', field_section, ordering';
        }

        return $orderby;
    }

    public function saveorder($cid = array(), $order)
    {
        $row        = $this->getTable('fields');
        $groupings  = array();
        $conditions = array();

        // update ordering values
        for ($i = 0; $i < count($cid); $i++)
        {
            $row->load((int)$cid[$i]);
            // track categories
            $groupings[] = $row->field_id;

            if ($row->ordering != $order[$i])
            {
                $row->ordering = $order[$i];
                if (!$row->store())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                // remember to updateOrder this group
                $condition = 'field_section = ' . (int)$row->field_section;
                $found     = false;
                foreach ($conditions as $cond)
                {
                    if ($cond[1] == $condition)
                    {
                        $found = true;
                        break;
                    }
                }
                if (!$found)
                {
                    $conditions[] = array($row->field_id, $condition);
                }
            }
        }
        // execute updateOrder for each group
        foreach ($conditions as $cond)
        {
            $row->load($cond[0]);
            $row->reorder($cond[1]);
        }
        //		// execute updateOrder for each parent group
        //		$groupings = array_unique( $groupings );
        //		foreach ($groupings as $group){
        //			$row->reorder((int) $group);
        //		}
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
        return $this->move(-1);
    }

    /**
     * Method to down the order
     *
     * @access public
     * @return boolean
     */
    public function orderdown()
    {
        return $this->move(1);
    }

    /**
     * Method to move
     *
     * @access  public
     * @return  boolean True on success
     * @since   0.9
     */
    public function move($direction)
    {
        $row = JTable::getInstance('fields_detail', 'Table');

        if (!$row->load($this->_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'fields' . ' SET published = ' . intval($publish) . ' WHERE field_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    public function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'fields WHERE field_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            // 	remove fields_data
            $query_field_data = 'DELETE FROM ' . $this->_table_prefix . 'fields_data  WHERE fieldid IN ( ' . $cids . ' ) ';
            $this->_db->setQuery($query_field_data);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                //return false;
            }
        }

        return true;
    }
}

