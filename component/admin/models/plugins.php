<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

/**
 * Redshop categories Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Plugins
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelPlugins extends RedshopModelList
{
    /**
     * Construct class
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'extension_id',
                'a.extension_id',
                'name',
                'a.name',
                'folder',
                'a.folder',
                'element',
                'a.element',
                'checked_out',
                'a.checked_out',
                'checked_out_time',
                'a.checked_out_time',
                'state',
                'a.state',
                'enabled',
                'a.enabled',
                'access',
                'a.access',
                'access_level',
                'ordering',
                'a.ordering',
                'client_id',
                'a.client_id',
            );
        }

        parent::__construct($config);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db    = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.extension_id as id, a.name, a.element, a.folder, a.checked_out, a.checked_out_time,a.manifest_cache,' .
                ' a.checked_out, a.checked_out_time, a.enabled as published, a.enabled, a.access, a.ordering'
            )
        )
            ->from($db->qn('#__extensions') . ' AS a')
            ->where($db->qn('type') . ' = ' . $db->q('plugin'));

        if ($this->getState('filter.search_type') !== 'all') {
            $query->where($db->qn('a.manifest_cache') . 'LIKE ' . $db->q('%redCOMPONENT.com%'));
        }

        // Join over the users for the checked out user.
        $query->select('uc.name AS editor')
            ->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the asset groups.
        $query->select('ag.title AS access_level')
            ->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

        // Filter by access level.
        if ($access = $this->getState('filter.access')) {
            $query->where('a.access = ' . (int) $access);
        }

        // Filter by published state.
        $published = $this->getState('filter.enabled');

        if (is_numeric($published)) {
            $query->where('a.enabled = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(a.enabled IN (0, 1))');
        }

        // Filter by state.
        $query->where('a.state >= 0');

        // Filter by folder.
        if ($folder = $this->getState('filter.folder')) {
            $query->where('a.folder = ' . $db->q($folder));
        }

        // Filter by element.
        if ($element = $this->getState('filter.element')) {
            $query->where('a.element = ' . $db->q($element));
        }

        // Filter by search in name or id.
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.extension_id = ' . (int) substr($search, 3));
            }
        }

        return $query;
    }

    /**
     * Method to auto-populate the model state.
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
    protected function populateState($ordering = 'folder', $direction = 'asc')
    {
        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string');
        $this->setState('filter.search', $search);

        $accessId = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', '', 'cmd');
        $this->setState('filter.access', $accessId);

        $state = $this->getUserStateFromRequest($this->context . '.filter.enabled', 'filter_enabled', '', 'cmd');
        $this->setState('filter.enabled', $state);

        $folder = $this->getUserStateFromRequest($this->context . '.filter.folder', 'filter_folder', '', 'string');
        $this->setState('filter.folder', $folder);

        $element = $this->getUserStateFromRequest($this->context . '.filter.element', 'filter_element', '', 'string');
        $this->setState('filter.element', $element);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_plugins');
        $this->setState('params', $params);

        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Returns an object list.
     *
     * @param JDatabaseQuery $query      A database query object.
     * @param integer        $limitstart Offset.
     * @param integer        $limit      The number of records.
     *
     * @return  array
     */
    protected function _getList($query, $limitstart = 0, $limit = 0)
    {
        $search   = $this->getState('filter.search');
        $ordering = $this->getState('list.ordering', 'ordering');

        // If "Sort Table By:" is not set, set ordering to name
        if ($ordering == '') {
            $ordering = 'name';
        }

        if ($ordering == 'name' || (!empty($search) && stripos($search, 'id:') !== 0)) {
            $this->_db->setQuery($query);
            $result = $this->_db->loadObjectList();
            $this->translate($result);

            if (!empty($search)) {
                $escapedSearchString = $this->refineSearchStringToRegex($search, '/');

                foreach ($result as $i => $item) {
                    if (!preg_match("/$escapedSearchString/i", $item->name)) {
                        unset($result[$i]);
                    }
                }
            }

            $orderingDirection = strtolower($this->getState('list.direction'));
            $direction         = ($orderingDirection == 'desc') ? -1 : 1;
            $result            = ArrayHelper::sortObjects($result, $ordering, $direction, true, true);

            $total                                      = count($result);
            $this->cache[$this->getStoreId('getTotal')] = $total;

            if ($total < $limitstart) {
                $limitstart = 0;
                $this->setState('list.start', 0);
            }

            return array_slice($result, $limitstart, $limit ?: null);
        } else {
            if ($ordering == 'ordering') {
                $query->order('a.folder ASC');
                $ordering = 'a.ordering';
            }

            $query->order($this->_db->qn($ordering) . ' ' . $this->getState('list.direction'));

            if ($ordering == 'folder') {
                $query->order('a.ordering ASC');
            }

            $result = parent::_getList($query, $limitstart, $limit);
            $this->translate($result);

            return $result;
        }
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
            $item->name = Text::_($item->name);
        }
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
        $id .= ':' . $this->getState('filter.access');
        $id .= ':' . $this->getState('filter.enabled');
        $id .= ':' . $this->getState('filter.folder');
        $id .= ':' . $this->getState('filter.element');

        return parent::getStoreId($id);
    }


    /**
     * Publish/Unpublish items
     *
     * @param mixed   $pks   id or array of ids of items to be published/unpublished
     * @param integer $state New desired state
     *
     * @return  boolean
     */
    public function publish($pks = null, $state = 1)
    {
        // Initialise variables.
        Joomla\CMS\Table\Extension::getInstance('extension')->publish($pks, $state);

        return true;
    }

    /**
     * Method override to check-in a record or an array of record
     *
     * @param mixed $pks The ID of the primary key or an array of IDs
     *
     * @return  integer|boolean  Boolean false if there is an error, otherwise the count of records checked in.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function checkin($pks = array())
    {
        $pks   = (array) $pks;
        $table = Joomla\CMS\Table\Extension::getInstance('extension');
        $count = 0;

        if (empty($pks)) {
            $pks = array((int) $this->getState($this->getName() . '.id'));
        }

        $checkedOutField = $table->getColumnAlias('checked_out');

        // Check in all items.
        foreach ($pks as $pk) {
            if ($table->load($pk)) {
                if ($table->{$checkedOutField} > 0) {
                    if (!$table->checkin($pk)) {
                        return false;
                    }

                    $count++;
                }
            } else {
                $this->setError($table->getError());

                return false;
            }
        }

        return $count;
    }

}