<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelSample_request extends RedshopModel
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    public $_context = null;

    public function __construct()
    {
        parent::__construct();

        $app = \JFactory::getApplication();

        $this->_context = 'request_id';

        $this->_table_prefix = '#__redshop_';

        $limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit',
            \Redshop::getConfig('list_limit'), 0);

        $limitStart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

        $filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitStart);
        $this->setState('filter', $filter);
    }

    public function getData()
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
    }

    public function getTotal()
    {
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    public function getPagination()
    {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(),
                $this->getState('limitstart'),
                $this->getState('limit'));
        }

        return $this->_pagination;
    }

    /**
     * @return JDatabaseQuery
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
    public function _buildQuery()
    {
        $app = \JFactory::getApplication();
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->qn('#__redshop_sample_request'));

        $filterOrder = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'request_id');
        $filterOrderDir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

        $query->order($db->escape($filterOrder . ' ' . $filterOrderDir));

        return $query;
    }

    /**
     * @param array $cid
     * @return bool
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
    public function delete($cid = [])
    {
        if (is_array($cid) && count($cid)) {
            $sampleIds = implode(',', $cid);
            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->delete($db->qn('#__redshop_sample_request'))
                ->where($db->qn('request_id') . ' IN (' . $db->q($sampleIds) . ')');

            $db->setQuery($query);

            try {
                $db->execute();
            } catch (\Exception $e) {
                \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $cid
     * @param int $publish
     * @return bool
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
    public function publish($cid = array(), $publish = 1)
    {
        if (count($cid)) {
            $sampleIds = implode(',', $cid);
            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->update($db->qn('#__redshop_sample_request'))
                ->set([
                    $db->qn('block') . ' = ' . $db->q((int)$publish)
                ])
                ->where($db->qn('request_id') . ' IN (' . $db->q($sampleIds) . ')');

            $db->setQuery($query);

            try {
                !$this->_db->execute();
            } catch (\Exception $e) {
                \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
                return false;
            }
        }

        return true;
    }
}
