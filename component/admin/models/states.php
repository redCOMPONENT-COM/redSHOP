<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The states model
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model.States
 * @since       2.0.0.4
 */
class RedshopModelStates extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_states';

	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @param   array $config config params
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id',
				'country_id',
				'state_name',
				'state_3_code',
				'state_2_code',
				'check_out',
				'check_out_time',
				'show_state',
				'c.country_name', 'country_name'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string $id A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   2.0.0.4
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.country_id');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   2.0.0.4
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'state_name', $direction = '')
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$countryId = $this->getUserStateFromRequest($this->context . '.filter.country_id', 'filter_country_id');
		$this->setState('filter.country_id', $countryId);

		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to build query string
	 *
	 * @return  string
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	public function getListQuery()
	{
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true);
		$search    = $this->getState('filter.search');
		$countryId = $this->getState('filter.country_id');

		$query->select('s.*')
			->select($db->qn('c.country_name'))
			->from($db->qn('#__redshop_state', 's'))
			->join('LEFT', $db->qn('#__redshop_country', 'c') . ' ON (' . $db->qn('s.country_id') . ' = ' . $db->qn('c.id') . ')');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->qn('s.id') . ' = ' . $db->q((int) substr($search, 3)));
			}
			else
			{
				$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where($db->qn('s.state_name') . ' LIKE ' . $search);
			}
		}

		if (!empty($countryId))
		{
			$query->where($db->qn('s.country_id') . ' = ' . $db->q($countryId));
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to get country name
	 *
	 * @param   int $countryId An optional ordering field.
	 *
	 * @return  object
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	public function getCountryName($countryId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn('country_name'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_id') . ' = ' . $db->q($countryId));

		$db->setQuery($query);

		return $db->loadResult();
	}
}
