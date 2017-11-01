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
 * Model Countries
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0.5
 */
class RedshopModelQuestions extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   2.x
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'q.id',
				'parent_id', 'q.parent_id',
				'product_id', 'q.product_id',
				'question', 'q.question',
				'user_id', 'q.user_id',
				'user_name', 'q.user_name',
				'user_email', 'q.user_email',
				'published', 'q.published',
				'question_date', 'q.question_date',
				'ordering', 'q.ordering',
				'telephone', 'q.telephone',
				'address', 'q.address',
				'product_name', 'p.product_name'
			);
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
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.product_id');

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
		$filter = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '');
		$product_id = $this->getUserStateFromRequest($this->context . '.filter.product_id', 'filter_product_id', 0);

		$this->setState('filter.search', $filter);
		$this->setState('filter.product_id', $product_id);

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
			->select($db->qn(['p.product_id', 'p.product_name']))
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_customer_question', 'q') . ' ON ' . $db->qn('q.product_id') . ' = ' . $db->qn('p.product_id'))
			->where($db->qn('q.id') . ' > 0')
			->group($db->qn('p.product_id'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$db->qn(
				[
					'q.id', 'q.parent_id', 'q.product_id',
					'q.question', 'q.user_id', 'q.user_name',
					'q.user_email', 'q.published', 'q.question_date',
					'q.ordering', 'q.telephone', 'q.address',
					'p.product_name'
				]
			)
		)
		->from($db->qn('#__redshop_customer_question', 'q'))
		->leftJoin(
			$db->qn('#__redshop_product', 'p')
			. ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('q.product_id')
			)
		->where($db->qn('q.parent_id') . ' = 0');

		$search = $this->getState('filter.search');
		$productId = $this->getState('filter.product_id');

		if ($search)
		{
			$query->where($db->qn('q.question') . ' LIKE ' . $db->q('%' . $search . '%'));
		}

		if ($productId != 0)
		{
			$query->where($db->qn('q.product_id') . ' = ' . $db->q($productId));
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
