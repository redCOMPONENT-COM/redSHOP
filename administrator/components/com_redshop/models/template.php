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

class RedshopModelTemplate extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'template_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit            = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart       = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $template_section = $app->getUserStateFromRequest($this->_context . 'template_section', 'template_section', 0);
        $filter           = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);

        $this->setState('filter', $filter);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('template_section', $template_section);
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
        $orderby          = $this->_buildContentOrderBy();
        $filter           = $this->getState('filter');
        $template_section = $this->getState('template_section');

        $where = '';
        if ($filter)
        {
            $where .= "AND t.template_name LIKE '" . $filter . "%' ";
        }
        if ($template_section)
        {
            $where .= "AND t.template_section='" . $template_section . "' ";
        }
        $query = 'SELECT distinct(t.template_id),t.* FROM ' . $this->_table_prefix . 'template AS t ' . 'WHERE 1=1 ' . $where . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'template_id');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    public function delete($cid = array())
    {
        $red_template = new Redtemplate();
        if (count($cid))
        {
            for ($i = 0; $i < count($cid); $i++)
            {
                $query = 'SELECT * FROM ' . $this->_table_prefix . 'template WHERE template_id = ' . $cid[$i];
                $this->_db->setQuery($query);
                $rs = $this->_db->loadObject();

                $tempate_file = $red_template->getTemplatefilepath($rs->template_section, $rs->template_name, true);

                unlink($tempate_file);
            }

            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'template WHERE template_id IN ( ' . $cids . ' )';
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
            $cids  = implode(',', $cid);
            $query = 'UPDATE ' . $this->_table_prefix . 'template' . ' SET published = ' . intval($publish) . ' WHERE template_id IN ( ' . $cids . ' )';
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

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'template WHERE template_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $this->_copydata = $this->_db->loadObjectList();
        }
        foreach ($this->_copydata as $cdata)
        {

            $post['template_id']      = 0;
            $post['template_name']    = 'Copy Of ' . $cdata->template_name;
            $post['template_section'] = $cdata->template_section;
            $post['template_desc']    = $cdata->template_desc;
            $post['order_status']     = $cdata->order_status;
            $post['payment_methods']  = $cdata->payment_methods;
            $post['published']        = $cdata->published;
            $post['shipping_methods'] = $cdata->shipping_methods;

            template_detailModeltemplate_detail::store($post);
        }
        return true;
    }
}

