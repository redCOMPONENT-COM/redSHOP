<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pagination');

class RedshopViewAccountgroup extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'), 'redshop_accountgroup48');
        JToolbarHelper::addNew('accountgroup_detail.add');
        JToolbarHelper::EditList('accountgroup_detail.edit');
        JToolbarHelper::deleteList('accountgroup.delete');
        JToolBarHelper::publishList('accountgroup.publish');
        JToolBarHelper::unpublishList('accountgroup.publish');
        $uri = JFactory::getURI();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'accountgroup_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;

        $detail     = $this->get('Data');
        $pagination = $this->get('Pagination');

        $this->pagination = $pagination;
        $this->assignRef('detail', $detail);
        $this->assignRef('lists', $lists);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
