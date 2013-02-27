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

class RedshopModelShipping_rate extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'shipping_rate_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit      = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $id         = $app->getUserStateFromRequest($this->_context . 'extension_id', 'extension_id', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('id', $id);
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
        $id      = $this->getState('id');

        $query = 'SELECT r.*,p.extension_id,p.element,p.folder FROM ' . $this->_table_prefix . 'shipping_rate AS r ' . 'LEFT JOIN #__extensions AS p ON CONVERT(p.element USING utf8)= CONVERT(r.shipping_class USING utf8) ' . 'WHERE p.extension_id="' . $id . '" ' . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'shipping_rate_id');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
        $orderby          = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        return $orderby;
    }

    public function copy($cid = array())
    {
        $copydata = array();
        if (count($cid))
        {
            $cids  = implode(',', $cid);
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'shipping_rate WHERE shipping_rate_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $copydata = $this->_db->loadObjectList();
        }
        for ($i = 0; $i < count($copydata); $i++)
        {
            $row = $this->getTable('shipping_rate');

            $pdata = &$copydata[$i];

            $post                                   = array();
            $post['shipping_rate_id']               = 0;
            $post['shipping_rate_name']             = JText::_('COM_REDSHOP_COPY_OF') . ' ' . $pdata->shipping_rate_name;
            $post['shipping_class']                 = $pdata->shipping_class;
            $post['shipping_rate_country']          = $pdata->shipping_rate_country;
            $post['shipping_rate_state']            = $pdata->shipping_rate_state;
            $post['shipping_rate_zip_start']        = $pdata->shipping_rate_zip_start;
            $post['shipping_rate_zip_end']          = $pdata->shipping_rate_zip_end;
            $post['shipping_rate_volume_start']     = $pdata->shipping_rate_volume_start;
            $post['shipping_rate_volume_end']       = $pdata->shipping_rate_volume_end;
            $post['shipping_rate_ordertotal_start'] = $pdata->shipping_rate_ordertotal_start;
            $post['shipping_rate_ordertotal_end']   = $pdata->shipping_rate_ordertotal_end;
            $post['shipping_rate_priority']         = $pdata->shipping_rate_priority;
            $post['shipping_rate_value']            = $pdata->shipping_rate_value;
            $post['shipping_rate_package_fee']      = $pdata->shipping_rate_package_fee;
            $post['shipping_rate_weight_start']     = $pdata->shipping_rate_weight_start;
            $post['shipping_rate_weight_end']       = $pdata->shipping_rate_weight_end;
            $post['company_only']                   = $pdata->company_only;
            $post['apply_vat']                      = $pdata->apply_vat;
            $post['shipping_rate_on_product']       = $pdata->shipping_rate_on_product;
            $post['shipping_rate_on_category']      = $pdata->shipping_rate_on_category;
            $post['shipping_rate_on_shopper_group'] = $pdata->shipping_rate_on_shopper_group;
            $post['shipping_location_info']         = $pdata->shipping_location_info;

            $row->bind($post);
            $result = $row->store();
        }
        return $result;
    }

    public function delete($cid = array())
    {
        if (count($cid))
        {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'shipping_rate WHERE shipping_rate_id  IN ( ' . $cids . ' )';
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

