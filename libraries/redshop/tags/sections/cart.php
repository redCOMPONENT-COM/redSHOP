<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

use Redshop\Traits\Replace;

/**
 * Tags replacer abstract class
 *
 * @since  3.0
 */
class RedshopTagsSectionsCart extends RedshopTagsAbstract
{
    use Replace\Template, Replace\Discount, Replace\ConditionTag, Replace\Tax, Replace\TermsConditions,
        Replace\NewsletterSubscription, Replace\CartItem;

    /**
     * @var    array
     *
     * @since   3.0
     */
    public $tags = array(
        '{with_vat}',
        '{without_vat}',
        '{coupon_code_lbl}',
        '{discount_form}',
        '{discount_form_lbl}',
        '{discount_rule}',
        '{empty_cart}',
        '{update}',
        '{shop_more}',
        '{quotation_request}',
        '{checkout_button}',
        '{checkout}',
        '{shipping_calculator_label}',
        '{shipping_calculator}',
        '{print}'
    );

    /**
     * Init
     *
     * @return  mixed
     *
     * @since   3.0
     */
    public function init()
    {
    }

    /**
     * Execute replace
     *
     * @return  string
     *
     * @since   3.0
     */
    public function replace()
    {
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $url        = JUri::base();
        $cart       = $this->data['cart'];

        $idx          = $cart['idx'];
        $itemId       = RedshopHelperRouter::getCheckoutItemId();
        $optionLayout = RedshopLayoutHelper::$layoutOption;
        // Process the product plugin before cart template replace tag
        JPluginHelper::importPlugin('redshop_product');
        $results = $dispatcher->trigger('onStartCartTemplateReplace', array(&$this->template, &$cart));
        $print   = JFactory::getApplication()->input->getInt('print');

        if ($print) {
            $onClick = "onclick='window.print();'";
        } else {
            $printUrl = $url . "index.php?option=com_redshop&view=cart&print=1&tmpl=component&Itemid=" . $itemId;
            $onClick  = "onclick='window.open(\"$printUrl\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
        }

        $printTag = RedshopLayoutHelper::render(
            'tags.common.print',
            array(
                'onClick' => $onClick
            ),
            '',
            $optionLayout
        );

        $this->addReplace('{print}', $printTag);
        $this->template = $this->replaceTemplate($cart, $this->template, 0);
        \Redshop\Cart\Helper::setCart($cart);

        if ($this->isTagExists('{shipping_calculator}') && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) {
            if (Redshop::getConfig()->get('SHOW_SHIPPING_IN_CART')) {
                $shippingCalc = $this->shippingRateCalc();
                $this->addReplace('{shipping_calculator}', $shippingCalc);
                $this->addReplace('{shipping_calculator_label}', JText::_('COM_REDSHOP_SHIPPING_CALCULATOR'));
            } else {
                $this->addReplace('{shipping_calculator}', '');
                $this->addReplace('{shipping_calculator_label}', '');
            }
        }

        if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) {
            $checkout = '';
        } else {
            JPluginHelper::importPlugin('redshop_payment');
            $dispatcher   = RedshopHelperUtility::getDispatcher();
            $pluginButton = $dispatcher->trigger('onPaymentCheckoutButton', array($cart));
            $pluginButton = implode("<br>", $pluginButton);

            if (Redshop::getConfig()->get('SSL_ENABLE_IN_CHECKOUT')) {
                $uri   = JURI::getInstance();
                $cLink = new JURI;
                $cLink->setScheme('https');
                $cLink->setHost($uri->getHost());

                $cLink->setPath(Redshop\IO\Route::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId));
                $link = $cLink->toString();
            } else {
                $link = Redshop\IO\Route::_('index.php?option=com_redshop&view=checkout&Itemid=' . $itemId);
            }

