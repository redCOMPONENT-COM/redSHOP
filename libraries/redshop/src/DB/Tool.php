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
 * @since  3.0
 */
class Tool
{
    /**
     * @param $db
     * @param $query
     *
     * @return bool
     */
    public static function safeExecute(\JDatabaseDriver $db, $query)
    {
        try {
            $db->transactionStart();
            $db->setQuery($query);
            $db->execute();
            $db->transactionCommit();

            return true;
        } catch (\Exception $e) {
            $db->transactionRollback();
            \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

            return false;
        }

        return false;
    }

    /**
     * @param         $db
     * @param         $query
     * @param   bool  $getList
     * @param   null  $defaultReturn
     *
     * @return null
     */
    public static function safeSelect(\JDatabaseDriver $db, $query, $getList = false, $defaultReturn = null)
    {
        try {
            if ($getList) {
                return $db->setQuery($query)->loadObjectList();
            }

            return $db->setQuery($query)->loadObject();
        } catch (\RuntimeException $e) {
            \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

            return $defaultReturn;
        }
    }
}