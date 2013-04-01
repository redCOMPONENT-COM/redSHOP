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

class stockroomViewstockroom extends JView
{
	public function display($tpl = null)
	{
		global $context;
		$context = 'stockroom_id';
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_STOCKROOM'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKROOM_MANAGEMENT'), 'redshop_stockroom48');
		JToolBarHelper::customX('listing', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_LISTING'), false);
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri = JFactory::getURI();

		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'stockroom_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists ['order'] = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;
		$stockroom = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		$this->assignRef('lists', $lists);
		$this->assignRef('stockroom', $stockroom);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
