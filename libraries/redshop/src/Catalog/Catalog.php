<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Catalog;

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Catalog sample
 *
 * @since  3.0.1
 */
class Catalog
{
    /**
     * Method for get catalog list
     *
     * @return array
     *
     * @since 3.0.1
     */
    public static function getCatalogList()
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('c.*')
            ->select($db->qn('c.catalog_id', 'value'))
            ->select($db->qn('c.catalog_name', 'text'))
            ->from($db->qn('#__redshop_catalog', 'c'))
            ->where($db->qn('c.published') . ' = 1');

        return \Redshop\DB\Tool::safeSelect($db, $query, true);
    }
}