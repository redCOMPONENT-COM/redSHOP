<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class RedshopViewTemplate extends JViewLegacy
{
    function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $context  = 'template_id';
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_TEMPLATES'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES_MANAGEMENT'), 'redshop_templates48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', 'Copy', true);
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri              = JFactory::getURI();
        $context          = 'template';
        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'template_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $template_section = $app->getUserStateFromRequest($context . 'template_section', 'template_section', 0);

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $templates          = $this->get('Data');

        $pagination = $this->get('Pagination');

        $redtemplate      = new Redtemplate();
        $optionsection    = $redtemplate->getTemplateSections();
        $lists['section'] = JHTML::_('select.genericlist', $optionsection, 'template_section', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $template_section);

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('templates', $templates);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}

