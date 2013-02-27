<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'quotation.php');

class RedshopViewQuotation extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app             = JFactory::getApplication();
        $quotationHelper = new quotationHelper();

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_quotation'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_QUOTATION_MANAGEMENT'), 'redshop_quotation48');
        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();

        $uri = JFactory::getURI();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'quotation_cdate');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');
        $filter_status    = $app->getUserStateFromRequest($context . 'filter_status', 'filter_status', 0);

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;

        $quotation  = $this->get('Data');
        $pagination = $this->get('Pagination');

        $optionsection          = $quotationHelper->getQuotationStatusList();
        $lists['filter_status'] = JHTML::_('select.genericlist', $optionsection, 'filter_status', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_status);

        $this->assignRef('lists', $lists);
        $this->assignRef('quotation', $quotation);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}

