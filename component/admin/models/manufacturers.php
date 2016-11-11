<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop manufacturers Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Manufacturers
 * @since       2.0.0.2
 */
class RedshopModelManufacturers extends RedshopModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_manufacturers';

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
				'manufacturer_id', 'm.manufacturer_id',
				'manufacturer_name', 'm.manufacturer_name',
				'manufacturer_desc', 'm.manufacturer_desc',
				'ordering', 'm.ordering',
				'published', 'm.published'
			);
		}

		parent::__construct($config);
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
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$product = $this->getUserStateFromRequest($this->context . '.filter.manufacturer', 'filter_manufacturer_id');
		$this->setState('filter.filter_manufacturer_id', $product);

		parent::populateState('manufacturer_id', 'ASC');
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
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.product');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 */
	protected function getListQuery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select('*')
				->from($db->quoteName('#__redshop_manufacturer', 'm'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if ($search)
		{
			$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query->where($db->quoteName('a,manufacturer_id') . ' LIKE ' . $search);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'manufacturer_id');
		$orderDirn = $this->state->get('list.direction', 'ASC');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Get media id
	 *
	 * @param   int  $mid  Media section ID
	 *
	 * @return  int
	 */
	public function getMediaId($mid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select($db->quoteName('media_id'))
				->from($db->quoteName('#__redshop_media'))
				->where($db->quoteName('media_section') . '=' . $db->quote('manufacturer'))
				->where($db->quoteName('section_id') . '=' . (int) $mid);

		$db->setQuery($query);

		return $db->loadResult();
	}
}

