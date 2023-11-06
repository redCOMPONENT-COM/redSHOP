<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Order detail view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewOrder_Detail extends RedshopViewAdmin
{
    /**
     * The request url.
     *
     * @var  string
     */
    public $request_url;

    /**
     * Do we have to display a sidebar ?
     *
     * @var  boolean
     */
    protected $displaySidebar = false;

    /**
     * Display the view.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed         A string if successful, otherwise an Error object.
     * @throws  Exception
     */
    public function display($tpl = null)
    {
        $document = JFactory::getDocument();
        $input    = JFactory::getApplication()->input;
        $document->setTitle(Text::_('COM_REDSHOP_ORDER'));

        $uri = \Joomla\CMS\Uri\Uri::getInstance();

        // Load payment languages
        RedshopHelperPayment::loadLanguages();

        // Load Shipping plugin language files
        RedshopHelperShipping::loadLanguages();

        $layout = $input->getCmd('layout', '');

        HTMLHelper::script('com_redshop/redshop.order.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.admin.common.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.validation.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/json.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/ajaxupload.min.js', ['relative' => true]);

        $lists = array();

        $model = $this->getModel();

        $detail = $this->get('data');

        $billing  = RedshopEntityOrder::getInstance($detail->order_id)->getBilling()->getItem();
        $shipping = RedshopEntityOrder::getInstance($detail->order_id)->getShipping()->getItem();

        $task = $input->getCmd('task', '');

        if ($task == 'ccdetail') {
            $ccdetail       = $model->getccdetail($detail->order_id);
            $this->ccdetail = $ccdetail;
            $this->setLayout('ccdetail');

            parent::display($tpl);
            JFactory::getApplication()->close();
        }

        if ($layout == 'shipping' || $layout == 'billing') {
            if (!$shipping || $layout == 'billing') {
                $shipping = $billing;
            }

            $this->setLayout($layout);

            $countryarray           = RedshopHelperWorld::getCountryList((array) $shipping);
            $shipping->country_code = $countryarray['country_code'];
            $lists['country_code']  = $countryarray['country_dropdown'];

            $statearray          = RedshopHelperWorld::getStateList((array) $shipping);
            $lists['state_code'] = $statearray['state_dropdown'];

            $showcountry = (count($countryarray['countrylist']) == 1 && count($statearray['statelist']) == 0) ? 0 : 1;
            $showstate   = ($statearray['is_states'] <= 0) ? 0 : 1;

            $isCompany           = array();
            $isCompany[0]        = new stdClass;
            $isCompany[0]->value = 0;
            $isCompany[0]->text  = Text::_('COM_REDSHOP_USER_CUSTOMER');
            $isCompany[1]        = new stdClass;
            $isCompany[1]->value = 1;
            $isCompany[1]->text  = Text::_('COM_REDSHOP_USER_COMPANY');
            $lists['is_company'] = JHTML::_(
                'select.genericlist',
                $isCompany,
                'is_company',
                'class="inputbox" onchange="showOfflineCompanyOrCustomer(this.value);" ',
                'value',
                'text',
                $billing->is_company
            );

            $lists['tax_exempt']            = JHTML::_(
                'select.booleanlist',
                'tax_exempt',
                'class="inputbox"',
                $billing->tax_exempt
            );
            $lists['tax_exempt_approved']   = JHTML::_(
                'select.booleanlist',
                'tax_exempt_approved',
                'class="inputbox"',
                $billing->tax_exempt_approved
            );
            $lists['requesting_tax_exempt'] = JHTML::_(
                'select.booleanlist',
                'requesting_tax_exempt',
                'class="inputbox"',
                $billing->requesting_tax_exempt
            );

            $this->showcountry = $showcountry;
            $this->showstate   = $showstate;
        } elseif ($layout == "print_order" || $layout == 'productorderinfo' || $layout == 'creditcardpayment') {
            $this->setLayout($layout);
        } else {
            $this->setLayout('default');
        }

        $payment_detail = RedshopHelperOrder::getPaymentInfo($detail->order_id);

        if (is_array($payment_detail) && count($payment_detail)) {
            $payment_detail = $payment_detail[0];
        }

        $isNew = ($detail->order_id < 1);

        $text = $isNew ? Text::_('COM_REDSHOP_NEW') : Text::_('COM_REDSHOP_EDIT');
        JToolBarHelper::title(
            Text::_('COM_REDSHOP_ORDER') . ': <small><small>[ ' . $text . ' ]</small></small>',
            'pencil-2 redshop_order48'
        );

        $toolbar  = JToolbar::getInstance();
        $order_id = $detail->order_id;

        if (RedshopHelperPdf::isAvailablePdfPlugins()) {
            $toolbar->linkButton('', 'COM_REDSHOP_CREATE_STOCKNOTE')
                ->icon('far fa-file-pdf')
                ->url(JRoute::_('index.php?option=com_redshop&view=order_detail&task=createpdfstocknote&cid[]=' . $order_id));

            $toolbar->linkButton('', 'COM_REDSHOP_CREATE_SHIPPING_LABEL')
                ->icon('far fa-file-pdf')
                ->url(JRoute::_('index.php?option=com_redshop&view=order_detail&task=createpdf&cid[]=' . $order_id));
        }

        $tmpl       = JFactory::getApplication()->input->get('tmpl', '');
        $appendTmpl = ($tmpl) ? '&tmpl=component' : '';

        $toolbar->linkButton('', 'COM_REDSHOP_SEND_DOWNLOEADMAIL')
            ->icon('far fa-envelope')
            ->url(JRoute::_('index.php?option=com_redshop&view=order_detail&task=send_downloadmail&cid[]=' . $order_id . $appendTmpl));

        $toolbar->linkButton('', 'COM_REDSHOP_RESEND_ORDER_MAIL')
            ->icon('far fa-envelope')
            ->url(JRoute::_('index.php?option=com_redshop&view=order_detail&task=resendOrderMail&orderid=' . $order_id . $appendTmpl));

        $toolbar->linkButton('', 'COM_REDSHOP_SEND_INVOICEMAIL')
            ->icon('far fa-envelope')
            ->url(JRoute::_('index.php?option=com_redshop&view=order_detail&task=send_invoicemail&cid[]=' . $order_id . $appendTmpl));

        if (
            isset($payment_detail->plugin->params) && $payment_detail->plugin->params->get('enableVault')
            && ('P' == $detail->order_status || 'Unpaid' == $detail->order_payment_status)
        ) {
            $toolbar->linkButton('', 'COM_REDSHOP_ORDER_PAY')
                ->icon('far fa-envelope')
                ->url(JRoute::_('index.php?option=com_redshop&view=order_detail&task=pay&orderId=' . $order_id . $appendTmpl));
        }

        if ($tmpl) {
            $toolbar->linkButton('back', 'COM_REDSHOP_BACK')
                ->icon('far fa-envelope')
                ->url(JRoute::_('index.php?option=com_redshop&view=order_detail&task=send_invoicemail&cid[]=' . $order_id . $appendTmpl));
        }

        $toolbar->linkButton('', 'COM_REDSHOP_PRINT')
            ->icon('fas fa-print')
            ->attributes(['target' => '_blank'])
            ->url(JRoute::_('index.php?tmpl=component&option=com_redshop&view=order_detail&layout=print_order&cid[]=' . $order_id));

        JToolBarHelper::cancel('cancel', Text::_('JTOOLBAR_CLOSE'));

        $lists['order_extra_fields'] = RedshopHelperExtrafields::listAllField(
            RedshopHelperExtrafields::SECTION_ORDER,
            $order_id
        );

        $this->lists            = $lists;
        $this->detail           = $detail;
        $this->billing          = $billing;
        $this->shipping         = $shipping;
        $this->payment_detail   = $payment_detail;
        $this->shipping_rate_id = $detail->ship_method_id;
        $this->request_url      = $uri->toString();

        parent::display($tpl);
    }
}