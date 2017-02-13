<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelCategory extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

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
		$id .= ':' . $this->getState('category_main_filter');
		$id .= ':' . $this->getState('category_id');

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
	protected function populateState($ordering = 'c.ordering', $direction = '')
	{
		$category_main_filter = $this->getUserStateFromRequest($this->context . 'category_main_filter', 'category_main_filter', '');
		$this->setState('category_main_filter', $category_main_filter);

		$category_id = $this->getUserStateFromRequest($this->context . 'category_id', 'category_id', 0);
		$this->setState('category_id', $category_id);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$category_main_filter = $this->getState('category_main_filter');

		$orderby = $this->_buildContentOrderBy();
		$and = "";

		if ($category_main_filter)
		{
			$and .= " AND category_name like '%" . $category_main_filter . "%' ";
		}

		$q = "SELECT c.category_id, cx.category_child_id, cx.category_child_id AS id, cx.category_parent_id,
		cx.category_parent_id AS parent_id,c.category_name, c.category_name AS title,c.category_description,c.published,ordering "
			. "FROM #__redshop_category AS c, #__redshop_category_xref AS cx "
			. "WHERE c.category_id=cx.category_child_id "
			. $and
			. $orderby;

		return $q;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.5
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

		$category_main_filter = $this->getState('category_main_filter');
		$category_id = $this->getState('category_id');

		if (!$category_main_filter)
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
			$treelist = RedshopHelperUtility::createTree($category_id, '<sup>|_</sup>&nbsp;', array(), $children);

			$total = count($treelist);
		}
		else
		{
			$total = count($rows);
			$treelist = $rows;
		}

		jimport('joomla.html.pagination');
		$this->_pagination = new JPagination($total, (int) $this->getState('limitstart'), (int) $this->getState('limit'));

		// Slice out elements based on limits
		$items = array_slice($treelist, $this->_pagination->limitstart, $this->_pagination->limit);

		return $items;
	}

	/**
	 * Method to get a JPagination object for the data set.
	 *
	 * @return  JPagination  A JPagination object for the data set.
	 *
	 * @since   1.5
	 */
	public function getPagination()
	{
		if ($this->_pagination == null)
		{
			$this->getData();
		}

		return $this->_pagination;
	}

	public function getProducts($cid)
	{
		$query = 'SELECT count(category_id) FROM #__redshop_product_category_xref WHERE category_id="' . $cid . '" ';
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/*
	 * assign template to multiple categories
	 * @prams: $data, post variable	array
	 * @return: boolean
	 */
	public function assignTemplate($data)
	{
		$cid = $data['cid'];

		$category_template = $data['category_template'];

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE #__redshop_category'
				. ' SET `category_template` = "' . intval($category_template) . '" '
				. ' WHERE category_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function saveorder($cid = array(), $order)
	{
		$row = $this->getTable('category_detail');
		$groupings = array();

		// Update ordering values
		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			$groupings[] = $row->category_id;

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

		return true;
	}
}
