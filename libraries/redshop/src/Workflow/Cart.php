<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Workflow;

use Redshop\Cart\Helper;

defined('_JEXEC') or die;

/**
 * Cart Workflow
 *
 * @since  __DEPLOY_VERION__
 */
class Cart
{
    /**
     * @param $action
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    protected static function checkCondition($action) {
        $condition = false;

        switch ($action) {
            case 'add':
                $app = \Joomla\CMS\Factory::getApplication();
                return !(empty($app->input->post->getInt('product_id')) || empty($app->input->post->getInt('quantity')));
            default:
                break;
        }

        return $condition;
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function add()
    {
        $condition = self::checkCondition(__FUNCTION__);
        $app       = \JFactory::getApplication();
        $post      = $app->input->post->getArray();

        // Invalid request then redirect to dashboard
        if (!$condition) {
            $app->enqueueMessage(\JText::_('COM_REDSHOP_CART_INVALID_REQUEST'), 'error');
            $app->redirect(\JRoute::_('index.php?option=com_redshop'));
        }

        \Redshop\Plugin\Helper::invoke('redshop_product',
            '',
            'onBeforeAddProductToCart',
            [&$post]);

        $result = \Redshop\Cart\Cart::addProduct($post);
        \Redshop\Cart\Helper::addToCartErrorHandler($result);
        \Redshop\Workflow\Accessory::prepareAccessoryCart();
        \Redshop\Cart\Helper::setUserDocumentToSession();
        \Redshop\Cart\Helper::routingAfterAddToCart();
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function update()
    {
        $app   = \JFactory::getApplication();
        $input = $app->input;
        $post  = $input->post->getArray();
        $ajax  = $input->getInt('ajax', 0);

        \JSession::checkToken('get') or die(\JText::_('JINVALID_TOKEN'));

        if (isset($post['checkQuantity'])) {
            unset($post['checkQuantity']);
        }

        // Call update method of model to update product info of cart
        \Redshop\Cart\Helper::updateCart($post);
        \Redshop\Cart\Ajax::renderModuleCartHtml();
        \RedshopHelperCart::addCartToDatabase();

        if ($ajax) {
            $cartObject = \RedshopHelperCart::renderModuleCartHtml(\Redshop\Cart\Helper::getCart());

            echo $cartObject->cartHtml? $cartObject->cartHtml: '';

            $app->close();
        } else {
            $link = \JRoute::_(
                'index.php?option=com_redshop&view=cart&Itemid=' . \RedshopHelperRouter::getCartItemId(),
                false
            );
            $app->redirect($link);
        }
    }

    /**
     * @throws \Exception
     * @since   __DEPLOY_VERSION__
     */
    public static function updateAll() {
        $post = \JFactory::getApplication()->input->post->getArray();

        // Call update_all method of model to update all products info of cart
        \Redshop\Cart\Helper::updateAll($post);

        \RedshopHelperCart::ajaxRenderModuleCartHtml();
        \RedshopHelperCart::addCartToDatabase();

        $link = \JRoute::_(
            'index.php?option=com_redshop&view=cart&Itemid=' . \RedshopHelperRouter::getCartItemId(),
            false
        );

        \Joomla\CMS\Factory::getApplication()->redirect($link);
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
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

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function removeAll() {
        $app  = \Joomla\CMS\Factory::getApplication();
        $ajax = $app->input->getInt('ajax', 0);

        // Call empty_cart method of model to remove all products from cart
        \RedshopHelperCart::emptyCart();;
        $user = \JFactory::getUser();

        if ($user->id) {
            \RedshopHelperCart::removeCartFromDatabase(0, $user->id, true);
        }

        if ($ajax) {
            $cartObject = \Redshop\Cart\Render::moduleCart(\Redshop\Cart\Helper::getCart());
            echo $cartObject->cartHtml? $cartObject->cartHtml: '';
            $app->close();
        } else {
            $link = \JRoute::_(
                'index.php?option=com_redshop&view=cart&Itemid=' . \RedshopHelperRouter::getCartItemId(),
                false
            );

            $app->redirect($link);
        }
    }

    /**
     * Alias of function removeAll()
     * @since __DEPLOY_VERSION__
     */
    public static function emptyCart() {
        self:: removeAll();
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function redMassCart() {
        $app  = \JFactory::getApplication();
        $post = $app->input->post->getArray();

        // Check for request forgeries.
        if (!\JSession::checkToken()) {
            $msg  = \JText::_('COM_REDSHOP_TOKEN_VARIFICATION');
            $redMassCartLink = base64_decode($post["rurl"]);
            $app->redirect($redMassCartLink, $msg);;
        }

        if ($post["numbercart"] == "") {
            $msg  = \JText::_('COM_REDSHOP_PLEASE_ENTER_PRODUCT_NUMBER');
            $redMassCartLink = base64_decode($post["rurl"]);
            $app->redirect($redMassCartLink, $msg);
        }

        \Redshop\Cart\Helper::redMassCart($post);

        $link = \JRoute::_('index.php?option=com_redshop&view=cart&Itemid='
            . $app->input->getInt('Itemid'), false);
        $app->redirect($link);
    }

    /**
     * Method called when user pressed cancel button
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function cancel()
    {
        \Redshop\Cart\Ajax::cancel();
    }
}