<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Currencies model
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.11
 */
class RedshopModelCurrencies extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   2.0.11
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'c.id',
				'name', 'c.code',
				'code', 'c.type',
				'checked_out', 'c.checked_out',
				'checked_out_time', 'c.checked_out_time',
				'created_by', 'c.created_by',
				'created_date', 'c.created_date',
				'modified_by', 'c.modified_by',
				'modified_date', 'c.modified_date'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   2.0.11
	 */
	protected function populateState($ordering = 'c.id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest((string) $this->context . '.filter.search', 'filter_search');
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
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   2.0.11
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
	 * @return  JDatabaseQuery  An SQL query
	 *
	 * @since   2.0.11
	 */
	public function getListQuery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('c.*')
			->from($db->qn('#__redshop_currency', 'c'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$query->where('('
				. $db->qn('c.name') . ' LIKE ' . $db->quote('%' . $search . '%')
				. ' OR ' . $db->qn('c.code') . ' LIKE ' . $db->quote('%' . $search . '%')
				. ')'
			);
		}

		// Add the list ordering clause.
		$orderCol       = $this->state->get('list.ordering', 'c.id');
		$orderDirection = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirection));

		return $query;
	}
}
