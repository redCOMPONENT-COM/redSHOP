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
	 * @param \JDatabaseDriver $db
	 * @param $query
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
    }


    /**
     * @param \JDatabaseDriver $db
     * @param $query
     * @param bool $getList
     * @param null|mixed $defaultReturn
     * @return array|mixed|null
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
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