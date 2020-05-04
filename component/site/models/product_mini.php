<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class product_miniModelproduct_mini
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelProduct_mini extends RedshopModel
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    /**
     * RedshopModelProduct_mini constructor.
     * @throws Exception
     */
    public function __construct()
    {
        global $context;
        parent::__construct();

        $app     = \JFactory::getApplication();
        $context = 'p.product_id';

        $limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitStart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitStart);
    }

    /**
     * @return mixed|object[]|null
     * @throws Exception
     */
    public function getData()
    {
        if (empty($this->_data)) {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }

        return $this->_data;
    }

    /**
     * @return JDatabaseQuery|string|null
     * @throws Exception
     */
    public function _buildQuery()
    {
        global $context;
        $app = JFactory::getApplication();

        $orderBy     = $this->_buildContentOrderBy();
        $searchField = $app->getUserStateFromRequest($context . 'search_field', 'search_field', '');
        $keyword     = $app->getUserStateFromRequest($context . 'keyword', 'keyword', '');
        $categoryId  = $app->getUserStateFromRequest($context . 'category_id', 'category_id', 0);
        $limit       = $this->getState('limit');

        return \Redshop\Product\Mini::getQueryObject($keyword, $categoryId, $searchField, $limit, $orderBy);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function _buildContentOrderBy()
    {
        global $context;
        $app            = \JFactory::getApplication();
        $db             = \JFactory::getDbo();
        $filterOrder    = urldecode(
            $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'p.product_id')
        );
        $filterOrderDir = urldecode(
            $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '')
        );

        if ($filterOrder == 'product_id') {
            $filterOrder = 'p.product_id';
        }

        return $db->escape($filterOrder . ' ' . $filterOrderDir);
    }

    /**
     * @return JPagination|null
     */
    public function getPagination()
    {
        if (empty($this->_pagination)) {
            $this->_pagination = new \JPagination(
                $this->getTotal(),
                $this->getState('limitstart'), $this->getState('limit')
            );
        }

        return $this->_pagination;
    }

    /**
     * @return int|mixed|null
     * @throws Exception
     */
    public function getTotal()
    {
        global $context;
        $app         = \JFactory::getApplication();
        $searchField = $app->getUserStateFromRequest($context . 'search_field', 'search_field', '');
        $keyword     = $app->getUserStateFromRequest($context . 'keyword', 'keyword', '');
        $categoryId  = $app->getUserStateFromRequest($context . 'category_id', 'category_id', 0);

        if (empty($this->_total)) {
            $this->_total = \Redshop\Product\Mini::getCountDistinctProduct($keyword, $categoryId, $searchField);
        }

        return $this->_total;
    }
}
