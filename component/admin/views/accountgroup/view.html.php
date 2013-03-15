<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
s
defined('_JEXEC') or die;

jimport('joomla.html.pagination');
jimport('joomla.application.component.view');

class accountgroupViewaccountgroup extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		global $mainframe, $context;

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'), 'redshop_accountgroup48');
		JToolbarHelper::addNewX();
		JToolbarHelper::EditListX();
		JToolbarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		$uri =& JFactory::getURI();

		$filter_order       = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'accountgroup_id');
		$filter_order_Dir   = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$detail     = & $this->get('Data');
		$total      = & $this->get('Total');
		$pagination = & $this->get('Pagination');

		$this->assignRef('pagination', $pagination);
		$this->assignRef('detail', $detail);
		$this->assignRef('lists', $lists);
		$this->assignRef('request_url', $uri->toString());
		parent::display($tpl);

	}

}