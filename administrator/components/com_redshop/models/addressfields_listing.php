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

class RedshopModelAddressfields_listing extends RedshopCoreModel
{
    public $_context = 'ordering';

    public $_total = null;

    public $_pagination = null;

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit              = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart         = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $field_section_drop = $app->getUserStateFromRequest($this->_context . 'section_id', 'section_id', 0);
        $limitstart         = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('section_id', $field_section_drop);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }

    public function getTotal()
    {

        $query = $this->_buildQuerycount();
        if (empty($this->_total))
        {
            $this->_db->setQuery($query);
            $this->_total = $this->_db->loadResult();
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

    public function _buildQuerycount()
    {
        $filter = $this->getState('section_id');
        $where  = '';
        if ($filter)
        {
            $where = " WHERE field_section = '" . $filter . "'";
        }
        if ($where == '')
        {
            $query = "SELECT count(*)  FROM " . $this->_table_prefix . "fields f WHERE 1=1";
        }
        else
        {
            $query = " SELECT count(*)  FROM " . $this->_table_prefix . "fields f" . $where;
        }
        return $query;
    }

    public function _buildQuery()
    {
        $filter  = $this->getState('section_id');
        $orderby = $this->_buildContentOrderBy();
        $where   = '';
        $limit   = "";
        if ($this->getState('limit') > 0)
        {
            $limit = " LIMIT " . $this->getState('limitstart') . "," . $this->getState('limit');
        }
        if ($filter)
        {
            $where = " WHERE field_section = '" . $filter . "'";
        }
        if ($where == '')
        {
            $query = "SELECT distinct(f.field_id),f.*  FROM " . $this->_table_prefix . "fields f WHERE 1=1" . $orderby . $limit;
        }
        else
        {
            $query = " SELECT distinct(f.field_id),f.*  FROM " . $this->_table_prefix . "fields f" . $where . $orderby . $limit;
        }
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
        $row        = $this->getTable("fields");
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
        //		// execute updateOrder for each parent group
        /*		$groupings = array_unique( $groupings );
                foreach ($groupings as $group){
                    $row->reorder((int) $group);
                }
        */
        foreach ($conditions as $cond)
        {
            $row->load($cond[0]);
            $row->reorder($cond[1]);
        }

        return true;
    }

    /*

     /**
      * Method to get max ordering
      *
      * @access public
      * @return boolean
      */
    public function MaxOrdering()
    {
        $query = "SELECT (count(*)+1) FROM " . $this->_table_prefix . "fields";
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /**
     * Method to move
     *
     * @access  public
     * @return  boolean True on success
     * @since   0.9
     */
    public function move($direction, $field_id)
    {
        $row = $this->getTable("fields");

        if (!$row->load($field_id))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move($direction, 'field_section = ' . (int)$row->field_section))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }
}

