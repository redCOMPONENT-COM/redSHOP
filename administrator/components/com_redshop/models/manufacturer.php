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

class manufacturerModelmanufacturer extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'manufacturer_id';

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
            $where = " WHERE m.manufacturer_name like '%" . $filter . "%' ";
        }

        $query = 'SELECT  distinct(m.manufacturer_id),m.* FROM ' . $this->_table_prefix . 'manufacturer m ' . $where . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'm.ordering');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    public function getMediaId($mid)
    {
        $query = ' SELECT media_id ' . ' FROM ' . $this->_table_prefix . 'media  WHERE media_section="manufacturer" AND section_id = ' . $mid;

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    public function saveOrder(&$cid)
    {
        $row = $this->getTable('manufacturer_detail');

        $total = count($cid);
        $order = JRequest::getVar('order', array(0), 'post', 'array');
        JArrayHelper::toInteger($order, array(0));

        // update ordering values
        for ($i = 0; $i < $total; $i++)
        {
            $row->load((int)$cid[$i]);
            if ($row->ordering != $order[$i])
            {
                $row->ordering = $order[$i];
                if (!$row->store())
                {
                    throw new RuntimeException($this->_db->getErrorMsg());
                }
            }
        }

        $row->reorder();
        return true;
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
        $row = JTable::getInstance('manufacturer_detail', 'Table');
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

    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'UPDATE ' . $this->_table_prefix . 'manufacturer' . ' SET published = ' . intval($publish) . ' WHERE manufacturer_id IN ( ' . $cids . ' )';
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

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $this->_copydata = $this->_db->loadObjectList();
        }
        foreach ($this->_copydata as $cdata)
        {
            $post['manufacturer_id']         = 0;
            $post['manufacturer_name']       = 'Copy Of ' . $cdata->manufacturer_name;
            $post['manufacturer_desc']       = $cdata->manufacturer_desc;
            $post['manufacturer_email']      = $cdata->manufacturer_email;
            $post['product_per_page']        = $cdata->product_per_page;
            $post['template_id']             = $cdata->template_id;
            $post['metakey']                 = $cdata->metakey;
            $post['metadata']                = $cdata->metadata;
            $post['metadesc']                = $cdata->metadesc;
            $post['excluding_category_list'] = $cdata->excluding_category_list;
            $post['published']               = $cdata->published;

            manufacturer_detailModelmanufacturer_detail::store($post);
        }
        return true;
    }

    public function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'manufacturer WHERE manufacturer_id IN ( ' . $cids . ' )';
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

