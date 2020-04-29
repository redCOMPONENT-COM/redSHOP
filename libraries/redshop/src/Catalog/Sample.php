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
class Sample
{
    /**
     * Method for get catalog sample list
     *
     * @return array
     *
     * @since 3.0.1
     */
    public static function getCatalogSampleList()
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('c.*')
            ->from($db->qn('#__redshop_catalog_sample', 'c'))
            ->where($db->qn('c.published') . ' = 1');

        return \Redshop\DB\Tool::safeSelect($db, $query, true);
    }

    /**
     * Method for get catalog sample color list
     *
     * @param   integer  $sampleId  Sample Id
     *
     * @return  array
     *
     * @since 3.0.1
     */
    public static function getCatalogSampleColorList($sampleId = 0)
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('c.*')
            ->from($db->qn('#__redshop_catalog_colour', 'c'));

        if ($sampleId) {
            $query->where($db->qn('c.sample_id') . ' = ' . (int)$sampleId);
        }

        return \Redshop\DB\Tool::safeSelect($db, $query, true);
    }
}