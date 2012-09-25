<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class textlibraryViewtextlibrary extends JViewLegacy
{
    function display($tpl = null)
    {
        global $mainframe, $context;
        $context  = 'textlibrary_id';
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_TEXTLIBRARY'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_TEXTLIBRARY_MANAGEMENT'), 'redshop_textlibrary48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', 'Copy', true);
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri = JFactory::getURI();

        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'textlibrary_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $section = $mainframe->getUserStateFromRequest($context . 'section', 'section', 0);

        $optionsection   = array();
        $optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
        $optionsection[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_Product'));
        $optionsection[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_Category'));
        $optionsection[] = JHTML::_('select.option', 'newsletter', JText::_('COM_REDSHOP_Newsletter'));

        $lists['section'] = JHTML::_('select.genericlist', $optionsection, 'section', 'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $section);

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $textlibrarys       = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('textlibrarys', $textlibrarys);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();
        parent::display($tpl);
    }
}

