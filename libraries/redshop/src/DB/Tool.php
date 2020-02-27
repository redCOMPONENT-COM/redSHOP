<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\DB;

defined('_JEXEC') or die;

/**
 * Account helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Tool
{
    /**
     * @param $db
     * @param $query
     * @return bool
     * @throws \Exception
     */
    public static function safeExecute($db, $query)
    {
        try {
            $db->setQuery($query);

            $db->transactionStart();
            $db->execute();
            $db->transactionCommit();
        } catch (\Exception $e) {
            $db->transactionRollback();
            \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }

        return true;
    }
}