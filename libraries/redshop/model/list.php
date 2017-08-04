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
		$view = $input->getString('view', '');
		$option = $input->getString('option', '');

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

		if ($this->limitstartField == 'auto')
		{
			$this->limitstartField = $this->paginationPrefix . 'limitstart';
		}

		if ($this->limitField == 'auto')
		{
			$this->limitField = $this->paginationPrefix . 'limit';
		}

		parent::__construct($config);
	}

	/**
	 * Gets an array of objects from the results of database query.
	 *
	 * @param   string   $query       The query.
	 * @param   integer  $limitstart  Offset.
	 * @param   integer  $limit       The number of records.
	 *
	 * @return  array  An array of results.
	 *
	 * @since   12.2
	 * @throws  RuntimeException
	 */
	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		// Disable limit for CSV export
		if ($this->getState('streamOutput', '') == 'csv')
		{
			$this->_db->setQuery($query);
		}
		else
		{
			$this->_db->setQuery($query, $limitstart, $limit);
		}

		$result = $this->_db->loadObjectList();

		return $result;
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
}
