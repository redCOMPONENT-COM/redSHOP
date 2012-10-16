<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class RedshopViewMail extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $context  = 'mail_id';
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_MAIL'));
        jimport('joomla.html.pagination');

        JToolBarHelper::title(JText::_('COM_REDSHOP_MAIL_MANAGEMENT'), 'redshop_mailcenter48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri = JFactory::getURI();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'm.mail_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $filter_section   = $app->getUserStateFromRequest($context . 'filter_section', 'filter_section', 0);

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;

        $redtemplate          = new Redtemplate();
        $optionsection        = $redtemplate->getMailSections();
        $lists['mailsection'] = JHTML::_('select.genericlist', $optionsection, 'filter_section', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_section);

        $media = $this->get('Data');

        $pagination = $this->get('Pagination');

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('media', $media);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
