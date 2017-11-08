<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelDiscount extends RedshopModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 */
	public function __construct($config = array())
	{
		// Different context depending on the view
		if (empty($this->context))
		{
			$this->input = JFactory::getApplication()->input;
			$view = $this->input->getCmd('view', '');
			$layout = $this->input->getCmd('layout', '');
			$this->context = strtolower('com_redshop.' . $view . '.' . $this->getName() . '.' . $layout);
		}

		parent::__construct($config);
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
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('spgrpdis_filter');
		$id .= ':' . $this->getState('discount_type');
		$id .= ':' . $this->getState('name_filter');

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
	protected function populateState($ordering = 'discount_id', $direction = '')
	{
		$spgrpdis_filter = $this->getUserStateFromRequest($this->context . '.spgrpdis_filter', 'spgrpdis_filter', 0);
		$discount_type   = $this->getUserStateFromRequest($this->context . '.discount_type', 'discount_type', 'select');
		$name_filter     = $this->getUserStateFromRequest($this->context . '.name_filter', 'name_filter', '');
		$this->setState('spgrpdis_filter', $spgrpdis_filter);
		$this->setState('discount_type', $discount_type);
		$this->setState('name_filter', $name_filter);
		$layout = $this->input->getCmd('layout', '');

		if ($layout == 'product')
		{
			$ordering = 'discount_product_id';
		}

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$where = "";
		$orderby = $this->_buildContentOrderBy();
		$layout = $this->input->getCmd('layout', '');
		$spgrpdis_filter = $this->getState('spgrpdis_filter');
		$discount_type = $this->getState('discount_type');
		$name_filter = $this->getState('name_filter');

		if (isset($layout) && $layout == 'product')
		{
			$query = ' SELECT * FROM #__redshop_discount_product ' . $orderby;
		}
		else
		{
			if ($name_filter)
			{
				$where .= " and d.name like '%" . $name_filter . "%' ";
			}

			if ($discount_type != 'select')
			{
				$where .= " and d.discount_type = '" . $discount_type . "' ";
			}

			$query = ' SELECT * FROM #__redshop_discount d WHERE 1=1 ' . $where . $orderby;

			if ($spgrpdis_filter)
			{
				$where .= " and ds.shopper_group_id = '" . $spgrpdis_filter . "' ";

				$query = ' SELECT d.* FROM #__redshop_discount d left outer join #__redshop_discount_shoppers ds on d.discount_id=ds.discount_id WHERE 1=1 '
					. $where
					. $orderby;
			}
		}
		return $query;
	}

	public function _buildContentOrderBy()
	{
		$db = JFactory::getDbo();

		$layout = $this->input->getCmd('layout', '');

		if (isset($layout) && $layout == 'product')
		{
			$filter_order = $this->getState('list.ordering', 'discount_product_id');
		}
		else
		{
			$filter_order = $this->getState('list.ordering', 'discount_id');
		}

		$filter_order_Dir = $this->getState('list.direction');

		$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $orderby;
	}
}
