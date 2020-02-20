<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Economic\RedshopEconomic;

/**
 * Checkout Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerCheckout extends RedshopController
{
    /**
     * @var  order_functions
     */
    public $_order_functions = null;

    /**
     * @var shipping
     */
    public $_shippinghelper = null;

    /**
     * Constructor.
     *
     * @param array $default config array
     *
     * @throws  Exception
     */
    public function __construct($default = array())
    {
        $this->_order_functions = order_functions::getInstance();
        $this->_shippinghelper = shipping::getInstance();
        JFactory::getApplication()->input->set('layout', 'default');
        parent::__construct($default);
    }

    /**
     *  Method to store user detail when user do checkout.
     *
     * @return  void
     *
     * @throws  Exception
     */
    public function checkoutprocess()
    {
        $input = JFactory::getApplication()->input;
        $post = $input->post->getArray();

        /** @var RedshopModelCheckout $model */
        $model = $this->getModel('checkout');

        if ($model->store($post)) {
            $this->setRedirect(
                JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $input->get('Itemid'), false)
            );
        } else {
            $input->set('view', 'checkout');
            $input->set('task', '');
            parent::display('default');
        }
    }

    /**
     *  Method for checkout second step.
     *
     * @return void
     *
     * @throws Exception
     */
    public function checkoutNext()
    {
        $app = \JFactory::getApplication();
        $input = $app->input;
        $session = \JFactory::getSession();
        $post = $input->post->getArray();
        $cart = \Redshop\Cart\Helper::getCart();

        if (isset($post['extrafields0'])
            && isset($post['extrafields']) 
            && count($cart) > 0) {
            if (count($post['extrafields0']) > 0 && count($post['extrafields']) > 0) {
                for ($r = 0, $countExtrafield = count($post['extrafields']); $r < $countExtrafield; $r++) {
                    $post['extrafields_values'][$post['extrafields'][$r]] = $post['extrafields0'][$r];
                }

                $cart['extrafields_values'] = $post['extrafields_values'];
                \Redshop\Cart\Helper::setCart($cart);
            }
        }

        $itemId = $input->post->getInt('Itemid', 0);
        $userInfoIds = $input->post->getInt('users_info_id', 0);
        $creditCardInfo = $input->post->getString('ccinfo', '');
        $rsUser = $session->get('rs_user');

        if ($userInfoIds) {
            $rsUser['rs_user_info_id'] = $userInfoIds;
        }

        $session->set('rs_user', $rsUser);
        $checkResult = $this->checkValidation($userInfoIds);

        if (!empty($checkResult)) {
            if ($checkResult == 1) {
                $link = 'index.php?option=com_redshop&view=account_billto&return=checkout&setexit=0&Itemid=' . $itemId;
            } else {
                $link = 'index.php?option=com_redshop&view=account_shipto&task=addshipping&setexit=0&return=checkout&infoid=' . $userInfoIds . '&Itemid=' . $itemId;
            }

            $app->redirect(\JRoute::_($link, true));
        }

        $errorMsg = "";

        if ($creditCardInfo == 1) {
            $errorMsg = $this->setcreditcardInfo();
        }

        if ($errorMsg != "") {
            $app->redirect(\JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId, false), $errorMsg);
        } else {
            $view = $this->getView('checkout', 'next');
            parent::display();
        }
    }

    /**
     * Update GLS Location
     *
     * @return void
     *
     * @throws Exception
     */
    public function updateGLSLocation()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        JPluginHelper::importPlugin('redshop_shipping');
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $usersInfoId = $input->getInt('users_info_id', 0);
        $values = RedshopHelperUser::getUserInformation(0, '', $usersInfoId, false);
        $values->zipcode = $input->get('zipcode', '');
        $ShopResponses = $dispatcher->trigger('GetNearstParcelShops', array($values));

        if ($ShopResponses && isset($ShopResponses[0]) && $ShopResponses[0]) {
            if (is_array($ShopResponses[0])) {
                $ShopRespons = $ShopResponses[0];
                $shopList = array();

                for ($i = 0, $c = count($ShopRespons); $i < $c; $i++) {
                    $shopList[] = JHTML::_('select.option', $ShopRespons[$i]->shop_id, $ShopRespons[$i]->CompanyName . ", " . $ShopRespons[$i]->Streetname . ", " . $ShopRespons[$i]->ZipCode . ", " . $ShopRespons[$i]->CityName);
                }

                echo JHTML::_('select.genericlist', $shopList, 'shop_id', 'class="inputbox" ', 'value', 'text', $ShopRespons[0]->shop_id);
            } else {
                echo $ShopResponses[0];
            }
        }

        $app->close();
    }

    /**
     * Get Shipping Information
     *
     * @return  void
     *
     * @throws  Exception
     */
    public function getShippingInformation()
    {
        $app = JFactory::getApplication();
        $plugin = $app->input->getCmd('plugin', '');

        JPluginHelper::importPlugin('redshop_shipping');
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $dispatcher->trigger('on' . $plugin . 'AjaxRequest');

        $app->close();
    }

    /**
     * Check validation
     *
     * @param integer $userInfoIds not used
     *
     * @return  integer
     *
     * @throws  Exception
     */
    public function checkValidation($userInfoIds)
    {
        /** @var RedshopModelCheckout $model */
        $model = $this->getModel('checkout');
        $billingAddresses = $model->billingaddresses();

        $return = 0;

        if (!$billingAddresses->is_company) {
            if ($billingAddresses->firstname == '') {
                $return = 1;
                $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME');
                \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                return $return;
            } elseif ($billingAddresses->lastname == '') {
                $return = 1;
                $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME');
                \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                return $return;
            }
        } else {
            if ($billingAddresses->company_name == '') {
                $return = 1;
                $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME');
                \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                return $return;
            }

            if ($billingAddresses->firstname == '') {
                $return = 1;
                $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME');
                \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                return $return;
            } elseif ($billingAddresses->lastname == '') {
                $return = 1;
                $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME');
                \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                return $return;
            } elseif (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1
                && Redshop::getConfig()->get('REQUIRED_EAN_NUMBER')
                && trim($billingAddresses->ean_number) != '') {
                RedshopEconomic::createUserInEconomic($billingAddresses);

                if (/** @scrutinizer ignore-deprecated */ JError::isError(/** @scrutinizer ignore-deprecated */ JError::getError())) {
                    $return = 1;
                    $error = /** @scrutinizer ignore-deprecated */
                        JError::getError();
                    $msg = $error->getMessage();
                    \JFactory::getApplication()->enqueueMessage($msg, 'error');

                    return $return;
                }
            }
        }

        if (!trim($billingAddresses->address) && Redshop::getConfig()->get('REQUIRED_ADDRESS')) {
            $return = 1;
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS');
            \JFactory::getApplication()->enqueueMessage($msg, 'warning');

            return $return;
        } elseif (!$billingAddresses->country_code && Redshop::getConfig()->get('REQUIRED_COUNTRY_CODE')) {
            $return = 1;
            $msg = JText::_('COM_REDSHOP_PLEASE_SELECT_COUNTRY');
            \JFactory::getApplication()->enqueueMessage($msg, 'warning');

            return $return;
        } elseif (!$billingAddresses->zipcode && Redshop::getConfig()->get('REQUIRED_POSTAL_CODE')) {
            $return = 1;
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_ZIPCODE');
            \JFactory::getApplication()->enqueueMessage($msg, 'warning');

            return $return;
        } elseif (!$billingAddresses->phone && Redshop::getConfig()->get('REQUIRED_PHONE')) {
            $return = 1;
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_PHONE');
            \JFactory::getApplication()->enqueueMessage($msg, 'warning');

            return $return;
        }

        if ($billingAddresses->is_company == 1) {
            $extraFieldName = RedshopHelperExtrafields::CheckExtraFieldValidation(
                RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS, $billingAddresses->users_info_id
            );

            if (!empty($extraFieldName)) {
                $return = 1;
                $msg = $extraFieldName . JText::_('COM_REDSHOP_IS_REQUIRED');
                \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                return $return;
            }
        } else {
            $extraFieldName = RedshopHelperExtrafields::CheckExtraFieldValidation(
                RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS, $billingAddresses->users_info_id
            );

            if (!empty($extraFieldName)) {
                $return = 1;
                $msg = $extraFieldName . JText::_('COM_REDSHOP_IS_REQUIRED');
                \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                return $return;
            }
        }

        if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE') && $userInfoIds != $billingAddresses->users_info_id) {
            if ($billingAddresses->is_company == 1) {
                $extraFieldName = RedshopHelperExtrafields::CheckExtraFieldValidation(
                    RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS, $userInfoIds
                );

                if (!empty($extraFieldName)) {
                    $return = 2;
                    $msg = $extraFieldName . JText::_('COM_REDSHOP_IS_REQUIRED');
                    \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                    return $return;
                }
            } else {
                $extraFieldName = RedshopHelperExtrafields::CheckExtraFieldValidation(
                    RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS, $userInfoIds
                );

                if (!empty($extraFieldName)) {
                    $return = 2;
                    $msg = $extraFieldName . JText::_('COM_REDSHOP_IS_REQUIRED');
                    \JFactory::getApplication()->enqueueMessage($msg, 'warning');

                    return $return;
                }
            }
        }

        return $return;
    }

    /**
     * Checkout final step function
     *
     * @return void
     *
     * @throws Exception
     */
    public function checkoutfinal()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $post = $input->post->getArray();
        $itemId = $input->post->getInt('Itemid', 0);

        /** @var RedshopModelCheckout $model */
        $model = $this->getModel('checkout');
        $session = JFactory::getSession();
        $cart = $session->get('cart');
        $user = JFactory::getUser();
        $payment_method_id = $input->post->getString('payment_method_id', '');

        if (isset($post['extrafields0']) && isset($post['extrafields']) && count($cart) > 0) {
            if (count($post['extrafields0']) > 0 && count($post['extrafields']) > 0) {
                for ($r = 0, $countExtrafield = count($post['extrafields']); $r < $countExtrafield; $r++) {
                    $post['extrafields_values'][$post['extrafields'][$r]] = $post['extrafields0'][$r];
                }

                $cart['extrafields_values'] = $post['extrafields_values'];
                \Redshop\Cart\Helper::setCart($cart);
            }
        }

        if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) {
            $shipping_rate_id = $input->post->getString('shipping_rate_id', '');
            $shippingdetail = Redshop\Shipping\Rate::decrypt($shipping_rate_id);

            if (count($shippingdetail) < 4) {
                $shipping_rate_id = "";
            }

            if ($shipping_rate_id == '' && $cart['free_shipping'] != 1) {
                $msg = JText::_('LIB_REDSHOP_SELECT_SHIP_METHOD');
                $app->redirect(JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId, false), $msg);
            }
        }

        if ($payment_method_id != '') {
            if (isset($cart['idx'])) {
                if ($cart['idx'] > 0) {
                    $session->set('order_id', 0);
                } else {
                    $app->redirect(JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $itemId, false));
                    $app->close();
                }
            }

            if (Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE')) {
                $userInfoIds = $input->getInt('users_info_id');

                if (empty($userInfoIds)) {
                    $userDetail = $model->store($post);
                    $userInfoIds = $userDetail !== false ? $userDetail->users_info_id : 0;
                }

                $checkResult = $this->checkValidation($userInfoIds);

                if (!empty($checkResult)) {
                    if ($checkResult == 1) {
                        $link = 'index.php?option=com_redshop&view=account_billto&return=checkout&setexit=0&Itemid=' . $itemId;
                    } else {
                        $link = 'index.php?option=com_redshop&view=account_shipto&task=addshipping&setexit=0&return=checkout&infoid=' . $userInfoIds . '&Itemid=' . $itemId;
                    }

                    $app->redirect(JRoute::_($link));

                    return;
                }

                // Skip checks for free cart
                if ($cart['total'] > 0) {
                    $errorMsg = $this->setcreditcardInfo();

                    if ($errorMsg != "") {
                        $app->redirect(JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId), $errorMsg);

                        return;
                    }
                }
            }

            $order_id = (int)$session->get('order_id');

            // Import files for plugin
            JPluginHelper::importPlugin('redshop_product');

            if ($order_id === 0) {
                // Add plugin support
                $dispatcher->trigger('beforeOrderPlace', array($cart));
                $orderresult = $model->orderplace();
                $order_id = $orderresult->order_id;
            } else {
                $input->set('order_id', $order_id);
            }

            if ($order_id) {
                $billingAddresses = RedshopHelperOrder::getOrderBillingUserInfo($order_id);

                JPluginHelper::importPlugin('redshop_product');
                JPluginHelper::importPlugin('redshop_alert');
                $data = $dispatcher->trigger('getStockroomStatus', array($order_id));

                $labelClass = '';

                if ($orderresult->order_payment_status == 'Paid') {
                    $labelClass = 'label-success';
                }

                $message = JText::sprintf('COM_REDSHOP_ALERT_ORDER_SUCCESSFULLY', $order_id, $billingAddresses->firstname . ' ' . $billingAddresses->lastname, RedshopHelperProductPrice::formattedPrice($orderresult->order_total), $labelClass, $orderresult->order_payment_status);
                $dispatcher->trigger('storeAlert', array($message));

                $model->resetcart();

                // Add Plugin support
                $dispatcher->trigger('afterOrderPlace', array($cart, $orderresult));

                JPluginHelper::importPlugin('system');
                $dispatcher->trigger('afterOrderCreated', array($orderresult));

                // New checkout flow
                /**
                 * change redirection
                 * The page will redirect to stand alon page where, payment extra infor code will execute.
                 * Note: ( Only when redirect payment gateway are in motion, not for credit card gateway)
                 *
                 */
                $paymentmethod = RedshopHelperOrder::getPaymentMethodInfo($payment_method_id);
                $paymentmethod = $paymentmethod[0];
                $params = new \Joomla\Registry\Registry($paymentmethod->params);
                $is_creditcard = $params->get('is_creditcard', 0);
                $is_redirected = $params->get('is_redirected', 0);

                $session->clear('userDocument');

                if ($is_creditcard && !$is_redirected) {
                    $link = JRoute::_('index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $order_id . '&Itemid=' . $itemId, false);
                    $this->setRedirect($link, JText::_('COM_REDSHOP_ORDER_PLACED'));
                } else {
                    $link = JRoute::_('index.php?option=com_redshop&view=order_detail&layout=checkout_final&oid=' . $order_id
                        . '&Itemid=' . $itemId, false);
                    $this->setRedirect($link);
                }
            } else {
                $errorMsg = $model->getError();
                /** @scrutinizer ignore-deprecated */
                JError::raiseWarning(21, $errorMsg);
                $app->redirect(JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId, false));
            }
        } else {
            $msg = JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD');
            $app->redirect(JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId, false), $msg, 'error');
        }
    }

    /**
     * Set credit Card info
     *
     * @return string
     */
    public function setcreditcardInfo()
    {
        $input = JFactory::getApplication()->input;
        $model = $this->getModel('checkout');
        $session = JFactory::getSession();
        $paymentMethodId = $input->post->getCmd('payment_method_id', '');
        $paymentMethod = RedshopHelperOrder::getPaymentMethodInfo($paymentMethodId);
        $paymentParams = new JRegistry($paymentMethod[0]->params);
        $isCreditcard = $paymentParams->get('is_creditcard', 0);

        if (!$isCreditcard) {
            return "";
        }

        $data = array();
        $data['order_payment_name'] = $input->post->getString('order_payment_name', '');
        $data['creditcard_code'] = $input->post->getString('creditcard_code', '');
        $data['order_payment_number'] = $input->post->getString('order_payment_number', '');
        $data['order_payment_expire_month'] = $input->post->getString('order_payment_expire_month', '');
        $data['order_payment_expire_year'] = $input->post->getString('order_payment_expire_year', '');
        $data['credit_card_code'] = $input->post->getString('credit_card_code', '');
        $data['selectedCardId'] = $input->post->getString('selectedCard', '');
        $session->set('ccdata', $data);

        $validPayment = $model->validatepaymentccinfo();

        if ($validPayment[0]) {
            return "";
        }

        return $validPayment[1];
    }

    /**
     * One Step checkout process
     *
     * @return void
     *
     * @throws Exception
     */
    public function oneStepCheckoutProcess()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $session = JFactory::getSession();
        $redShopUser = $session->get('rs_user');
        $post = $input->post->getArray();
        $usersInfoId = $post['users_info_id'];

        if ($usersInfoId) {
            $redShopUser['rs_user_info_id'] = $usersInfoId;
        } elseif (!empty($post['anonymous'])) {
            if (!empty($post['anonymous']['BT'])) {
                $redShopUser['vatCountry'] = $post['anonymous']['BT']['country_code'];
                $redShopUser['vatState'] = $post['anonymous']['BT']['state_code'];
            }

            if (Redshop::getConfig()->getInt('VAT_BASED_ON') != 0 && Redshop::getConfig()->getString('CALCULATE_VAT_ON') == 'ST'
                && !empty($post['anonymous']['ST'])) {
                $redShopUser['vatCountry'] = $post['anonymous']['ST']['country_code_ST'];
                $redShopUser['vatState'] = $post['anonymous']['ST']['state_code_ST'];
            }
        }

        $session->set('rs_user', $redShopUser);

        $user = JFactory::getUser();
        $cart = $session->get('cart');
        $shipping_box_id = $post['shipping_box_id'];
        $shipping_rate_id = $post['shipping_rate_id'];
        $customer_note = $post['customer_note'];
        $req_number = $post['requisition_number'];
        $customer_message = $post['rs_customer_message_ta'];
        $referral_code = $post['txt_referral_code'];

        $payment_method_id = $post['payment_method_id'];
        $order_total = $cart['total'];
        $total_discount = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
        $order_subtotal = (Redshop::getConfig()->get('SHIPPING_AFTER') == 'total') ? $cart['product_subtotal_excl_vat'] - $total_discount : $cart['product_subtotal_excl_vat'];
        $itemId = $post['Itemid'];
        $objectName = $post['objectname'];
        $rate_template_id = $post['rate_template_id'];
        $cart_template_id = $post['cart_template_id'];

        $oneStepTemplateHtml = "";
        $rateTemplateHtml = "";

        if ($objectName == "users_info_id" || $objectName == "shipping_box_id") {
            $shipping_template = RedshopHelperTemplate::getTemplate("redshop_shipping", $rate_template_id);

            if (count($shipping_template) > 0) {
                $rateTemplateHtml = $shipping_template[0]->template_desc;
            }

            $return = \Redshop\Shipping\Tag::replaceShippingTemplate($rateTemplateHtml, $shipping_rate_id, $shipping_box_id, $user->id, $usersInfoId, $order_total, $order_subtotal, $post);
            $rateTemplateHtml = $return['template_desc'];
            $shipping_rate_id = $return['shipping_rate_id'];
        }

        if ($shipping_rate_id != "") {
            $shipArr = Redshop\Helper\Shipping::calculateShipping($shipping_rate_id);
            $cart['shipping'] = $shipArr['order_shipping_rate'];
            $cart['shipping_vat'] = $shipArr['shipping_vat'];
            $cart = RedshopHelperDiscount::modifyDiscount($cart);
        }

        if ($cart_template_id != 0) {
            $templatelist = RedshopHelperTemplate::getTemplate("checkout", $cart_template_id);
            $oneStepTemplateHtml = $templatelist[0]->template_desc;

            $oneStepTemplateHtml = \RedshopTagsReplacer::_(
                'commondisplaycart',
                $oneStepTemplateHtml,
                array(
                    'usersInfoId' => $usersInfoId,
                    'shippingRateId' => $shipping_rate_id,
                    'paymentMethodId' => $payment_method_id,
                    'itemId' => $itemId,
                    'customerNote' => $customer_note,
                    'regNumber' => $req_number,
                    'thirpartyEmail' => '',
                    'customerMessage' => $customer_message,
                    'referralCode' => $referral_code,
                    'shopId' => '',
                    'data' => $post
                )
            );
        }

        $display_shippingrate = '<div id="onestepshiprate">' . $rateTemplateHtml . '</div>';
        $display_cart = '<div id="onestepdisplaycart">' . $oneStepTemplateHtml . '</div>';

        $description = $display_shippingrate . $display_cart;
        $lang = JFactory::getLanguage();
        $locale = $lang->getLocale();

        if (in_array('ru', $locale)) {
            // Commented because redshop currency symbol has been changed because of ajax response
            $description = html_entity_decode($description, ENT_QUOTES, 'KOI8-R');
        }

        $cart_total = RedshopHelperProductPrice::formattedPrice($cart['mod_cart_total']);

        echo eval("?>" . "`_`" . $description . "`_`" . $cart_total . "<?php ");
        $app->close();
    }

    /**
     * Display Credit Card
     *
     * @return void
     */
    public function displaycreditcard()
    {
        $app = JFactory::getApplication();
        $cart = JFactory::getSession()->get('cart');

        $creditcard = "";

        if ($cart['total'] > 0) {
            $paymentMethodId = $app->input->getCmd('payment_method_id');

            if ($paymentMethodId != "") {
                $creditcard = \Redshop\Payment\Helper::replaceCreditCardInformation($paymentMethodId);
            }

            $creditcard = '<div id="creditcardinfo">' . $creditcard . '</div>';
        }

        ob_clean();
        echo $creditcard;
        $app->close();
    }

    /**
     * Display payment extra field
     *
     * @return void
     */
    public function ajaxDisplayPaymentExtraField()
    {
        \Redshop\Helper\Ajax::validateAjaxRequest('get');

        $app = JFactory::getApplication();
        $plugin = RedshopHelperPayment::info($app->input->getCmd('paymentMethod'));

        $layoutFile = new JLayoutFile('order.payment.extrafields');

        // Append plugin JLayout path to improve view based on plugin if needed.
        $layoutFile->addIncludePath(JPATH_SITE . '/plugins/' . $plugin->type . '/' . $plugin->name . '/layouts');

        ob_clean();
        echo $layoutFile->render(array('plugin' => $plugin));
        $app->close();
    }

    /**
     * Display shipping extra field
     *
     * @return void
     */
    public function displayshippingextrafield()
    {
        ob_clean();
        $app = JFactory::getApplication();
        $shipping_rate_id = $app->input->post->getCmd('shipping_rate_id', '');
        $shippingmethod = RedshopHelperOrder::getShippingMethodInfo($shipping_rate_id);
        $shippingparams = new JRegistry($shippingmethod[0]->params);
        $extrafield_shipping = $shippingparams->get('extrafield_shipping', '');

        $extrafield_total = "";
        $extrafield_hidden = '';

        if (count($extrafield_shipping) > 0) {
            for ($ui = 0, $countExtrafield = count($extrafield_shipping); $ui < $countExtrafield; $ui++) {
                if ($extrafield_shipping[$ui] != "") {
                    $productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($extrafield_shipping[$ui], 19, '', 0, 0, 0);
                    $extrafield_total .= $productUserFields[0] . " " . $productUserFields[1] . "<br>";
                    $extrafield_hidden .= "<input type='hidden' name='extrafields[]' value='" . $extrafield_shipping[$ui] . "'>";
                }
            }

            echo $extrafield_total . $extrafield_hidden;
            $app->close();
        }
    }

    /**
     * Display payment method
     *
     * @return  void
     *
     * @throws  Exception
     */
    public function ajaxDisplayPaymentAnonymous()
    {
        $app = JFactory::getApplication();
        $post = $app->input->post->getArray();
        $cart = \Redshop\Cart\Helper::getCart();

        $isCompany = $post['is_company'];
        $eanNumber = $post['eanNumber'];
        $paymentMethods = RedshopHelperUtility::getPlugins('redshop_payment');
        $selectedPaymentMethodId = 0;

        if (count($paymentMethods) > 0) {
            $productId = $cart[0]['product_id'];

            if (!empty(RedshopHelperPayment::getPaymentByIdProduct($productId)[0])) {
                $selectedPaymentMethodId = RedshopHelperPayment::getPaymentByIdProduct($productId)[0];
            } else {
                foreach ($paymentMethods as $paymentMethod) {
                    if ($paymentMethod->enabled == 1) {
                        $selectedPaymentMethodId = $paymentMethod->element;
                        break;
                    }
                }
            }
        }

        $templates = RedshopHelperTemplate::getTemplate("redshop_payment");
        $templateHtml = !empty($templates) ? $templates[0]->template_desc : '';

        echo RedshopTagsReplacer::_(
            'paymentmethod',
            $templateHtml,
            array(
                'paymentMethodId' => $selectedPaymentMethodId,
                'isCompany' => $isCompany,
                'eanNumber' => $eanNumber
            )
        );

        $app->close();
    }
}
