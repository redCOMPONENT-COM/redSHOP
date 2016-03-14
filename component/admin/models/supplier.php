<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelSupplier extends RedshopModel
{
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
	protected function populateState($ordering = 'supplier_id', $direction = '')
	{
		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$filterOrderDir = $this->getState('list.direction');
		$filterOrder = $this->getState('list.ordering');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('s.*')
			->from($db->qn('#__redshop_supplier', 's'))
			->order($db->escape($filterOrder . ' ' . $filterOrderDir));

		return $query;
	}
}
