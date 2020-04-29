<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  2.1.5
 */
class RedshopTagsSectionsOrderDetail extends RedshopTagsAbstract
{
    /**
     * @var    array
     *
     * @since 3.0
     */
    public $tags = array(
        '{print}',
        '{discount_type_lbl}',
        '{discount_type}',
        '{order_status}',
        '{order_status_order_only}',
        '{order_status_payment_only}'
    );

    /**
     * Init function
     * @return mixed|void
     *
     * @throws Exception
     * @since 3.0
     */
    public function init()
    {
    }

    /**
     * Executing replace
     * @return string
     *
     * @throws Exception
     * @since 3.0
     */
    public function replace()
    {
        $ordersDetail = $this->data['ordersDetail'];

        $input   = JFactory::getApplication()->input;
        $orderId = $input->getInt('oid');
        $print   = $input->getInt('print');
        $url     = JURI::base();

        if ($print) {
            $onclick = 'onclick="window.print();"';
        } else {
            $printUrl = $url . 'index.php?option=com_redshop&task=order_detail.printPDF&oid=' . $orderId;
            $onclick  = 'onclick=window.open("' . $printUrl . '","mywindow","scrollbars=1","location=1")';
        }

        $printTag = RedshopLayoutHelper::render(
            'tags.common.img_link',
            array(
                'link'     => 'javascript:void(0)',
                'linkAttr' => $onclick . ' title="' . JText::_('COM_REDSHOP_PRINT_LBL') . '"',
                'src'      => JSYSTEM_IMAGES_PATH . 'printButton.png',
                'alt'      => JText::_('COM_REDSHOP_PRINT_LBL'),
                'imgAttr'  => 'title="' . JText::_('COM_REDSHOP_PRINT_LBL') . '"'
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->addReplace('{print}', $printTag);

        $brTag = RedshopLayoutHelper::render(
            'tags.common.short_tag',
            array(
                'tag' => 'br'
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $arrDiscount  = explode('@', $ordersDetail->discount_type);
        $discountType = '';
        $d            = 0;

        for ($dn = count($arrDiscount); $d < $dn; $d++) {
            if ($arrDiscount[$d]) {
                $arrDiscountType = explode(':', $arrDiscount[$d]);

                if ($arrDiscountType[0] == 'c') {
                    $discountType .= JText::_('COM_REDSHOP_COUPEN_CODE') . ' : ' . $arrDiscountType[1] . $brTag;
                }

                if ($arrDiscountType[0] == 'v') {
                    $discountType .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arrDiscountType[1] . $brTag;
                }
            }
        }

        $this->replacements['{discount_type_lbl}'] = JText::_('COM_REDSHOP_CART_DISCOUNT_CODE_TBL');

        if ($discountType) {
            $this->replacements['{discount_type}'] = $discountType;
        } else {
            $this->replacements['{discount_type}'] = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
        }

        $statusText = RedshopHelperOrder::getOrderStatusTitle($ordersDetail->order_status);

        if (trim($ordersDetail->order_payment_status) == 'Paid') {
            $orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_PAID');
        } elseif (trim($ordersDetail->order_payment_status) == 'Unpaid') {
            $orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID');
        } elseif (trim($ordersDetail->order_payment_status) == 'Partial Paid') {
            $orderPaymentStatus = JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID');
        } else {
            $orderPaymentStatus = $ordersDetail->order_payment_status;
        }

        $this->replacements['{order_status}'] = $statusText . " - " . $orderPaymentStatus;

        if ($this->isTagExists('{order_status_order_only}')) {
            $this->replacements['{order_status_order_only}'] = $statusText;
        }

        if ($this->isTagExists('{order_status_payment_only}')) {
            $this->replacements['{order_status_payment_only}'] = $orderPaymentStatus;
        }

        $this->template = $this->strReplace($this->replacements, $this->template);

        return parent::replace();
    }
}
