<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Quotation;

defined('_JEXEC') or die;

/**
 * Quotation Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param $question
     * @param null $default
     * @return mixed|null
     * @since __DEPLOY_VERSION__
     */
    public static function is($question, $default = null) {
        return \Redshop::getConfig()->get($question, $default);
    }
}