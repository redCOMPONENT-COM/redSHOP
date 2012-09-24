<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class countryViewcountry extends JViewLegacy
{
    function display($tpl = null)
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_COUNTRY'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT'), 'redshop_country_48');
        jimport('joomla.html.pagination');
        global $mainframe, $context;
        $context = 'country_id';
        JToolbarHelper::addNewX();
        JToolbarHelper::EditListX();
        JToolbarHelper::deleteList();
        $uri = JFactory::getURI();

        $filter_order       = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'country_id');
        $filter_order_Dir   = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;

        $fields     = $this->get('Data');
        $pagination = $this->get('Pagination');

        $this->user = JFactory::getUser();
        $this->assignRef('pagination', $pagination);
        $this->assignRef('fields', $fields);
        $this->assignRef('lists', $lists);
        $this->request_url = $uri->toString();
        parent::display($tpl);
    }
}
