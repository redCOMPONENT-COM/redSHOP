<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class catalogViewcatalog extends JViewLegacy
{
    function display($tpl = null)
    {
        global $mainframe, $context;
        $context  = 'catalog_id';
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_CATALOG'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_CATALOG_MANAGEMENT'), 'redshop_catalogmanagement48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri              = JFactory::getURI();
        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'catalog_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $catalog            = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('catalog', $catalog);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
