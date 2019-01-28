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
 * Model Countries
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0.8
 */
class RedshopModelZipcodes extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_zipcodes';

	/**
	 * Construct class
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function __construct()
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'z.id',
				'country_code', 'z.country_code',
				'state_code', 'z.state_code',
				'city_name', 'z.city_name',
				'zipcode', 'z.zipcode',
				'zipcodeto', 'z.zipcodeto',
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
	 * @return  parent
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// List state information.
		return parent::populateState($ordering, $direction);
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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(
			$db->qn(
				[
					'z.id',
					'z.country_code',
					'z.state_code',
					'z.city_name',
					'z.zipcode',
					'z.zipcodeto',
					'c.country_name',
					's.state_name',
				]
			)
		)
			->from($db->qn('#__redshop_zipcode', 'z'))
			->leftJoin($db->qn('#__redshop_country', 'c') . ' ON ' . $db->qn('z.country_code') . ' = ' . $db->qn('c.country_3_code'))
			->leftJoin(
				$db->qn('#__redshop_state', 's')
				. ' ON ' . $db->qn('z.state_code') . ' = ' . $db->qn('s.state_2_code')
				. ' AND ' . $db->qn('c.id') . ' = ' . $db->qn('s.country_id')
			);
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$query->where($db->qn('z.zipcode') . ' LIKE ' . $db->q('%' . $search . '%'));
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'z.id');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * [getCountryName function]
	 *
	 * @param   integer  $countryId  Id of country
	 *
	 * @return  object
	 */
	public function getCountryName($countryId)
	{
		$query = $this->_db->getQuery(true)
			->select('c.country_name')
			->from($this->_db->qn('#__redshop_country AS c'))
			->where($this->_db->qn('c.id') . " = " . $this->_db->q($countryId));

		return $this->_db->setQuery($query)->loadResult();
	}
}