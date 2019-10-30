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
 * Model Discounts
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.1.0
 */
class RedshopModelDiscounts extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   2.1.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'discount_id', 'd.discount_id',
				'name', 'd.name',
				'amount', 'd.amount',
				'condition', 'd.condition',
				'start_date', 'd.start_date',
				'end_date', 'd.end_date',
				'discount_amount', 'd.discount_amount',
				'discount_type', 'd.discount_type',
				'published', 'd.published'
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
	 * @since   2.1.0
	 */
	protected function populateState($ordering = 'd.discount_id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest((string) $this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$filterPublished = $this->getUserStateFromRequest((string) $this->context . '.filter.published', 'filter_published');
		$this->setState('filter.published', $filterPublished);

		$filterShopperGroup = $this->getUserStateFromRequest((string) $this->context . '.filter.shopper_group', 'filter_shopper_group');
		$this->setState('filter.shopper_group', $filterShopperGroup);

		$filterType = $this->getUserStateFromRequest((string) $this->context . '.filter.type', 'filter_type');
		$this->setState('filter.type', $filterType);

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
	 * @since   2.1.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery  An SQL query
	 *
	 * @since   2.1.0
	 */
	public function getListQuery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('d.*')
			->from($db->qn('#__redshop_discount', 'd'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$query->where($db->qn('d.name') . ' LIKE ' . $db->quote('%' . $search . '%'));
		}

		// Filter: Published
		$filterPublished = $this->getState('filter.published', null);

		if (is_numeric($filterPublished))
		{
			$query->where($db->qn('d.published') . ' = ' . (int) $filterPublished);
		}
		else
		{
			$query->where($db->qn('d.published') . ' IN (0,1)');
		}

		// Filter: Shopper Group
		$filterShopperGroup = $this->getState('filter.shopper_group', null);

		if ($filterShopperGroup)
		{
			$subQuery = $db->getQuery(true)
				->select('DISTINCT(' . $db->qn('discount_id') . ')')
				->from($db->qn('#__redshop_discount_shoppers'))
				->where($db->qn('shopper_group_id') . ' = ' . (int) $filterShopperGroup);

			$query->where($db->qn('d.discount_id') . ' IN (' . $subQuery . ')');
		}

		// Filter: Type
		$filterType = $this->getState('filter.type', null);

		if (is_numeric($filterType))
		{
			$query->where($db->qn('d.discount_type') . ' = ' . (int) $filterType);
		}

		// Add the list ordering clause.
		$orderCol       = $this->state->get('list.ordering', 'd.discount_id');
		$orderDirection = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirection));

		return $query;
	}
}
