<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product;

defined('_JEXEC') or die;

/**
 * Compare helper
 *
 * @since  3.0
 */
class Mini
{
    /**
     * @param   null    $keyword
     * @param   null    $categoryId
     * @param   string  $searchField
     * @param   int     $limit
     * @param   null    $orderBy
     *
     * @return array|mixed
     * @throws \Exception
     */
    public static function getCountDistinctProduct(
        $keyword = null,
        $categoryId = null,
        $searchField = '',
        $limit = 0,
        $orderBy = null
    ) {
        try {
            $db    = \JFactory::getDbo();
            $query = self::getQueryObject($keyword, $categoryId, $searchField);
            $db->setQuery($query);

            return $db->loadResult();
        } catch (\Exception $e) {
            \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

            return [];
        }
    }

    /**
     * @param   null    $keyword
     * @param   null    $categoryId
     * @param   string  $searchField
     * @param   int     $limit
     * @param   null    $orderBy
     *
     * @return \JDatabaseQuery
     */
    public static function getQueryObject(
        $keyword = null,
        $categoryId = null,
        $searchField = '',
        $limit = 0,
        $orderBy = null
    ) {
        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $where = false;

        if (trim($keyword) != '') {
            $where = true;
            $query->where($db->qn($searchField) . ' LIKE ' . $db->q('%' . $keyword . '%'));
        }

        if ($categoryId) {
            $where = true;
            $query->where($db->qn('c.category_id') . ' = ' . $db->q((int)$categoryId));
        }

        if ($where) {
            $query->select(
                'COUNT(DISTINCT('
                . $db->qn('p.product_id')
                . '))'
            )
                ->from($db->qn('#__redshop_product', 'p'))
                ->leftJoin(
                    $db->qn('#__redshop_product_category_xref', 'x')
                    . ' ON ' . $db->qn('x.product_id') . ' = ' . $db->qn('p.product_id')
                )
                ->leftJoin(
                    $db->qn('#__redshop_category', 'c')
                    . ' ON ' . $db->qn('x.category_id') . ' = ' . $db->qn('c.id')
                );
        } else {
            $query->select('COUNT(*)')
                ->from($db->qn('#__redshop_product', 'p'));
        }

        if ($limit) {
            $query->setLimit((int)$limit);
        }

        if (isset($orderBy)) {
            $query->order($orderBy);
        }

        return $query;
    }
}