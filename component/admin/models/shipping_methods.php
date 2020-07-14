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
 * Model Shipping methods
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelShipping_Methods extends RedshopModelList
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
                'z.id'
            );
        }

        parent::__construct($config);
    }

    /**
     * Get the associated JTable
     *
     * @param string $name   Table name
     * @param string $prefix Table prefix
     * @param array  $config Configuration array
     *
     * @return  JTable
     *
     * @throws  Exception
     */
    public function getTable($name = 'extension', $prefix = 'Table', $options = array())
    {
        return Joomla\CMS\Table\Extension::getInstance('extension');
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
    protected function populateState($ordering = 's.extension_id', $direction = 'asc')
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
        // Compile the store id.
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
            ->select('s.*')
            ->from($db->qn('#__extensions', 's'))
            ->where($db->qn('s.folder') . ' = "redshop_shipping"');

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', 's.extension_id');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /**
     * Method to get an array of data items.
     *
     * @return  mixed  An array of data items on success, false on failure.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getItems()
    {
        $items = parent::getItems();
        $this->translate($items);

        return $items;
    }

    /**
     * Translate a list of objects.
     *
     * @param array  &$items The array of objects.
     *
     * @return  array The array of translated objects.
     */
    protected function translate(&$items)
    {
        $lang = JFactory::getLanguage();

        foreach ($items as &$item) {
            $source    = JPATH_PLUGINS . '/' . $item->folder . '/' . $item->element;
            $extension = 'plg_' . $item->folder . '_' . $item->element;
            $lang->load($extension . '.sys', JPATH_ADMINISTRATOR, null, false, true)
            || $lang->load($extension . '.sys', $source, null, false, true);
            $item->name = JText::_($item->name);
        }
    }
}