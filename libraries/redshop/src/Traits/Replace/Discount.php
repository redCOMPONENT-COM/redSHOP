<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Traits\Replace;

defined('_JEXEC') || die;

/**
 * For classes extends class RedshopTagsAbstract
 *
 * @since  3.0
 */
trait Discount
{
    /**
     * @param   string  $template       Template
     * @param   int     $discount       Discount
     * @param   int     $subTotal       Subtotal
     * @param   int     $quotationMode  Quotation mode
     *
     * @return  string
     *
     * @since   3.0
     */
    public function replaceDiscount($template = '', $discount = 0, $subTotal = 0, $quotationMode = 0)
    {
        if (!\RedshopHelperCartTag::isBlockTagExists($template, '{if discount}', '{discount end if}')) {
            return $template;
        }

        $percentage = '';

        if ($discount <= 0) {
            $templateData = $this->getTemplateBetweenLoop('{if discount}', '{discount end if}', $template);
            $template     = $templateData['begin'] . $templateData['end'];
        } else {
            $replacement                  = [];
            $replacement['{if discount}'] = '';

            if ($quotationMode && !\Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')) {
                $replacement['{discount}']               = '';
                $replacement['{discount_in_percentage}'] = $percentage;
            } else {
                $replacement['{discount}']       = \RedshopHelperProductPrice::formattedPrice($discount, true);
                $replacement['{order_discount}'] = \RedshopHelperProductPrice::formattedPrice($discount, true);

                if (!empty($subTotal) && $subTotal > 0) {
                    $percentage = round(($discount * 100 / $subTotal), 2) . " %";
                }

                $replacement['{discount_in_percentage}'] = $percentage;
            }

            $replacement['{discount_lbl}']    = \JText::_('COM_REDSHOP_CHECKOUT_DISCOUNT_LBL');
            $replacement['{discount end if}'] = '';

            $template = $this->strReplace($replacement, $template);
        }

        return $template;
    }
}