<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelTemplate extends RedshopModel
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
		$id .= ':' . $this->getState('template_section');

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
	protected function populateState($ordering = 'template_id', $direction = 'desc')
	{
		$template_section = $this->getUserStateFromRequest($this->context . '.template_section', 'template_section', 0);
		$filter = $this->getUserStateFromRequest($this->context . '.filter', 'filter', '');

		$this->setState('filter', $filter);
		$this->setState('template_section', $template_section);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$filter           = $this->getState('filter');
		$template_section = $this->getState('template_section');

		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('distinct(t.template_id)')
			->select('t.*')
			->from($db->qn('#__redshop_template', 't'));

		if (!empty($filter))
		{
			$query->where($db->qn('t.template_name') . 'LIKE ' . $db->q('%' . $filter . '%'));
		}

		if ($template_section)
		{
			$query->where($db->qn('t.template_section') . 'LIKE ' . $db->q($template_section));
		}

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=t.checked_out');

		$filter_order_Dir = $this->getState('list.direction');
		$filter_order = $this->getState('list.ordering');
		$query->order($db->escape($filter_order . ' ' . $filter_order_Dir));

		return $query;
	}
}
