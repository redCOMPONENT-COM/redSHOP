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
 * Model Currencies
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0.6
 */
class RedshopModelCurrencies extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_currencies';

	/**
	 * Construct class
	 *
	 * @since 1.x
	 */

	public function __construct()
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'id',
				'currency_name', 'currency_name',
				'currency_code', 'currency_code',
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

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	public function getListQuery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$filter = $this->getState('filter.search');

		$query->select($db->qn(['id', 'currency_name', 'currency_code']))
			->from($db->qn('#__redshop_currency'));

		if ($filter)
		{
			$query->where(
				$db->qn('currency_name') . ' LIKE ' . $db->q('%' . $filter . '%')
				. 'OR ' . $db->qn('currency_code') . 'LIKE ' . $db->q($filter . '%')
			);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'currency_code');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
