<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Tax Groups
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.4
 */
class RedshopModelTax_Groups extends RedshopModelList
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
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'tg.id',
                'name',
                'tg.name',
                'published',
                'tg.published'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    public function getListQuery()
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('tg.*')
            ->from($db->qn('#__redshop_tax_group', 'tg'));

        // Filter by search in name.
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where($db->qn('tg.id') . ' = ' . (int)substr($search, 3));
            } else {
                $search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                $query->where($db->qn('tg.name') . ' LIKE ' . $search);
            }
        }

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', 'tg.id');
        $orderDirn = $this->state->get('list.direction', 'asc');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
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
    protected function populateState($ordering = 'tg.id', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

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

        return parent::getStoreId($id);
    }

	/**
	 * @param   string  $id
	 *
	 * @return  array
	 *
	 * @since   3.0.2
	 */
	public static function getShopperTax($id)
	{
		$result = [];
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('shopper_group_id')
			->from($db->qn('#__redshop_tax_shoppergroup_xref'))
			->where($db->qn('tax_rate_id') . ' = ' . $db->q($id));

		$shopperGroupIds = $db->setQuery($query)->loadColumn();

		foreach ($shopperGroupIds as $shopperGroupId)
		{
			$query = $db->getQuery(true)
				->select('shopper_group_name')
				->from($db->qn('#__redshop_shopper_group'))
				->where($db->qn('shopper_group_id') . ' = ' . $db->q($shopperGroupId));

			$result[] = $db->setQuery($query)->loadResult();
		}

		return $result;
	}
}
