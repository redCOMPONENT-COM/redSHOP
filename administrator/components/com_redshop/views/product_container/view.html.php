<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class product_containerViewproduct_container extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $model    = $this->getModel('product_container');
        $document = JFactory::getDocument();

        $container = JRequest::getVar('container', '', 'request', 0);

        $preorder = JRequest::getVar('preorder', '', 'request', 0);

        if ($preorder == '1')
        {
            $tpl = 'preorder';
        }

        if ($container == 1)
        {
            $document->setTitle(JText::_('COM_REDSHOP_CONTAINER_ORDER_PRODUCTS'));
            JToolBarHelper::title(JText::_('COM_REDSHOP_CONTAINER_ORDER_PRODUCTS'), 'redshop_container48');

            JToolBarHelper::custom('export_data', 'save.png', 'save_f2.png', 'Export Data', false);

            JToolBarHelper::custom('print_data', 'save.png', 'save_f2.png', 'Print Data', false);
        }
        else
        {
            JToolBarHelper::title(JText::_('COM_REDSHOP_CONTAINER_PRE_ORDER'), 'redshop_container48');
            $document->setTitle(JText::_('COM_REDSHOP_CONTAINER_PRE_ORDER'));
            JToolBarHelper::custom('addcontainer', 'new.png', 'new_f2.png', 'Add new container', false);
        }

        $uri = JFactory::getURI();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'product_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $filter_supplier  = $app->getUserStateFromRequest($context . 'filter_supplier', 'filter_supplier', 0);
        $filter_container = $app->getUserStateFromRequest($context . 'filter_container', 'filter_container', 0);

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $products           = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $lists['filter_supplier'] = $model->getsupplierlist('filter_supplier', $filter_supplier, 'class="inputbox" size="1" onchange="document.adminForm.submit();"');

        $lists['filter_container'] = $model->getcontainerlist('filter_container', $filter_container, 'class="inputbox" size="1" onchange="document.adminForm.submit();"');

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('products', $products);
        $this->assignRef('filter_container', $filter_container);
        $this->assignRef('filter_manufacturer', $filter_manufacturer);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
