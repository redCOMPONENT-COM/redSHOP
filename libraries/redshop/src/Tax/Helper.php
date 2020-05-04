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
 * @since  3.0
 */
class Helper
{
    /**
     * @param   int  $taxGroupId
     *
     * @return null
     * @throws \Exception
     * @since 3.0
     */
    public static function getTaxRatesById($taxGroupId = 1)
    {
        $db    = \JFactory::getDbo();
        $query = \Redshop\Tax\Helper::getTaxRatesQuery($taxGroupId);

        return \Redshop\DB\Tool::safeSelect($db, $query, true, []);
    }

    /**
     * @param   int  $taxGroupId
     *
     * @return \JDatabaseQuery
     * @since 3.0
     */
    public static function getTaxRatesQuery($taxGroupId = 1)
    {
        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(
            $db->qn(
                [
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
}