<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelStockroom extends RedshopModel
{
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
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'stockroom_id', $direction = '')
	{
		$filter = $this->getUserStateFromRequest($this->context . '.filter', 'filter', '');
		$this->setState('filter', $filter);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$filter = $this->getState('filter');
		$orderby = $this->_buildContentOrderBy();
		$where = '';

		if ($filter)
		{
			$where = " WHERE stockroom_name like '%" . $filter . "%' ";
		}

		$query = "SELECT distinct(s.stockroom_id),s.* FROM #__redshop_stockroom s" . $where . $orderby;

		return $query;
	}
}
