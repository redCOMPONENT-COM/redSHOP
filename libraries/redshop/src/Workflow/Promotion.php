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
 * Promotion Workflow
 *
 * @since  __DEPLOY_VERION__
 */
class Promotion
{
    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function applyCoupon()
    {
        $itemId   = \RedshopHelperRouter::getCartItemId();
        $app      = \JFactory::getApplication();
        $ajax     = $app->input->getInt('ajax', 0);
        $language = \JFactory::getLanguage()->getTag();

        // Call coupon method of model to apply coupon
        $valid = \RedshopHelperCartDiscount::applyCoupon();;

        $cart = \Redshop\Cart\Helper::getCart();
        $cart = \RedshopHelperDiscount::modifyDiscount($cart);
        \RedshopHelperCart::renderModuleCartHtml();

        // Store cart entry in db
        \RedshopHelperCart::addCartToDatabase();

        $message     = '';
        $messageType = '';

        // If coupon code is valid than apply to cart else raise error
        if ($valid) {
            $link = \JRoute::_(
                'index.php?option=com_redshop&view=cart&lang=' . $language . '&Itemid=' . $itemId,
                false
            );

            $isProductDiscounted = 0;
            $discountType = \Redshop::getConfig()->getInt('DISCOUNT_TYPE');

            if ($discountType == 1) {
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

                $app->enqueueMessage($message, $messageType);
            }

            if (\Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT') != 1) {
                $message     = \JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE');
                $messageType = 'warning';
            } else {
                $message = \JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID');
                $messageType = 'success';

                $app->enqueueMessage($messageType, $messageType);
                $app->redirect($link);
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
        }

        $app->enqueueMessage($message, $messageType);
        $app->redirect($link);
    }

    /**
     * @throws \Exception
     * @since  __DEPLOY_VERSION__
     */
    public static function applyVoucher() {
        $app      = \Joomla\CMS\Factory::getApplication();
        $itemId   = \RedshopHelperRouter::getCartItemId();
        $language = \Joomla\CMS\Factory::getLanguage()->getTag();

        // Call voucher method of model to apply voucher to cart if f voucher code is valid than apply to cart else raise error
        if (\RedshopHelperCartDiscount::applyVoucher()) {
            $cart = \Redshop\Cart\Helper::getCart();
            \RedshopHelperCart::modifyCalculation($cart);
            \Redshop\Cart\Ajax::renderModuleCartHtml(false);

            $link = \JRoute::_(
                'index.php?option=com_redshop&view=cart&seldiscount=voucher&lang=' . $language . '&Itemid=' . $itemId,
                false
            );
            $message     = '';
            $messageType = '';

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
                $app->enqueueMessage(\JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE'),
                    'warning');
                $app->redirect($link);
            } else {
                $app->enqueueMessage(\JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID', 'success'));
                $app->redirect($link);
            }
        } else {
            $msg  = \JText::_('COM_REDSHOP_VOUCHER_CODE_IS_NOT_VALID');
            $link = \JRoute::_(
                'index.php?option=com_redshop&view=cart&msg=' . $msg . '&seldiscount=voucher&lang=' . $language
                . '&Itemid=' . $itemId,
                false
            );

            $app->enqueueMessage($msg, 'error');
            $app->redirect($link);
        }
    }

    /**
     * @return array
     * @since  __DEPLOY_VERSION__
     */
    public static function apply(&$cart) {
        return \Redshop\Plugin\Helper::invoke('redshop_promotion', null, 'onApply', [&$cart]);
    }
}