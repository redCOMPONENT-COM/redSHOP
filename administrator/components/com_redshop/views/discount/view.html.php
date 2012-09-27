<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class discountViewdiscount extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_DISCOUNT'));

        $layout = JRequest::getVar('layout');
        if (isset($layout) && $layout == 'product')
        {
            $context = 'discount_product_id';
        }
        else
        {
            $context = 'discount_id';
        }
        JToolBarHelper::title(JText::_('COM_REDSHOP_DISCOUNT_MANAGEMENT'), 'redshop_discountmanagmenet48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri = JFactory::getURI();

        if (isset($layout) && $layout == 'product')
        {
            $this->setLayout('product');
            $filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'discount_product_id');
        }
        else
        {
            $filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'discount_id');
        }

        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $discounts          = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('discounts', $discounts);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
