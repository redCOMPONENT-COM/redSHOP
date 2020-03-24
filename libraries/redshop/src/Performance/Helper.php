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
     * @param   bool  $force
     *
     * @return  mixed|null
     * @since   __DEPLOY_VERSION__
     */
    public static function load($property, $id, $force = false)
    {
        if (self::isApply() || $force)
        {
            $redCache = \JFactory::getSession()->get('redCache', new \stdClass);

            if (isset($redCache->$property[$id])
                && ($redCache->$property[$id] == $id)) {
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
     * @param $force
     *
     * @since __DEPLOY_VERSION__
     */
    public static function save($property, $id, $data, $force = false)
    {
        if (self::isApply() || $force) {
            $redCache = \JFactory::getSession()->get('redCache', new \stdClass);
            $redCache->$property[$id] = $data;
            \JFactory::getSession()->set('redCache', $redCache);
        }
    }
}