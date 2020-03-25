<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Performance;

defined('_JEXEC') or die;

/**
 * Payment Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    protected static function isEnable()
    {
        return true;
    }

    protected static function isApply()
    {
        $condition = true;

        return (self::isEnable() && $condition);
    }

    /**
     * @param         $property
     * @param         $id
     * @param         $subId
     * @param   bool  $force
     *
     * @return  mixed|null
     * @since   __DEPLOY_VERSION__
     */
    public static function load($property, $id, $subId = 0, $force = false)
    {
        if (self::isApply() || $force)
        {
            $redCache = \JFactory::getSession()->get('redCache', new \stdClass);

            if (isset($subId)
                && (bool) $subId
                && isset($redCache->$property[$id][$subId])
            ) {
                return $redCache->$property[$id][$subId];
            } elseif (isset($redCache->$property[$id])) {
                    return $redCache->$property[$id];
            }

            return null;
        }

        return null;
    }

    /**
     * @param $property
     * @param $id
     * @param $data
     * @param $subId
     * @param $force
     *
     * @since __DEPLOY_VERSION__
     */
    public static function save($property, $id, $data, $subId = 0, $force = false)
    {
        if (self::isApply() || $force) {
            $redCache = \JFactory::getSession()->get('redCache', new \stdClass);

            if (isset($subId) && (bool) $subId) {
                $redCache->$property[$id][$subId] = $data;
            } else {
                $redCache->$property[$id] = $data;
            }

            \JFactory::getSession()->set('redCache', $redCache);
        }
    }
}