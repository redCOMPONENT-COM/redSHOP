<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

jimport('joomla.application.component.view');

class pricesViewprices extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		global $mainframe, $context;

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT_PRICE'));
		jimport('joomla.html.pagination');
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_PRICE'), 'redshop_vatrates48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		$uri =& JFactory::getURI();

		$limitstart = $mainframe->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', '10');

		$total = & $this->get('Total');
		$media = & $this->get('Data');
		$product_id = $this->get('ProductId');
		$pagination = new JPagination($total, $limitstart, $limit);
		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('media', $media);
		$this->assignRef('product_id', $product_id);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());
		parent::display($tpl);
	}
}