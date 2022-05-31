<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelProducttags extends RedshopModel
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    public $_context = null;

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $this->_context = 't.tags_id';

        $this->_table_prefix = '#__redshop_';
        $limit               = $app->getUserStateFromRequest(
            $this->_context . 'limit',
            'limit',
            $app->getCfg('list_limit'),
            0
        );
        $limitstart          = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    public function getData()
    {
        if (empty($this->_data)) {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
    }

    public function _buildQuery()
    {
        $orderby = $this->_buildContentOrderBy();

        $query = ' SELECT DISTINCT t.*,count(ptx.product_id) as products,count(ptx.users_id) as users,count(ptx.tags_id) as usag '
            . ' FROM ' . $this->_table_prefix . 'product_tags as t '
            . ' left join ' . $this->_table_prefix . 'product_tags_xref as ptx on ptx.tags_id = t.tags_id '
            . ' GROUP BY t.tags_name '
            . $orderby;

        return $query;
    }

    public function _buildContentOrderBy()
    {
        $db  = JFactory::getDbo();
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest(
            $this->_context . 'filter_order',
            'filter_order',
            't.tags_id'
        );
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

        return $orderby;
    }

    public function getPagination()
    {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination(
                $this->getTotal(),
                $this->getState('limitstart'),
                $this->getState('limit')
            );
        }

        return $this->_pagination;
    }

    public function getTotal()
    {
        if (empty($this->_total)) {
            $query        = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }
}
