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

class sample_requestViewsample_request extends JView
{
	public function display($tpl = null)
	{
		global $mainframe, $context;

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_CATALOG_SAMPLE'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_SAMPLE'), 'redshop_colorsample48');
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri = JFactory::getURI();
		$context = "request_id";
		$filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'request_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$catalog = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('catalog', $catalog);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
