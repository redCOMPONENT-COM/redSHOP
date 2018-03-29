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
 * The Catalogs model.
 *
 * @package     RedSHOP.Backend.
 * @subpackage  Model.Catalogs
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelCatalogs extends RedshopModelList
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc/desc).
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = 'c.catalog_id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest($this->context . 'filter.search', 'filter.search');
		$this->setState('filter.search', $search);

		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query string to load the list data.
	 *
	 * @return  string  An SQL query
	 */
	public function getListQuery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->qn('#__redshop_catalog', 'c'));

		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->qn('c.catalog_id') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where($db->qn('c.catalog_name') . ' LIKE ' . $search);
			}
		}

		$orderCol = $this->state->get('list.ordering', 'c.catalog_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
