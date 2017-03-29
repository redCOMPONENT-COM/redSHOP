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
 * Redshop categories Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Categories
 * @since       2.0.4
 */
class RedshopModelCategories extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_categories';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   2.0.0.2
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'category_name', 'c.category_name',
				'category_description', 'c.category_description',
				'ordering', 'c.ordering',
				'category_id', 'c.category_id',
				'published', 'c.published'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', 0);
		$this->setState('filter.filter_category_id', $categoryId);

		// List state information.
		parent::populateState('c.ordering', 'ASC');
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
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 *
	 * @since   2.0.0.2
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select('c.*')
				->select($db->qn('cx.category_child_id'))
				->select($db->qn('cx.category_child_id', 'id'))
				->select($db->qn('cx.category_parent_id'))
				->select($db->qn('cx.category_parent_id', 'parent_id'))
				->select($db->qn('c.category_name', 'title'))
				->from($db->qn('#__redshop_category', 'c'))
				->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('cx.category_child_id'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->qn('c.category_id') . ' = ' . $db->q((int) substr($search, 3)));
			}
			else
			{
				$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where($db->qn('c.category_name') . ' LIKE ' . $search);
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'c.ordering');
		$orderDirn = $this->state->get('list.direction', 'ASC');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   2.0.0.2
	 */
	public function getData()
	{
		// Load the list items.
		$query = $this->_getListQuery();

		try
		{
			$rows = $this->_getList($query);
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$search = $this->getState('filter.search');
		$categoryId = $this->getState('filter.filter_category_id');

		if (empty($search))
		{
			// Establish the hierarchy of the menu
			$children = array();

			// First pass - collect children
			foreach ($rows as $v)
			{
				$pt = $v->parent_id;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}

			// Second pass - get an indent list of the items
			$treeList = JHTML::_('menu.treerecurse', $categoryId, '', array(), $children, 9999);
			$total = count($treeList);
		}
		else
		{
			$total = count($rows);
			$treeList = $rows;
		}

		$this->_pagination = new JPagination($total, (int) $this->getState('limitstart'), (int) $this->getState('limit'));

		// Slice out elements based on limits
		$items = array_slice($treeList, $this->_pagination->limitstart, $this->_pagination->limit);

		return $items;
	}

	/**
	 * Method to count category.
	 *
	 * @param   int  $cid  category id.
	 *
	 * @return  void.
	 */
	public function getProducts($cid)
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true)
			->select('COUNT(category_id)')
			->from($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('category_id') . ' = ' . $db->q((int) $cid));

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Method to assign template for category.
	 *
	 * @param   array  $data  data for assign template.
	 *
	 * @return  void.
	 */
	public function assignTemplate($data)
	{
		$cid = $data['cid'];
		$categoryTemplate = $data['category_template'];

		if (count($cid))
		{
			$db = $this->getDBO();
			$fields = array(
				$db->qn('category_template') . ' = ' . $db->q((int) $categoryTemplate)
			);
			$conditions = array(
				$db->qn('category_id') . ' IN (' . implode(',', $cid) . ')'
			);
			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_category'))
				->set($fields)
				->where($conditions);

			if (!$db->setQuery($query)->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
