<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelMail extends RedshopModel
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
		// Compile the store id.
		$id .= ':' . $this->getState('filter');
		$id .= ':' . $this->getState('filter_section');

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
	protected function populateState($ordering = 'm.mail_id', $direction = '')
	{
		$filter = $this->getUserStateFromRequest($this->context . 'filter', 'filter', '');
		$filter_section = $this->getUserStateFromRequest($this->context . 'filter_section', 'filter_section', 0);

		$this->setState('filter', $filter);
		$this->setState('filter_section', $filter_section);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$filter = $this->getState('filter');
		$filter_section = $this->getState('filter_section');
		$orderby = $this->_buildContentOrderBy();
		$where = '';

		if ($filter)
		{
			$where .= "AND mail_name LIKE '" . $filter . "%' ";
		}
		if ($filter_section)
		{
			$where .= "AND mail_section='" . $filter_section . "' ";
		}
		$query = "SELECT distinct(m.mail_id),m.* FROM #__redshop_mail AS m "
			. "WHERE 1=1 "
			. $where
			. $orderby;

		return $query;
	}
}
