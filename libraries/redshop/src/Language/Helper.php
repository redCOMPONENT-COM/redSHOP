<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Language;

defined('_JEXEC') or die;

/**
 * Language Helper
 *
 * @since  __DEPLOY_VERION__
 */

class Helper
{
    /**
     * @return \Joomla\CMS\Language\Language|null
     * @since  __DEPLOY_VERSION__
     */
    public static function getLanguage() {
        return \Joomla\CMS\Factory::getLanguage();
    }
}