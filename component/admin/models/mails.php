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
 * Model Mails
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelMails extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_mails';

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
				'mail_id',
				'mail_name',
				'mail_description',
				'mail_subject',
				'mail_section',
				'published'
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
	protected function populateState($ordering = 'mail_id', $direction = 'asc')
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_section');
		$this->setState('filter.section', $search);

		// List state information.
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

		$query->select('*')
			->from($db->qn('#__redshop_mail'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query->where($db->qn('mail_name') . ' LIKE ' . $search . ' OR ' . $db->qn('mail_subject') . ' LIKE ' . $search);
		}

		// Filter by section
		$section = $this->getState('filter.section');

		if (!empty($search))
		{
			$query->where($db->qn('mail_section') . ' = ' . $db->quote($section));
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'mail_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
