<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelOpsearch extends RedshopModel
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
		$id .= ':' . $this->getState('filter_user');
		$id .= ':' . $this->getState('filter_product');
		$id .= ':' . $this->getState('filter_status');

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
	protected function populateState($ordering = 'order_item_name', $direction = '')
	{
		$filter_user = $this->getUserStateFromRequest($this->context . '.filter_user', 'filter_user', 0);
		$filter_product = $this->getUserStateFromRequest($this->context . '.filter_product', 'filter_product', 0);
		$filter_status = $this->getUserStateFromRequest($this->context . '.filter_status', 'filter_status', 0);

		$this->setState('filter_user', $filter_user);
		$this->setState('filter_product', $filter_product);
		$this->setState('filter_status', $filter_status);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$filter_user = $this->getState('filter_user', '');
		$filter_product = $this->getState('filter_product', '');
		$filter_status = $this->getState('filter_status', '');

		$where = '';

		if ($filter_user)
		{
			$where .= 'AND op.user_info_id="' . $filter_user . '" ';
		}

		if ($filter_product)
		{
			$where .= 'AND op.product_id ="' . $filter_product . '" ';
		}

		if ($filter_status)
		{
			$where .= 'AND op.order_status="' . $filter_status . '" ';
		}

		$query = 'SELECT op.*, CONCAT(ouf.firstname," ",ouf.lastname) AS fullname, ouf.company_name FROM #__redshop_order_item AS op '
			. 'LEFT JOIN #__redshop_order_users_info as ouf ON ouf.order_id=op.order_id AND ouf.address_type="BT" '
			. 'WHERE 1=1 '
			. $where
			. $orderby;

		return $query;
	}

	public function getuserlist($name = 'userlist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		$query = "SELECT uf.users_info_id AS value, CONCAT(uf.firstname,' ',uf.lastname) AS text FROM #__redshop_users_info AS uf "
			. "WHERE uf.address_type='BT' "
			. "ORDER BY text ";
		$userlist = $this->_getList($query);
		$types[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_USER') . ' -');
		$types = array_merge($types, $userlist);
		$mylist['userlist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $selected);

		return $mylist['userlist'];
	}
}
