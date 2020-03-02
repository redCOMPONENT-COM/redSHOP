<?php
/**
 * @package     RedShop
 * @subpackage  Currency
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Tax;

defined('_JEXEC') or die;

/**
 * Currency class
 *
 * @since  __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param   int  $taxGroupId
     *
     * @return \JDatabaseQuery
     * @since __DEPLOY_VERSION__
     */
    public static function getTaxRatesQuery($taxGroupId = 1) {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(
            $db->qn([
                    'tax_group_id',
                    'id',
                    'tax_country',
                    'tax_rate'
                ]
            )
        );
        $query->from($db->qn('#__redshop_tax_rate'));
        $query->where($db->qn('tax_group_id') . '=' . $db->q($taxGroupId));

        return $query;
    }

    /**
     * @param   int  $taxGroupId
     *
     * @return null
     * @throws \Exception
     * @since __DEPLOY_VERSION__
     */
    public static function getTaxRatesById($taxGroupId = 1)
    {
        $db = \JFactory::getDbo();
        $query = \Redshop\Tax\Helper::getTaxRatesQuery($taxGroupId);

        return \Redshop\DB\Tool::safeSelect($db, $query, true, []);
    }
}