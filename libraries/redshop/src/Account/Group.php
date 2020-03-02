<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Account;

defined('_JEXEC') or die;

/**
 * Account helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Group
{
    /**
     * @param array $accountGroupIds
     * @return bool
     * @throws \Exception
     */
    public static function deleteAccountGroups($accountGroupIds = [])
    {
        if (is_array($accountGroupIds) && count($accountGroupIds) > 0) {
            // Sanitise ids
            $accountGroupIds = \Joomla\Utilities\ArrayHelper::toInteger($accountGroupIds);
            $accountGroupIds = implode(',', $accountGroupIds);
            $db = \JFactory::getDbo();
            $query = $db->getQUery(true);
            $query->delete($db->qn('#__redshop_economic_accountgroup'))
                ->where($db->qn('accountgroup_id') . ' IN (' . $db->q($accountGroupIds) . ')');

            return \Redshop\DB\Tool::safeExecute($db, $query);
        }

        return true;
    }

    /**
     * @param array $accountGroupIds
     * @param int $publish
     * @return bool
     * @throws \Exception
     */
    public static function setPublishStatus($accountGroupIds = [], $publish = 1)
    {
        if (count($accountGroupIds)) {
            // Sanitise ids
            $accountGroupIds = \Joomla\Utilities\ArrayHelper::toInteger($accountGroupIds);
            $accountGroupIds = implode(',', $accountGroupIds);
            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->update($db->qn('#__redshop_economic_accountgroup'))
                ->set([
                    $db->qn('published') . ' = ' . $db->q($publish)
                ])
                ->where($db->qn('accountgroup_id') . ' IN (' . $db->q($accountGroupIds) . ')');

            return \Redshop\DB\Tool::safeExecute($db, $query);
        }

        return true;
    }
}