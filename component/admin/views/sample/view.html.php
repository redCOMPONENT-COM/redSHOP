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

class sampleViewsample extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		global $mainframe, $context;
		$context  = 'sample_id';
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_CATALOG'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_SAMPLE'), 'redshop_catalogmanagement48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri =& JFactory::getURI();
		//$context = "rating";
		$filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'sample_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$catalog            = & $this->get('Data');
		$total              = & $this->get('Total');
		$pagination         = & $this->get('Pagination');

		$this->assignRef('lists', $lists);
		$this->assignRef('catalog', $catalog);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());
		parent::display($tpl);
	}
}