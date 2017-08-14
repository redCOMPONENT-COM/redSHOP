<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Redshopb list Model
 *
 * @package     Redshopb
 * @subpackage  List
 * @since       1.0
 */
class RedshopModelList extends JModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = null;

	/**
	 * Associated HTML form
	 *
	 * @var  string
	 */
	protected $htmlFormName = 'adminForm';

	/**
	 * Array of form objects.
	 *
	 * @var  JForm[]
	 */
	protected $forms = array();

	/**
	 * A prefix for pagination request variables.
	 *
	 * @var  string
	 */
	protected $paginationPrefix = '';

	/**
	 * Limit field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitField = 'auto';

	/**
	 * Limitstart field used by the pagination
	 *
	 * @var  string
	 */
	protected $limitstartField = 'auto';

	/**
	 * A blacklist of filter variables to not merge into the model's state
	 *
	 * @var    array
	 * @since  1.6.10
	 */
	protected $filterBlacklist = array();

	/**
	 * A blacklist of list variables to not merge into the model's state
	 *
	 * @var    array
	 * @since  1.6.10
	 */
	protected $listBlacklist = array('select');

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
		$view = $input->getCmd('view', '');
		$option = $input->getCmd('option', '');

		// Different context depending on the view
		if (empty($this->context))
		{
			$this->context = strtolower($option . '.' . $view . '.' . $this->getName());
		}

		// Different pagination depending on the view
		if (empty($this->paginationPrefix))
		{
			$this->paginationPrefix = strtolower($option . '_' . $view . '_' . $this->getName() . '_');
		}

		if ($this->limitstartField === 'auto')
		{
			$this->limitstartField = $this->paginationPrefix . 'limitstart';
		}

		if ($this->limitField === 'auto')
		{
			$this->limitField = $this->paginationPrefix . 'limit';
		}

		parent::__construct($config);
	}

	/**
	 * Delete items
	 *
	 * @param   mixed  $pks  id or array of ids of items to be deleted
	 *
	 * @return  boolean
	 */
	public function delete($pks = null)
	{
		// Initialise variables.
		$table = $this->getTable();
		$table->delete($pks);

		return true;
	}

	/**
	 * Function to get the active filters
	 *
	 * @return  array  Associative array in the format: array('filter_published' => 0)
	 *
	 * @since   3.2
	 */
	public function getActiveFilters()
	{
		$activeFilters = array();

		if (!empty($this->filter_fields))
		{
			foreach ($this->filter_fields as $filter)
			{
				$filterName = 'filter.' . $filter;

				if (property_exists($this->state, $filterName) && (!empty($this->state->{$filterName}) || is_numeric($this->state->{$filterName})))
				{
					$activeFilters[$filter] = $this->state->get($filterName);
				}
			}
		}

		return $activeFilters;
	}

	/**
	 * Get the zone form
	 *
	 * @param   array    $data      data
	 * @param   boolean  $loadData  load current data
	 *
	 * @return  JForm/false  the JForm object or false
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$form = null;

		if (!empty($this->filterFormName))
		{
			// Get the form.
			$form = $this->loadForm(
				$this->context . '.filter',
				$this->filterFormName,
				array('control' => '', 'load_data' => $loadData)
			);

			if (!empty($form))
			{
				$form->setFieldAttribute($this->limitField, 'default', JFactory::getApplication()->getCfg('list_limit'), 'list');
			}
		}

		return $form;
	}

	/**
	 * Method to get the associated form name
	 *
	 * @return  string  The name of the form
	 */
	public function getHtmlFormName()
	{
		return $this->htmlFormName;
	}

	/**
	 * Method to get a JPagination object for the data set.
	 *
	 * @return  JPagination  A JPagination object for the data set.
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
		$limit = (int) $this->getState('list.limit') - (int) $this->getState('list.links');
		$page = new JPagination($this->getTotal(), $this->getStart(), $limit, $this->paginationPrefix);

		// Set the name of the HTML form associated
		$page->set('formName', $this->htmlFormName);

		// Add the object to the internal cache.
		$this->cache[$store] = $page;

		return $this->cache[$store];
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
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// If the context is set, assume that stateful lists are used.
		if (!$this->context)
		{
			$this->setState('list.start', 0);
			$this->setState('list.limit', 0);

			return;
		}

		$app         = JFactory::getApplication();
		$inputFilter = JFilterInput::getInstance();

		// Load the parameters for frontend.
		$params = $app->isSite() ? $app->getParams() : null;

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				// Exclude if blacklisted
				if (!in_array($name, $this->filterBlacklist))
				{
					$this->setState('filter.' . $name, $value);
				}
			}
		}

		$limit = 0;

		// Receive & set list options
		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Exclude if blacklisted
				if (!in_array($name, $this->listBlacklist))
				{
					// Extra validations
					switch ($name)
					{
						case 'fullordering':
							$orderingParts = explode(' ', $value);

							if (count($orderingParts) >= 2)
							{
								// Latest part will be considered the direction
								$fullDirection = end($orderingParts);

								if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
								{
									$this->setState('list.direction', $fullDirection);
								}
								else
								{
									$this->setState('list.direction', $direction);

									// Fallback to the default value
									$value = $ordering . ' ' . $direction;
								}

								unset($orderingParts[count($orderingParts) - 1]);

								// The rest will be the ordering
								$fullOrdering = implode(' ', $orderingParts);

								if (in_array($fullOrdering, $this->filter_fields))
								{
									$this->setState('list.ordering', $fullOrdering);
								}
								else
								{
									$this->setState('list.ordering', $ordering);

									// Fallback to the default value
									$value = $ordering . ' ' . $direction;
								}
							}
							else
							{
								$this->setState('list.ordering', $ordering);
								$this->setState('list.direction', $direction);

								// Fallback to the default value
								$value = $ordering . ' ' . $direction;
							}
							break;

						case 'ordering':
							if (!in_array($value, $this->filter_fields))
							{
								$value = $ordering;
							}
							break;

						case 'direction':
							if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
							{
								$value = $direction;
							}
							break;

						case $this->limitField:
						case 'limit':
							$value = $inputFilter->clean($value, 'int');
							$this->setState('list.limit', $value);
							$limit = $value;
							break;

						case $this->limitstartField:
							$value = $inputFilter->clean($value, 'int');
							break;

						case 'select':
							$explodedValue = explode(',', $value);

							foreach ($explodedValue as &$field)
							{
								$field = $inputFilter->clean($field, 'cmd');
							}

							$value = implode(',', $explodedValue);
							break;
					}

					$this->setState('list.' . $name, $value);
				}
			}
		}
		else
			// Keep B/C for components previous to jform forms for filters
		{
			// Pre-fill the limits
			$defaultLimit = $params ? $params->get('list_limit', $app->get('list_limit')) : $app->get('list_limit');
			$limit = $app->getUserStateFromRequest('global.list.' . $this->limitField, $this->limitField, $defaultLimit, 'uint');
			$this->setState('list.limit', $limit);

			// Check if the ordering field is in the white list, otherwise use the incoming value.
			$value = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);

			if (!in_array($value, $this->filter_fields))
			{
				$value = $ordering;
				$app->setUserState($this->context . '.ordercol', $value);
			}

			$this->setState('list.ordering', $value);

			// Check if the ordering direction is valid, otherwise use the incoming value.
			$value = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $direction);

			if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
			{
				$value = $direction;
				$app->setUserState($this->context . '.orderdirn', $value);
			}

			$this->setState('list.direction', $value);
		}

		// Support old ordering field
		$oldOrdering = $app->input->get('filter_order');

		if (!empty($oldOrdering) && in_array($oldOrdering, $this->filter_fields))
		{
			$this->setState('list.ordering', $oldOrdering);
		}

		// Support old direction field
		$oldDirection = $app->input->get('filter_order_Dir');

		if (!empty($oldDirection) && in_array(strtoupper($oldDirection), array('ASC', 'DESC', '')))
		{
			$this->setState('list.direction', $oldDirection);
		}

		$value = $app->getUserStateFromRequest($this->context . '.' . $this->limitstartField, $this->limitstartField, 0, 'int');
		$limitstart = ($limit > 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);
	}

	/**
	 * Method to get a form object.
	 *
	 * @param   string   $name     The name of the form.
	 * @param   string   $source   The form source. Can be XML string if file flag is set to false.
	 * @param   array    $options  Optional array of options for the form creation.
	 * @param   boolean  $clear    Optional argument to force load a new form.
	 * @param   mixed    $xpath    An optional xpath to search for the fields.
	 *
	 * @return  mixed  JForm object on success, False on error.
	 *
	 * @see     JForm
	 */
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);

		// Create a signature hash.
		$hash = md5($source . serialize($options));

		// Check if we can use a previously loaded form.
		if (isset($this->forms[$hash]) && !$clear)
		{
			return $this->forms[$hash];
		}

		// Get the form.
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

		try
		{
			$form = JForm::getInstance($name, $source, $options, false, $xpath);

			if (isset($options['load_data']) && $options['load_data'])
			{
				// Get the data for the form.
				$data = $this->loadFormData();
			}
			else
			{
				$data = array();
			}

			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);

			// Filter and validate the form data.
			$data = $form->filter($data);

			// Load the data into the form after the plugins have operated.
			$form->bind($data);
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Store the form for later.
		$this->forms[$hash] = $form;

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState($this->context, array());

		return $data;
	}

	/**
	 * Method to allow derived classes to preprocess the form.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		// Import the appropriate plugin group.
		JPluginHelper::importPlugin($group);

		// Get the dispatcher.
		$dispatcher = RedshopHelperUtility::getDispatcher();

		// Trigger the form preparation event.
		$results = $dispatcher->trigger('onContentPrepareForm', array($form, $data));

		// Check for errors encountered while preparing the form.
		if (count($results) && in_array(false, $results, true))
		{
			// Get the last error.
			$error = $dispatcher->getError();

			if (!($error instanceof Exception))
			{
				throw new Exception($error);
			}
		}
	}

	/**
	 * Publish/Unpublish items
	 *
	 * @param   mixed    $pks    id or array of ids of items to be published/unpublished
	 * @param   integer  $state  New desired state
	 *
	 * @return  boolean
	 */
	public function publish($pks = null, $state = 1)
	{
		// Initialise variables.
		$table = $this->getTable();
		$table->publish($pks, $state);

		return true;
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param   JForm   $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 *
	 * @see     JFormRule
	 * @see     JFilterInput
	 * @since   1.7
	 */
	public function validate($form, $data, $group = null)
	{
		// Filter and validate the form data.
		$data = $form->filter($data);
		$return = $form->validate($data, $group);

		// Check for an error.
		if ($return instanceof Exception)
		{
			$this->setError($return->getMessage());

			return false;
		}

		// Check the validation results.
		if ($return === false)
		{
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message)
			{
				$this->setError($message);
			}

			return false;
		}

		return $data;
	}
}
