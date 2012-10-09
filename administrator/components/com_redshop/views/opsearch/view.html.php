<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class RedshopViewOpsearch extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $model          = $this->getModel('opsearch');
        $order_function = new order_functions();

        $document = JFactory::getDocument();
        $document->addStyleSheet('components/com_redshop/assets/css/search.css');
        $document->addScript('components/com_redshop/assets/js/search.js');

        $document->setTitle(JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH_BY_CUSTOMER'));
        JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH_BY_CUSTOMER'), 'redshop_order48');

        $uri = JFactory::getURI();

        $lists['order']     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'order_item_name');
        $lists['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $filter_user        = $app->getUserStateFromRequest($context . 'filter_user', 'filter_user', 0);
        $filter_status      = $app->getUserStateFromRequest($context . 'filter_status', 'filter_status', 0);

        $products   = $this->get('Data');
        $pagination = $this->get('Pagination');

        $lists['filter_user']   = $model->getuserlist('filter_user', $filter_user, 'class="inputbox" size="1" onchange="document.adminForm.submit();"');
        $lists['filter_status'] = $order_function->getstatuslist('filter_status', $filter_status, 'class="inputbox" size="1" onchange="document.adminForm.submit();"');

        $this->assignRef('lists', $lists);
        $this->assignRef('products', $products);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();
        parent::display($tpl);
    }
}
