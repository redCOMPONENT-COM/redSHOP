<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelQuestion extends RedshopModel
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
		$id .= ':' . $this->getState('product_id');

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
	protected function populateState($ordering = 'question_date', $direction = 'desc')
	{
		$filter = $this->getUserStateFromRequest($this->context . '.filter', 'filter', '');
		$product_id = $this->getUserStateFromRequest($this->context . '.product_id', 'product_id', 0);

		$this->setState('filter', $filter);
		$this->setState('product_id', $product_id);

		parent::populateState($ordering, $direction);
	}

	/**
	 * Get product with questions
	 *
	 * @return mixed
	 */
	public function getProduct()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('p.product_id, p.product_name')
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_customer_question', 'cq') . ' ON cq.product_id = p.product_id')
			->where('cq.question_id > 0')
			->group('p.product_id');

		return $db->setQuery($query)->loadObjectList();
	}

	public function _buildQuery()
	{
		$where = "";
		$filter = $this->getState('filter');
		$product_id = $this->getState('product_id');

		if ($filter)
		{
			$where .= " AND q.question LIKE '%" . $filter . "%' ";
		}

		if ($product_id != 0)
		{
			$where .= " AND q.product_id ='" . $product_id . "' ";
		}

		$orderby = $this->_buildContentOrderBy();

		$query = "SELECT q.*, p.product_name FROM #__redshop_customer_question AS q "
			. "LEFT JOIN #__redshop_product AS p ON p.product_id = q.product_id "
			. "WHERE q.parent_id = 0 "
			. $where
			. $orderby;

		return $query;
	}

	public function saveorder($cid = array(), $order)
	{
		$row = $this->getTable('question_detail');
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		$groupings = array();

		// Update ordering values
		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			$groupings[] = $row->question_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		// Execute updateOrder for each parent group
		$groupings = array_unique($groupings);

		foreach ($groupings as $group)
		{
			$row->reorder((int) $group);
		}

		return true;
	}
}
