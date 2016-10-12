<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelFields extends RedshopModel
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
		$id .= ':' . $this->getState('filtertype');
		$id .= ':' . $this->getState('filtersection');

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
	protected function populateState($ordering = 'ordering', $direction = '')
	{
		$filter         = $this->getUserStateFromRequest($this->context . '.filter', 'filter', '');
		$filtertype     = $this->getUserStateFromRequest($this->context . '.filtertype', 'filtertype', 0);
		$filtersection  = $this->getUserStateFromRequest($this->context . '.filtersection', 'filtersection', 0);

		$this->setState('filter', $filter);
		$this->setState('filtertype', $filtertype);
		$this->setState('filtersection', $filtersection);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$filter = $this->getState('filter');
		$filtertype = $this->getState('filtertype');
		$filtersection = $this->getState('filtersection');

		$where = '';

		if ($filter)
		{
			$where .= " AND f.field_title like '%" . $filter . "%' ";
		}

		if ($filtertype)
		{
			$where .= " AND f.field_type='" . $filtertype . "' ";
		}

		if ($filtersection)
		{
			$where .= " AND f.field_section='" . $filtersection . "' ";
		}

		$query = "SELECT * FROM #__redshop_fields AS f "
			. "WHERE 1=1 "
			. $where
			. $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$filter_order_Dir = $this->getState('list.direction');
		$filter_order = $this->getState('list.ordering');

		if ($filter_order == 'ordering')
		{
			$orderBy = ' ORDER BY field_section, ordering ' . $filter_order_Dir;
		}
		else
		{
			$orderBy = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir) . ', field_section, ordering';
		}

		return $orderBy;
	}

	/**
	 * Save Custom Field order
	 *
	 * @param   array  $cid    List Index Id
	 * @param   array  $order  Order Number
	 *
	 * @return  boolean
	 */
	public function saveorder($cid = array(), $order = array())
	{
		$row        = $this->getTable('fields_detail');
		$conditions = array();

		// Update ordering values
		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$row->load((int) $cid[$i]);

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}

				// Remember to updateOrder this group
				$condition = 'field_section = ' . (int) $row->field_section;
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$conditions[] = array($row->field_id, $condition);
				}
			}
		}

		// Execute updateOrder for each group
		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}

		return true;
	}

	/**
	 * Move ordering up
	 *
	 * @return  boolean
	 */
	public function orderup()
	{
		$app = JFactory::getApplication();

		$cid = $app->input->get('cid', array(), 'post', 'array');
		$cid = $cid[0];

		$row = $this->getTable('fields_detail');
		$row->load($cid[0]);
		$row->move(-1, 'field_section = ' . $row->field_section);

		return true;
	}

	/**
	 * Move Ordering down
	 *
	 * @return  boolean
	 */
	public function orderdown()
	{
		$app = JFactory::getApplication();

		$cid = $app->input->get('cid', array(), 'post', 'array');
		$cid = $cid[0];

		$row = $this->getTable('fields_detail');
		$row->load($cid[0]);
		$row->move(1, 'field_section = ' . $row->field_section);

		return true;
	}

	/**
	 * Get Fields information from Sections Ids.
	 * 		Note: This will return non-published fields also
	 *
	 * @param   array  $section  Sections Ids in index array
	 *
	 * @return  mixed  Object information array of Fields
	 */
	public function getFieldInfoBySection($section)
	{
		if (!is_array($section))
		{
			throw new InvalidArgumentException(__FUNCTION__ . 'only accepts Array. Input was ' . $section);
		}

		JArrayHelper::toInteger($section);
		$sections = implode(',', $section);

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('field_name,field_type,field_section')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('field_section') . ' IN(' . $sections . ')');

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$fields = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $fields;
	}

	/**
	 * Published or unpublished
	 *
	 * @param   array    $cid      primary keys
	 * @param   integer  $publish  State for publish is 1 and other is 0
	 *
	 * @return  boolean
	 */
	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$row = $this->getTable('fields_detail');
			$row->publish($cid, $publish);
		}

		return true;
	}

	public function getFieldsBySection($section, $fieldName = '')
	{
		$db = JFactory::getDbo();
		$query  = ' SELECT *'
			. ' FROM ' . $db->quoteName('#__redshop_fields')
			. ' WHERE ' . $db->quoteName('field_section') . ' = ' . (int) $section . ' AND ' . $db->quoteName('published') . '= 1 ';

		if ($fieldName != '')
		{
			$fieldName = redhelper::quote(explode(',', $fieldName));
			$query .= ' AND ' . $db->quoteName('field_name') . ' IN (' . implode(',', $fieldName) . ') ';
		}

		$query .= ' ORDER BY ' . $db->quoteName('ordering');
		$db->setQuery($query);
		return $db->loadObjectlist();
	}

	public function getFieldDataList($fieldid, $section = 0, $orderitemid = 0, $user_email = "")
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
					->select('*')
					->from($db->quoteName('#__redshop_fields_data'))
					->where($db->quoteName('itemid') . ' = ' . (int) $orderitemid)
					->where($db->quoteName('fieldid') . ' = ' . (int) $fieldid)
					->where($db->quoteName('user_email') . ' = ' . $db->quote($user_email))
					->where($db->quoteName('section') . ' = ' . (int) $section);

		$db->setQuery($query);

		return $db->loadObject();
	}

	public function getFieldValue($id)
	{
		$db = JFactory::getDbo();

		$q = ' SELECT * '
			. ' FROM ' . $db->quoteName('#__redshop_fields_value')
			. ' WHERE ' . $db->quoteName('field_id') . ' = ' . (int) $id
			. ' ORDER BY ' . $db->quoteName('value_id') . ' ASC ';
		$db->setQuery($q);
		return $db->loadObjectlist();
	}
}

