<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');

class template_detailVIEWtemplate_detail extends JView
{
    function display ($tpl = null)
    {
        $mainframe = JFactory::getApplication();

        JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES_MANAGEMET'), 'redshop_templates48');

        $uri = JFactory::getURI();

        jimport('joomla.html.pane');
        $pane = JPane::getInstance('sliders');
        $this->assignRef('pane', $pane);

        $model       = $this->getModel('template_detail');
        $user        = JFactory::getUser();
        $redtemplate = new Redtemplate();

        // 	fail if checked out not by 'me'
        if ($model->isCheckedOut($user->get('id'))) {
            $msg = JText::sprintf('DESCBEINGEDITTED', JText::_('COM_REDSHOP_THE_DETAIL'), $detail->title);
            $mainframe->redirect('index.php?option=com_redshop&view=template', $msg);
        }

        $this->setLayout('default');

        $lists = array();

        $detail = $this->get('data');

        $isNew = ($detail->template_id < 1);

        $text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_templates48');

        JToolBarHelper::apply();

        JToolBarHelper::save();

        if ($isNew) {
            JToolBarHelper::cancel();
        } else {

            //EDIT - check out the item
            $model->checkout($user->get('id'));

            JToolBarHelper::cancel('cancel', 'Close');
        }
        // TEMPLATE MOVE DB TO FILE
        $post = JRequest::get('post');
        if ($isNew && (isset($post['template_name']) && $post['template_name'] != "")) {
            $detail->template_name    = $post['template_name'];
            $detail->template_section = $post['template_section'];
            $template_desc            = JRequest::getVar('template_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $detail->template_desc    = $template_desc;
            $detail->published        = $post['published'];
            $detail->msg              = JText ::_('PLEASE_CHANGE_FILE_NAME_IT_IS_ALREADY_EXISTS');
        }
        // TEMPLATE MOVE DB TO FILE END

        // Section can be added from here
        $optionsection    = $redtemplate->getTemplateSections();
        $lists['section'] = JHTML::_('select.genericlist', $optionsection, 'template_section', 'class="inputbox" size="1"  onchange="showclicktellbox();"', 'value', 'text', $detail->template_section);

        $lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox" ', $detail->published);

        $order_functions = new order_functions();

        $paymentMethod = $order_functions->getPaymentMethodInfo();

        $payment_methods = explode(',', $detail->payment_methods);
        //$tmp = new stdClass;
        //$tmp = @array_merge($tmp,$payment_methods);
        $lists['payment_methods'] = JHTML::_('select.genericlist', $paymentMethod, 'payment_methods[]', 'class="inputbox" multiple="multiple" size="4" ', 'element', 'name', $payment_methods);

        $shippingMethod   = $order_functions->getShippingMethodInfo();
        $shipping_methods = explode(',', $detail->shipping_methods);
        //$tmp = new stdClass;
        //$tmp = @array_merge($tmp,$shipping_methods);

        $lists['shipping_methods'] = JHTML::_('select.genericlist', $shippingMethod, 'shipping_methods[]', 'class="inputbox" multiple="multiple" size="4" ', 'element', 'name', $shipping_methods);
        $lists['order_status']     = $order_functions->getstatuslist('order_status', $detail->order_status, 'class="inputbox" multiple="multiple"');

        $this->assignRef('lists', $lists);
        $this->assignRef('detail', $detail);
        $this->assignRef('request_url', $uri->toString());

        parent::display($tpl);
    }
}

