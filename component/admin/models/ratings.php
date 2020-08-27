<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Model Ratings
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelRatings extends RedshopModelList
{
    /**
     * Construct class
     *
     * @since __DEPLOY_VERSION__
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'r.id',
                'product_id',
                'r.product_id',
                'title',
                'r.title',
                'comment',
                'r.comment',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param string $ordering  An optional ordering field.
     * @param string $direction An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function populateState($ordering = 'r.id', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param string $id A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('p.product_name,u.username,r.*')
            ->from($db->qn('#__redshop_product_rating', 'r'))
            ->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = r.product_id')
            ->leftJoin($db->qn('#__users', 'u') . ' ON u.id = r.userid');

        $search = $this->getState('filter.search');

        if ($search) {
            $query->where(
                $db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR ' .
                $db->qn('r.comment') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR ' .
                $db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%')
            );
        }

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', 'r.id');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }
}