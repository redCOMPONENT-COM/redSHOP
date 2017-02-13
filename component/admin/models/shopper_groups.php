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
 * Redshop Shopper Groups Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Shopper_Groups
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelShopper_Groups extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_shopper_groups';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'shopper_group_id', 'sg.shopper_group_id',
				'shopper_group_name', 'sg.shopper_group_name',
				'published', 'sg.published',
				'shopper_group_customer_type', 'sg.shopper_group_customer_type',
				'shopper_group_portal', 'sg.shopper_group_portal'
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

		$filter = $this->getUserStateFromRequest($this->context . '.filter.customer_type', 'filter_customer_type');
		$this->setState('filter.customer_type', $filter);

		// List state information.
		parent::populateState('sg.shopper_group_id', 'asc');
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
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.customer_type');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_shopper_group', 'sg'));

		// Filter by search in name.
		$search = $this->getState('filter.search', null);

		if (!empty($search))
		{
			$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query->where($db->qn('sg.shopper_group_name') . ' LIKE ' . $search);
		}

		// Filter by customer_type
		$filterCustomerType = $this->getState('filter.customer_type', null);

		if (is_numeric($filterCustomerType))
		{
			$query->where($db->qn('sg.shopper_group_customer_type') . ' = ' . (int) $filterCustomerType);
		}

		// Filter by published.
		$filterPublished = $this->getState('filter.published', null);

		if (is_numeric($filterPublished))
		{
			$query->where($db->qn('sg.published') . ' = ' . (int) $filterPublished);
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'sg.shopper_group_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems()
	{
		$items = parent::getItems();

		if (!empty($items))
		{
			// Establish the hierarchy of the menu
			$children = array();

			// First pass - collect children
			foreach ($items as $item)
			{
				$item->title = $item->shopper_group_name;
				$item->id = $item->shopper_group_id;
				$parentItem = $item->parent_id;
				$list = @$children[$parentItem] ? $children[$parentItem] : array();
				array_push($list, $item);
				$children[$parentItem] = $list;
			}

			// Second pass - get an indent list of the items
			$items = RedshopHelperUtility::createTree(0, '<sup>|_</sup>&nbsp;', array(), $children);
		}

		return $items;
	}
}
