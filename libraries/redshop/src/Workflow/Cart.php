<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Workflow;

defined('_JEXEC') or die;

/**
 * Cart Workflow
 *
 * @since  __DEPLOY_VERION__
 */
class Cart
{
    public static function delete()
    {
        $app  = \JFactory::getApplication();
        $post = $app->input->post;
        $cartElement = $post->getInt('cart_index', 0);

        \Redshop\Cart\Helper::removeItemCart($cartElement);
        \Redshop\Cart\Ajax::renderModuleCartHtml();
        \RedshopHelperCart::addCartToDatabase();

        $link = \JRoute::_(
            'index.php?option=com_redshop&view=cart&Itemid=' . \RedshopHelperRouter::getCartItemId(),
            false
        );
        $app->redirect($link);
    }
}