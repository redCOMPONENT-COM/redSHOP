<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class DB {
    /**
     * @return JDatabaseQuery|string
     * @since  __DEPLOY_VERSION__
     */
    public static function buildQueryList() {
        $db = \Joomla\CMS\Factory::getDbo();

        return $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_promotion'))
            ->where($db->qn('published') . ' = ' . $db->q('1'))
            ->where($db->qn('type') . ' = ' . $db->q('promotion'));
    }

    /**
     * @return null
     * @since  __DEPLOY_VERSION__
     */
    public static function getPromotionsFromDB() {
        return \Redshop\DB\Tool::safeSelect(
            \Joomla\CMS\Factory::getDbo(),
            self::buildQueryList(),
            true);
    }
}