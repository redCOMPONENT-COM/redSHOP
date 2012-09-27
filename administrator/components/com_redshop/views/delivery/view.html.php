<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class deliveryViewdelivery extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_DELIVERY_LIST'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_DELIVERY_LIST'), 'redshop_redshopcart48');
        JToolBarHelper::custom('export_data', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_EXPORT_DATA_LBL'), false);

        $uri              = JFactory::getURI();
        $context          = 'delivery';
        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'order_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;

        $this->assignRef('lists', $lists);
        $this->request_url = $uri->toString();
        parent::display($tpl);
    }
}
