<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelManufacturer extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

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
	protected function populateState($ordering = 'm.ordering', $direction = '')
	{
		$filter = $this->getUserStateFromRequest($this->context . 'filter', 'filter', '');
		$this->setState('filter', $filter);

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
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter');

		return parent::getStoreId($id);
	}

	public function _buildQuery()
	{
		$filter = $this->getState('filter');
		$orderby = $this->_buildContentOrderBy();
		$where = '';

		if ($filter)
		{
			$where = " WHERE m.manufacturer_name like '%" . $filter . "%' ";
		}

		$query = 'SELECT  distinct(m.manufacturer_id),m.* FROM #__redshop_manufacturer m '
			. $where
			. $orderby;

		return $query;
	}

	public function getMediaId($mid)
	{
		$database = JFactory::getDbo();

		$query = ' SELECT media_id '
			. ' FROM #__redshop_media  WHERE media_section="manufacturer" AND section_id = ' . $mid;

		$database->setQuery($query);

		return $database->loadResult();
	}

	public function saveOrder(&$cid)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable('manufacturer_detail');

		$total = count($cid);
		$order = JFactory::getApplication()->input->post->get('order', array(0), 'array');
		JArrayHelper::toInteger($order, array(0));

		// Update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load((int) $cid[$i]);

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					throw new Exception($db->getErrorMsg());
				}
			}
		}

		$row->reorder();

		return true;
	}

	/**
	 * Get array of manufacturers
	 *
	 * @return  array<object>
	 *
	 * @since   2.0.7
	 */
	public function getManufacturers()
	{
		$db            = JFactory::getDbo();
		$query         = $db->getQuery(true)
			->select($db->qn('manufacturer_id', 'value'))
			->select($db->qn('manufacturer_name', 'text'))
			->from($db->qn('#__redshop_manufacturer'));

		return $db->setQuery($query)->loadObjectList();
	}
}

