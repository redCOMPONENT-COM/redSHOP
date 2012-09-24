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

class giftcardViewgiftcard extends JView
{
    function display($tpl = null)
    {
        global $mainframe, $context;

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_GIFTCARD'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_GIFTCARD_MANAGEMENT'), 'redshop_giftcard_48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', 'Copy', true);
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri = JFactory::getURI();

        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'giftcard_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists ['order']     = $filter_order;
        $lists ['order_Dir'] = $filter_order_Dir;
        $giftcard            = $this->get('Data');
        $pagination          = $this->get('Pagination');

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('giftcard', $giftcard);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
