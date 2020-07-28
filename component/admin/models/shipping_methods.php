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
                'extension_id',
                's.extension_id',
                'name',
                's.name',
                'element',
                's.element'
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
     * @param mixed  &$items The array of objects.
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