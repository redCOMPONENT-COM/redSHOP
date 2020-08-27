<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Wrappers
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelWrappers extends RedshopModelList
{
	/**
	 * Construct class
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id',
				'w.id',
				'product_id',
				'w.product_id',
				'name',
				'w.name',
				'category_id',
				'w.category_id',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param string $ordering  An optional ordering field.
	 * @param string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = 'w.id', $direction = 'asc')
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
	 * @param string $id A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		$db       = \JFactory::getDbo();
		$app      = \JFactory::getApplication();
		$productId = JFactory::getApplication()->input->get('product_id');
		$showAll  = $app->input->get('showall', '0');
		$subQuery = [];

		if ($showAll && $productId) {
			$subQuery[] = 'FIND_IN_SET(' . $db->q($productId) . ',' . $db->qn('w.product_id') . ')';
			$subQuery[] = $db->qn('use_to_all') . '=' . $db->q(1);

			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_product_category_xref'))
				->where($db->qn('product_id') . ' = ' . $db->q((int)$productId));
			$db->setQuery($query);
			$cat = $db->loadObjectList();

			for ($i = 0, $in = count($cat); $i < $in; $i++) {
				$subQuery[] = 'FIND_IN_SET(' . $db->q($cat[$i]->category_id) . ',' . $db->qn('category_id') . ')';
			}
		}

		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->qn('#__redshop_wrapper', 'w'));

		if (!empty($subQuery)) {
			$query->where('(' . implode(' OR ', $subQuery) . ')');
		}

		$search = $this->getState('filter.search');

		if ($search) {
			$query->where($db->qn('w.name') . " LIKE '%" . $search . "%' ");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'w.id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

    /**
     * @param   string  $id
     *
     * @return  string
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getProductNameById($id)
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('product_name')
            ->from($db->qn('#__redshop_product'))
            ->where($db->qn('product_id') . ' = ' . $db->q($id));

        return $db->setQuery($query)->loadResult();
    }

    /**
     * @param   string  $id
     *
     * @return  string
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getCategoryNameById($id)
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('name')
            ->from($db->qn('#__redshop_category'))
            ->where($db->qn('id') . ' = ' . $db->q($id));

        return $db->setQuery($query)->loadResult();
    }

	/**
	 * @param   string  $publish
	 * @param   array  $cids
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
    public function useToAllpublish($cids = array(), $publish = 1)
    {
        if (count($cids)) {
            $cid = implode(',', $cids);

            $query = $this->_db->getQuery(true)
                ->update($this->_db->qn('#__redshop_wrapper'))
                ->set($this->_db->qn('use_to_all') . ' = ' . $this->_db->q(intval($publish)))
                ->where($this->_db->qn('id') . ' = ' . $this->_db->q((int) $cid));
            $this->_db->setQuery($query);

            if (!$this->_db->execute()) {
                return false;
            }
        }

        return true;
    }
}
