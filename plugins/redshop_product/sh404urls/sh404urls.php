<?php
/**
 * @package     redSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Plugin Redshop_ProductSh404urls
 *
 * @since 2.1.3
 */
class PlgRedshop_ProductSh404urls extends JPlugin
{
    /**
     * Constructor
     *
     * @param   object  $subject  The object to observe
     * @param   array   $config   An array that holds the plugin configuration
     *
     * @since    2.1.3
     */
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    /**
     * @param   $pids  array  products id
     *
     * @return  boolean
     *
     * @since   2.1.3
     */
    public function onAfterProductDelete($pids)
    {
        if (empty($pids)) {
            return false;
        }

        $db      = JFactory::getDbo();
        $query   = $db->getQuery(true);
        $conditions   = array();
        $results = $db->setQuery('SHOW TABLES')->loadColumn();
        $table   = $db->getPrefix() . 'sh404sef_urls';

        if (empty($results) || !in_array($table, $results)) {
            return false;
        }

        foreach ($pids as $pid) {
            $conditions[] = $db->qn('newurl') . ' LIKE ' . $db->q('%pid=' . (int)$pid . '%');
        }

        $query->clear()
            ->delete($db->qn($table))
            ->where($db->qn('newurl') . ' LIKE ' . $db->q('%view=product%'))
            ->where($db->qn('newurl') . ' LIKE ' . $db->q('%option=com_redshop%'))
            ->where(implode(' OR ', $conditions));

        return $db->setQuery($query)->execute();
    }

    /**
     * @param $manufacturerIds
     *
     * @return bool
     * @since  1.1
     */
    public function onAfterManufacturerDelete($manufacturerIds)
    {
        if (empty($manufacturerIds)) {
            return false;
        }

        $db = \Joomla\CMS\Factory::getDbo();
        $query = $db->getQuery(true);
        $conditions   = array();
        $con2 = [];

        if (!$this->isTableExist())

        foreach ($manufacturerIds as $mid) {
            $conditions[] = $db->qn('newurl') . ' LIKE ' . $db->q('%manufacturer_id=' . (int)$mid . '%');
            $con2[] = $db->qn('newurl') . ' LIKE ' . $db->q('%mid=' . (int)$mid . '%');
        }

        $query->delete($db->qn('#__sh404sef_urls'))
            ->where($db->qn('newurl') . ' LIKE ' . $db->q('%view=category%'))
            ->where($db->qn('newurl') . ' LIKE ' . $db->q('%option=com_redshop%'))
            ->where(implode(' OR ', $conditions));

        $flag = \Redshop\DB\Tool::safeExecute($db, $query);

        $query->clear()
            ->delete($db->qn('#__sh404sef_urls'))
            ->where($db->qn('newurl') . ' LIKE ' . $db->q('%view=manufacturers%'))
            ->where($db->qn('newurl') . ' LIKE ' . $db->q('%option=com_redshop%'))
            ->where(implode(' OR ', $con2));

        return $flag && \Redshop\DB\Tool::safeExecute($db, $query);
    }

    /**
     * @param $categoryIds
     *
     * @return bool
     * @since  1.1
     */
    public function onAfterCategoryDelete($categoryIds)
    {
        if (empty($categoryIds)) {
            return false;
        }

        $db = \Joomla\CMS\Factory::getDbo();
        $query = $db->getQuery(true);
        $conditions   = array();

        if (!$this->isTableExist())

            foreach ($categoryIds as $cid) {
                $conditions[] = $db->qn('newurl') . ' LIKE ' . $db->q('%cid=' . (int)$cid . '%');
            }

        $query->delete($db->qn('#__sh404sef_urls'))
            ->where('(' . $db->qn('newurl') . ' LIKE ' . $db->q('%view=product%') . ')'
            . ' OR ' . '(' . $db->qn('newurl') . ' LIKE ' . $db->q('%view=category%') . ')' )
            ->where($db->qn('newurl') . ' LIKE ' . $db->q('%option=com_redshop%'))
            ->where(implode(' OR ', $conditions));

        return \Redshop\DB\Tool::safeExecute($db, $query);
    }

    /**
     * @return bool
     * @since  1.1
     */
    public function isTableExist()
    {
        $db = \Joomla\CMS\Factory::getDbo();
        $results = $db->setQuery('SHOW TABLES')->loadColumn();
        $table   = $db->getPrefix() . 'sh404sef_urls';

        if (empty($results) || !in_array($table, $results)) {
            return false;
        }

        return true;
    }

}
