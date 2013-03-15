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

class taxViewtax extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		global $mainframe, $context;


		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_TAX'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_TAX_MANAGEMENT'), 'redshop_vat48');


		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();


		$uri =& JFactory::getURI();

		$filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'tax_rate_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$limitstart       = $mainframe->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit            = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', '10');

		$tax_group_id          =& $this->get('ProductId');
		$lists['order']        = $filter_order;
		$lists['order_Dir']    = $filter_order_Dir;
		$lists['tax_group_id'] = $tax_group_id;

		$total = & $this->get('Total');
		$media = & $this->get('Data');


		$pagination = new JPagination($total, $limitstart, $limit);


		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('media', $media);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());
		parent::display($tpl);
	}
}