            $checkout = RedshopLayoutHelper::render(
                'tags.cart.checkout',
                array(
                    'pluginButton' => $pluginButton,
                    'link'         => $link,
                    'cart'         => $cart
                ),
                '',
                $optionLayout
            );
        }

        $this->addReplace('{checkout}', $checkout);
        $this->addReplace('{checkout_button}', $checkout);

        $qlink            = $url . 'index.php?option=com_redshop&view=quotation&tmpl=component&return=1&Itemid=' . $itemId;
        $quotationRequest = RedshopLayoutHelper::render(
            'tags.checkout.quotation_request',
            array(
                'link' => $qlink,
            ),
            '',
            $optionLayout
        );

        $this->addReplace('{quotation_request}', $quotationRequest);
        /*
         * continue redirection link
         */
        if (strstr($this->template, "{shop_more}")) {
            if (Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK') != '') {
                $shopMoreLink = Redshop\IO\Route::_(Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK'));
            } elseif ($catItemId = RedshopHelperRouter::getCategoryItemid()) {
                $shopMoreLink = Redshop\IO\Route::_('index.php?option=com_redshop&view=category&Itemid=' . $catItemId);
            } else {
                $shopMoreLink = Redshop\IO\Route::_('index.php');
            }

            $shopMore = RedshopLayoutHelper::render(
                'tags.common.button',
                array(
                    'class' => 'blackbutton btn',
                    'text'  => JText::_('COM_REDSHOP_SHOP_MORE'),
                    'attr'  => 'onclick="javascript:document.location=\'' . $shopMoreLink . '\'" type="button"'
                ),
                '',
                $optionLayout
            );

            $this->addReplace('{shop_more}', $shopMore);
        }

        if (Redshop::getConfig()->getBool('QUANTITY_TEXT_DISPLAY')) {
            $updateAll = RedshopLayoutHelper::render(
                'tags.cart.update',
                array(
                    'itemId' => $itemId,
                    'idx'    => $idx
                ),
                '',
                $optionLayout
            );

            $this->addReplace('{update}', $updateAll);
        } else {
            $this->addReplace('{update}', '');
        }


        $emptyCart = RedshopLayoutHelper::render(
            'tags.cart.empty',
            array(),
            '',
            $optionLayout
        );

        $this->addReplace('{empty_cart}', $emptyCart);

        $discount = RedshopHelperDiscount::getDiscount($cart ['product_subtotal']);

        if (is_object($discount)) {
            if (isset($discount->discount_type) && $discount->discount_type == 0) {
                $discountAmount = $discount->discount_amount;
                $discountSign   = " " . Redshop::getConfig()->get('REDCURRENCY_SYMBOL');
            } else {
                $discountAmount = ($discount->amount * $discount->discount_amount) / (100);
                $discountSign   = " %";
            }

            $price = number_format(
                $discount->discount_amount,
                Redshop::getConfig()->get('PRICE_DECIMAL'),
                Redshop::getConfig()->get('PRICE_SEPERATOR'),
                Redshop::getConfig()->get('THOUSAND_SEPERATOR')
            );

            $conditionText = '';

            switch ($discount->condition) {
                case 1:
                    $conditionText = JText::_('COM_REDSHOP_LOWER');
                    break;
                case 2:
                    $conditionText = JText::_('COM_REDSHOP_EQUAL');
                    break;
                case 3:
                    $conditionText =  JText::_('COM_REDSHOP_HIGHER');
                    break;
                };

            $text = sprintf(
                JText::_('COM_REDSHOP_DISCOUNT_TEXT'),
                RedshopHelperProductPrice::formattedPrice($discountAmount, true),
                $price . $discountSign,
                $conditionText ,
                RedshopHelperProductPrice::formattedPrice($discount->amount, true)
            );

            /*
              *  Discount type =  1 // Discount/coupon/voucher
              *  Discount type =  2 // Discount + coupon/voucher
              *  Discount type =  3 // Discount + coupon + voucher
              *  Discount type =  4 // Discount + coupons + voucher
              */
            if (Redshop::getConfig()->get('DISCOUNT_TYPE') && Redshop::getConfig()->get('DISCOUNT_ENABLE') == 1) {
                $this->addReplace('{discount_rule}', $text);
            } else {
                $this->addReplace('{discount_rule}', '');
            }
        } else {
            $this->addReplace('{discount_rule}', '');
        }

        $couponLable  = '';
        $discountForm = '';

        if (Redshop::getConfig()->get('DISCOUNT_TYPE') != "0" || Redshop::getConfig()->get('DISCOUNT_TYPE') != "") {
            $discountForm = RedshopLayoutHelper::render(
                'tags.cart.discount_form',
                array('item' => $itemId),
                '',
                $optionLayout
            );

            if (Redshop::getConfig()->get('VOUCHERS_ENABLE') == 1 || Redshop::getConfig()->get('COUPONS_ENABLE') == 1) {
                $couponLable = RedshopLayoutHelper::render(
                    'tags.common.tag',
                    array(
                        'tag'   => 'div',
                        'class' => 'coupon_label',
                        'id'    => 'coupon_label',
                        'text'  => JText::_('COM_REDSHOP_CART_COUPON_CODE_TBL')
                    ),
                    '',
                    $optionLayout
                );
            }
        }

        $this->addReplace('{discount_form_lbl}', '');
        $this->addReplace('{discount_form}', $discountForm);
        $this->addReplace('{coupon_code_lbl}', $couponLable);
        $this->addReplace('{without_vat}', '');
        $this->addReplace('{with_vat}', '');

        // Process the product plugin for cart item
        JPluginHelper::importPlugin('redshop_product');
        $results = $dispatcher->trigger('atEndCartTemplateReplace', array(&$this->template, $cart));

        $this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

        return parent::replace();
    }

    /**
     * shipping rate calculator
     *
     * @return   string
     *
     * @since    3.0
     */
    public function shippingRateCalc()
    {
        JHtml::_('redshopjquery.framework');
        /** @scrutinizer ignore-deprecated */
        JHtml::script('com_redshop/redshop.common.min.js', false, true);

        $countries            = RedshopHelperWorld::getCountryList();
        $post['country_code'] = $countries['country_code'];
        $country              = $countries['country_dropdown'];

        $states = RedshopHelperWorld::getStateList($post);
        $state  = $states['state_dropdown'];

        return RedshopLayoutHelper::render(
            'tags.cart.shipping_calculator',
            array(
                'country' => $country,
                'state'   => $state
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );
    }
}