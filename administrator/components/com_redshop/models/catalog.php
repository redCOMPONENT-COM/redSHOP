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
 * Catalog Model.
 *
 * @package        redSHOP
 * @subpackage     Models
 * @since          1.2
 */
class RedshopModelCatalog extends JModelList
{
    /**
     * Context string for the model type.  This is used to handle uniqueness
     * when dealing with the getStoreId() method and caching data structures.
     *
     * @var    string
     */
    protected $context = 'sample_id';

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     */
    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        $ordering  = $db->escape($this->getState('list.ordering', 'catalog_id'));
        $direction = $db->escape($this->getState('list.direction', 'DESC'));

        $query = $db->getQuery(true)
            ->select('distinct(c.catalog_id), c.*')
            ->from('#__redshop_catalog as c')
            ->order($ordering . ' ' . $direction);

        return $query;
    }

    public function MediaDetail($pid)
    {
        $query = 'SELECT * FROM ' . $this->_table_prefix . 'media  WHERE section_id =' . $pid . ' AND media_section = "catalog"';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
    }
}
