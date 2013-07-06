<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

require_once JPATH_COMPONENT . '/helpers/shopper.php';

class shopper_groupViewshopper_group extends JView
{
	public function display($tpl = null)
	{
		global $context;

		$shoppergroup = new shoppergroup;

		$uri      = JFactory::getURI()->toString();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user     = JFactory::getUser();

		$document->setTitle(JText::_('COM_REDSHOP_SHOPPER_GROUP'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_SHOPPER_GROUP_MANAGEMENT'), 'redshop_manufact48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'shopper_group_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$groups = $shoppergroup->getshopperGroupListArray();

		$pagination = $this->get('Pagination');

		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('media', $groups);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri);

		parent::display($tpl);
	}
}
