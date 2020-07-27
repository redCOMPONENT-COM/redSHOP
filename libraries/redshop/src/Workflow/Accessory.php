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
 * Accessory Workflow
 *
 * @since __DEPLOY_VERSION__
 */
class Accessory
{
    /**
     * @param $action
     * @return bool
     * @since  __DEPLOY_VERSION__
     */
    protected static function checkCondition($action) {
        $condition = false;

        switch ($action) {
            case 'prepareAccessoryCart':
                $cart = \Redshop\Cart\Helper::getCart();
                $post = \Joomla\CMS\Factory::getApplication()->input->post->getArray();
                return isset($cart['AccessoryAsProduct']) && !empty($post['accessory_data']);
            default:
                break;
        }

        return $condition;
    }

    /**
     * @param array $cart
     * @param array $post
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function prepareAccessoryCart($post) {
        $app = \Joomla\CMS\Factory::getApplication();
        $cart = empty($cart)? \Redshop\Cart\Helper::getCart(): $cart;
        $condition = self::checkCondition(__FUNCTION__);


        if ($condition) {
            $attributes = $cart['AccessoryAsProduct'];

            if (\Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')) {
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
                        $cartData['parent_accessory_product_id'] = $post['product_id'];
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

                            if (\Redshop::getConfig()->getBool('AJAX_CART_BOX')) {
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
                                    'index.php?option=com_redshop&view=product&pid='
                                    . $post['product_id'] . '&Itemid=' . $productItemId,
                                    false
                                )
                            );
                        }
                    }
                }
            }

            \Redshop\Workflow\Quotation::saveCartToDB();
            \Redshop\Cart\Ajax::renderModuleCartHtml();
            unset($cart['AccessoryAsProduct']);
        } else {
            \Redshop\Workflow\Quotation::saveCartToDB();
            \Redshop\Cart\Ajax::renderModuleCartHtml();
        }
    }
}