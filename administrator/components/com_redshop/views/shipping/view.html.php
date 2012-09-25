<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class shippingViewshipping extends JViewLegacy
{
    function display($tpl = null)
    {
        global $mainframe, $context;

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_SHIPPING'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_SHIPPING_MANAGEMENT'), 'redshop_shipping48');
        JToolBarHelper::editListX();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri              = JFactory::getURI();
        $context          = 'shipping';
        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $shippings          = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $this->assignRef('lists', $lists);
        $this->assignRef('shippings', $shippings);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
