<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Redshop fields Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Fields
 * @since       2.0.6
 */
class RedshopModelFields extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_fields';

	/**
	 * Construct class
	 *
	 * @since 1.x
	 */
	public function __construct()
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'f.id',
				'ordering', 'f.ordering',
				'title', 'f.title',
				'name', 'f.name',
				'type', 'f.type',
				'section', 'f.section',
				'published', 'f.published'
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
	 * @param   string $id A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.type');
		$id .= ':' . $this->getState('filter.section');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$filterFieldType = $this->getUserStateFromRequest($this->context . '.filter.field_type', 'filter_field_type');
		$this->setState('filter.field_type', $filterFieldType);

		$filterFieldSection = $this->getUserStateFromRequest($this->context . '.filter.field_section', 'filter_field_section');
		$this->setState('filter.field_section', $filterFieldSection);

		parent::populateState('ordering', $direction);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 *
	 * @since   2.0.6
	 */
	public function getListQuery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('f.*')
			->from($db->qn('#__redshop_fields', 'f'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->qn('f.id') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where($db->qn('f.title') . ' LIKE ' . $search);
			}
		}

		// Filter: Field type
		$filterFieldType = $this->getState('filter.field_type', '');

		if ($filterFieldType)
		{
			$query->where($db->qn('f.type') . ' = ' . $db->q($filterFieldType));
		}

		// Filter: Field section
		$filterFieldSection = $this->getState('filter.field_section', '');

		if ($filterFieldSection)
		{
			$query->where($db->qn('f.section') . ' = ' . $filterFieldSection);
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'ordering');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'ordering')
		{
			$query->order($db->escape('f.section, f.ordering ' . $orderDirn));
		}
		else
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn) . ', f.section, f.ordering');
		}

		return $query;
	}

	/**
	 * Get Fields information from Sections
	 *        Note: This will return non-published fields also
	 *
	 * @param   array  $section  Sections in index array
	 *
	 * @return  mixed            Object information array of Fields
	 */
	public function getFieldInfoBySection($section)
	{
		if (!is_array($section))
		{
			throw new InvalidArgumentException(__FUNCTION__ . 'only accepts Array. Input was ' . $section);
		}

		$section  = ArrayHelper::toInteger($section);
		$sections = implode(',', $section);

		// Init variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('f.name,f.type,f.section')
			->from($db->qn('#__redshop_fields', 'f'))
			->where($db->qn('f.section') . ' IN(' . $sections . ')');

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
	 * Get Fields information from Section.
	 *
	 * @param   string $section   Section of fields
	 * @param   string $fieldName Field name
	 * @param   int    $front     Show field in front
	 * @param   int    $checkout  Show field in checkout
	 *
	 * @return  mixed
	 */
	public function getFieldsBySection($section, $fieldName = '', $front = 0, $checkout = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_fields', 'f'))
			->where($db->qn('f.section') . ' = ' . (int) $section)
			->where($db->qn('f.published') . '= 1 ');

		if ($front)
		{
			$query->where($db->qn('show_in_front') . ' = 1');
		}

		if ($checkout)
		{
			$query->where($db->qn('display_in_checkout') . ' = 1');
		}

		if ($fieldName != '')
		{
			$fieldName = RedshopHelperUtility::quote(explode(',', $fieldName));
			$query->where($db->qn('f.name') . ' IN (' . implode(',', $fieldName) . ') ');
		}

		$query->order($db->qn('f.ordering'));
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get Field Data from Field Id, Section, Order Item Id and User Email
	 *
	 * @param   int      $fieldId      Id of field
	 * @param   integer  $section      Section of field
	 * @param   integer  $orderItemId  Order item Id
	 * @param   string   $userEmail    User's email
	 *
	 * @return  mixed                 Object information array of Field's Data
	 */
	public function getFieldDataList($fieldId, $section = 0, $orderItemId = 0, $userEmail = "")
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_fields_data'))
			->where($db->qn('itemid') . ' = ' . (int) $orderItemId)
			->where($db->qn('fieldid') . ' = ' . (int) $fieldId)
			->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
			->where($db->qn('section') . ' = ' . (int) $section);

		$db->setQuery($query);

		return $db->loadObject();
	}
}
