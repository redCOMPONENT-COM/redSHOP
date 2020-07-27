<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Promotion;

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Ajax Helper
 *
 * @since 3.0
 */
class Ajax
{
    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function discountCalculator()
    {
        ob_clean();
        $get = Factory::getApplication()->input->get->getArray();
        \Redshop\Promotion\Discount::discountCalculator($get);

        Factory::getApplication()->close();
    }
}