<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

/**
 * Answer Model.
 *
 * @package        redSHOP
 * @subpackage     Models
 * @since          1.2
 */
class RedshopModelAnswer extends JModelList
{
    /**
     * Context string for the model type.  This is used to handle uniqueness
     * when dealing with the getStoreId() method and caching data structures.
     *
     * @var    string
     */
    protected $context = 'question_id';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Load the filter state.
        $filter = $this->getUserStateFromRequest($this->context . 'filter', 'filter', 0);
        $this->setState('filter.filter', $filter);

        $parentId = $this->getUserStateFromRequest($this->context . 'product_id', 'product_id', 0);
        $this->setState('filter.parent_id', $parentId);

        $productId = $this->getUserStateFromRequest($this->context . 'parent_id', 'parent_id', 0);
        $this->setState('filter.product_id', $productId);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string	$id  A prefix for the store id.
     *
     * @return	string	A store id.
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id	.= ':'.$this->getState('filter.filter');
        $id	.= ':'.$this->getState('filter.parent_id');
        $id	.= ':'.$this->getState('filter.product_id');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     */
    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        // Get the model state.
        $ordering  = $db->escape($this->getState('list.ordering', 'q.parent_id'));
        $direction = $db->escape($this->getState('list.direction', 'DESC'));
        $parentId = $db->escape($this->getState('filter.parent_id', 'DESC'));
        $filter = $this->getState('filter.filter');
        $productId = $this->getState('filter.product_id', 'DESC');

        $query = $db->getQuery(true)
            ->select('q.*')
            ->from('#__redshop_customer_question as q')
            ->where('q.parent_id =' . $parentId);

        if ($filter)
        {
            $query->where('q.question LIKE %' . $db->escape($filter) . '%');
        }

        if ($productId)
        {
            $query->where('q.product_id =' . $db->escape($productId));
        }

        $query->order($ordering . ' ' . $direction);

        return $query;
    }

    /**
     * ????
     */
    public function getProduct()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__redshop_product');

        return $this->_getList($query);
    }
}

