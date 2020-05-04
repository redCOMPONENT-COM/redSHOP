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
class RedshopTagsSectionsOrderReceipt extends RedshopTagsAbstract
{
    /**
     * @var    array
     *
     * @since 3.0
     */
    public $tags = array(
        '{print}',
        '{product_name_lbl}',
        '{price_lbl}',
        '{quantity_lbl}',
        '{total_price_lbl}',
        '{customer_note_lbl}',
        '{txtextra_info}'
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
        $order   = $this->data['order'];
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
        $this->addReplace('{product_name_lbl}', JText::_('COM_REDSHOP_PRODUCT_NAME_LBL'));
        $this->addReplace('{price_lbl}', JText::_('COM_REDSHOP_PRICE_LBL'));
        $this->addReplace('{quantity_lbl}', JText::_('COM_REDSHOP_QUANTITY_LBL'));
        $this->addReplace('{total_price_lbl}', JText::_('COM_REDSHOP_TOTAL_PRICE_LBL'));
        $this->addReplace('{customer_note_lbl}', JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL'));

        $this->template = Redshop\Order\Template::replaceTemplate($order, $this->template);

        // Added new tag
        /**
         * The Tag {txtextra_info} to display some extra information about payment method ( Only For display purpose ).
         *
         * Output is fatched from Payment Gateway Plugin Parameter 'txtextra_info'
         */

        $paymentMethodClass = null;
        $payment            = RedshopEntityOrder::getInstance($orderId)->getPayment();

        if (!empty($payment)) {
            $orderPayment       = $payment->getItem();
            $paymentMethodClass = $orderPayment->payment_method_class;
        }

        JLoader::import('joomla.plugin.helper');
        $plugin = JPluginHelper::getPlugin('redshop_payment', $paymentMethodClass);

        $params       = new JRegistry($plugin->params);
        $txtExtraInfo = $params->get('txtextra_info');
        $this->addReplace('{txtextra_info}', $txtExtraInfo);

        // End

        $this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

        /**
         *
         * trigger content plugin
         */
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $o          = new stdClass;
        $o->text    = $this->template;
        JPluginHelper::importPlugin('content');
        $x = array();
        $dispatcher->trigger('onPrepareContent', array(&$o, &$x, 0));
        $this->template = $o->text;

        $dispatcher->trigger('onRenderReceipt', array(&$this->template, $orderId));

        return parent::replace();
    }
}
