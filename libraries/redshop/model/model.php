<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Redshop Model
 *
 * @package     Redshop.library
 * @subpackage  Model
 * @since       1.4
 */
class RedshopModel extends JModelLegacy
{
	/**
	 * Internal memory based cache array of data.
	 *
	 * @var    array
	 * @since  1.5
	 */
	protected $cache = array();

	/**
	 * An internal cache for the last query used.
	 *
	 * @var    JDatabaseQuery|string
	 * @since  1.5
	 */
	protected $query = array();

	/**
	 * Context string for the model type.  This is used to handle uniqueness
	 * when dealing with the getStoreId() method and caching data structures.
	 *
	 * @var    string
	 */
	public $context = null;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 */
	public function __construct($config = array())
	{
		$input = JFactory::getApplication()->input;
		$view = $input->getString('view', '');
		$option = $input->getString('option', '');

		// Different context depending on the view
		if (empty($this->context))
		{
			$this->context = strtolower($option . '.' . $view . '.' . $this->getName());
		}

		parent::__construct($config);
	}

	/**
	 * Method to rename value to unique in current table.
	 *
	 * @param   string  $fieldName   Field name
	 * @param   string  $fieldValue  Field value
	 * @param   string  $style       The the style (default|dash)
	 * @param   string  $tableName   Use table with name in value
	 *
	 * @return  string  Unique field value
	 *
	 * @since   1.5
	 */
	protected function renameToUniqueValue($fieldName, $fieldValue, $style = 'default', $tableName = '')
	{
		$table = $this->getTable($tableName);

		while ($table->load(array($fieldName => $fieldValue)))
		{
			$fieldValue = JString::increment($fieldValue, $style);
		}

		return $fieldValue;
	}

	/**
	 * Gets the value of a user state variable and sets it in the session
	 *
	 * This is the same as the method in JApplication except that this also can optionally
	 * force you back to the first page when a filter has changed
	 *
	 * @param   string   $key        The key of the user state variable.
	 * @param   string   $request    The name of the variable passed in a request.
	 * @param   string   $default    The default value for the variable if not found. Optional.
	 * @param   string   $type       Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
	 * @param   boolean  $resetPage  If true, the limitstart in request is set to zero
	 *
	 * @return  The request user state.
	 *
	 * @since   1.5
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none', $resetPage = true)
	{
		$app       = JFactory::getApplication();
		$input     = $app->input;
		$old_state = $app->getUserState($key);
		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = $input->get($request, null, $type);

		if (($cur_state != $new_state) && $resetPage && $new_state)
		{
			$input->set('limitstart', 0);
		}

		// Save the new value only if it is set in this request.
		if ($new_state !== null)
		{
			$app->setUserState($key, $new_state);
		}
		else
		{
			$new_state = $cur_state;
		}

		return $new_state;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->context . 'filter_order', 'filter_order', $ordering);
		$this->setState('list.ordering', $filter_order);

		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $direction);

		if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
		{
			$value = $direction;
			$app->setUserState($this->context . '.orderdirn', $value);
		}

		$this->setState('list.direction', $value);

		$limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		$this->setState('limit', $limit);

		$value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get the starting number of items for the data set.
	 *
	 * @return  integer  The starting number of items available in the data set.
	 *
	 * @since   1.5
	 */
	public function getStart()
	{
		$store = $this->getStoreId('getstart');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$start = $this->getState('limitstart');
		$limit = $this->getState('limit');
		$total = $this->getTotal();

		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}

		// Add the total to the internal cache.
		$this->cache[$store] = $start;

		return $this->cache[$store];
	}

	/**
	 * Method to get the total number of items for the data set.
	 *
	 * @return  integer  The total number of items available in the data set.
	 *
	 * @since   1.5
	 */
	public function getTotal()
	{
		// Get a storage key.
		$store = $this->getStoreId('getTotal');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the total.
		$query = $this->_getListQuery();

		try
		{
			$total = (int) $this->_getListCount($query);
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Add the total to the internal cache.
		$this->cache[$store] = $total;

		return $this->cache[$store];
	}

	/**
	 * Method to cache the last query constructed.
	 *
	 * This method ensures that the query is constructed only once for a given state of the model.
	 *
	 * @return  JDatabaseQuery  A JDatabaseQuery object
	 *
	 * @since   1.5
	 */
	protected function _getListQuery()
	{
		// Capture the last store id used.
		static $lastStoreId;

		// Compute the current store id.
		$currentStoreId = $this->getStoreId();

		// If the last store id is different from the current, refresh the query.
		if ($lastStoreId != $currentStoreId || empty($this->query))
		{
			$lastStoreId = $currentStoreId;
			$this->query = $this->_buildQuery();
		}

		return $this->query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.5
	 */
	public function getData()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$query = $this->_getListQuery();

		try
		{
			$items = $this->_getList($query, $this->getStart(), (int) $this->getState('limit'));
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$this->preprocessData($this->context, $items);

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Set order by values
	 *
	 * @return string
	 *
	 * @since   1.5
	 */
	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$filter_order_Dir = $this->getState('list.direction');
		$filter_order = $this->getState('list.ordering');

		return ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);
	}

	/**
	 * Method to get a JPagination object for the data set.
	 *
	 * @return  JPagination  A JPagination object for the data set.
	 *
	 * @since   1.5
	 */
	public function getPagination()
	{
		// Get a storage key.
		$store = $this->getStoreId('getPagination');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Create the pagination object.
		jimport('joomla.html.pagination');
		$limit = (int) $this->getState('limit') - (int) $this->getState('list.links');
		$page = new JPagination($this->getTotal(), $this->getStart(), $limit);

		// Add the object to the internal cache.
		$this->cache[$store] = $page;

		return $this->cache[$store];
	}

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.5
	 */
	protected function _buildQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		return $query;
	}

	/**
	 * Method to get a store id based on the model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  An identifier string to generate the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		// Add the list state to the store id.
		$id .= ':' . $this->getState('limitstart');
		$id .= ':' . $this->getState('limit');
		$id .= ':' . $this->getState('list.ordering');
		$id .= ':' . $this->getState('list.direction');

		return md5($this->context . ':' . $id);
	}

	/**
	 * Method to allow derived classes to preprocess the data.
	 *
	 * @param   string  $context  The context identifier.
	 * @param   mixed   &$data    The data to be processed. It gets altered directly.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	protected function preprocessData($context, &$data)
	{
		// Get the dispatcher and load the users plugins.
		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('redshop');

		// Trigger the data preparation event.
		$results = $dispatcher->trigger('onRedshopPrepareData', array($context, &$data));

		// Check for errors encountered while preparing the data.
		if (count($results) > 0 && in_array(false, $results, true))
		{
			$this->setError($dispatcher->getError());
		}
	}

	/*
	 * Method for saving data via webservices
	 *
	 * @param   array  $data  Data to be stored.
	 *
	 * @return  integer|boolean
	 */
	public function saveWS($data)
	{
		$table = $this->getTable();
		$pkField = $table->getKeyName();

		try
		{
			if (!$table->save($data))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			return false;
		}

		return $table->get($pkField);
	}

	/**
	 * Delete an item from the web service
	 *
	 * @param   mixed  $pk  PK to be found to delete (internal id)
	 *
	 * @return  boolean
	 */
	public function deleteWS($pk)
	{
		$table = $this->getTable();

		try
		{
			if (!$result = $table->delete($pk))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			return false;
		}

		return $result;
	}
}
