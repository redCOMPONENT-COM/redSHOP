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
        $cart                 = \Redshop\Cart\Helper::getCart();
        $isQuotationMode      = \Redshop::getConfig()->getBool('DEFAULT_QUOTATION_MODE');
        $isShowQuotationPrice = \Redshop::getConfig()->getBool('SHOW_QUOTATION_PRICE');

        if (isset($cart['AccessoryAsProduct']) && !empty($post['accessory_data'])) {
            $attributes = $cart['AccessoryAsProduct'];

            if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')) {
                $data['accessory_data']       = $attributes[0];
                $data['acc_quantity_data']    = $attributes[1];
                $data['acc_attribute_data']   = $attributes[2];
                $data['acc_property_data']    = $attributes[3];
                $data['acc_subproperty_data'] = $attributes[4];

                if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0)) {
                    $accessories            = explode("@@", $data['accessory_data']);
                    $accessoriesQuantity    = explode("@@", $data['acc_quantity_data']);
                    $accessoriesAttribute   = explode("@@", $data['acc_attribute_data']);
                    $accessoriesProperty    = explode("@@", $data['acc_property_data']);
                    $accessoriesSubProperty = explode("@@", $data['acc_subproperty_data']);

                    foreach ($accessories as $i => $accessoryId) {
                        $accessory                               = \RedshopHelperAccessory::getProductAccessories(
                            $accessoryId
                        );
                        $cartData                                = [];
                        $cartData['parent_accessory_product_id'] = $parentAccessoryProductId;
                        $cartData['product_id']                  = $accessory[0]->child_product_id;
                        $cartData['quantity']                    = $accessoriesQuantity[$i];
                        $cartData['category_id']                 = 0;
                        $cartData['sel_wrapper_id']              = 0;
                        $cartData['attribute_data']              = $accessoriesAttribute[$i];
                        $cartData['property_data']               = $accessoriesProperty[$i];
                        $cartData['subproperty_data']            = $accessoriesSubProperty[$i];
                        $cartData['accessory_id']                = $accessories[$i];

                        $result = \Redshop\Cart\Cart::addProduct($cartData);
                        $cart   = \Redshop\Cart\Helper::getCart();

                        if (!is_bool($result) || !$result) {
                            $errorMessage = ($result) ? $result : \JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

                            $app->enqueueMessage($errorMessage, 'error');

                            if (/** @scrutinizer ignore-deprecated */ \JError::isError(
                            /** @scrutinizer ignore-deprecated */ \JError::getError()
                            )) {
                                $error = /** @scrutinizer ignore-deprecated */ \JError::getError();
                                $errorMessage = $error->getMessage();
                                $app->enqueueMessage($errorMessage, 'error');
                            }

                            if ($isAjaxCartBox) {
                                echo '`0`' . $errorMessage;
                                $app->close();
                            }

                            $itemData = \RedshopHelperProduct::getMenuInformation(
                                0,
                                0,
                                '',
                                'product&pid=' . $post['product_id']
                            );

                            if (count($itemData) > 0) {
                                $productItemId = $itemData->id;
                            } else {
                                $productItemId = \RedshopHelperRouter::getItemId($post['product_id']);
                            }

                            $app->redirect(
                                \JRoute::_(
                                    'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&Itemid=' . $productItemId,
                                    false
                                )
                            );
                        }
                    }
                }
            }

            if (!$isQuotationMode || ($isQuotationMode && $isShowQuotationPrice)) {
                \RedshopHelperCart::addCartToDatabase();
            }

            \RedshopHelperCart::ajaxRenderModuleCartHtml();
            unset($cart['AccessoryAsProduct']);
        } else {
            if (!$isQuotationMode || ($isQuotationMode && $isShowQuotationPrice)) {
                \RedshopHelperCart::addCartToDatabase();
            }

            \RedshopHelperCart::ajaxRenderModuleCartHtml();
        }

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

    public static function emptyCart() {
        self:: removeAll();
    }
}