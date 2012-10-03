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
class RedshopModelAttribute_set extends JModelList
{
    /**
     * Context string for the model type.  This is used to handle uniqueness
     * when dealing with the getStoreId() method and caching data structures.
     *
     * @var    string
     */
    protected $context = 'attribute_set_id';

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     */
    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        $ordering  = $db->escape($this->getState('list.ordering', 'attribute_set_id'));
        $direction = $db->escape($this->getState('list.direction', 'DESC'));

        $query = $db->getQuery(true)
            ->select('distinct(a.attribute_set_id), a.*')
            ->from('#__redshop_attribute_set as a')
            ->order($ordering . ' ' . $direction);

        return $query;
    }
}

