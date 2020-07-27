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
 * Model Stockrooms
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelStockrooms extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id',
				'name'
			);
		}

		parent::__construct($config);
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
	 * @param string $ordering  An optional ordering field.
	 * @param string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = 'id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest('id' . '.filter.search', 'filter_search');
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
	 * @param string $id A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getStoreId($id = '')
	{
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
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_stockroom'));

		$search = $this->getState('filter.search');

		if ($search) {
			$query->where(
				$db->qn('name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
			);
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	public function frontpublish($cids = array(), $publish = 1)
	{
		if (count($cids)) {
			$cid = implode(',', $cids);

			$query = $this->_db->getQuery(true)
				->update($this->_db->qn('#__redshop_stockroom'))
				->set($this->_db->qn('show_in_front') . ' = ' . $this->_db->q(intval($publish)))
				->where($this->_db->qn('id') . ' = ' . $this->_db->q((int) $cid));
			$this->_db->setQuery($query);

			if (!$this->_db->execute()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
}