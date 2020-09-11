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
 * Redshop statistics Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Statistic Product
 * @since       2.0.0.2
 */
class RedshopModelStatistic_Product extends RedshopModelList
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
        parent::__construct();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'view_date',
                'p.product_name',
                'product_name',
                'p.product_number',
                'product_number',
                'count',
                'm.name',
                'manufacturer_name',
                'total_sale',
                'unit_sold'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to buil query string
     *
     * @return  string
     *
     * @note    Calling getState in this method will result in recursion.
     */
    public function getListQuery()
    {
        $db = $this->getDbo();
        $db->setQuery(
            "set sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'"
        );
        $db->execute();
        $subQuery = $db->getQuery(true)
            ->select('SUM(' . $db->qn('oi.product_final_price') . ') AS ' . $db->qn('total_sale'))
            ->select('SUM(' . $db->qn('oi.product_quantity') . ') AS ' . $db->qn('unit_sold'))
            ->select('COUNT(*) AS ' . $db->qn('order_count'))
            ->select($db->qn('oi.product_id') . ' AS ' . $db->qn('product_id'))
            ->select($db->qn('oi.cdate', 'order_create_date'))
            ->from($db->qn('#__redshop_order_item', 'oi'))
            ->leftJoin(
                $db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('oi.order_id')
            )
            ->where($db->qn('o.order_payment_status') . ' = ' . $db->quote('Paid'))
            ->group($db->qn('oi.product_id'));

        // Filter: Date Range
        $filterDateRange = $this->state->get('filter.date_range', '');

        if (!empty($filterDateRange)) {
            $filterDateRange = explode('-', $filterDateRange);

            $startDate = (isset($filterDateRange[0])) ? (int)$filterDateRange[0] : '';

            if ($startDate) {
                $subQuery->where($db->qn('oi.cdate') . ' >= ' . $db->quote(JFactory::getDate($startDate)->toUnix()));
            }

            $endDate = (isset($filterDateRange[1])) ? (int)$filterDateRange[1] : '';

            if ($endDate) {
                $subQuery->where($db->qn('oi.cdate') . ' <= ' . $db->quote(JFactory::getDate($endDate)->toUnix()));
            }
        }

	    $subQuery->where($db->qn('oi.product_final_price') . ' > 0');

        $query = $db->getQuery(true)
            ->select(
                $db->qn(
                    array(
                        'p.product_id',
                        'p.product_name',
                        'p.product_number',
                        'oi.order_create_date',
                        'oi.total_sale',
                        'oi.unit_sold',
                        'oi.order_count'
                    )
                )
            )
            ->select($db->qn('m.name', 'manufacturer_name'))
            ->select('COUNT(*) AS count')
            ->from($db->qn('#__redshop_product', 'p'))
            ->leftjoin(
                $db->qn('#__redshop_manufacturer', 'm') . ' ON ' . $db->qn('m.id') . ' = ' . $db->qn(
                    'p.manufacturer_id'
                )
            )
            ->leftjoin(
                '(' . $subQuery . ') AS oi ' . ' ON ' . $db->qn('oi.product_id') . ' = ' . $db->qn('p.product_id')
            )
            ->group($db->qn('p.product_id'));

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', 'p.product_name');
        $orderDirn = $this->state->get('list.direction', 'asc');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        $query->having($db->qn('oi.order_create_date') . ' is not null');

        return $query;
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
     * @since   2.0.0.4
     */
    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.date_range');

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
     * @since   2.0.0.4
     * @note    Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = 'p.product_name', $direction = 'asc')
    {
        $dateRange = $this->getUserStateFromRequest($this->context . '.filter.date_range', 'filter_date_range');
        $this->setState('filter.date_range', $dateRange);

        parent::populateState($ordering, $direction);
    }
}
