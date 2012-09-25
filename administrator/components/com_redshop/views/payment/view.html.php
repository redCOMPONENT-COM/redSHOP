<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class paymentViewpayment extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe, $context;

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_PAYMENTS'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_PAYMENT_MANAGEMENT'), 'redshop_payment48');
        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri              = JFactory::getURI();
        $context          = 'payment';
        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $payments           = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $this->assignRef('lists', $lists);
        $this->assignRef('payments', $payments);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();
        parent::display($tpl);
    }
}
