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

class discountViewdiscount extends JView
{
	public function display($tpl = null)
	{
		global $mainframe, $context;

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_DISCOUNT'));

		$layout = JRequest::getVar('layout');

		if (isset($layout) && $layout == 'product')
		{
			$context = 'discount_product_id';
		}
		else
		{
			$context = 'discount_id';
		}

		JToolBarHelper::title(JText::_('COM_REDSHOP_DISCOUNT_MANAGEMENT'), 'redshop_discountmanagmenet48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri =& JFactory::getURI();

		if (isset($layout) && $layout == 'product')
		{
			$this->setLayout('product');
			$filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'discount_product_id');
		}

		else
		{
			$filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'discount_id');
		}

		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$discounts = & $this->get('Data');
		$total = & $this->get('Total');
		$pagination = & $this->get('Pagination');

		$spgrpdis_filter = $mainframe->getUserStateFromRequest($context . 'spgrpdis_filter', 'spgrpdis_filter', 0);
		$userhelper = new rsUserhelper;
		$shopper_groups = $userhelper->getShopperGroupList();
		$temps = array();
		$temps[0]->value = 0;
		$temps[0]->text = JText::_('COM_REDSHOP_SELECT');
		$shopper_groups = array_merge($temps, $shopper_groups);
		$lists['shopper_group'] = JHTML::_('select.genericlist', $shopper_groups, 'spgrpdis_filter',
			'class="inputbox" size="1" onchange="document.adminForm.submit()"', 'value', 'text', $spgrpdis_filter
		);

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('discounts', $discounts);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
