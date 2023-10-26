<?php

/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Class Redshop Helper for Cart - Tag replacer
 *
 * @since  2.0.7
 */
class RedshopHelperCartTag
{
    /**
     * replace Conditional tag from Redshop tax
     *
     * @param   string  $template       Template
     * @param   int     $amount         Amount
     * @param   int     $discount       Discount
     * @param   int     $check          Check
     * @param   int     $quotationMode  Quotation mode
     *
     * @return  string
     * @since   2.0.7
     */
    public static function replaceTax($template = '', $amount = 0, $discount = 0, $check = 0, $quotationMode = 0)
    {
        if (!self::isBlockTagExists($template, '{if vat}', '{vat end if}')) {
            return $template;
        }

        $cart = \Redshop\Cart\Helper::getCart();

        if ($amount <= 0) {
            $templateVatSdata = explode('{if vat}', $template);
            $templateVatEdata = explode('{vat end if}', $templateVatSdata[1]);
            $template         = $templateVatSdata[0] . $templateVatEdata[1];

            return $template;
        }

        if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')) {
            $template = str_replace("{tax}", "", $template);
            $template = str_replace("{order_tax}", "", $template);
        } else {
            $template = str_replace("{tax}", RedshopHelperProductPrice::formattedPrice($amount, true), $template);
            $template = str_replace("{order_tax}", RedshopHelperProductPrice::formattedPrice($amount, true), $template);
        }

        if (strpos($template, '{tax_after_discount}') !== false) {
            if (
                Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') && (float) Redshop::getConfig()->get(
                    'VAT_RATE_AFTER_DISCOUNT'
                )
            ) {
                if ($check) {
                    $taxAfterDiscount = $discount;
                } else {
                    if (!isset($cart['tax_after_discount'])) {
                        $taxAfterDiscount = RedshopHelperCart::calculateTaxAfterDiscount($amount, $discount);
                    } else {
                        $taxAfterDiscount = $cart['tax_after_discount'];
                    }
                }

                if ($taxAfterDiscount > 0) {
                    $template = str_replace(
                        "{tax_after_discount}",
                        RedshopHelperProductPrice::formattedPrice($taxAfterDiscount),
                        $template
                    );
                } else {
                    $template = str_replace(
                        "{tax_after_discount}",
                        RedshopHelperProductPrice::formattedPrice($cart['tax']),
                        $template
                    );
                }
            } else {
                $template = str_replace(
                    "{tax_after_discount}",
                    RedshopHelperProductPrice::formattedPrice($cart['tax']),
                    $template
                );
            }
        }

        $template = str_replace("{vat_lbl}", Text::_('COM_REDSHOP_CHECKOUT_VAT_LBL'), $template);
        $template = str_replace("{if vat}", '', $template);
        $template = str_replace("{vat end if}", '', $template);

        return $template;
    }

    /**
     * @param   string  $template  Template
     * @param   string  $beginTag  Begin tag
     * @param   string  $closeTag  Close tag
     *
     * @return  boolean
     *
     * @since   2.0.7
     */
    public static function isBlockTagExists($template, $beginTag, $closeTag)
    {
        return (strpos($template, $beginTag) !== false && strpos($template, $closeTag) !== false);
    }

    /**
     * @param   string  $template       Template
     * @param   int     $discount       Discount
     * @param   int     $subTotal       Subtotal
     * @param   int     $quotationMode  Quotation mode
     *
     * @return  string
     *
     * @since   2.0.7
     */
    public static function replaceDiscount($template = '', $discount = 0, $subTotal = 0, $quotationMode = 0)
    {
        if (!self::isBlockTagExists($template, '{if discount}', '{discount end if}')) {
            return $template;
        }

        $percentage = '';

        if ($discount <= 0) {
            $templateDiscountSdata = explode('{if discount}', $template);
            $templateDiscountEdata = explode('{discount end if}', $templateDiscountSdata[1]);
            $template              = $templateDiscountSdata[0] . $templateDiscountEdata[1];
        } else {
            $template = str_replace("{if discount}", '', $template);

            if ($quotationMode && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')) {
                $template = str_replace("{discount}", "", $template);
                $template = str_replace("{discount_in_percentage}", $percentage, $template);
            } else {
                $template = str_replace(
                    "{discount}",
                    RedshopHelperProductPrice::formattedPrice($discount, true),
                    $template
                );
                $template = str_replace(
                    "{order_discount}",
                    RedshopHelperProductPrice::formattedPrice($discount, true),
                    $template
                );

                if (!empty($subTotal) && $subTotal > 0) {
                    $percentage = round(($discount * 100 / $subTotal), 2) . " %";
                }

                $template = str_replace("{discount_in_percentage}", $percentage, $template);
            }

            $template = str_replace("{discount_lbl}", Text::_('COM_REDSHOP_CHECKOUT_DISCOUNT_LBL'), $template);
            $template = str_replace("{discount end if}", '', $template);
        }

        return $template;
    }

    /**
     * @param   string  $template       Template
     * @param   object  $order          Order data
     * @param   int     $quotationMode  Quotation mode
     *
     * @return  string
     *
     * @since   2.0.7
     */
    public static function replaceSpecialDiscount($template, $order, $quotationMode = 0)
    {
        if (strstr($template, '{if special_discount}') && strstr($template, '{special_discount end if}')) {
            $percentage = '';

            if ($order->special_discount_amount <= 0) {
                $template_discount_sdata = explode('{if special_discount}', $template);
                $template_discount_edata = explode('{special_discount end if}', $template_discount_sdata[1]);
                $template                = $template_discount_sdata[0] . $template_discount_edata[1];
            } else {
                $template = str_replace("{if special_discount}", '', $template);

                if ($quotationMode && !Redshop::getConfig()->getBool('SHOW_QUOTATION_PRICE')) {
                    $template = str_replace("{special_discount}", "", $template);
                    $template = str_replace("{special_discount_amount}", $order->special_discount, $template);
                } else {
                    $discount = $order->special_discount_amount;

                    $template = str_replace(
                        "{special_discount_amount}",
                        RedshopHelperProductPrice::formattedPrice($discount, true),
                        $template
                    );

                    $template = str_replace("{special_discount}", $order->special_discount . '%', $template);
                }

                $template = str_replace("{special_discount_lbl}", Text::_('COM_REDSHOP_SPECIAL_DISCOUNT'), $template);
                $template = str_replace("{special_discount end if}", '', $template);
            }
        }

        return $template;
    }
}
