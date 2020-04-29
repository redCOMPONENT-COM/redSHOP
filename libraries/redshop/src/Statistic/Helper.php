<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Statistic;

defined('_JEXEC') or die;

/**
 * Order helper
 *
 * @since 3.0
 */
class Helper
{
    /**
     * @return mixed
     */
    public static function getStatisticDashboard()
    {
        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true);

        // Todo: We didn't use JDatabase because $query->unionAll() is not working, please change to use $query->unionAll() when Joomla fixed it
        $query->select('SUM(' . $db->qn('order_total') . ') AS total')
            ->from($db->qn('#__redshop_orders'))
            ->where(
                $db->qn('order_status')
                . ' IN (' . $db->q(implode(',', ['C', 'PR', 'S'])) . ')'
            );

        // Orders
        $q1 = $db->getQuery(true);
        $q1->select($db->qn('order_id'))
            ->from($db->qn('#__redshop_orders'));
        $query->unionALl($q1);

        // User info
        $q2 = $db->getQuery(true);
        $q2->select($db->qn('users_info_id'))
            ->from($db->qn('#__redshop_users_info'));
        $query->unionALl($q2);

        // Site Viewers
        $q3 = $db->getQuery(true);
        $q3->select('COUNT(' . $db->qn('id') . ')')
            ->from($db->qn('#__redshop_siteviewer'));
        $query->unionALl($q3);

        return $db->setQuery($query)->loadColumn();
    }
}