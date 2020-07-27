<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Cart Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerCart extends RedshopController
{
    /**
     * Constructor
     *
     * @param   array  $default  config array
     */
    public function __construct($default = array())
    {
        parent::__construct($default);
    }

    /**
     * Method to add product in cart
     *
     * @return  void
     * @throws  Exception
     */
    public function add()
    {
        $app                      = \JFactory::getApplication();
        $post                     = $app->input->post->getArray();
        $parentAccessoryProductId = $post['product_id'];

        // Invalid request then redirect to dashboard
        if (empty($app->input->post->getInt('product_id')) || empty($app->input->post->getInt('quantity'))) {
            $app->enqueueMessage(JText::_('COM_REDSHOP_CART_INVALID_REQUEST'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_redshop'));
        }

        $itemId = \RedshopHelperRouter::getCartItemId();

        // Call add method of modal to store product in cart session
        $userField = $app->input->get('userfield');

        JPluginHelper::importPlugin('redshop_product');
        $dispatcher = \RedshopHelperUtility::getDispatcher();
        $dispatcher->trigger('onBeforeAddProductToCart', array(&$post));

        $isAjaxCartBox = \Redshop::getConfig()->getBool('AJAX_CART_BOX');
        $result        = \Redshop\Cart\Cart::addProduct($post);

        if (!is_bool($result) || (is_bool($result) && !$result)) {
            $errorMessage = $result ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

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
                        $cartData                                = array();
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
                            $errorMessage = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

                            $app->enqueueMessage($errorMessage, 'error');

                            if (/** @scrutinizer ignore-deprecated */ JError::isError(
                            /** @scrutinizer ignore-deprecated */ JError::getError()
                            )) {
                                $error        = /** @scrutinizer ignore-deprecated */
                                    JError::getError();
                                $errorMessage = $error->getMessage();
                                $app->enqueueMessage(/** @scrutinizer ignore-deprecated */ $this->getError(), 'error');
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
                                JRoute::_(
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
                        $this->setMessage($cart['notice_message'], 'warning');
                    }

                    $this->setMessage(\JText::_('COM_REDSHOP_PRODUCT_ADDED_TO_CART'), 'message');
                    $link = \JRoute::_($_SERVER['HTTP_REFERER'], false);
                }
            }
        }

        $userDocuments = $session->get('userDocument', array());

        if (isset($userDocuments[$post['product_id']])) {
            unset($userDocuments[$post['product_id']]);
            $session->set('userDocument', $userDocuments);
        }

        $this->setRedirect($link);
    }

    /**
     * Method to add coupon code in cart for discount
     *
     * @return  void
     * @throws  Exception
     */
    public function coupon()
    {
        $itemId   = \RedshopHelperRouter::getCartItemId();
        $app      = \JFactory::getApplication();
        $ajax     = $app->input->getInt('ajax', 0);
        $language = \JFactory::getLanguage()->getTag();

        /** @var RedshopModelCart $model */
        $model = $this->getModel('Cart');

        // Call coupon method of model to apply coupon
        $valid = \RedshopHelperCartDiscount::applyCoupon();;

        $cart = \Redshop\Cart\Helper::getCart();
        $cart = \RedshopHelperDiscount::modifyDiscount($cart);
        \RedshopHelperCart::renderModuleCartHtml();

        // Store cart entry in db
        \RedshopHelperCart::addCartToDatabase();

        $message     = null;
        $messageType = null;

        // If coupon code is valid than apply to cart else raise error
        if ($valid) {
            $link = \JRoute::_(
                'index.php?option=com_redshop&view=cart&lang=' . $language . '&Itemid=' . $itemId,
                false
            );

            $isProductDiscounted = 0;

            if (\Redshop::getConfig()->get('DISCOUNT_TYPE') == 1) {
                foreach ($cart as $index => $value) {
                    if (!is_numeric($index)) {
                        continue;
                    }

                    $isProductDiscounted = \RedshopHelperDiscount::getDiscountPriceBaseDiscountDate($value['product_id']);
                }

                if ($isProductDiscounted != 0) {
                    $message     = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE');
                    $messageType = 'error';
                } else {
                    $message     = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID');
                    $messageType = 'success';
                }
            }

            if (\Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT') != 1) {
                $message     = \JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE');
                $messageType = 'warning';
            } else {
                $message = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID');

                $this->setRedirect($link, \JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID'));
            }
        } else {
            $link = \JRoute::_(
                'index.php?option=com_redshop&view=cart&lang=' . $language . '&Itemid=' . $itemId,
                false
            );

            $message     = \JText::_('COM_REDSHOP_COUPON_CODE_IS_NOT_VALID');
            $messageType = 'error';
        }

        if ($ajax) {
            $cartObject = \RedshopHelperCart::renderModuleCartHtml(\Redshop\Cart\Helper::getCart());

            echo json_encode(array($valid, $message, $cartObject->cartHtml? $cartObject->cartHtml: ''));

            $app->close();
        } else {
            $this->setRedirect($link, $message, $messageType);
        }
    }

    /**
     * Method for modify calculate cart
     *
     * @param   array  $cart  Cart data.
     * @return  mixed
     * @throws  Exception
     * @deprecated Please use \RedshopHelperCart::modifyCalculation();
     * @since   __DEPLOY_VERSION__
     */
    public function modifyCalculation()
    {
        return \RedshopHelperCart::modifyCalculation();
    }

    /**
     * Method to add voucher code in cart for discount
     *
     * @return  void
     * @throws  Exception
     */
    public function voucher()
    {
        $itemId   = \RedshopHelperRouter::getCartItemId();
        $language = \JFactory::getLanguage()->getTag();

        /** @var RedshopModelCart $model */
        $model = $this->getModel('Cart');

        // Call voucher method of model to apply voucher to cart if f voucher code is valid than apply to cart else raise error
        if ($model->voucher()) {
            $cart = \Redshop\Cart\Helper::getCart();
            $this->modifyCalculation($cart);
            \RedshopHelperCart::ajaxRenderModuleCartHtml(false);

            $link        = \JRoute::_(
                'index.php?option=com_redshop&view=cart&seldiscount=voucher&lang=' . $language . '&Itemid=' . $itemId,
                false
            );
            $message     = null;
            $messageType = null;

            if (\Redshop::getConfig()->get('DISCOUNT_TYPE') == 1) {
                foreach ($cart as $index => $value) {
                    if (!is_numeric($index)) {
                        continue;
                    }

                    $isProductDiscounted = \RedshopHelperDiscount::getDiscountPriceBaseDiscountDate($value['product_id']);
                }

                if ($isProductDiscounted != 0) {
                    $message     = \JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE');
                    $messageType = 'error';
                } else {
                    $message     = \JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID');
                    $messageType = 'success';
                }
            }

            if (\Redshop::getConfig()->getInt('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT') != 1) {
                $this->setRedirect(
                    $link,
                    \JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE'),
                    'warning'
                );
            } else {
                $this->setRedirect($link, \JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID'));
            }
        } else {
            $msg  = \JText::_('COM_REDSHOP_VOUCHER_CODE_IS_NOT_VALID');
            $link = \JRoute::_(
                'index.php?option=com_redshop&view=cart&msg=' . $msg . '&seldiscount=voucher&lang=' . $language
                . '&Itemid=' . $itemId,
                false
            );
            $this->setRedirect($link, $msg, 'error');
        }
    }

    /**
     * Method to update product info in cart
     *
     * @return void
     * @throws Exception
     */
    public function update()
    {
        $app   = JFactory::getApplication();
        $input = $app->input;
        $post  = $input->post->getArray();
        $ajax  = $input->getInt('ajax', 0);

        \JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));

        /** @var RedshopModelCart $model */
        $model = $this->getModel('cart');

        if (isset($post['checkQuantity'])) {
            unset($post['checkQuantity']);
        }

        // Call update method of model to update product info of cart
        $model->update($post);

        \RedshopHelperCart::ajaxRenderModuleCartHtml();
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
            $this->setRedirect($link);
        }
    }

    /**
     * Method to update all product info in cart
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function update_all()
    {
        $post = \JFactory::getApplication()->input->post->getArray();

        // Call update_all method of model to update all products info of cart
        \Redshop\Cart\Helper::updateAll($post);

        \RedshopHelperCart::ajaxRenderModuleCartHtml();
        \RedshopHelperCart::addCartToDatabase();

        $link = \JRoute::_(
            'index.php?option=com_redshop&view=cart&Itemid=' . \RedshopHelperRouter::getCartItemId(),
            false
        );
        $this->setRedirect($link);
    }

    /**
     * Method to make cart empty
     * @return void
     * @since  __DEPLOY_VERSION__
     */
    public function empty_cart()
    {
        $app  = \JFactory::getApplication();
        $ajax = $app->input->getInt('ajax', 0);

        // Call empty_cart method of model to remove all products from cart
        \RedshopHelperCart::emptyCart();;
        $user = \JFactory::getUser();

        if ($user->id) {
            \RedshopHelperCart::removeCartFromDatabase(0, $user->id, true);
        }

        if ($ajax) {
            $cartObject = \RedshopHelperCart::renderModuleCartHtml(\Redshop\Cart\Helper::getCart());
            echo $cartObject->cartHtml? $cartObject->cartHtml: '';
            $app->close();
        } else {
            $link = JRoute::_(
                'index.php?option=com_redshop&view=cart&Itemid=' . \RedshopHelperRouter::getCartItemId(),
                false
            );
            $this->setRedirect($link);
        }
    }

    /**
     * Method to delete cart entry from session
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function delete()
    {
        \Redshop\Workflow\Cart::delete();
    }

    /**
     * Method to delete cart entry from session by ajax
     *
     * @return void
     *
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function ajaxDeleteCartItem()
    {
        \Redshop\Helper\Ajax::validateAjaxRequest();

        $app         = JFactory::getApplication();
        $input       = $app->input;
        $cartElement = $input->post->getInt('idx');

        $input->set('ajax_cart_box', 1);
        \Redshop\Cart\Helper::removeItemCart($cartElement);

        \RedshopHelperCart::addCartToDatabase();
        \RedshopHelperCart::ajaxRenderModuleCartHtml();

        $cartObject = \RedshopHelperCart::renderModuleCartHtml(\Redshop\Cart\Helper::getCart());

        echo $cartObject->cartHtml? $cartObject->cartHtml: '' ;

        $app->close();
    }

    /**
     * discount calculator Ajax Function
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function discountCalculator()
    {
        ob_clean();
        $get = \JFactory::getApplication()->input->get->getArray();
        \Redshop\Promotion\Discount::discountCalculator($get);

        \JFactory::getApplication()->close();
    }

    /**
     * Method to add multiple products by its product number using mod_redmasscart module.
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function redmasscart()
    {
        $app  = \JFactory::getApplication();
        $post = $app->input->post->getArray();

        // Check for request forgeries.
        if (!\JSession::checkToken()) {
            $msg  = \JText::_('COM_REDSHOP_TOKEN_VARIFICATION');
            $redMassCartLink = base64_decode($post["rurl"]);
            $app->redirect($redMassCartLink, $msg);;
        }

        if ($post["numbercart"] == "") {
            $msg  = JText::_('COM_REDSHOP_PLEASE_ENTER_PRODUCT_NUMBER');
            $redMassCartLink = base64_decode($post["rurl"]);
            $app->redirect($redMassCartLink, $msg);
        }

        \Redshop\Cart\Helper::redMassCart($post);

        $link = \JRoute::_('index.php?option=com_redshop&view=cart&Itemid='
            . $app->input->getInt('Itemid'), false);
        $this->setRedirect($link);
    }

    /**
     * Get Shipping rate function
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function getShippingRate()
    {
        echo \Redshop\Shipping\Rate::calculate();

        \JFactory::getApplication()->close();
    }

    /**
     * Change Attribute
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function changeAttribute()
    {
        $post = \JFactory::getApplication()->input->post->getArray();
        $cart = \Redshop\Cart\Cart::modify(\Redshop\Cart\Helper::changeAttribute($post), JFactory::getUser()->id);
        \Redshop\Cart\Helper::setCart($cart);
        \RedshopHelperCart::ajaxRenderModuleCartHtml();

        ?>
        <script type="text/javascript">
            window.parent.location.reload();
        </script>
        <?php
    }

    /**
     * Method called when user pressed cancel button
     *
     * @return void
     * @throws Exception
     * @since  __DEPLOY_VERSION__
     */
    public function cancel()
    {
        $link = \JRoute::_(
            'index.php?option=com_redshop&view=cart&Itemid=' . \JFactory::getApplication()->input->getInt('Itemid'),
            false
        ); ?>
        <script language="javascript">
            window.parent.location.href = "<?php echo $link ?>";
        </script>
        <?php
        JFactory::getApplication()->close();
    }

    /**
     * Get product tax for ajax request
     *
     * @return  void
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     * @deprecated
     * @see  \Redshop\Cart\Ajax::getProductTax();
     */
    public function ajaxGetProductTax()
    {
        \Redshop\Cart\Ajax::getProductTax();
    }
}
