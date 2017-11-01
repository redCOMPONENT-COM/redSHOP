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
 * Redshop attributes Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Attributes
 * @since       2.0.0.2
 */
class RedshopModelAttributes extends RedshopModelList
{
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
				'attribute_id', 'a.attribute_id',
				'attribute_name', 'a.attribute_name',
				'product_name', 'p.product_name',
				'display_type', 'a.display_type',
				'attribute_published', 'a,attribute_published'
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

		$product = $this->getUserStateFromRequest($this->context . '.filter.product', 'filter_product_id');
		$this->setState('filter.filter_product_id', $product);

		// List state information.
		parent::populateState('attribute_id', 'ASC');
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
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.product');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select('a.*, p.product_name')
				->from($db->qn('#__redshop_product_attribute', 'a'))
				->leftJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('a.product_id') . ' = ' . $db->qn('p.product_id'));

		// Filter by search in name.
		$search = $this->getState('filter.search');

		// Filter by product ID.
		$product = $this->getState('filter.filter_product_id');

		// Filter by State.
		$published = $this->getState('filter.published');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->qn('a.attribute_id') . ' = ' . $db->q((int) substr($search, 3)));
			}
			else
			{
				$search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where($db->qn('a.attribute_name') . ' LIKE ' . $search);
			}
		}

		if (!empty($product))
		{
			$query->where($db->qn('a.product_id') . ' = ' . $db->q((int) $product));
		}

		if (is_numeric($published))
		{
			$query->where($db->qn('a.attribute_published') . ' = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where($db->qn('a.attribute_published') . ' IN (0, 1)');
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'attribute_id');
		$orderDirn = $this->state->get('list.direction', 'ASC');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
