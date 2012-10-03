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
 * Account group Model.
 *
 * @package        redSHOP
 * @subpackage     Models
 * @since          1.2
 */
class RedshopModelAccountgroup extends JModelList
{
    /**
     * Context string for the model type.  This is used to handle uniqueness
     * when dealing with the getStoreId() method and caching data structures.
     *
     * @var    string
     */
    protected $context = 'accountgroup';

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     */
    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        $ordering  = $db->escape($this->getState('list.ordering', 'accountgroup_id'));
        $direction = $db->escape($this->getState('list.direction', 'DESC'));

        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__redshop_economic_accountgroup')
            ->order($ordering . ' ' . $direction);

        return $query;
    }
}

