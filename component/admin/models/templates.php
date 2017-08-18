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
 * Model Templates
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.4
 */
class RedshopModelTemplates extends RedshopModelList
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
				'template_id', 't.template_id',
				'template_name', 't.template_name',
				'template_desc', 't.template_desc',
				'template_section', 't.template_section',
				'published', 't.published'
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
	protected function populateState($ordering = 't.template_id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$section = $this->getUserStateFromRequest($this->context . '.filter.section', 'filter_section');
		$this->setState('filter.section', $section);

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirementt.
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
		$id .= ':' . $this->getState('filter.section');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	public function getListQuery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('t.*')
			->from($db->qn('#__redshop_template', 't'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query->where($db->qn('t.template_name') . ' LIKE ' . $search);
		}

		// Filter by search in name.
		$filterSection = $this->getState('filter.section');

		if (!empty($filterSection))
		{
			$query->where($db->qn('t.template_section') . ' = ' . $db->q($filterSection));
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'template_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
