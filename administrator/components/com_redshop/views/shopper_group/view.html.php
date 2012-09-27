<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'shopper.php');

class shopper_groupViewshopper_group extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $shoppergroup = new shoppergroup();
        $document     = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_SHOPPER_GROUP'));
        jimport('joomla.html.pagination');

        JToolBarHelper::title(JText::_('COM_REDSHOP_SHOPPER_GROUP_MANAGEMENT'), 'redshop_manufact48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri = JFactory::getURI();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'shopper_group_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;

        $groups = $shoppergroup->getshopperGroupListArray();

        $pagination = $this->get('Pagination');
        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('media', $groups);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
