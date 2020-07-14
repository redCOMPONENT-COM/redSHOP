<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Rating;

defined('_JEXEC') or die;

/**
 * Rating Helper
 *
 * @since 3.0
 */
class Helper
{
    /**
     * @param   array  $cid
     * @param   int    $publish
     *
     * @return bool
     * @throws \Exception
     * @since 3.0
     */
    public static function setFavoured($cid = [], $publish = 1)
    {
        if (is_array($cid) && count($cid) > 0) {
            $categoryIds = implode(',', $cid);
            $db          = \JFactory::getDbo();
            $query       = $db->getQuery(true);
            $query->update($db->qn('#__redshop_product_rating'));

            $fields = [
                $db->qn('favoured') . ' = ' . $db->q($publish)
            ];

            $conditions = [
                $db->qn('id') . ' IN (' . $db->q($categoryIds) . ')',
            ];

            $query->set($fields)
                ->where($conditions);

            $db->setQuery($query);

            try {
                $db->execute();
            } catch (\Exception $e) {
                \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

                return false;
            }
        }

        return true;
    }

    public static function setPublish($categoryIds = [], $publish = 1)
    {
        if (is_array($categoryIds) && count($categoryIds) > 0) {
            $db    = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->update($db->qn('#__redshop_product_rating'));

            $fields = [
                $db->qn('published') . ' = ' . $db->q($publish)
            ];

            $conditions = [
                $db->qn('id') . ' IN (' . implode(',', $categoryIds) . ')',
            ];

            $query->set($fields)
                ->where($conditions);

            $db->setQuery($query);

            try {
                $db->execute();
            } catch (\Exception $e) {
                \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

                return false;
            }
        }

        return true;
    }

    /**
     * @param   array  $ids
     *
     * @return bool
     * @throws \Exception
     * @since 3.0
     */
    public static function removeRatings($ids = [])
    {
        if (is_array($ids) && count($ids) > 0) {
            $db    = \JFactory::getDbo();
            $query = $db->getQuery(true)
                ->delete($db->qn('#__redshop_product_rating'))
                ->where($db->qn('rating_id') . ' IN (' . $db->q(implode(',', $ids)) . ')');

            try {
                $db->setQuery($query)->execute();
            } catch (\Exception $e) {
                \JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

                return false;
            }
        }

        return true;
    }
}