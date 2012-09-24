<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class stockroomViewstockroom extends JView
{
    function display($tpl = null)
    {
        global $mainframe, $context;

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_STOCKROOM'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKROOM_MANAGEMENT'), 'redshop_stockroom48');

        JToolBarHelper::customX('listing', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_LISTING'), false);

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', 'Copy', true);
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri = JFactory::getURI();

        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'stockroom_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists ['order']     = $filter_order;
        $lists ['order_Dir'] = $filter_order_Dir;
        $stockroom           = $this->get('Data');
        $pagination          = $this->get('Pagination');

        $this->assignRef('lists', $lists);
        $this->assignRef('stockroom', $stockroom);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
