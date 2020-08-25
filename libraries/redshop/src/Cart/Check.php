<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

defined('_JEXEC') or die;

/**
 * Cart helper
 *
 * @since  2.1.0
 */
class Check
{
    /**
     * @param $action
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    public static function checkCondition($action) {
        $condition = false;

        switch ($action) {
            case 'add':
                $app = \Joomla\CMS\Factory::getApplication();
                $condition = !(empty($app->input->post->getInt('product_id'))
                    || empty($app->input->post->getInt('quantity')));

                // Invalid request then redirect to dashboard
                if (!$condition) {
                    $app->enqueueMessage(\JText::_('COM_REDSHOP_CART_INVALID_REQUEST'), 'error');
                    $app->redirect(\JRoute::_('index.php?option=com_redshop'));
                }

                return $condition;
            default:
                break;
        }

        return $condition;
    }
}