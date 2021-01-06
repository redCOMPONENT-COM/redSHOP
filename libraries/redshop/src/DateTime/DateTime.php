<?php
/**
 * @package RedShop
 * @subpackage Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\DateTime;

defined('_JEXEC') or die;

use Carbon\Carbon;

class DateTime
{
    /**
     * Handle start end date
     *
     * @param   string  $startDate  Start date
     * @param   string  $endDate    End date
     *
     * @return void
     */
    public static function handleDateTimeRange(&$startDate, &$endDate)
    {
        if (empty($startDate) && empty($endDate)) {
            return;
        }

        $carbon = new Carbon();
        $tz     = new \DateTimeZone(\JFactory::getConfig()->get('offset'));
        $UTC    = new \DateTimeZone('UTC');
        $format = \Redshop::getConfig()->get('DEFAULT_DATEFORMAT');

        if ($startDate == $endDate) {
            $startDate = $carbon::createFromFormat($format, $startDate, $tz)->startOfDay()->setTimezone($UTC)->getTimestamp();
            $endDate   = $carbon::createFromFormat($format, $startDate, $tz)->endOfDay()->setTimezone($UTC)->getTimestamp();

            return;
        }

        if (!empty($startDate) && !is_numeric($startDate)) {
            $startDate = $carbon::createFromFormat($format, $startDate, $tz)->startOfDay()->setTimezone($UTC)->getTimestamp();
        }

        if (!empty($endDate) && !is_numeric($endDate)) {
            $endDate = $carbon::createFromFormat($format, $startDate, $tz)->endOfDay()->setTimezone($UTC)->getTimestamp();
        }
    }
}