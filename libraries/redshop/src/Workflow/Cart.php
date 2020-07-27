<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
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
    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function add()
    {
        $app                      = \JFactory::getApplication();
        $post                     = $app->input->post->getArray();
        $parentAccessoryProductId = $post['product_id'];

        // Invalid request then redirect to dashboard
        if (empty($app->input->post->getInt('product_id')) || empty($app->input->post->getInt('quantity'))) {
            $app->enqueueMessage(\JText::_('COM_REDSHOP_CART_INVALID_REQUEST'), 'error');
            $app->redirect(\JRoute::_('index.php?option=com_redshop'));
        }

        $itemId = \RedshopHelperRouter::getCartItemId();

        // Call add method of modal to store product in cart session
        $userField = $app->input->get('userfield');

        \JPluginHelper::importPlugin('redshop_product');
        $dispatcher = \RedshopHelperUtility::getDispatcher();
        $dispatcher->trigger('onBeforeAddProductToCart', array(&$post));

        $isAjaxCartBox = \Redshop::getConfig()->getBool('AJAX_CART_BOX');
        $result        = \Redshop\Cart\Cart::addProduct($post);

        if (!is_bool($result) || (is_bool($result) && !$result)) {
            $errorMessage = $result ? $result : \JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

            // Set Error Message
            $app->enqueueMessage($errorMessage, 'error');

            if ($isAjaxCartBox) {
                echo '`0`' . $errorMessage;
                $app->close();
            } else {
                $itemData = \RedshopHelperProduct::getMenuInformation(0, 0, '', 'product&pid=' . $post['product_id']);

                if (count($itemData) > 0) {
                    $productItemId = $itemData->id;
                } else {
                    $productItemId = \RedshopHelperRouter::getItemId(
                        $post['product_id'],
                        \RedshopProduct::getInstance($post['product_id'])->cat_in_sefurl
                    );
                }

                // Directly redirect if error found
                $app->redirect(
                    \JRoute::_(
                        'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&cid='
                        . $post['category_id'] . '&Itemid=' . $productItemId,
                        false
                    )
                );
            }
        }
        $session              = \JFactory::getSession();
        \Redshop\Workflow\Accessory::prepareAccessoryCart($post);
        $cart = \Redshop\Cart\Helper::getCart();

        $link = \JRoute::_(
            'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&Itemid=' . $itemId,
            false
        );

        if (!$userField) {
            if ($isAjaxCartBox && isset($post['ajax_cart_box'])) {
                $link = \JRoute::_(
                    'index.php?option=com_redshop&view=cart&ajax_cart_box=' . $post['ajax_cart_box'] . '&tmpl=component&Itemid=' . $itemId,
                    false
                );
            } else {
                if (\Redshop::getConfig()->getInt('ADDTOCART_BEHAVIOUR') === 1) {
                    $link = \JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $itemId, false);
                } else {
                    if (isset($cart['notice_message']) && !empty($cart['notice_message'])) {
                        $app->enqueueMessage($cart['notice_message'], 'warning');
                    }

                    $app->enqueueMessage(\JText::_('COM_REDSHOP_PRODUCT_ADDED_TO_CART'), 'message');
                    $link = \JRoute::_($_SERVER['HTTP_REFERER'], false);
                }
            }
        }

        $userDocuments = $session->get('userDocument', array());

        if (isset($userDocuments[$post['product_id']])) {
            unset($userDocuments[$post['product_id']]);
            $session->set('userDocument', $userDocuments);
        }

        $app->redirect($link);
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