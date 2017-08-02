<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelAlert extends RedshopModel
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
			$input = JFactory::getApplication()->input;
			$view = $input->getString('view', '');
			$this->context = strtolower('com_redshop.' . $view . '.' . $this->getName());
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
		$id .= ':' . $this->getState('read_filter');
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
	protected function populateState($ordering = 'a.sent_date', $direction = 'DESC')
	{
		$readFilter = $this->getUserStateFromRequest($this->context . '.read_filter', 'read_filter', 'select');
		$nameFilter = $this->getUserStateFromRequest($this->context . '.name_filter', 'name_filter', '');
		$this->setState('read_filter', $readFilter);
		$this->setState('name_filter', $nameFilter);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$readFilter = $this->getState('read_filter');
		$nameFilter = $this->getState('name_filter');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->qn('#__redshop_alerts', 'a'));

		if ($readFilter != 'select')
		{
			$query->where($db->qn('a.read') . ' = ' . $db->q((int) $readFilter));
		}

		if (!empty($nameFilter))
		{
			$search = $db->q('%' . $db->escape($nameFilter, true) . '%');
			$query->where($db->qn('a.message') . ' LIKE ' . $search);
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.sent_date')) . ' ' . $db->escape($this->getState('list.direction', 'DESC')));

		return $query;
	}

	public function countAlert()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__redshop_alerts'))
			->where($db->qn('read') . ' = 0');

		return $db->setQuery($query)->loadResult();
	}

	public function getAlert($limit)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_alerts'))
			->where($db->qn('read') . ' = 0')
			->order($db->qn('sent_date') . ' DESC')
			->setLimit($limit);

		return $db->setQuery($query)->loadObjectList();
	}
}
