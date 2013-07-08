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

class tax_groupViewtax_group extends JView
{
	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI()->toString();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user     = JFactory::getUser();

		$document->setTitle(JText::_('COM_REDSHOP_TAX'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_TAX_GROUP_MANAGEMENT'), 'redshop_vatgroup48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'tax_group_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$total      = $this->get('Total');
		$media      = $this->get('Data');
		$pagination = $this->get('Pagination');

		$this->assignRef('user', $user);
		$this->assignRef('lists', $lists);
		$this->assignRef('media', $media);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri);

		parent::display($tpl);
	}
}
