<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class taxViewtax extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_TAX'));
        jimport('joomla.html.pagination');

        JToolBarHelper::title(JText::_('COM_REDSHOP_TAX_MANAGEMENT'), 'redshop_vat48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();

        $uri = JFactory::getURI();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'tax_rate_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $limitstart       = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
        $limit            = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

        $tax_group_id          = $this->get('ProductId');
        $lists['order']        = $filter_order;
        $lists['order_Dir']    = $filter_order_Dir;
        $lists['tax_group_id'] = $tax_group_id;

        $total = $this->get('Total');
        $media = $this->get('Data');

        $pagination = new JPagination($total, $limitstart, $limit);

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('media', $media);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
