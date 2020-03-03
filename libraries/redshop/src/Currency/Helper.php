<?php
/**
 * @package     RedShop
 * @subpackage  Currency
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Currency;

defined('_JEXEC') or die;

/**
 * Currency class
 *
 * @since  3.0
 */
class Helper
{
    /**
     * @param string $currencyCode
     * @return mixed
     * @since 3.0
     */
    public static function getCurrenciesListForSelectBox($currencyCode = "")
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        if (isset($currencyCode) && $currencyCode != '') {
            $query->where($db->qn('code') . ' IN (' . $db->q($currencyCode) . ')');
        }

        $query->select($db->qn('code', 'value'), $db->qn('name'. 'text'))
            ->from($db->qn('#__redshop_currency'))
            ->order($db->qn('name') . ' ASC');

        $db->setQuery($query);

        return $db->loadObjectlist();
    }
}