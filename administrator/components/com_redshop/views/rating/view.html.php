<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class ratingViewrating extends JViewLegacy
{
    function display($tpl = null)
    {
        global $mainframe, $context;

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_RATING'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_RATING_MANAGEMENT'), 'redshop_rating48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri              = JFactory::getURI();
        $context          = "rating";
        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'rating_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $ratings            = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('ratings', $ratings);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
