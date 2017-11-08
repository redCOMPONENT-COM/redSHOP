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
 * Model Tax Rates
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0.6
 */
class RedshopModelTax_Rates extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   2.x
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 't.id',
				'name', 't.name',
				'tax_rate', 't.tax_rate',
				'is_eu_country', 't.is_eu_country',
				'tax_country', 't.tax_country', 'country_name',
				'tax_state', 't.tax_state', 'state_name',
				'tax_group_id', 't.tax_group_id', 'tax_group_name'
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
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState('t.id', 'asc');
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
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.country');
		$id .= ':' . $this->getState('filter.tax_group');
		$id .= ':' . $this->getState('filter.eu');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	public function getListQuery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('t.*')
			->select($db->qn('c.country_name', 'country_name'))
			->select($db->qn('s.state_name', 'state_name'))
			->select($db->qn('g.name', 'tax_group_name'))
			->from($db->qn('#__redshop_tax_rate', 't'))
			->leftJoin($db->qn('#__redshop_country', 'c') . ' ON ' . $db->qn('t.tax_country') . ' = ' . $db->qn('c.country_3_code'))
			->leftJoin($db->qn('#__redshop_state', 's') . ' ON ' . $db->qn('t.tax_state') . ' = ' . $db->qn('s.state_3_code'))
			->leftJoin($db->qn('#__redshop_tax_group', 'g') . ' ON ' . $db->qn('t.tax_group_id') . ' = ' . $db->qn('g.id'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->qn('t.id') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where($db->qn('t.name') . ' LIKE ' . $search);
			}
		}

		// Filter: Country
		$filterCountry = $this->getState('filter.country', '');

		if ($filterCountry)
		{
			$query->where($db->qn('t.tax_country') . ' = ' . $db->quote($filterCountry));
		}

		// Filter: Country
		$filterRaxGroup = (int) $this->getState('filter.tax_group', 0);

		if ($filterRaxGroup)
		{
			$query->where($db->qn('t.tax_group_id') . ' = ' . $filterRaxGroup);
		}

		// Filter: EU
		$filterEU = $this->getState('filter.eu', null);

		if (is_numeric($filterEU))
		{
			$query->where($db->qn('t.is_eu_country') . ' = ' . (int) $filterEU);
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 't.id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		$query->group($db->qn('t.id'));

		return $query;
	}
}
