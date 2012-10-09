<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

class RedshopViewStockimage extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_STOCKIMAGE'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKIMAGE_MANAGEMENT'), 'redshop_stockroom48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();

        $uri = JFactory::getURI();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'stock_amount_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists ['order']     = $filter_order;
        $lists ['order_Dir'] = $filter_order_Dir;

        $data       = $this->get('Data');
        $pagination = $this->get('Pagination');

        $this->assignRef('lists', $lists);
        $this->assignRef('data', $data);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
